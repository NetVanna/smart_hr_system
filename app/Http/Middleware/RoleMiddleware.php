<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        if (!in_array(auth()->user()->role, $roles)) {
            // Auto-revert impersonation if the original Super Admin navigates back to a Super Admin route
            if (in_array('Super Admin', $roles) && session()->has('impersonated_by')) {
                $superAdminId = session()->pull('impersonated_by');
                $superAdmin = \App\Models\User::withoutGlobalScopes()->find($superAdminId);
                if ($superAdmin) {
                    auth()->login($superAdmin);
                    return $next($request);
                }
            }

            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json(['message' => 'Forbidden - Insufficient Permissions.'], 403);
            }

            return redirect()->route('dashboard')->with('error', 'Forbidden - Insufficient Permissions.');
        }

        return $next($request);
    }
}
