<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CompanySettingsController extends Controller
{
    public function edit()
    {
        $user = request()->user();

        if (!$user || !$user->company) {
            return redirect()->route('login');
        }

        $company = $user->company;
        return view('settings.company', compact('company'));
    }

    public function update(Request $request)
    {
        $user = request()->user();

        if (!$user || !$user->company) {
            return redirect()->route('login');
        }

        $company = $user->company;

        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'email'            => 'required|email|max:255',
            'phone'            => 'nullable|string|max:20',
            'address'          => 'nullable|string',
            'base_currency'    => 'required|string|in:USD,KHR',
            'exchange_rate'    => 'required|numeric|min:1',
            'geofence_radius'  => 'required|numeric|min:10',
            'latitude'         => 'nullable|numeric',
            'longitude'        => 'nullable|numeric',
            'telegram_chat_id' => 'nullable|string'
        ]);

        $company->update($validated);

        return back()->with('success', '✅ Company workspace settings and Cambodian currency rates updated successfully.');
    }
}
