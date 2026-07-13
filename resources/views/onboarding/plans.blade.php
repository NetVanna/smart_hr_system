@extends('layouts.plain')

@section('title', 'Choose Your Plan')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
<style>
    body { background: linear-gradient(135deg, #0f0c29, #302b63, #24243e); font-family: 'Inter', sans-serif; }
    .plain-container { max-width: 1000px; }

    .step-bar { display: flex; align-items: center; justify-content: center; margin-bottom: 2.5rem; }
    .step-item { display: flex; flex-direction: column; align-items: center; position: relative; flex: 1; }
    .step-item:not(:last-child)::after {
        content: ''; position: absolute; top: 16px; left: 50%; width: 100%;
        height: 2px; background: rgba(255,255,255,0.15); z-index: 0;
    }
    .step-item.done::after { background: rgba(16,185,129,0.5); }
    .step-item.active::after { background: rgba(99,102,241,0.4); }
    .step-circle {
        width: 34px; height: 34px; border-radius: 50%; display: flex; align-items: center;
        justify-content: center; font-size: 0.8rem; font-weight: 700; z-index: 1;
        border: 2px solid rgba(255,255,255,0.15); background: rgba(255,255,255,0.05); color: rgba(255,255,255,0.3);
    }
    .step-item.active .step-circle { border-color: #6366f1; background: #6366f1; color: #fff; box-shadow: 0 0 18px rgba(99,102,241,0.6); }
    .step-item.done .step-circle   { border-color: #10b981; background: #10b981; color: #fff; }
    .step-label { font-size: 0.7rem; margin-top: 6px; color: rgba(255,255,255,0.35); font-weight: 500; letter-spacing: 0.05em; text-transform: uppercase; }
    .step-item.active .step-label  { color: #a5b4fc; }
    .step-item.done .step-label    { color: #6ee7b7; }

    .plan-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.2rem; margin: 1.5rem 0; }

    .plan-card {
        position: relative;
        background: rgba(255,255,255,0.04);
        border: 2px solid rgba(255,255,255,0.08);
        border-radius: 20px;
        padding: 2rem 1.5rem 1.5rem;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: center;
    }
    .plan-card:hover { transform: translateY(-6px); border-color: rgba(99,102,241,0.4); background: rgba(99,102,241,0.06); }
    .plan-card.recommended {
        border-color: #6366f1;
        background: rgba(99,102,241,0.08);
        box-shadow: 0 0 40px rgba(99,102,241,0.2);
    }
    .recommend-badge {
        position: absolute; top: -13px; left: 50%; transform: translateX(-50%);
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        color: #fff; font-size: 0.7rem; font-weight: 700;
        padding: 0.25rem 0.9rem; border-radius: 50px; letter-spacing: 0.08em; white-space: nowrap;
    }
    .plan-icon { font-size: 2.2rem; margin-bottom: 0.8rem; }
    .plan-name { font-size: 1.1rem; font-weight: 800; color: #fff; letter-spacing: -0.02em; }
    .plan-price { font-size: 2.5rem; font-weight: 800; color: #fff; line-height: 1; margin: 0.8rem 0 0.3rem; }
    .plan-price sup { font-size: 1.2rem; vertical-align: top; margin-top: 0.6rem; color: rgba(255,255,255,0.7); }
    .plan-price span { font-size: 0.85rem; color: rgba(255,255,255,0.4); font-weight: 400; }
    .plan-employees { font-size: 0.8rem; color: rgba(255,255,255,0.45); margin-bottom: 1.2rem; }

    .plan-features { list-style: none; padding: 0; margin: 0 0 1.5rem; text-align: left; }
    .plan-features li { font-size: 0.82rem; color: rgba(255,255,255,0.6); padding: 0.3rem 0; display: flex; align-items: center; gap: 0.5rem; }
    .plan-features li i { color: #10b981; font-size: 0.7rem; }
    .plan-features li.dim i { color: rgba(255,255,255,0.2); }
    .plan-features li.dim { color: rgba(255,255,255,0.25); }

    .btn-select {
        width: 100%; padding: 0.75rem; border-radius: 50px; font-weight: 700;
        font-size: 0.9rem; border: none; cursor: pointer; transition: all 0.3s;
        background: rgba(255,255,255,0.08); color: #fff; border: 1px solid rgba(255,255,255,0.15);
    }
    .plan-card.recommended .btn-select,
    .btn-select:hover {
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        border-color: transparent;
        box-shadow: 0 4px 15px rgba(99,102,241,0.4);
    }

    .section-header { text-align: center; color: #fff; margin-bottom: 2rem; }
    .section-header h2 { font-size: 1.8rem; font-weight: 800; letter-spacing: -0.03em; }
    .section-header p  { color: rgba(255,255,255,0.45); font-size: 0.95rem; }
</style>
@endpush

@section('content')

{{-- Step Bar --}}
<div class="step-bar">
    <div class="step-item done">
        <div class="step-circle"><i class="fa-solid fa-check" style="font-size:0.75rem;"></i></div>
        <div class="step-label">Welcome</div>
    </div>
    <div class="step-item active">
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

<div class="section-header">
    <h2>Pick the perfect plan for your team</h2>
    <p>All plans include a 14-day free trial. No hidden fees.</p>
</div>

<form action="{{ route('onboarding.plans.select') }}" method="POST" id="planForm">
    @csrf
    <input type="hidden" name="plan" id="selectedPlan" value="">

    <div class="plan-grid">

        {{-- Starter --}}
        <div class="plan-card" onclick="selectPlan('Starter', this)">
            <div class="plan-icon">🚀</div>
            <div class="plan-name">Starter</div>
            <div class="plan-price"><sup>$</sup>19<span>/year</span></div>
            <div class="plan-employees">Up to 10 employees</div>
            <ul class="plan-features">
                <li><i class="fa-solid fa-circle-check"></i> Employee Management</li>
                <li><i class="fa-solid fa-circle-check"></i> Attendance Tracking</li>
                <li><i class="fa-solid fa-circle-check"></i> Leave Management</li>
                <li><i class="fa-solid fa-circle-check"></i> Basic Payroll</li>
                <li class="dim"><i class="fa-solid fa-circle-xmark"></i> Performance Reviews</li>
                <li class="dim"><i class="fa-solid fa-circle-xmark"></i> Recruitment</li>
            </ul>
            <button type="button" class="btn-select">Select Starter</button>
        </div>

        {{-- Pro (recommended) --}}
        <div class="plan-card recommended" onclick="selectPlan('Pro', this)">
            <div class="recommend-badge">⭐ Most Popular</div>
            <div class="plan-icon">💼</div>
            <div class="plan-name">Pro</div>
            <div class="plan-price"><sup>$</sup>49<span>/year</span></div>
            <div class="plan-employees">Up to 50 employees</div>
            <ul class="plan-features">
                <li><i class="fa-solid fa-circle-check"></i> Everything in Starter</li>
                <li><i class="fa-solid fa-circle-check"></i> Performance Reviews</li>
                <li><i class="fa-solid fa-circle-check"></i> Training Management</li>
                <li><i class="fa-solid fa-circle-check"></i> Skill Matrix</li>
                <li><i class="fa-solid fa-circle-check"></i> Recruitment Module</li>
                <li><i class="fa-solid fa-circle-check"></i> Document Management</li>
            </ul>
            <button type="button" class="btn-select">Select Pro</button>
        </div>

        {{-- Enterprise --}}
        <div class="plan-card" onclick="selectPlan('Enterprise', this)">
            <div class="plan-icon">🏢</div>
            <div class="plan-name">Enterprise</div>
            <div class="plan-price"><sup>$</sup>99<span>/year</span></div>
            <div class="plan-employees">Unlimited employees</div>
            <ul class="plan-features">
                <li><i class="fa-solid fa-circle-check"></i> Everything in Pro</li>
                <li><i class="fa-solid fa-circle-check"></i> GPS Geofencing</li>
                <li><i class="fa-solid fa-circle-check"></i> Asset Tracking</li>
                <li><i class="fa-solid fa-circle-check"></i> Shift Management</li>
                <li><i class="fa-solid fa-circle-check"></i> Audit Logs</li>
                <li><i class="fa-solid fa-circle-check"></i> Priority Support</li>
            </ul>
            <button type="button" class="btn-select">Select Enterprise</button>
        </div>
    </div>

    @error('plan')
        <div class="alert alert-danger text-center mt-2">{{ $message }}</div>
    @enderror
</form>

@push('scripts')
<script>
function selectPlan(name, card) {
    document.getElementById('selectedPlan').value = name;
    document.querySelectorAll('.plan-card').forEach(c => c.classList.remove('selected-active'));
    card.classList.add('selected-active');
    setTimeout(() => document.getElementById('planForm').submit(), 300);
}
</script>
<style>
.plan-card.selected-active { border-color: #10b981 !important; box-shadow: 0 0 40px rgba(16,185,129,0.3) !important; }
</style>
@endpush
@endsection
