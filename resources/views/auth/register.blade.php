<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - HR Attendee</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Kantumruy+Pro:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <style>
        .section-label {
            font-size: 0.74rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            color: var(--auth-primary);
            margin-bottom: 0.6rem;
            display: flex;
            align-items: center;
            gap: 0.45rem;
        }
    </style>
</head>
<body>
    <div class="container-fluid px-3 py-4">
        <div class="auth-split-wrapper register-split">
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
                            <h2>Disciplinary & operations in your hand.</h2>
                            <p>Set up your organization workspace in minutes and empower your HR and management teams with state-of-the-art tools.</p>

                            <ul class="info-feature-list">
                                <li class="info-feature-item">
                                    <div class="info-feature-icon">
                                        <i class="fa-solid fa-users-gear"></i>
                                    </div>
                                    <span>Multi-department structure & customized role access.</span>
                                </li>
                                <li class="info-feature-item">
                                    <div class="info-feature-icon">
                                        <i class="fa-brands fa-telegram"></i>
                                    </div>
                                    <span>Instant Telegram notifications for credentials and updates.</span>
                                </li>
                                <li class="info-feature-item">
                                    <div class="info-feature-icon">
                                        <i class="fa-solid fa-shield-halved"></i>
                                    </div>
                                    <span>Enterprise-grade security, audit logs, and data backups.</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Glass Preview Testimonial Card -->
                    <div class="info-glass-card">
                        <div class="info-glass-avatar">
                            J
                        </div>
                        <div class="info-glass-text">
                            <h6>Jane Hawkins</h6>
                            <small>Product Designer &bull; HR Attendee Corp</small>
                        </div>
                        <i class="fa-solid fa-quote-right opacity-50 ms-auto fs-5"></i>
                    </div>
                </div>

                <!-- Right Column: Registration Form -->
                <div class="col-lg-7 auth-form-col">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="auth-form-header mb-0">
                            <h3>{{ __('messages.register_org') }}</h3>
                            <p>{{ __('messages.register_subtitle') }}</p>
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

                    <!-- Free Trial Badge -->
                    <div class="trial-badge-banner">
                        <i class="fa-solid fa-gift text-primary fs-5"></i>
                        <span>{{ __('messages.free_trial_badge') }}</span>
                    </div>

                    @if(session('error'))
                        <div class="alert alert-danger py-2 px-3 mb-3 small rounded-3" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('register.post') }}" method="POST">
                        @csrf
                        
                        <div class="section-label">
                            <i class="fa-solid fa-building"></i> {{ __('messages.company_info') }}
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="company_name">{{ __('messages.company_name_label') }}</label>
                            <input type="text" id="company_name" name="company_name" class="form-control @error('company_name') is-invalid @enderror" value="{{ old('company_name') }}" required autofocus placeholder="Acme Corporation Cambodia">
                            @error('company_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="section-label mt-2">
                            <i class="fa-solid fa-user-shield"></i> {{ __('messages.admin_name_label') }}
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="name">{{ __('messages.name') }}</label>
                                <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required placeholder="Michael Mitc">
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="email">{{ __('messages.email_label') }}</label>
                                <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required placeholder="admin@company.com">
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="phone">
                                    {{ __('messages.phone_label') }} <span class="text-muted fw-normal">({{ __('messages.optional') }})</span>
                                </label>
                                <input type="text" id="phone" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="012 345 678">
                                <div class="form-text text-muted" style="font-size: 0.73rem;">
                                    {{ __('messages.phone_hint') }}
                                </div>
                                @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="telegram_username">
                                    Telegram Username <span class="text-muted fw-normal">({{ __('messages.optional') }})</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted border-end-0" style="border-radius: 12px 0 0 12px; border: 1.5px solid var(--auth-border); border-right: none;">@</span>
                                    <input type="text" id="telegram_username" name="telegram_username" class="form-control @error('telegram_username') is-invalid @enderror" style="border-left: none; border-radius: 0 12px 12px 0 !important;" value="{{ old('telegram_username') }}" placeholder="username">
                                </div>
                                @error('telegram_username') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="password">{{ __('messages.password_label') }}</label>
                                <div class="input-group">
                                    <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required placeholder="••••••••••••">
                                    <button class="btn btn-eye" type="button" onclick="togglePassword('password')">
                                        <i class="fa-regular fa-eye-slash" id="password-icon"></i>
                                    </button>
                                </div>
                                @error('password') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="password_confirmation">{{ __('messages.confirm_password_label') }}</label>
                                <div class="input-group">
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required placeholder="••••••••••••">
                                    <button class="btn btn-eye" type="button" onclick="togglePassword('password_confirmation')">
                                        <i class="fa-regular fa-eye-slash" id="password_confirmation-icon"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="form-check mb-3 mt-1">
                            <input class="form-check-input" type="checkbox" required id="terms" style="border-radius: 6px;">
                            <label class="form-check-label text-muted small" for="terms">
                                {{ __('messages.agree_terms') }}
                            </label>
                        </div>

                        <button type="submit" class="btn btn-auth-primary mb-3">{{ __('messages.start_free_trial') }}</button>

                        <div class="auth-footer">
                            {{ __('messages.already_account') }} <a href="{{ route('login') }}">{{ __('messages.sign_in') }}</a>
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
