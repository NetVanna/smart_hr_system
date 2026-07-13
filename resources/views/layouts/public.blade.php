<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Careers at SmartHR - @yield('title')</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --primary-light: #818cf8;
            --secondary: #64748b;
            --background: #f8fafc;
            --surface: #ffffff;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --glass: rgba(255, 255, 255, 0.7);
            --glass-border: rgba(255, 255, 255, 0.2);
            --indigo-subtle: #eef2ff;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--background);
            color: var(--text-main);
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        .navbar {
            background: var(--glass);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--glass-border);
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar-brand {
            font-weight: 800;
            color: var(--primary-dark) !important;
            font-size: 1.4rem;
            letter-spacing: -0.04em;
        }

        .hero-section {
            background: radial-gradient(circle at top right, rgba(99, 102, 241, 0.08) 0%, transparent 50%),
                        radial-gradient(circle at bottom left, rgba(124, 58, 237, 0.05) 0%, transparent 50%);
            padding: 80px 0 60px;
            text-align: center;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, #1e293b 0%, #475569 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 1.5rem;
            letter-spacing: -0.02em;
        }

        .job-card {
            background: var(--surface);
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 20px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            padding: 1.5rem;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .job-card:hover {
            transform: translateY(-8px) scale(1.01);
            border-color: var(--primary-light);
            box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.08);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border: none;
            padding: 0.8rem 1.75rem;
            font-weight: 600;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(99, 102, 241, 0.25);
            transition: all 0.3s;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.4);
            filter: brightness(1.1);
        }

        .badge-pill {
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.75rem;
            letter-spacing: 0.01em;
        }

        .text-indigo { color: var(--primary-dark); }
        .bg-indigo-subtle { background-color: var(--indigo-subtle); }

        .footer {
            padding: 80px 0 40px;
            background-color: var(--surface);
            border-top: 1px solid rgba(226, 232, 240, 0.8);
            margin-top: 120px;
        }

        .company-name {
            font-weight: 600;
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        .icon-box {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: var(--indigo-subtle);
            color: var(--primary-dark);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.25rem;
            font-size: 1.2rem;
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            color: var(--text-muted);
            font-size: 0.95rem;
            line-height: 1.6;
        }

        @media (max-width: 768px) {
            .hero-title { font-size: 2.5rem; }
        }
    </style>
    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('public.jobs.index') }}">
                <div class="bg-primary text-white p-2 rounded-3 me-2 shadow-sm" style="width:36px; height:36px; display:flex; align-items:center; justify-content:center;">
                    <i class="fa-solid fa-briefcase small"></i>
                </div>
                SmartHR <span class="text-dark ms-1">Careers</span>
            </a>
            <div class="ms-auto d-flex align-items-center gap-3">
                <a href="{{ route('landing') }}" class="btn btn-sm btn-light border-0 bg-transparent text-muted fw-bold">
                    Home
                </a>
            </div>
        </div>
    </nav>

    @yield('content')

    <footer class="footer">
        <div class="container text-center">
            <div class="mb-5">
                <div class="bg-primary text-white p-2 rounded-3 mx-auto mb-3 shadow-sm" style="width:48px; height:48px; display:flex; align-items:center; justify-content:center;">
                    <i class="fa-solid fa-briefcase fs-5"></i>
                </div>
                <h4 class="fw-bold">SmartHR Platform</h4>
                <p class="text-muted mx-auto" style="max-width: 500px;">Optimizing recruitment and HR management for modern teams worldwide.</p>
            </div>
            <div class="d-flex justify-content-center gap-4 mb-5 text-muted">
                <a href="#" class="text-reset text-decoration-none"><i class="fa-brands fa-linkedin-in fs-5"></i></a>
                <a href="#" class="text-reset text-decoration-none"><i class="fa-brands fa-x-twitter fs-5"></i></a>
                <a href="#" class="text-reset text-decoration-none"><i class="fa-brands fa-github fs-5"></i></a>
            </div>
            <hr class="mb-4 opacity-5">
            <p class="text-muted small mb-0">© {{ date('Y') }} SmartHR SaaS Platform. Modernize your workplace.</p>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
