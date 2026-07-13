<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MagicLoginController extends Controller
{
    public function login($token)
    {
        $user = User::where('magic_login_token', $token)
            ->where('magic_login_token_expires_at', '>', Carbon::now())
            ->first();

        if (!$user) {
            return redirect()->route('login')->with('error', 'The magic login link is invalid or has expired.');
        }

        // Clear the token so it's one-time use
        $user->update([
            'magic_login_token' => null,
            'magic_login_token_expires_at' => null,
        ]);

        Auth::login($user);

        // If company is not active, redirect to onboarding or show message
        if ($user->role === 'Company Admin' && optional($user->company)->subscription_status === 'Pending') {
            return redirect()->route('onboarding.pending');
        }

        return redirect()->route('dashboard')->with('success', 'Logged in successfully via secure link.');
    }
}
