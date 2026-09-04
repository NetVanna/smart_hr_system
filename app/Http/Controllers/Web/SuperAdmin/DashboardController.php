<?php

namespace App\Http\Controllers\Web\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Subscription;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. MRR Calculation (Sum of price for active subscriptions this month)
        $mrr = Subscription::where('status', 'Active')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->sum('price');
        $mrr_khr = $mrr * 4100;

        // 2. Growth Data (Company registrations per month for the last 6 months)
        $monthExpr = DB::connection()->getDriverName() === 'sqlite'
            ? "strftime('%Y-%m', created_at) as month"
            : "DATE_FORMAT(created_at, '%Y-%m') as month";

        $growthData = Company::select(
                DB::raw('COUNT(*) as count'), 
                DB::raw($monthExpr)
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        // 3. Subscription Status Breakdown
        $subscriptionStatus = Subscription::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        // 4. Platform Coverage & Totals
        $totalCompanies = Company::count();
        $paidCompanies = Company::where('subscription_status', 'Active')->count();
        $trialCompanies = Company::where('subscription_status', 'Trial')->count();
        $totalBranches = \App\Models\Branch::count();
        $totalEmployees = \App\Models\Employee::count();

        // 5. Churn Rate Calculation (Companies with Expired/Canceled/Pending status)
        $churnCount = Subscription::whereIn('status', ['Expired', 'Canceled'])->distinct('company_id')->count();
        $churnRate = $totalCompanies > 0 ? ($churnCount / $totalCompanies) * 100 : 0;

        // 6. Immediate Action Queues (Pending KHQR Approvals & Open Tickets)
        $pendingApprovals = Subscription::with('company')
            ->where('status', 'Pending Approval')
            ->latest()
            ->get();

        $openTickets = \App\Models\Ticket::with(['company', 'user'])
            ->where('status', '!=', 'Closed')
            ->latest()
            ->take(5)
            ->get();

        $recentCompanies = Company::withCount(['branches', 'employees'])
            ->with(['users' => function($q) {
                $q->where('role', 'Company Admin');
            }])
            ->latest()
            ->take(5)
            ->get();

        return view('superadmin.dashboard', compact(
            'mrr', 
            'mrr_khr',
            'growthData', 
            'subscriptionStatus', 
            'totalCompanies', 
            'paidCompanies', 
            'trialCompanies',
            'totalBranches',
            'totalEmployees',
            'churnRate',
            'pendingApprovals',
            'openTickets',
            'recentCompanies'
        ));
    }
}
