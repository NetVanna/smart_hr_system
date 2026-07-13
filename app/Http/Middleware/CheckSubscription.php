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

        if ($user && $user->role !== 'Super Admin') {
            // Always allow access to onboarding and subscription routes
            if ($request->routeIs('onboarding.*') || $request->routeIs('subscription.*') || $request->routeIs('logout')) {
                return $next($request);
            }

            $company = $user->company;

            // If company is in onboarding, redirect to current step
            if ($company && $company->subscription_status !== 'Active') {
                $step = $company->onboarding_step ?? 'introduction';
                return redirect()->route('onboarding.' . $step)
                    ->with('warning', 'Please complete your account setup first.');
            }
        }

        return $next($request);
    }
}
