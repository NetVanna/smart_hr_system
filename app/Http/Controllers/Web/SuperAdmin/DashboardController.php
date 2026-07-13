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

        // 2. Growth Data (Company registrations per month for the last 6 months)
        $growthData = Company::select(
                DB::raw('COUNT(*) as count'), 
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month")
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        // 3. Subscription Status Breakdown
        $subscriptionStatus = Subscription::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        // 4. Trial to Paid Conversion (Simplified)
        $totalCompanies = Company::count();
        $paidCompanies = Company::where('subscription_status', 'Active')->count();
        $trialCompanies = Company::where('subscription_status', 'Trial')->count();

        // 5. Churn Rate Calculation (Companies with Expired/Canceled/Pending status)
        $churnCount = Subscription::whereIn('status', ['Expired', 'Canceled'])->distinct('company_id')->count();
        $churnRate = $totalCompanies > 0 ? ($churnCount / $totalCompanies) * 100 : 0;

        return view('superadmin.dashboard', compact(
            'mrr', 
            'growthData', 
            'subscriptionStatus', 
            'totalCompanies', 
            'paidCompanies', 
            'trialCompanies',
            'churnRate'
        ));
    }
}
