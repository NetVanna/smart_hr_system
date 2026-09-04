<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'SmartHR SaaS') }} - @yield('title')</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    @if(app()->getLocale() === 'kh')
        <!-- Kantumruy Pro – modern Khmer font -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:ital,wght@0,100..700;1,100..700&display=swap" rel="stylesheet">
    @else
        <!-- Inter – clean modern Latin font -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @endif

    <!-- Attendee Design System Theme -->
    <link rel="stylesheet" href="{{ asset('css/theme-attendee.css') }}">

    <style>
        /* ── Base font ── */
        @if(app()->getLocale() === 'kh')
        :root {
            --font-main: 'Kantumruy Pro', 'Noto Sans Khmer', sans-serif;
        }
        body, button, input, select, textarea, .navbar, .sidebar,
        .dropdown-item, .btn, h1, h2, h3, h4, h5, h6, small, span, p, a {
            font-family: var(--font-main) !important;
            font-size: 0.97rem;
            line-height: 1.85;
            letter-spacing: 0.01em;
        }
        .sidebar a { line-height: 1.9; }
        h4 { font-size: 1.2rem; font-weight: 700; }
        small { font-size: 0.82rem; }
        .btn-sm { font-size: 0.875rem; }
        .dropdown-item { font-size: 0.93rem; }
        @else
        :root {
            --font-main: 'Inter', system-ui, -apple-system, sans-serif;
        }
        body, button, input, select, textarea {
            font-family: var(--font-main);
        }
        @endif

        .sidebar-wrapper {
            width: 270px;
            flex-shrink: 0;
        }
        .main-wrapper {
            flex-grow: 1;
            min-width: 0;
            background-color: var(--bg-canvas);
            min-height: 100vh;
        }
        .lang-flag { font-size: 1rem; }
    </style>
    @stack('styles')
</head>
<body>
    @auth
    <div class="d-flex min-vh-100">
        <!-- Sidebar -->
        <aside class="sidebar sidebar-wrapper p-0 d-none d-lg-block">
            <div class="brand-header d-flex align-items-center gap-3">
                <div class="brand-logo-wrap">
                    <img src="{{ asset('brand-assets/images/hr-attendee-logo.svg') }}" alt="HR Attendee Logo" style="height: 30px; width: 30px;">
                </div>
                <div class="d-flex flex-column">
                    <span class="brand-title">HR Attendee</span>
                    <span class="brand-subtitle text-truncate" style="max-width: 155px;">{{ auth()->user()->company->name ?? __('messages.system_admin') }}</span>
                </div>
            </div>
            <ul class="nav flex-column mt-2 pb-5">
                {{-- ============ Company Admin & HR Manager Sidebar (Grouped Step-by-Step) ============ --}}
                @if(auth()->user()->role === 'Company Admin' || auth()->user()->role === 'HR Manager')
                {{-- Step 1: Dashboard --}}
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link d-flex align-items-center {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="fa-solid fa-gauge me-2"></i> {{ __('messages.dashboard') }}
                        <span class="ms-auto d-flex gap-1">
                            @if(($sidebarStats['pendingLeaves'] ?? 0) > 0)
                                <span class="badge badge-pill-counter bg-danger">{{ $sidebarStats['pendingLeaves'] }} {{ __('messages.leaves') }}</span>
                            @endif
                            @if(($sidebarStats['openTickets'] ?? 0) > 0)
                                <span class="badge badge-pill-counter bg-warning text-dark">{{ $sidebarStats['openTickets'] }} {{ __('messages.tickets') }}</span>
                            @endif
                        </span>
                    </a>
                </li>

                {{-- Step 2: Company Setup --}}
                <li class="sidebar-nav-section">
                    {{ __('messages.company_setup') }}
                </li>
                <li class="nav-item">
                    <a href="{{ route('departments.index') }}" class="nav-link {{ request()->routeIs('departments.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-building me-2"></i> {{ __('messages.departments') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('branches.index') }}" class="nav-link {{ request()->routeIs('branches.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-map-location-dot me-2"></i> {{ __('messages.branches') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('settings.company.edit') }}" class="nav-link {{ request()->routeIs('settings.company.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-gears me-2"></i> {{ __('messages.company_settings') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('holidays.index') }}" class="nav-link {{ request()->routeIs('holidays.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-calendar-check me-2"></i> {{ __('messages.public_holidays') }}
                    </a>
                </li>
                @if(auth()->user()->role === 'Company Admin')
                <li class="nav-item">
                    <a href="{{ route('company.users.index') }}" class="nav-link {{ request()->routeIs('company.users.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-users-gear me-2"></i> {{ __('messages.team_access') ?? 'Team & Access' }}
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('company.billing.index') }}" class="nav-link {{ request()->routeIs('company.billing.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-credit-card me-2"></i> {{ __('messages.subscription_billing') ?? 'Subscription & Billing' }}
                    </a>
                </li>
                @endif

                {{-- Step 3: Employee Management --}}
                <li class="sidebar-nav-section">
                    {{ __('messages.employee_management') }}
                </li>
                <li class="nav-item">
                    <a href="{{ route('employees.index') }}" class="nav-link {{ request()->routeIs('employees.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-users me-2"></i> {{ __('messages.employees') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('shifts.index') }}" class="nav-link {{ request()->routeIs('shifts.index') ? 'active' : '' }}">
                        <i class="fa-solid fa-clock-rotate-left me-2"></i> {{ __('messages.shift_management') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('shifts.monitor') }}" class="nav-link {{ request()->routeIs('shifts.monitor') ? 'active' : '' }}">
                        <i class="fa-solid fa-eye me-2"></i> {{ __('messages.attendance_monitor') }}
                    </a>
                </li>

                {{-- Step 4: Daily Operations --}}
                <li class="sidebar-nav-section">
                    {{ __('messages.daily_operations') }}
                </li>
                <li class="nav-item">
                    <a href="{{ route('attendances.index') }}" class="nav-link {{ request()->routeIs('attendances.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-clock me-2"></i> {{ __('messages.attendance') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('expenses.index') }}" class="nav-link {{ request()->routeIs('expenses.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-hand-holding-dollar me-2"></i> {{ __('messages.expenses') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('documents.index') }}" class="nav-link {{ request()->routeIs('documents.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-folder-open me-2"></i> {{ __('messages.documents') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('assets.index') }}" class="nav-link {{ request()->routeIs('assets.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-laptop-code me-2"></i> {{ __('messages.asset_tracking') }}
                    </a>
                </li>

                {{-- Step 5: Leave & Payroll --}}
                <li class="sidebar-nav-section">
                    {{ __('messages.leave_payroll') }}
                </li>
                <li class="nav-item">
                    <a href="{{ route('leaves.index') }}" class="nav-link {{ request()->routeIs('leaves.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-calendar-times me-2"></i> {{ __('messages.leaves') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('payrolls.index') }}" class="nav-link {{ request()->routeIs('payrolls.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-file-invoice-dollar me-2"></i> {{ __('messages.payroll_management') }}
                    </a>
                </li>

                {{-- Step 6: Development & Support --}}
                <li class="sidebar-nav-section">
                    {{ __('messages.development_support') }}
                </li>
                <li class="nav-item">
                    <a href="{{ route('evaluations.index') }}" class="nav-link {{ request()->routeIs('evaluations.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-star-half-stroke me-2"></i> {{ __('messages.performance') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('trainings.index') }}" class="nav-link {{ request()->routeIs('trainings.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-graduation-cap me-2"></i> {{ __('messages.training') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('skills.matrix.index') }}" class="nav-link {{ request()->routeIs('skills.matrix.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-layer-group me-2"></i> {{ __('messages.skill_matrix') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('recruitment.index') }}" class="nav-link {{ request()->routeIs('recruitment.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-briefcase me-2"></i> {{ __('messages.recruitment') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('tickets.index') }}" class="nav-link {{ request()->routeIs('tickets.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-headset me-2"></i> {{ __('messages.support') }}
                    </a>
                </li>
                @endif

                {{-- ============ Employee Sidebar (Basic) ============ --}}
                @if(auth()->user()->role === 'Employee')
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="fa-solid fa-gauge me-2"></i> {{ __('messages.dashboard') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('evaluations.index') }}" class="nav-link {{ request()->routeIs('evaluations.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-star-half-stroke me-2"></i> {{ __('messages.performance') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('trainings.index') }}" class="nav-link {{ request()->routeIs('trainings.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-graduation-cap me-2"></i> {{ __('messages.training') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('skills.matrix.index') }}" class="nav-link {{ request()->routeIs('skills.matrix.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-layer-group me-2"></i> {{ __('messages.skill_matrix') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('recruitment.index') }}" class="nav-link {{ request()->routeIs('recruitment.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-briefcase me-2"></i> {{ __('messages.recruitment') }}
                    </a>
                </li>
                @endif

                {{-- ============ Super Admin Sidebar ============ --}}
                @if(auth()->user()->role === 'Super Admin')
                <li class="nav-item">
                    <a href="{{ route('superadmin.dashboard') }}" class="nav-link {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}">
                        <i class="fa-solid fa-gauge me-2"></i> {{ __('messages.dashboard') }}
                    </a>
                </li>
                <li class="sidebar-nav-section">
                    {{ __('messages.system_management') }}
                </li>
                <li class="nav-item">
                    <a href="{{ route('superadmin.companies.index') }}" class="nav-link {{ request()->routeIs('superadmin.companies.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-building-user me-2"></i> {{ __('messages.manage_companies') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('superadmin.subscriptions.index') }}" class="nav-link {{ request()->routeIs('superadmin.subscriptions.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-credit-card me-2"></i> {{ __('messages.subscriptions_billing') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('audit.index') }}" class="nav-link {{ request()->routeIs('audit.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-shield-halved me-2"></i> {{ __('messages.audit_logs') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('health.index') }}" class="nav-link {{ request()->routeIs('health.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-heart-pulse me-2"></i> {{ __('messages.system_health') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('tickets.index') }}" class="nav-link {{ request()->routeIs('tickets.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-headset me-2"></i> {{ __('messages.support_tickets') }}
                    </a>
                </li>
                @endif
            </ul>
        </aside>

        <!-- Main Content Wrapper -->
        <div class="main-wrapper d-flex flex-column">
            @if(session()->has('impersonated_by'))
            <!-- Support Impersonation Banner -->
            <div class="bg-warning text-dark py-2 px-4 d-flex justify-content-between align-items-center shadow-sm" style="background-color: #fef3c7 !important; border-bottom: 1px solid #fcd34d; font-size: 0.88rem;">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-warning text-dark border border-dark-subtle fw-bold text-uppercase" style="font-size: 0.72rem;">{{ __('messages.support_mode') }}</span>
                    <span>{{ __('messages.impersonating_msg') }}: <strong>{{ auth()->user()->name }}</strong> ({{ auth()->user()->company?->name }}).</span>
                </div>
                <a href="{{ route('superadmin.impersonate.leave') }}" class="btn btn-sm btn-dark rounded-pill px-3 py-1 fw-bold" style="font-size: 0.78rem;">
                    <i class="fa-solid fa-arrow-right-from-bracket me-1"></i> {{ __('messages.return_to_superadmin') }}
                </a>
            </div>
            @endif

            <!-- Navbar -->
            <nav class="navbar navbar-expand px-4 sticky-top">
                <button class="btn btn-icon-circle d-lg-none me-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar" aria-controls="mobileSidebar">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <div class="d-none d-sm-flex flex-column">
                    <span class="fw-bold text-dark fs-6">@yield('title', __('messages.dashboard'))</span>
                    <small class="text-muted" style="font-size: 0.76rem;">{{ now()->format('l, F j, Y') }}</small>
                </div>
                <div class="ms-auto d-flex align-items-center gap-3">
                    <!-- Notification Bell -->
                    <a href="{{ route('tickets.index') }}" class="btn-icon-circle text-decoration-none position-relative" title="Notifications">
                        <i class="fa-regular fa-bell"></i>
                        @if(($sidebarStats['openTickets'] ?? 0) > 0 || ($sidebarStats['pendingLeaves'] ?? 0) > 0)
                            <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle" style="transform: translate(-10px, 10px) !important;"></span>
                        @endif
                    </a>

                    <!-- Language Selector -->
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle rounded-pill px-3" type="button" data-bs-toggle="dropdown">
                            <i class="fa-solid fa-globe me-1 text-primary"></i>
                            @if(app()->getLocale() === 'kh')
                                &#x1F1F0;&#x1F1ED; KH
                            @else
                                &#x1F1FA;&#x1F1F8; EN
                            @endif
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                            <li>
                                <a class="dropdown-item {{ app()->getLocale() === 'en' ? 'active' : '' }}"
                                   href="{{ route('locale.change', 'en') }}">
                                    &#x1F1FA;&#x1F1F8; {{ __('messages.english') }}
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item {{ app()->getLocale() === 'kh' ? 'active' : '' }}"
                                   href="{{ route('locale.change', 'kh') }}">
                                    &#x1F1F0;&#x1F1ED; {{ __('messages.khmer') }}
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- User Profile & Quick Logout -->
                    <div class="dropdown">
                        <div class="user-profile-badge dropdown-toggle" role="button" data-bs-toggle="dropdown">
                            <div class="user-avatar me-2">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <div class="d-none d-md-flex flex-column text-start me-2">
                                <span class="fw-semibold text-dark text-truncate" style="max-width: 120px; font-size: 0.84rem; line-height: 1.2;">{{ auth()->user()->name }}</span>
                                <span class="badge bg-primary" style="font-size: 0.65rem; padding: 2px 6px;">{{ auth()->user()->role }}</span>
                            </div>
                            <i class="fa-solid fa-chevron-down text-muted" style="font-size: 0.68rem;"></i>
                        </div>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                            <li class="px-3 py-2 border-bottom">
                                <p class="mb-0 fw-bold text-dark">{{ auth()->user()->name }}</p>
                                <small class="text-muted">{{ auth()->user()->email }}</small>
                            </li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST" class="p-1">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger d-flex align-items-center gap-2">
                                        <i class="fa-solid fa-arrow-right-from-bracket"></i> {{ __('messages.logout') }}
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <div class="content">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>
    @else
        @yield('content')
    @endauth

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    @if(auth()->check() && auth()->user()->role === 'Super Admin')
    <script>
        // Store the password from the session if it exists
        const sessionPassword = "{{ session('admin_password') }}";
        const sessionEmail = "{{ session('admin_email') }}";

        async function sendCredentials(username, email, name) {
            const action = confirm({{ json_encode(__('messages.instant_login_fast')) }});

            if (action) {
                // Generate Magic Link
                try {
                    const urlTemplate = "{{ route('superadmin.companies.magic-link', ['username' => '__USERNAME__']) }}";
                    const url = urlTemplate.replace('__USERNAME__', username);

                    const response = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });

                    const result = await response.json();
                    if (result.success) {
                        let message = "🌟 *" + {{ json_encode(__('messages.welcome_to', ['app' => config('app.name', 'SmartHR'), 'name' => '__NAME__'])) }}.replace('__NAME__', name) + "* 🌟\n\n";
                        message += {{ json_encode(__('messages.magic_link_intro')) }} + "\n\n";
                        message += "🌐 *" + {{ json_encode(__('messages.magic_login')) }} + ":* " + result.link + "\n\n";
                        message += "_" + {{ json_encode(__('messages.magic_link_hint')) }} + "_\n";
                        message += {{ json_encode(__('messages.best_regards')) }} + "\n" + {{ json_encode(__('messages.smarthr_team')) }};

                        const telegramUrl = "https://t.me/" + username + "?text=" + encodeURIComponent(message);
                        window.open(telegramUrl, '_blank');
                        return;
                    } else {
                        alert({{ json_encode(__('messages.magic_link_error')) }} + " " + (result.error || "Unknown"));
                    }
                } catch (error) {
                    alert({{ json_encode(__('messages.server_conn_error')) }});
                }
                return;
            }

            // Normal Password Flow (Old logic)
            let defaultPassword = (email === sessionEmail) ? sessionPassword : "";
            const password = prompt({{ json_encode(__('messages.reset_pwd_prompt')) }}, defaultPassword);
            if (password === null) return;

            if (password.length > 0 && password !== sessionPassword) {
                try {
                    const urlTemplate = "{{ route('superadmin.companies.reset-password', ['username' => '__USERNAME__']) }}";
                    const url = urlTemplate.replace('__USERNAME__', username);

                    const response = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ password: password })
                    });
                } catch (error) {}
            }

            let message = "🌟 *" + {{ json_encode(__('messages.welcome_to', ['app' => config('app.name', 'SmartHR'), 'name' => '__NAME__'])) }}.replace('__NAME__', name) + "* 🌟\n\n";
            message += {{ json_encode(__('messages.workspace_ready')) }} + "\n\n";
            message += "📧 *" + {{ json_encode(__('messages.email_label')) }} + ":* `" + email + "`\n";
            if (password) {
                message += "🔑 *" + {{ json_encode(__('messages.password_label')) }} + ":* `" + password + "`\n";
            }
            message += "\n🌐 *" + {{ json_encode(__('messages.login_url')) }} + ":* " + window.location.origin + "/login\n\n";
            message += {{ json_encode(__('messages.pwd_change_hint')) }} + "\n";
            message += {{ json_encode(__('messages.best_regards')) }} + "\n" + {{ json_encode(__('messages.smarthr_team')) }};

            const url = "https://t.me/" + username + "?text=" + encodeURIComponent(message);
            window.open(url, '_blank');
        }
    </script>
    @endif

    @stack('scripts')
</body>
</html>
