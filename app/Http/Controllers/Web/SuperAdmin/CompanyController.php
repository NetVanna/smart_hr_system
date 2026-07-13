<?php

namespace App\Http\Controllers\Web\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = \App\Models\Company::orderBy('created_at', 'desc')->get();
        return view('superadmin.companies.index', compact('companies'));
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
