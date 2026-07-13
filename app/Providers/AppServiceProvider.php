<?php

namespace App\Providers;

use App\Models\Leave;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        // Share dashboard sidebar stats with all authenticated views
        View::composer('layouts.app', function ($view) {
            $user = Auth::user();
            if ($user) {
                $companyId = $user->company_id;

                // Only compute company-scoped stats for Company Admin & HR Manager
                if (in_array($user->role, ['Company Admin', 'HR Manager']) && $companyId) {
                    $sidebarStats = [
                        'pendingLeaves' => Leave::where('company_id', $companyId)
                            ->where('status', 'Pending')->count(),
                        'openTickets'   => Ticket::where('company_id', $companyId)
                            ->whereIn('status', ['Open', 'In Progress'])->count(),
                    ];
                } else {
                    $sidebarStats = ['pendingLeaves' => 0, 'openTickets' => 0];
                }

                $view->with('sidebarStats', $sidebarStats);
            }
        });
    }
}
