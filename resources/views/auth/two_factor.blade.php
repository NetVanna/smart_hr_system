@extends('layouts.app')

@section('title', 'Two-Factor Authentication')

@section('content')
<div class="row justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="col-md-5">
        <div class="card shadow-sm border-0">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                        <i class="fa-solid fa-shield-check text-primary fs-2"></i>
                    </div>
                    <h4 class="fw-bold">Security Verification</h4>
                    <p class="text-muted small">A verification code has been generated. Please enter it below to continue.</p>
                </div>

                <form action="{{ route('verify.two_factor') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label small fw-bold">Verification Code</label>
                        <input type="text" name="two_factor_code" class="form-control form-control-lg text-center letter-spacing-lg" placeholder="123456" required autofocus>
                        @error('two_factor_code')
                            <small class="text-danger mt-1 d-block">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary btn-lg">Verify & Continue</button>
                    </div>

                    <div class="text-center">
                        <small class="text-muted">Didn't receive the code?</small>
                        <a href="{{ route('resend.two_factor') }}" class="small text-decoration-none ms-1">Resend Code</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .letter-spacing-lg {
        letter-spacing: 0.5rem;
        font-weight: bold;
    }
</style>
@endsection
