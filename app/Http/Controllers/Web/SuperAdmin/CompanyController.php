<?php

namespace App\Http\Controllers\Web\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = \App\Models\Company::withCount(['branches', 'employees'])
            ->with(['users' => function($q) {
                $q->where('role', 'Company Admin');
            }])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('superadmin.companies.index', compact('companies'));
    }

    public function impersonate(\App\Models\Company $company)
    {
        // 1. Find Company Admin or fallback to any user in the company (ignoring global company scopes)
        $admin = \App\Models\User::withoutGlobalScopes()
            ->where('company_id', $company->id)
            ->where('role', 'Company Admin')
            ->first()
            ?? \App\Models\User::withoutGlobalScopes()
            ->where('company_id', $company->id)
            ->first();

        // 2. If company has no users at all, auto-provision an admin account for it
        if (!$admin) {
            $admin = \App\Models\User::withoutGlobalScopes()->create([
                'company_id'  => $company->id,
                'name'        => $company->name . ' Admin',
                'email'       => 'admin.' . $company->id . '@smarthr.kh',
                'password'    => \Illuminate\Support\Facades\Hash::make('password123'),
                'role'        => 'Company Admin',
            ]);
        }

        // 3. Keep the original Super Admin ID in session (never overwrite if switching between tenants)
        if (!session()->has('impersonated_by')) {
            session()->put('impersonated_by', auth()->id());
        }

        // 4. Log in as tenant admin
        auth()->login($admin);

        return redirect()->route('dashboard')->with('success', "Support Mode: Logged in as Company Admin for \"{$company->name}\".");
    }

    public function leaveImpersonation()
    {
        if (session()->has('impersonated_by')) {
            $superAdminId = session()->pull('impersonated_by');
            $superAdmin = \App\Models\User::withoutGlobalScopes()->find($superAdminId);

            if ($superAdmin) {
                auth()->login($superAdmin);
            }

            return redirect()->route('superadmin.companies.index')->with('success', 'Returned to Super Admin Control Center.');
        }

        return redirect()->route('login');
    }

    public function extendTrial(Request $request, \App\Models\Company $company)
    {
        $days = (int) ($request->input('days', 14));

        $company->update([
            'subscription_status' => 'Trial',
            'subscription_plan'   => '14-Day Free Trial',
        ]);

        \App\Models\Subscription::updateOrCreate(
            ['company_id' => $company->id, 'status' => 'Trial'],
            [
                'plan'       => '14-Day Free Trial',
                'price'      => 0.00,
                'start_date' => now(),
                'end_date'   => now()->addDays($days),
            ]
        );

        return back()->with('success', "✅ Extended trial for \"{$company->name}\" by {$days} days.");
    }

    public function create()
    {
        return view('superadmin.companies.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:companies,email',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'base_currency' => 'nullable|string|in:USD,KHR',
            'exchange_rate' => 'nullable|numeric',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'geofence_radius' => 'nullable|numeric',
            'subscription_plan' => 'required|string',
            'subscription_status' => 'required|in:Active,Inactive,Trial,Expired',
            // Admin User Fields
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|unique:users,email',
            'admin_password' => 'required|string|min:8',
            'telegram_username' => 'nullable|string|max:100',
        ]);

        \Illuminate\Support\Facades\DB::transaction(function() use ($validated) {
            $company = \App\Models\Company::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'base_currency' => $validated['base_currency'],
                'exchange_rate' => $validated['exchange_rate'],
                'latitude' => $validated['latitude'],
                'longitude' => $validated['longitude'],
                'geofence_radius' => $validated['geofence_radius'],
                'subscription_plan' => $validated['subscription_plan'],
                'subscription_status' => $validated['subscription_status'],
            ]);

            \App\Models\User::create([
                'company_id' => $company->id,
                'name' => $validated['admin_name'],
                'email' => $validated['admin_email'],
                'password' => \Illuminate\Support\Facades\Hash::make($validated['admin_password']),
                'role' => 'Company Admin',
                'telegram_username' => ltrim($validated['telegram_username'] ?? '', '@'),
            ]);
        });

        return redirect()->route('superadmin.companies.index')
            ->with('success', 'Company and Admin account added successfully.')
            ->with('admin_password', $validated['admin_password'])
            ->with('admin_email', $validated['admin_email']);
    }

    public function edit(\App\Models\Company $company)
    {
        return view('superadmin.companies.edit', compact('company'));
    }

    public function resetAdminPassword(\Illuminate\Http\Request $request, $username)
    {
        $request->validate([
            'password' => 'required|string|min:6'
        ]);

        $user = \App\Models\User::where('telegram_username', $username)
            ->where('role', 'Company Admin')
            ->first();

        if (!$user) {
            return response()->json(['error' => 'Admin user not found'], 404);
        }

        $user->update([
            'password' => \Illuminate\Support\Facades\Hash::make($request->password)
        ]);

        return response()->json(['success' => true]);
    }

    public function generateMagicLink($username)
    {
        $user = \App\Models\User::where('telegram_username', $username)
            ->where('role', 'Company Admin')
            ->first();

        if (!$user) {
            return response()->json(['error' => 'Admin user not found'], 404);
        }

        $token = \Illuminate\Support\Str::random(64);
        $user->update([
            'magic_login_token' => $token,
            'magic_login_token_expires_at' => \Carbon\Carbon::now()->addHours(24),
        ]);

        $link = route('login.magic', $token);

        return response()->json(['success' => true, 'link' => $link]);
    }

    public function update(Request $request, \App\Models\Company $company)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:companies,email,' . $company->id,
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'base_currency' => 'nullable|string|in:USD,KHR',
            'exchange_rate' => 'nullable|numeric',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'geofence_radius' => 'nullable|numeric',
            'subscription_plan' => 'required|string',
            'subscription_status' => 'required|in:Active,Inactive,Trial,Expired'
        ]);

        $company->update($validated);

        return redirect()->route('superadmin.companies.index')->with('success', 'Company updated successfully.');
    }

    public function destroy(\App\Models\Company $company)
    {
        $company->delete();
        return redirect()->route('superadmin.companies.index')->with('success', 'Company deleted successfully.');
    }
}
