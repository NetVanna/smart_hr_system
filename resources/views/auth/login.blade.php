<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - HR Attendee</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Kantumruy+Pro:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>
    <div class="container-fluid px-3 py-4">
        <div class="auth-split-wrapper">
            <div class="row g-0">
                <!-- Left Column: Information & Brand Showcase -->
                <div class="col-lg-5 auth-info-col">
                    <div>
                        <div class="info-brand-header">
                            <div class="info-brand-logo">
                                <img src="{{ asset('brand-assets/images/hr-attendee-logo.svg') }}" alt="HR Attendee Logo" style="width: 32px; height: 32px;">
                            </div>
                            <div>
                                <div class="info-brand-title">HR Attendee</div>
                                <div class="info-brand-sub">SmartHR Cloud System</div>
                            </div>
                        </div>

                        <div class="info-main-content">
                            <h2>Easy way to confirm your attendance.</h2>
                            <p>Reduce the workload of HR management and keep your workforce connected, punctual, and productive.</p>

                            <ul class="info-feature-list">
                                <li class="info-feature-item">
                                    <div class="info-feature-icon">
                                        <i class="fa-solid fa-qrcode"></i>
                                    </div>
                                    <span>Fast & secure QR code and GPS attendance check-in.</span>
                                </li>
                                <li class="info-feature-item">
                                    <div class="info-feature-icon">
                                        <i class="fa-solid fa-calendar-check"></i>
                                    </div>
                                    <span>Instant leave balance tracking and 1-click approvals.</span>
                                </li>
                                <li class="info-feature-item">
                                    <div class="info-feature-icon">
                                        <i class="fa-solid fa-chart-pie"></i>
                                    </div>
                                    <span>Real-time shift schedules, payroll, and performance logs.</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Glass Preview Testimonial Card -->
                    <div class="info-glass-card">
                        <div class="info-glass-avatar">
                            M
                        </div>
                        <div class="info-glass-text">
                            <h6>Michael Mitc</h6>
                            <small>Lead UI/UX Designer &bull; HR Attendee Corp</small>
                        </div>
                        <i class="fa-solid fa-quote-right opacity-50 ms-auto fs-5"></i>
                    </div>
                </div>

                <!-- Right Column: Authentication Form -->
                <div class="col-lg-7 auth-form-col">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="auth-form-header mb-0">
                            <h3>{{ __('messages.login_title') }} <span class="fs-4">👋</span></h3>
                            <p>{{ __('messages.login_subtitle') }}</p>
                        </div>
                        <div class="auth-lang-pill">
                            <a href="{{ route('locale.change', 'en') }}" class="auth-lang-btn {{ app()->getLocale() == 'en' ? 'active' : '' }}">
                                🇺🇸 EN
                            </a>
                            <a href="{{ route('locale.change', 'kh') }}" class="auth-lang-btn {{ app()->getLocale() == 'kh' ? 'active' : '' }}">
                                🇰🇭 KH
                            </a>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success py-2 px-3 mb-3 small rounded-3" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger py-2 px-3 mb-3 small rounded-3" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('login.post') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="login">{{ __('messages.identifier_label') }}</label>
                            <input type="text" id="login" name="login" class="form-control @error('login') is-invalid @enderror" value="{{ old('login') }}" required autofocus placeholder="{{ __('messages.identifier_placeholder') }}">
                            @error('login') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="password">{{ __('messages.password_label') }}</label>
                            <div class="input-group">
                                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required placeholder="••••••••••••">
                                <button class="btn btn-eye" type="button" onclick="togglePassword()" id="btn-toggle-pwd">
                                    <i class="fa-regular fa-eye-slash" id="password-icon"></i>
                                </button>
                            </div>
                            @error('password') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <div class="form-check">
                                <input type="checkbox" name="remember" class="form-check-input" id="remember" style="border-radius: 6px;">
                                <label class="form-check-label text-muted small" for="remember">{{ __('messages.remember_me') }}</label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-auth-primary mb-3">{{ __('messages.sign_in') }}</button>

                        <div class="auth-footer">
                            {{ __('messages.no_account') }} <a href="{{ route('register') }}">{{ __('messages.register') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const icon = document.getElementById('password-icon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        }
    </script>
</body>
</html>
