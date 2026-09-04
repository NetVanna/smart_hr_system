<?php

namespace App\Http\Controllers\Web\Company;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TenantBillingController extends Controller
{
    /**
     * Show subscription & billing management for Company Admin.
     */
    public function index()
    {
        $company = auth()->user()->company;

        if (!$company) {
            abort(404, 'Company not found.');
        }

        $activeSubscription = $company->subscriptions()
            ->latest()
            ->first();

        $subscriptions = $company->subscriptions()
            ->latest()
            ->get();

        $employeeCount = Employee::where('company_id', $company->id)->count();

        // Calculate limits
        $planLimits = [
            'Basic'      => 10,
            'Pro'        => 50,
            'Growth'     => 50,
            'Enterprise' => 500,
        ];

        $currentPlan = $company->subscription_plan ?? 'Basic';
        $maxEmployees = $planLimits[$currentPlan] ?? 25;
        $usagePercent = min(100, round(($employeeCount / max(1, $maxEmployees)) * 100));

        $plans = [
            [
                'name'        => 'Basic',
                'price_usd'   => 19,
                'price_khr'   => 19 * 4100,
                'limit'       => 'Up to 10 staff',
                'features'    => ['QR Code Clock-in', '1 Branch Location', 'Basic Leave Requests', 'Email Support'],
                'is_current'  => $currentPlan === 'Basic',
            ],
            [
                'name'        => 'Growth',
                'price_usd'   => 79,
                'price_khr'   => 79 * 4100,
                'limit'       => 'Up to 50 staff',
                'features'    => ['Multi-Branch GPS Presets', 'Telegram Bot Alerts', 'Payroll in USD & KHR', 'Priority Khmer Support'],
                'is_current'  => in_array($currentPlan, ['Growth', 'Pro']),
            ],
            [
                'name'        => 'Enterprise',
                'price_usd'   => 149,
                'price_khr'   => 149 * 4100,
                'limit'       => 'Unlimited staff & branches',
                'features'    => ['All Provincial Branches', 'Face Recognition Clock-in', 'Audit Trail & Compliance', 'Dedicated Account Manager'],
                'is_current'  => $currentPlan === 'Enterprise',
            ],
        ];

        return view('company.billing.index', compact(
            'company',
            'activeSubscription',
            'subscriptions',
            'employeeCount',
            'maxEmployees',
            'usagePercent',
            'plans'
        ));
    }

    /**
     * Submit ABA / Bakong KHQR transfer proof for subscription upgrade or renewal.
     */
    public function submitProof(Request $request)
    {
        $company = auth()->user()->company;

        $request->validate([
            'plan'    => 'required|string|in:Basic,Pro,Growth,Enterprise',
            'receipt' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $path = $request->file('receipt')->store('receipts', 'public');

        $priceMap = [
            'Basic'      => 19,
            'Pro'        => 49,
            'Growth'     => 79,
            'Enterprise' => 149,
        ];

        $plan = $request->plan;
        $price = $priceMap[$plan] ?? 49;

        Subscription::create([
            'company_id'   => $company->id,
            'plan'         => $plan,
            'price'        => $price,
            'start_date'   => now(),
            'end_date'     => now()->addYear(),
            'status'       => 'Pending Approval',
            'receipt_path' => $path,
        ]);

        return back()->with('success', '✅ Your KHQR payment receipt has been submitted for SuperAdmin verification. Your plan will be updated upon approval.');
    }
}
