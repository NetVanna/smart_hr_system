<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            // Company
            'company_name'     => 'required|string|max:255',
            'company_email'    => 'required|email|unique:companies,email',
            'company_phone'    => 'nullable|string|max:20',
            'company_address'  => 'nullable|string',

            // Admin
            'name'             => 'required|string|max:255',
            'email'            => 'required|email|unique:users,email',
            'telegram_username'=> 'required|string|max:100',
            'password'         => 'required|string|min:8|confirmed',
        ]);

        try {
            DB::beginTransaction();

            $company = Company::create([
                'name'                => $request->company_name,
                'email'               => $request->company_email,
                'phone'               => $request->company_phone,
                'address'             => $request->company_address,
                'subscription_plan'   => 'Starter',
                'subscription_status' => 'Pending',
                'onboarding_step'     => 'introduction',
            ]);

            $user = User::create([
                'company_id'        => $company->id,
                'name'              => $request->name,
                'email'             => $request->email,
                'telegram_username' => ltrim($request->telegram_username, '@'), // strip @ if typed
                'password'          => Hash::make($request->password),
                'role'              => 'Company Admin',
            ]);

            DB::commit();

            // Notify Super Admin
            $superAdmins = User::where('role', 'Super Admin')->whereNotNull('fcm_token')->get();
            $notificationService = new \App\Services\NotificationService();
            foreach ($superAdmins as $admin) {
                $notificationService->sendPushNotification(
                    $admin->fcm_token,
                    'New Company Registration',
                    "Company {$company->name} has registered and awaits subscription approval.",
                    ['type' => 'subscription', 'company_id' => $company->id]
                );
            }

            Auth::login($user);

            return redirect()->route('onboarding.introduction')
                ->with('success', 'Welcome to SmartHR! Let\'s get you set up.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Registration failed: ' . $e->getMessage());
        }
    }
}
