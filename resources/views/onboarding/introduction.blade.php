@extends('layouts.plain')

@section('title', 'Welcome to SmartHR')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
<style>
    body {
        background: linear-gradient(135deg, #0f0c29, #302b63, #24243e);
        font-family: 'Inter', sans-serif;
    }
    .plain-container { max-width: 860px; }

    /* Step indicator */
    .step-bar { display: flex; align-items: center; justify-content: center; gap: 0; margin-bottom: 2.5rem; }
    .step-item { display: flex; flex-direction: column; align-items: center; position: relative; flex: 1; }
    .step-item:not(:last-child)::after {
        content: ''; position: absolute; top: 16px; left: 50%; width: 100%;
        height: 2px; background: rgba(255,255,255,0.15); z-index: 0;
    }
    .step-item.done::after, .step-item.active::after { background: rgba(99,102,241,0.5); }
    .step-circle {
        width: 34px; height: 34px; border-radius: 50%; display: flex; align-items: center;
        justify-content: center; font-size: 0.8rem; font-weight: 700; z-index: 1;
        border: 2px solid rgba(255,255,255,0.15); background: rgba(255,255,255,0.05);
        color: rgba(255,255,255,0.3);
    }
    .step-item.active .step-circle { border-color: #6366f1; background: #6366f1; color: #fff; box-shadow: 0 0 18px rgba(99,102,241,0.6); }
    .step-item.done .step-circle   { border-color: #10b981; background: #10b981; color: #fff; }
    .step-label { font-size: 0.7rem; margin-top: 6px; color: rgba(255,255,255,0.35); font-weight: 500; letter-spacing: 0.05em; text-transform: uppercase; }
    .step-item.active .step-label  { color: #a5b4fc; }
    .step-item.done .step-label    { color: #6ee7b7; }

    /* Card */
    .intro-card {
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 24px;
        padding: 3rem 2.5rem;
        backdrop-filter: blur(20px);
    }
    .feature-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin: 2rem 0; }
    .feature-item {
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 16px;
        padding: 1.2rem 1.4rem;
        display: flex; align-items: flex-start; gap: 0.9rem;
        transition: all 0.3s ease;
    }
    .feature-item:hover { background: rgba(99,102,241,0.1); border-color: rgba(99,102,241,0.3); transform: translateY(-2px); }
    .feature-icon {
        width: 42px; height: 42px; border-radius: 12px; display: flex; align-items: center;
        justify-content: center; font-size: 1.1rem; flex-shrink: 0;
    }
    .feature-title { font-weight: 600; color: #fff; font-size: 0.9rem; margin-bottom: 0.2rem; }
    .feature-desc  { color: rgba(255,255,255,0.5); font-size: 0.78rem; line-height: 1.5; }

    .btn-next {
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        border: none; color: #fff; padding: 0.9rem 2.5rem;
        border-radius: 50px; font-weight: 700; font-size: 1rem;
        letter-spacing: 0.02em; cursor: pointer;
        box-shadow: 0 4px 20px rgba(99,102,241,0.4);
        transition: all 0.3s ease;
    }
    .btn-next:hover { transform: translateY(-2px); box-shadow: 0 8px 30px rgba(99,102,241,0.5); }

    .brand { color: #fff; font-weight: 800; font-size: 1.5rem; letter-spacing: -0.03em; }
    .brand span { color: #818cf8; }
    .headline { font-size: 2rem; font-weight: 800; color: #fff; line-height: 1.2; letter-spacing: -0.03em; }
    .headline em { color: #818cf8; font-style: normal; }
    .subline { color: rgba(255,255,255,0.5); font-size: 1rem; margin-top: 0.5rem; }

    .welcome-badge {
        display: inline-flex; align-items: center; gap: 0.5rem;
        background: rgba(99,102,241,0.15); border: 1px solid rgba(99,102,241,0.3);
        color: #a5b4fc; padding: 0.35rem 1rem; border-radius: 50px;
        font-size: 0.78rem; font-weight: 600; letter-spacing: 0.05em;
        text-transform: uppercase; margin-bottom: 1.2rem;
    }
</style>
@endpush

@section('content')

{{-- Step Progress Bar --}}
<div class="step-bar">
    <div class="step-item active">
        <div class="step-circle">1</div>
        <div class="step-label">Welcome</div>
    </div>
    <div class="step-item">
        <div class="step-circle">2</div>
        <div class="step-label">Choose Plan</div>
    </div>
    <div class="step-item">
        <div class="step-circle">3</div>
        <div class="step-label">Payment</div>
    </div>
    <div class="step-item">
        <div class="step-circle">4</div>
        <div class="step-label">Pending</div>
    </div>
</div>

<div class="intro-card">
    {{-- Header --}}
    <div class="text-center mb-4">
        <div class="welcome-badge">
            <i class="fa-solid fa-party-horn"></i> Account Created Successfully
        </div>
        <div class="headline">Welcome, <em>{{ auth()->user()->name }}</em>!</div>
        <p class="subline">Here's everything SmartHR will do for your team.</p>
    </div>

    {{-- Feature Grid --}}
    <div class="feature-grid">
        <div class="feature-item">
            <div class="feature-icon" style="background: rgba(99,102,241,0.2);">
                <i class="fa-solid fa-users" style="color: #818cf8;"></i>
            </div>
            <div>
                <div class="feature-title">Employee Management</div>
                <div class="feature-desc">Manage profiles, roles, documents, and ID cards in one place.</div>
            </div>
        </div>
        <div class="feature-item">
            <div class="feature-icon" style="background: rgba(16,185,129,0.2);">
                <i class="fa-solid fa-clock" style="color: #34d399;"></i>
            </div>
            <div>
                <div class="feature-title">GPS Attendance</div>
                <div class="feature-desc">Real-time attendance tracking with geofencing and shift monitoring.</div>
            </div>
        </div>
        <div class="feature-item">
            <div class="feature-icon" style="background: rgba(245,158,11,0.2);">
                <i class="fa-solid fa-file-invoice-dollar" style="color: #fbbf24;"></i>
            </div>
            <div>
                <div class="feature-title">Payroll & Expenses</div>
                <div class="feature-desc">Automated payroll calculations with multi-currency support.</div>
            </div>
        </div>
        <div class="feature-item">
            <div class="feature-icon" style="background: rgba(239,68,68,0.2);">
                <i class="fa-solid fa-briefcase" style="color: #f87171;"></i>
            </div>
            <div>
                <div class="feature-title">Recruitment</div>
                <div class="feature-desc">Post jobs, track applicants, and schedule interviews easily.</div>
            </div>
        </div>
        <div class="feature-item">
            <div class="feature-icon" style="background: rgba(14,165,233,0.2);">
                <i class="fa-solid fa-graduation-cap" style="color: #38bdf8;"></i>
            </div>
            <div>
                <div class="feature-title">Training & Skills</div>
                <div class="feature-desc">Assign training programs and track employee skill development.</div>
            </div>
        </div>
        <div class="feature-item">
            <div class="feature-icon" style="background: rgba(168,85,247,0.2);">
                <i class="fa-solid fa-chart-line" style="color: #c084fc;"></i>
            </div>
            <div>
                <div class="feature-title">Performance Reviews</div>
                <div class="feature-desc">360° appraisals, KPI tracking, and goal management.</div>
            </div>
        </div>
    </div>

    {{-- CTA --}}
    <div class="text-center mt-3">
        <form action="{{ route('onboarding.introduction.next') }}" method="POST">
            @csrf
            <button type="submit" class="btn-next">
                Get Started — Choose Your Plan <i class="fa-solid fa-arrow-right ms-2"></i>
            </button>
        </form>
        <p class="mt-3" style="color: rgba(255,255,255,0.3); font-size: 0.8rem;">
            <i class="fa-solid fa-shield-halved me-1"></i> 14-day satisfaction guarantee &nbsp;·&nbsp;
            <i class="fa-solid fa-lock me-1"></i> Secure &amp; encrypted
        </p>
    </div>
</div>
@endsection
