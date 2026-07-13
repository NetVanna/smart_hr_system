<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OnboardingController extends Controller
{
    /** Pricing table */
    const PLANS = [
        'Starter'    => ['price' => 19,  'employees' => 10,  'color' => 'primary'],
        'Pro'        => ['price' => 49,  'employees' => 50,  'color' => 'success'],
        'Enterprise' => ['price' => 99,  'employees' => 999, 'color' => 'warning'],
    ];

    /** Step guard — redirect to correct step if user tries to skip */
    private function checkStep(string $required)
    {
        $step = auth()->user()->company->onboarding_step ?? 'introduction';
        $order = ['introduction', 'plans', 'payment', 'pending'];
        $currentIndex = array_search($step, $order);
        $requiredIndex = array_search($required, $order);

        // Allow going back, but not skipping forward
        if ($requiredIndex > $currentIndex) {
            return redirect()->route('onboarding.' . $step);
        }
        return null;
    }

    /* ─────────────────────────────────────────
     | STEP 1 — Introduction
     ───────────────────────────────────────── */
    public function introduction()
    {
        return view('onboarding.introduction');
    }

    public function introductionNext()
    {
        auth()->user()->company->update(['onboarding_step' => 'plans']);
        return redirect()->route('onboarding.plans');
    }

    /* ─────────────────────────────────────────
     | STEP 2 — Plan Selection
     ───────────────────────────────────────── */
    public function plans()
    {
        if ($r = $this->checkStep('plans')) return $r;
        $plans = self::PLANS;
        return view('onboarding.plans', compact('plans'));
    }

    public function selectPlan(Request $request)
    {
        $request->validate([
            'plan' => 'required|in:Starter,Pro,Enterprise',
        ]);

        auth()->user()->company->update([
            'subscription_plan' => $request->plan,
            'onboarding_step'   => 'payment',
        ]);

        return redirect()->route('onboarding.payment');
    }

    /* ─────────────────────────────────────────
     | STEP 3 — Payment
     ───────────────────────────────────────── */
    public function payment()
    {
        if ($r = $this->checkStep('payment')) return $r;
        $company = auth()->user()->company;
        $plan    = $company->subscription_plan ?? 'Starter';
        $price   = self::PLANS[$plan]['price'] ?? 19;
        return view('onboarding.payment', compact('plan', 'price'));
    }

    /* ─────────────────────────────────────────
     | STEP 3 — Upload Proof (POST)
     ───────────────────────────────────────── */
    public function submitProof(Request $request)
    {
        $request->validate([
            'receipt' => 'required|image|mimes:jpeg,png,jpg,gif|max:4096',
        ]);

        $user    = auth()->user();
        $company = $user->company;
        $plan    = $company->subscription_plan ?? 'Starter';
        $price   = self::PLANS[$plan]['price'] ?? 19;
        $path    = $request->file('receipt')->store('receipts', 'public');

        Subscription::updateOrCreate(
            ['company_id' => $user->company_id, 'status' => 'Pending Approval'],
            [
                'plan'         => $plan,
                'price'        => $price,
                'start_date'   => now(),
                'end_date'     => now()->addYear(),
                'status'       => 'Pending Approval',
                'receipt_path' => $path,
            ]
        );

        $company->update(['onboarding_step' => 'pending']);

        return redirect()->route('onboarding.pending');
    }

    /* ─────────────────────────────────────────
     | STEP 4 — Pending Review
     ───────────────────────────────────────── */
    public function pending()
    {
        $subscription = Subscription::where('company_id', auth()->user()->company_id)
                            ->where('status', 'Pending Approval')
                            ->latest()
                            ->first();

        return view('onboarding.pending', compact('subscription'));
    }
}
