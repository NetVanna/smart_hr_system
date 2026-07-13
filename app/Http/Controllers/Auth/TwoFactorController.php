<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TwoFactorController extends Controller
{
    public function index()
    {
        return view('auth.two_factor');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'two_factor_code' => 'required|integer',
        ]);

        $user = auth()->user();

        if ($request->two_factor_code == $user->two_factor_code && now()->lt($user->two_factor_expires_at)) {
            $user->resetTwoFactorCode();
            session(['two_factor_authenticated' => true]);
            
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors(['two_factor_code' => 'The code you entered is invalid or has expired.']);
    }

    public function resend()
    {
        $user = auth()->user();
        $user->generateTwoFactorCode();
        
        // In a real app, send email/SMS here. For this demo, we'll just show it in a success message.
        return back()->with('success', 'New code generated: ' . $user->two_factor_code);
    }
}
