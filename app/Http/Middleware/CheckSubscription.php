<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscription
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if ($user && $user->role !== 'Super Admin' && !session()->has('impersonated_by')) {
            // Always allow access to onboarding and subscription routes
            if ($request->routeIs('onboarding.*') || $request->routeIs('subscription.*') || $request->routeIs('logout') || $request->routeIs('superadmin.impersonate.leave')) {
                return $next($request);
            }

            $company = $user->company;

            // If company is not active or in trial, check onboarding step
            if ($company && !in_array($company->subscription_status, ['Active', 'Trial'])) {
                $step = $company->onboarding_step ?? 'introduction';
                if (!in_array($step, ['completed', 'complete'])) {
                    return redirect()->route('onboarding.' . $step)
                        ->with('warning', 'Please complete your account setup first.');
                }
            }
        }

        return $next($request);
    }
}
