<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login'    => ['required', 'string'],
            'password' => ['required'],
        ]);

        $loginInput = trim($request->input('login'));
        $cleanPhone = preg_replace('/[^0-9]/', '', $loginInput);

        // Find user by email, employee_id, or phone
        $user = User::where('email', $loginInput)
            ->orWhere('employee_id', $loginInput)
            ->when(!empty($loginInput), function ($q) use ($loginInput) {
                $q->orWhere('phone', $loginInput);
            })
            ->when(strlen($cleanPhone) >= 7, function ($q) use ($cleanPhone) {
                // Match local Cambodian phone suffixes (e.g. 012345678 vs +85512345678)
                $suffix = substr($cleanPhone, -8);
                $q->orWhere('phone', 'LIKE', '%' . $suffix);
            })
            ->first();

        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();
            return redirect()->intended('dashboard')->with('success', __('messages.welcome') . ' ' . $user->name . '!');
        }

        return back()->withErrors([
            'login' => __('auth.failed', [], app()->getLocale()) ?: 'Invalid credentials. Please check your email, phone, or employee ID.',
        ])->onlyInput('login');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
