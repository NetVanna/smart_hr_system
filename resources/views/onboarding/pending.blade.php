@extends('layouts.plain')

@section('title', 'Payment Submitted — Awaiting Review')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
<style>
    body { background: linear-gradient(135deg, #0f0c29, #302b63, #24243e); font-family: 'Inter', sans-serif; }
    .plain-container { max-width: 680px; }

    .step-bar { display: flex; align-items: center; justify-content: center; margin-bottom: 2.5rem; }
    .step-item { display: flex; flex-direction: column; align-items: center; position: relative; flex: 1; }
    .step-item:not(:last-child)::after {
        content: ''; position: absolute; top: 16px; left: 50%; width: 100%;
        height: 2px; background: rgba(255,255,255,0.15); z-index: 0;
    }
    .step-item.done::after { background: rgba(16,185,129,0.5); }
    .step-circle {
        width: 34px; height: 34px; border-radius: 50%; display: flex; align-items: center;
        justify-content: center; font-size: 0.8rem; font-weight: 700; z-index: 1;
        border: 2px solid rgba(255,255,255,0.15); background: rgba(255,255,255,0.05); color: rgba(255,255,255,0.3);
    }
    .step-item.active .step-circle { border-color: #f59e0b; background: #f59e0b; color: #fff; box-shadow: 0 0 18px rgba(245,158,11,0.5); }
    .step-item.done .step-circle   { border-color: #10b981; background: #10b981; color: #fff; }
    .step-label { font-size: 0.7rem; margin-top: 6px; color: rgba(255,255,255,0.35); font-weight: 500; letter-spacing: 0.05em; text-transform: uppercase; }
    .step-item.active .step-label  { color: #fcd34d; }
    .step-item.done .step-label    { color: #6ee7b7; }

    .pending-card {
        background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08);
        border-radius: 24px; padding: 3rem 2.5rem; backdrop-filter: blur(20px); text-align: center;
    }

    /* Pulsing hourglass */
    .pulse-icon {
        width: 90px; height: 90px; border-radius: 50%; margin: 0 auto 1.5rem;
        display: flex; align-items: center; justify-content: center;
        background: rgba(245,158,11,0.12); border: 2px solid rgba(245,158,11,0.3);
        animation: pulseAnim 2.5s ease-in-out infinite;
    }
    .pulse-icon i { color: #f59e0b; font-size: 2.2rem; }
    @keyframes pulseAnim {
        0%, 100% { box-shadow: 0 0 0 0 rgba(245,158,11,0.25); }
        50%       { box-shadow: 0 0 0 20px rgba(245,158,11,0); }
    }

    .headline { color: #fff; font-size: 1.8rem; font-weight: 800; letter-spacing: -0.03em; }
    .subline  { color: rgba(255,255,255,0.45); font-size: 0.95rem; margin-top: 0.5rem; }

    .info-box {
        background: rgba(245,158,11,0.08); border: 1px solid rgba(245,158,11,0.2);
        border-radius: 14px; padding: 1.2rem 1.5rem; margin: 2rem 0; text-align: left;
    }
    .info-row { display: flex; align-items: flex-start; gap: 0.8rem; margin-bottom: 0.7rem; }
    .info-row:last-child { margin-bottom: 0; }
    .info-row i { color: #f59e0b; margin-top: 0.15rem; flex-shrink: 0; }
    .info-row span { color: rgba(255,255,255,0.65); font-size: 0.87rem; line-height: 1.5; }
    .info-row span strong { color: #fff; }

    .telegram-box {
        background: rgba(0,136,204,0.1); border: 1px solid rgba(0,136,204,0.25);
        border-radius: 14px; padding: 1.2rem 1.5rem; margin-bottom: 2rem;
        display: flex; align-items: center; gap: 1rem; text-align: left;
    }
    .tg-icon { font-size: 2rem; flex-shrink: 0; }
    .tg-text { color: rgba(255,255,255,0.65); font-size: 0.87rem; line-height: 1.5; }
    .tg-text strong { color: #38bdf8; font-size: 1rem; }

    .receipt-detail {
        background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.07);
        border-radius: 10px; padding: 0.5rem 1rem; display: inline-flex;
        align-items: center; gap: 0.5rem; font-size: 0.8rem;
        color: rgba(255,255,255,0.4); margin-bottom: 1.5rem;
    }
    .receipt-detail i { color: #10b981; }

    .btn-login {
        display: inline-flex; align-items: center; gap: 0.6rem;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        border: none; color: #fff; padding: 0.85rem 2rem;
        border-radius: 50px; font-weight: 700; font-size: 0.95rem;
        cursor: pointer; box-shadow: 0 4px 20px rgba(99,102,241,0.4);
        transition: all 0.3s; text-decoration: none;
    }
    .btn-login:hover { color: #fff; transform: translateY(-2px); box-shadow: 0 8px 30px rgba(99,102,241,0.5); }

    .status-badge {
        display: inline-flex; align-items: center; gap: 0.4rem;
        background: rgba(245,158,11,0.1); border: 1px solid rgba(245,158,11,0.25);
        color: #fcd34d; padding: 0.3rem 0.9rem; border-radius: 50px;
        font-size: 0.75rem; font-weight: 700; letter-spacing: 0.06em; text-transform: uppercase;
        margin-bottom: 1.2rem;
    }

    /* Countdown timer */
    .countdown { color: rgba(255,255,255,0.3); font-size: 0.8rem; margin-top: 1rem; }
</style>
@endpush

@section('content')

{{-- Step Bar --}}
<div class="step-bar">
    <div class="step-item done">
        <div class="step-circle"><i class="fa-solid fa-check" style="font-size:0.75rem;"></i></div>
        <div class="step-label">Welcome</div>
    </div>
    <div class="step-item done">
        <div class="step-circle"><i class="fa-solid fa-check" style="font-size:0.75rem;"></i></div>
        <div class="step-label">Choose Plan</div>
    </div>
    <div class="step-item done">
        <div class="step-circle"><i class="fa-solid fa-check" style="font-size:0.75rem;"></i></div>
        <div class="step-label">Payment</div>
    </div>
    <div class="step-item active">
        <div class="step-circle"><i class="fa-solid fa-hourglass-half" style="font-size:0.75rem;"></i></div>
        <div class="step-label">Pending</div>
    </div>
</div>

<div class="pending-card">

    {{-- Pulsing icon --}}
    <div class="pulse-icon">
        <i class="fa-solid fa-hourglass-half"></i>
    </div>

    <div class="status-badge">
        <span style="width:7px;height:7px;border-radius:50%;background:#f59e0b;display:inline-block;"></span>
        Awaiting Review
    </div>

    <h1 class="headline">Payment Submitted!</h1>
    <p class="subline">Your receipt is under review. We'll notify you via Telegram once approved.</p>

    {{-- Submission details --}}
    @if($subscription)
    <div class="receipt-detail">
        <i class="fa-solid fa-circle-check"></i>
        Receipt received on {{ $subscription->created_at->format('d M Y, h:i A') }} ·
        Plan: <strong style="color: #fff; margin-left: 4px;">{{ $subscription->plan }}</strong>
    </div>
    @endif

    {{-- Telegram notification box --}}
    <div class="telegram-box">
        <div class="tg-icon">✈️</div>
        <div class="tg-text">
            <strong>{{ auth()->user()->telegram_username ?? 'Username not set' }}</strong><br>
            Your login credentials will be sent to your Telegram account
            once our team verifies and approves your payment.
        </div>
    </div>

    {{-- What happens next --}}
    <div class="info-box">
        <div class="info-row">
            <i class="fa-solid fa-magnifying-glass"></i>
            <span><strong>Step 1 — Review (5–30 min):</strong> Our team manually verifies your receipt to confirm the bank transfer.</span>
        </div>
        <div class="info-row">
            <i class="fa-brands fa-telegram"></i>
            <span><strong>Step 2 — Credentials via Telegram:</strong> Once approved, your login email and password will be sent to your Telegram.</span>
        </div>
        <div class="info-row">
            <i class="fa-solid fa-rocket"></i>
            <span><strong>Step 3 — Login & Go:</strong> Use the credentials from Telegram to log in and access your full HR dashboard.</span>
        </div>
    </div>

    {{-- CTA — Logout and go to login --}}
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="btn-login" style="width: auto; border: none;">
            <i class="fa-solid fa-right-to-bracket"></i> Go to Login Page
        </button>
    </form>

    <p class="countdown">
        <i class="fa-solid fa-clock me-1"></i>
        Average review time: <strong style="color: rgba(255,255,255,0.5);">5–30 minutes</strong> during business hours
    </p>

    <div class="mt-4">
        <form action="{{ route('logout') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-link text-decoration-none" style="color: rgba(255,255,255,0.2); font-size: 0.78rem;">
                Logout
            </button>
        </form>
    </div>
</div>

@endsection
