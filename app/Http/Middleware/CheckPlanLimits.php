<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPlanLimits
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $resource): Response
    {
        $user = auth()->user();
        if (!$user || !$user->company_id) {
            return $next($request);
        }

        $company = \App\Models\Company::find($user->company_id);
        $plan = $company->subscription_plan;

        $limits = [
            'Free Trial' => ['employee' => 5, 'asset' => 2],
            'Starter'    => ['employee' => 20, 'asset' => 10],
            'Silver'     => ['employee' => 100, 'asset' => 50],
            'Gold'       => ['employee' => 999999, 'asset' => 999999],
        ];

        $currentPlanLimits = $limits[$plan] ?? $limits['Free Trial'];

        if ($resource === 'employee') {
            $count = \App\Models\Employee::where('company_id', $company->id)->count();
            if ($count >= $currentPlanLimits['employee']) {
                return $this->refuseRequest($plan, 'employees', $currentPlanLimits['employee']);
            }
        }

        if ($resource === 'asset') {
            $count = \App\Models\Asset::where('company_id', $company->id)->count();
            if ($count >= $currentPlanLimits['asset']) {
                return $this->refuseRequest($plan, 'assets', $currentPlanLimits['asset']);
            }
        }

        return $next($request);
    }

    private function refuseRequest($plan, $resourceLabel, $limit)
    {
        $message = "Your current plan ({$plan}) is limited to {$limit} {$resourceLabel}. Please upgrade to add more.";
        
        if (request()->expectsJson()) {
            return response()->json(['message' => $message, 'upgrade_required' => true], 403);
        }

        return redirect()->back()->with('error', $message);
    }
}
