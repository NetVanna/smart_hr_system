<?php

namespace App\Http\Controllers\Web\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index()
    {
        $subscriptions = \App\Models\Subscription::with('company')->orderBy('created_at', 'desc')->get();
        return view('superadmin.subscriptions.index', compact('subscriptions'));
    }

    public function create()
    {
        $companies = \App\Models\Company::all();
        return view('superadmin.subscriptions.create', compact('companies'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'plan' => 'required|string',
            'price' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|string',
            'receipt' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        if ($request->hasFile('receipt')) {
            $path = $request->file('receipt')->store('receipts', 'public');
            $validated['receipt_path'] = $path;
        }

        \App\Models\Subscription::create($validated);

        // Update company status
        $company = \App\Models\Company::find($request->company_id);
        $company->update([
            'subscription_plan' => $request->plan,
            'subscription_status' => in_array($request->status, ['Approved', 'Active']) ? 'Active' : 'Trial'
        ]);

        return redirect()->route('superadmin.subscriptions.index')->with('success', 'Subscription recorded successfully.');
    }

    public function edit(\App\Models\Subscription $subscription)
    {
        $companies = \App\Models\Company::all();
        return view('superadmin.subscriptions.edit', compact('subscription', 'companies'));
    }

    public function update(Request $request, \App\Models\Subscription $subscription)
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'plan' => 'required|string',
            'price' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|string',
            'receipt' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        if ($request->hasFile('receipt')) {
            $path = $request->file('receipt')->store('receipts', 'public');
            $validated['receipt_path'] = $path;
        }

        $subscription->update($validated);

        // Sync with company
        $subscription->company->update([
            'subscription_plan' => $request->plan,
            'subscription_status' => in_array($request->status, ['Approved', 'Active']) ? 'Active' : 'Inactive'
        ]);

        return redirect()->route('superadmin.subscriptions.index')->with('success', 'Subscription updated successfully.');
    }

    public function destroy(\App\Models\Subscription $subscription)
    {
        $subscription->delete();
        return redirect()->route('superadmin.subscriptions.index')->with('success', 'Subscription record deleted.');
    }

    public function submitProof(Request $request)
    {
        $request->validate([
            'receipt' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $user = auth()->user();
        $path = $request->file('receipt')->store('receipts', 'public');

        \App\Models\Subscription::create([
            'company_id'   => $user->company_id,
            'plan'         => $user->company->subscription_plan ?? 'Basic',
            'price'        => $user->company->subscription_plan == 'Pro' ? 49 : ($user->company->subscription_plan == 'Enterprise' ? 99 : 19),
            'start_date'   => now(),
            'end_date'     => now()->addYear(),
            'status'       => 'Pending Approval',
            'receipt_path' => $path
        ]);

        return back()->with('success', 'Your payment proof has been submitted. Our team will verify and activate your account shortly.');
    }

    /**
     * SuperAdmin approves a pending subscription → activates the company account
     */
    public function approve(\App\Models\Subscription $subscription)
    {
        // 1. Activate subscription
        $subscription->update([
            'status'     => 'Active',
            'start_date' => now(),
            'end_date'   => now()->addYear(),
        ]);

        // 2. Activate company
        $company = $subscription->company;
        if ($company) {
            $company->update([
                'subscription_plan'   => $subscription->plan,
                'subscription_status' => 'Active',
                'onboarding_step'     => 'completed',
            ]);
        }

        // 3. Log it
        $companyName = $company?->name ?? 'Company #' . $subscription->company_id;
        \Illuminate\Support\Facades\Log::info("Subscription approved for company #{$subscription->company_id} ({$companyName})");

        $admin = $company ? \App\Models\User::withoutGlobalScopes()->where('company_id', $company->id)->where('role', 'Company Admin')->first() : null;
        $tgInfo = $admin?->telegram_username ? " (@{$admin->telegram_username})" : "";

        return redirect()
            ->route('superadmin.subscriptions.index')
            ->with('success', "✅ Account for \"{$companyName}\" has been activated. Notify client via Telegram{$tgInfo}.");
    }
}

