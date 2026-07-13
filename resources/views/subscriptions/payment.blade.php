@extends('layouts.plain')

@section('title', 'Activate SmartHR')

@section('content')
<div class="card glass-card shadow-lg p-3">
    <div class="card-body p-md-5">
        <div class="text-center mb-5">
            <h1 class="fw-bold text-primary">Ready to activate?</h1>
            <p class="text-muted fs-5">Scan to pay and unlock your full HR dashboard.</p>
        </div>

        <div class="row align-items-center">
            <div class="col-md-6 mb-4 mb-md-0">
                <div class="bg-white p-3 d-inline-block rounded-4 shadow-sm border mb-4">
                    <img src="{{ asset('images/khqr_payment.png') }}"
                         alt="KHQR Payment QR Code"
                         style="width:280px;height:280px;object-fit:contain;display:block;">
                </div>
                <div class="mt-2 text-center">
                    <span class="badge bg-primary px-3 py-2">Plan: {{ auth()->user()->company->subscription_plan }}</span>
                    <h3 class="mt-3 fw-bold">${{ auth()->user()->company->subscription_plan == 'Pro' ? '49.00' : (auth()->user()->company->subscription_plan == 'Enterprise' ? '99.00' : '19.00') }}/year</h3>
                </div>
            </div>
            
            <div class="col-md-6">
                @php
                    $pendingSub = \App\Models\Subscription::where('company_id', auth()->user()->company_id)
                                    ->where('status', 'Pending Approval')
                                    ->first();
                @endphp

                @if($pendingSub)
                    <div class="text-center py-5">
                        <div class="mb-4">
                            <i class="fa-solid fa-hourglass-half text-warning fs-1 animate-pulse"></i>
                        </div>
                        <h4 class="fw-bold">Verification in Progress</h4>
                        <p class="text-muted">We have received your receipt. Our team is verifying the payment and will activate your account soon.</p>
                        <div class="alert alert-info small border-0">
                            <i class="fa-solid fa-circle-info me-2"></i> Activation usually takes 5-30 minutes.
                        </div>
                    </div>
                @else
                    <div class="alert alert-light border-0 bg-light p-4 rounded-4 mb-4">
                        <h6 class="fw-bold mb-3"><i class="fa-solid fa-circle-info text-primary me-2"></i> How to Activate:</h6>
                        <ul class="list-unstyled small mb-0">
                            <li class="mb-3"><i class="fa-solid fa-check text-success me-2"></i> Open **ABA**, **Bakong**, or any KHQR App.</li>
                            <li class="mb-3"><i class="fa-solid fa-check text-success me-2"></i> Scan the code and complete the transfer.</li>
                            <li><i class="fa-solid fa-check text-success me-2"></i> Upload the receipt screenshot below.</li>
                        </ul>
                    </div>

                    <form action="{{ route('subscription.payment.submit') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-uppercase text-muted">Upload Transaction Receipt</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="fa-solid fa-image"></i></span>
                                <input type="file" name="receipt" class="form-control" required>
                            </div>
                            <div class="form-text small">Accepted: JPEG, PNG (Max 2MB)</div>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary py-3 fw-bold rounded-pill text-uppercase tracking-wider">
                                Submit and Activate
                            </button>
                        </div>
                    </form>
                @endif

                <div class="text-center mt-4">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-link text-decoration-none text-muted small">
                            <i class="fa-solid fa-arrow-left"></i> Logout and return later
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@endsection

