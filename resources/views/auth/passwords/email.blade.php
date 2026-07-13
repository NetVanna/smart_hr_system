<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password | SmartHR SaaS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body class="d-flex align-items-center">
    <div class="container auth-container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="text-center mb-5">
                    <a class="text-decoration-none d-flex flex-column align-items-center justify-content-center mb-2" href="{{ route('landing') }}">
                        <img src="{{ asset('brand-assets/images/logo.png') }}" alt="SmartHR Logo" style="height: 60px;" class="mb-2">
                        <span class="fw-bold fs-4 text-white">SmartHR<span class="text-primary">SaaS</span></span>
                    </a>
                    <h2 class="fw-bold">Forgot Password?</h2>
                    <p class="text-white-50">Enter your email to receive a reset link.</p>
                </div>

                <div class="auth-card shadow-lg">
                    @if (session('status'))
                        <div class="alert alert-success border-0 bg-success bg-opacity-10 text-success small mb-4">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form action="{{ route('password.email') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label small text-white-50">Email Address</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autofocus placeholder="admin@example.com">
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mb-3 shadow">SEND RESET LINK</button>
                        
                        <div class="text-center small text-white-50">
                            Remember your password? <a href="{{ route('login') }}" class="text-indigo fw-bold">Sign In</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
