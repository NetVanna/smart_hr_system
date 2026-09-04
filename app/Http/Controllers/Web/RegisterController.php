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
            'company_name'      => 'required|string|max:255',
            'name'              => 'required|string|max:255',
            'email'             => 'required|email|unique:users,email',
            'phone'             => 'nullable|string|max:25|unique:users,phone',
            'telegram_username' => 'nullable|string|max:100',
            'password'          => 'required|string|min:6|confirmed',
        ]);

        try {
            DB::beginTransaction();

            $company = Company::create([
                'name'                => $request->company_name,
                'email'               => $request->email,
                'phone'               => $request->phone,
                'subscription_plan'   => '14-Day Free Trial',
                'subscription_status' => 'Active',
                'onboarding_step'     => 'completed',
            ]);

            $user = User::create([
                'company_id'        => $company->id,
                'name'              => $request->name,
                'email'             => $request->email,
                'phone'             => $request->phone,
                'telegram_username' => $request->telegram_username ? ltrim($request->telegram_username, '@') : null,
                'password'          => Hash::make($request->password),
                'role'              => 'Company Admin',
            ]);

            DB::commit();

            // Notify Super Admin (Optional background notification)
            try {
                $superAdmins = User::where('role', 'Super Admin')->whereNotNull('fcm_token')->get();
                $notificationService = new \App\Services\NotificationService();
                foreach ($superAdmins as $admin) {
                    $notificationService->sendPushNotification(
                        $admin->fcm_token,
                        'New Company Registration',
                        "Company {$company->name} registered with 14-day Free Trial.",
                        ['type' => 'registration', 'company_id' => $company->id]
                    );
                }
            } catch (\Exception $ne) {}

            Auth::login($user);

            return redirect()->route('dashboard')
                ->with('success', '🎉 ' . __('messages.welcome') . ' ' . $user->name . '! ' . __('messages.free_trial_badge'));

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Registration failed: ' . $e->getMessage());
        }
    }
}
