<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Get Started | SmartHR SaaS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body class="d-flex align-items-center">
    <div class="container auth-container">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="text-center mb-5">
                    <a class="text-decoration-none d-flex align-items-center justify-content-center mb-2" href="{{ route('landing') }}">
                        <i class="fa-solid fa-bolt-lightning text-primary fs-3 me-2"></i>
                        <span class="fw-bold fs-4 text-white">SmartHR<span class="text-primary">SaaS</span></span>
                    </a>
                    <h2 class="fw-bold">Create Your Organization</h2>
                    <p class="text-white-50">Start managing your workforce smarter today.</p>
                </div>

                <div class="auth-card shadow-lg">
                    <form action="{{ route('register.post') }}" method="POST">
                        @csrf
                        
                        <h5 class="mb-4 text-indigo"><i class="fa-solid fa-building me-2"></i> Company Information</h5>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label small text-white-50">Company Name</label>
                                <input type="text" name="company_name" class="form-control @error('company_name') is-invalid @enderror" value="{{ old('company_name') }}" required placeholder="Acme Inc.">
                                @error('company_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small text-white-50">Business Email</label>
                                <input type="email" name="company_email" class="form-control @error('company_email') is-invalid @enderror" value="{{ old('company_email') }}" required placeholder="info@acme.com">
                                @error('company_email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small text-white-50">Company Phone</label>
                                <input type="text" name="company_phone" class="form-control @error('company_phone') is-invalid @enderror" value="{{ old('company_phone') }}" placeholder="+855 ...">
                                @error('company_phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <h5 class="mt-4 mb-4 text-indigo"><i class="fa-brands fa-telegram me-2"></i> Telegram Contact</h5>
                        <div class="mb-3">
                            <label class="form-label small text-white-50">Your Telegram Username</label>
                            <div class="input-group">
                                <span class="input-group-text bg-transparent text-white-50 border-secondary">@</span>
                                <input type="text" name="telegram_username" class="form-control @error('telegram_username') is-invalid @enderror" value="{{ old('telegram_username') }}" required placeholder="your_telegram_username">
                            </div>
                            <div class="form-text text-white-50" style="font-size: 0.77rem;">
                                <i class="fa-solid fa-circle-info me-1"></i>
                                Your login credentials will be sent to this Telegram account after payment is approved.
                            </div>
                            @error('telegram_username') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        <h5 class="mt-4 mb-4 text-indigo"><i class="fa-solid fa-user-shield me-2"></i> Admin Account</h5>
                        <div class="mb-3">
                            <label class="form-label small text-white-50">Administrator Name</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required placeholder="John Doe">
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label small text-white-50">Admin Email Address</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required placeholder="admin@acme.com">
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small text-white-50">Password</label>
                                <div class="input-group">
                                    <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required placeholder="••••••••">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                                        <i class="fa-solid fa-eye" id="password-icon"></i>
                                    </button>
                                </div>
                                @error('password') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small text-white-50">Confirm Password</label>
                                <div class="input-group">
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required placeholder="••••••••">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_confirmation')">
                                        <i class="fa-solid fa-eye" id="password_confirmation-icon"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="form-check mb-4 mt-2">
                            <input class="form-check-input" type="checkbox" required id="terms">
                            <label class="form-check-label small text-white-50 underline" for="terms">
                                I agree to the <a href="#" class="text-indigo text-decoration-none">Terms of Service</a>
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mb-3 shadow">CREATE ORGANIZATION</button>
                        
                        <div class="text-center small text-white-50">
                            Already have an account? <a href="{{ route('login') }}" class="text-indigo fw-bold">Sign In</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(id) {
            const input = document.getElementById(id);
            const icon = document.getElementById(id + '-icon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
