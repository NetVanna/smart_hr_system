<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartHR SaaS — The Modern HR Ecosystem for Cambodia</title>
    <meta name="description" content="SmartHR SaaS is the all-in-one HR platform for Cambodian businesses. GPS attendance, payroll, recruitment, and more. Start your 14-day free trial today.">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --indigo: #6366f1;
            --indigo-dark: #4f46e5;
            --purple: #8b5cf6;
            --teal: #14b8a6;
            --bg: #080b14;
            --bg2: #0d1117;
            --glass: rgba(255,255,255,0.04);
            --border: rgba(255,255,255,0.08);
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: #f1f5f9;
            overflow-x: hidden;
        }

        /* ── NAVBAR ── */
        .navbar {
            background: rgba(8,11,20,0.85);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--border);
            padding: 1rem 0;
            transition: all 0.3s;
        }
        .navbar.scrolled { padding: 0.6rem 0; background: rgba(8,11,20,0.97); }
        .nav-logo { font-weight: 900; font-size: 1.4rem; letter-spacing: -0.04em; }
        .nav-logo span { color: var(--indigo); }
        .nav-link { color: rgba(255,255,255,0.55) !important; font-weight: 500; font-size: 0.9rem; transition: all 0.2s; border-radius: 8px; }
        .nav-link:hover { color: #fff !important; background: rgba(255,255,255,0.05); }
        .nav-link.active { color: #fff !important; background: rgba(99,102,241,0.15) !important; font-weight: 700; }
        .btn-nav-sign { background: transparent; border: 1px solid var(--border); color: rgba(255,255,255,0.7); padding: 0.45rem 1.2rem; border-radius: 8px; font-size: 0.88rem; font-weight: 500; transition: all 0.2s; text-decoration: none; }
        .btn-nav-sign:hover { border-color: rgba(255,255,255,0.3); color: #fff; }
        .btn-nav-cta { background: linear-gradient(135deg, var(--indigo), var(--purple)); border: none; color: #fff; padding: 0.45rem 1.3rem; border-radius: 8px; font-size: 0.88rem; font-weight: 600; transition: all 0.3s; text-decoration: none; box-shadow: 0 4px 12px rgba(99,102,241,0.35); }
        .btn-nav-cta:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(99,102,241,0.5); color: #fff; }

        /* ── HERO ── */
        .hero {
            padding: 160px 0 100px;
            position: relative;
            overflow: hidden;
        }
        .hero::before {
            content: '';
            position: absolute; inset: 0;
            background: radial-gradient(ellipse 80% 50% at 50% -10%, rgba(99,102,241,0.18) 0%, transparent 70%);
            pointer-events: none;
        }
        .hero-eyebrow {
            display: inline-flex; align-items: center; gap: 0.5rem;
            background: rgba(99,102,241,0.12); border: 1px solid rgba(99,102,241,0.25);
            color: #a5b4fc; padding: 0.35rem 1rem; border-radius: 50px;
            font-size: 0.8rem; font-weight: 600; letter-spacing: 0.06em;
            text-transform: uppercase; margin-bottom: 1.5rem;
        }
        .hero-eyebrow .dot { width: 6px; height: 6px; border-radius: 50%; background: #6366f1; animation: pulse 2s infinite; }
        @keyframes pulse { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:0.5;transform:scale(1.3)} }
        .hero-title {
            font-size: clamp(2.8rem, 6vw, 5rem);
            font-weight: 900;
            line-height: 1.08;
            letter-spacing: -0.04em;
            color: #fff;
            margin-bottom: 1.5rem;
        }
        .hero-title .grad {
            background: linear-gradient(135deg, #818cf8 0%, #c084fc 50%, #38bdf8 100%);
            -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent;
        }
        .hero-sub { color: rgba(255,255,255,0.45); font-size: 1.1rem; max-width: 580px; line-height: 1.7; margin-bottom: 2.5rem; }
        .btn-hero-primary {
            background: linear-gradient(135deg, var(--indigo), var(--purple));
            border: none; color: #fff; padding: 0.9rem 2rem; border-radius: 12px;
            font-weight: 700; font-size: 1rem; text-decoration: none;
            box-shadow: 0 8px 25px rgba(99,102,241,0.4); transition: all 0.3s; display: inline-flex; align-items: center; gap: 0.5rem;
        }
        .btn-hero-primary:hover { transform: translateY(-3px); box-shadow: 0 12px 35px rgba(99,102,241,0.55); color: #fff; }
        .btn-hero-ghost {
            background: transparent; border: 1px solid var(--border); color: rgba(255,255,255,0.6);
            padding: 0.9rem 2rem; border-radius: 12px; font-weight: 600; font-size: 1rem;
            text-decoration: none; transition: all 0.3s; display: inline-flex; align-items: center; gap: 0.5rem;
        }
        .btn-hero-ghost:hover { border-color: rgba(255,255,255,0.25); color: #fff; background: rgba(255,255,255,0.04); }
        .hero-trust { color: rgba(255,255,255,0.25); font-size: 0.8rem; margin-top: 1.5rem; }
        .hero-trust strong { color: rgba(255,255,255,0.5); }

        /* Hero dashboard mockup */
        .dashboard-mockup {
            background: rgba(13,17,23,0.9);
            border: 1px solid var(--border);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 40px 80px rgba(0,0,0,0.6), 0 0 60px rgba(99,102,241,0.08);
            position: relative;
        }
        .mockup-bar { background: rgba(255,255,255,0.03); padding: 0.75rem 1.2rem; display: flex; align-items: center; gap: 0.5rem; border-bottom: 1px solid var(--border); }
        .dot-r { width:10px;height:10px;border-radius:50%;background:#ff5f57; }
        .dot-y { width:10px;height:10px;border-radius:50%;background:#febc2e; }
        .dot-g { width:10px;height:10px;border-radius:50%;background:#28c840; }
        .mockup-url { background: rgba(255,255,255,0.04); border: 1px solid var(--border); border-radius: 6px; padding: 0.25rem 0.9rem; font-size: 0.75rem; color: rgba(255,255,255,0.25); margin: 0 auto; flex: 0 0 200px; text-align: center; }
        .mockup-body { padding: 1.5rem; display: flex; gap: 1rem; height: 340px; }
        .mockup-sidebar { width: 120px; flex-shrink: 0; }
        .mock-sidebar-item { background: rgba(255,255,255,0.04); border-radius: 8px; padding: 0.5rem 0.7rem; margin-bottom: 0.4rem; font-size: 0.7rem; color: rgba(255,255,255,0.3); display: flex; align-items: center; gap: 0.4rem; }
        .mock-sidebar-item.active { background: rgba(99,102,241,0.15); color: #a5b4fc; }
        .mockup-main { flex: 1; overflow: hidden; }
        .mock-stat-grid { display: grid; grid-template-columns: repeat(4,1fr); gap: 0.6rem; margin-bottom: 0.9rem; }
        .mock-stat { background: rgba(255,255,255,0.04); border: 1px solid var(--border); border-radius: 10px; padding: 0.7rem; }
        .mock-stat-val { font-size: 1.1rem; font-weight: 700; color: #fff; }
        .mock-stat-label { font-size: 0.6rem; color: rgba(255,255,255,0.3); margin-top: 0.15rem; }
        .mock-chart { background: rgba(255,255,255,0.03); border: 1px solid var(--border); border-radius: 10px; padding: 0.8rem; height: 140px; overflow: hidden; }
        .mock-chart-title { font-size: 0.65rem; color: rgba(255,255,255,0.35); margin-bottom: 0.5rem; }
        .mock-bars { display: flex; align-items: flex-end; gap: 5px; height: 90px; }
        .mock-bar { flex: 1; border-radius: 4px 4px 0 0; background: linear-gradient(180deg, rgba(99,102,241,0.5), rgba(99,102,241,0.1)); transition: all 0.5s; }
        .mock-row { display: grid; grid-template-columns: 1fr 1fr; gap: 0.6rem; margin-top: 0.6rem; }
        .mock-mini { background: rgba(255,255,255,0.03); border: 1px solid var(--border); border-radius: 10px; padding: 0.65rem; font-size: 0.6rem; color: rgba(255,255,255,0.3); }
        .mock-mini-val { font-size: 0.9rem; font-weight: 700; color: #c084fc; margin-top: 0.15rem; }

        /* ── LOGOS ── */
        .logos-strip { padding: 3rem 0; border-top: 1px solid var(--border); border-bottom: 1px solid var(--border); }
        .logos-label { color: rgba(255,255,255,0.2); font-size: 0.75rem; font-weight: 600; letter-spacing: 0.1em; text-transform: uppercase; margin-bottom: 1.5rem; }
        .logo-item { color: rgba(255,255,255,0.15); font-weight: 800; font-size: 1.1rem; letter-spacing: -0.02em; transition: color 0.3s; }
        .logo-item:hover { color: rgba(255,255,255,0.4); }

        /* ── STATS ── */
        .stats-section { padding: 5rem 0; }
        .stat-card { text-align: center; padding: 2rem 1rem; }
        .stat-number { font-size: 3rem; font-weight: 900; letter-spacing: -0.04em; background: linear-gradient(135deg, #fff, #94a3b8); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent; }
        .stat-label { color: rgba(255,255,255,0.4); font-size: 0.88rem; margin-top: 0.3rem; }

        /* ── FEATURES ── */
        .features-section { padding: 5rem 0; }
        .section-eyebrow { color: var(--indigo); font-size: 0.78rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; margin-bottom: 0.8rem; }
        .section-title { font-size: clamp(1.8rem, 4vw, 2.6rem); font-weight: 800; letter-spacing: -0.03em; color: #fff; margin-bottom: 1rem; }
        .section-sub { color: rgba(255,255,255,0.4); font-size: 1rem; max-width: 520px; }
        .feature-card {
            background: var(--glass); border: 1px solid var(--border);
            border-radius: 20px; padding: 1.8rem;
            transition: all 0.35s ease; height: 100%;
            position: relative; overflow: hidden;
        }
        .feature-card::after {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 1px;
            background: linear-gradient(90deg, transparent, rgba(99,102,241,0.5), transparent);
            opacity: 0; transition: opacity 0.35s;
        }
        .feature-card:hover { transform: translateY(-6px); border-color: rgba(99,102,241,0.3); background: rgba(99,102,241,0.06); }
        .feature-card:hover::after { opacity: 1; }
        .f-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; margin-bottom: 1.2rem; }
        .f-title { font-weight: 700; font-size: 1rem; color: #fff; margin-bottom: 0.5rem; }
        .f-desc { color: rgba(255,255,255,0.45); font-size: 0.85rem; line-height: 1.6; }
        .f-tag { display: inline-flex; align-items: center; gap: 0.3rem; background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.2); color: #34d399; padding: 0.15rem 0.6rem; border-radius: 50px; font-size: 0.7rem; font-weight: 600; margin-top: 1rem; }

        /* ── HOW IT WORKS ── */
        .how-section { padding: 5rem 0; }
        .step-card { position: relative; padding-left: 4rem; margin-bottom: 2.5rem; }
        .step-num { position: absolute; left: 0; top: 0; width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, var(--indigo), var(--purple)); display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.85rem; color: #fff; }
        .step-line { position: absolute; left: 17px; top: 36px; bottom: -16px; width: 2px; background: linear-gradient(180deg, var(--indigo), transparent); }
        .step-title { font-weight: 700; color: #fff; margin-bottom: 0.4rem; }
        .step-desc { color: rgba(255,255,255,0.4); font-size: 0.88rem; line-height: 1.6; }

        /* ── PRICING ── */
        .pricing-section { padding: 5rem 0; }
        .price-card {
            background: var(--glass); border: 1px solid var(--border);
            border-radius: 24px; padding: 2rem 1.8rem; height: 100%;
            transition: all 0.3s ease; position: relative;
        }
        .price-card.popular {
            border-color: var(--indigo);
            background: rgba(99,102,241,0.07);
            box-shadow: 0 0 50px rgba(99,102,241,0.12);
        }
        .popular-badge { position: absolute; top: -13px; left: 50%; transform: translateX(-50%); background: linear-gradient(135deg, var(--indigo), var(--purple)); color: #fff; font-size: 0.7rem; font-weight: 700; padding: 0.25rem 0.9rem; border-radius: 50px; white-space: nowrap; letter-spacing: 0.05em; }
        .price-name { font-weight: 700; font-size: 1rem; color: rgba(255,255,255,0.6); margin-bottom: 1.2rem; }
        .price-amount { font-size: 3rem; font-weight: 900; letter-spacing: -0.04em; color: #fff; line-height: 1; }
        .price-amount sup { font-size: 1.3rem; vertical-align: top; margin-top: 0.6rem; color: rgba(255,255,255,0.6); font-weight: 600; }
        .price-amount span { font-size: 0.85rem; font-weight: 400; color: rgba(255,255,255,0.4); }
        .price-limits { background: rgba(255,255,255,0.04); border-radius: 8px; padding: 0.5rem 0.8rem; font-size: 0.8rem; color: rgba(255,255,255,0.4); margin: 1rem 0 1.2rem; }
        .price-features { list-style: none; padding: 0; margin-bottom: 1.5rem; }
        .price-features li { font-size: 0.85rem; color: rgba(255,255,255,0.6); padding: 0.35rem 0; display: flex; align-items: center; gap: 0.6rem; }
        .price-features li i.check { color: #10b981; font-size: 0.75rem; }
        .price-features li.dim { color: rgba(255,255,255,0.2); }
        .price-features li.dim i { color: rgba(255,255,255,0.15); }
        .btn-price { width: 100%; padding: 0.8rem; border-radius: 10px; font-weight: 700; font-size: 0.9rem; border: 1px solid var(--border); background: rgba(255,255,255,0.05); color: #fff; cursor: pointer; transition: all 0.3s; text-decoration: none; display: block; text-align: center; }
        .btn-price:hover { background: rgba(255,255,255,0.1); color: #fff; }
        .price-card.popular .btn-price { background: linear-gradient(135deg, var(--indigo), var(--purple)); border: none; box-shadow: 0 4px 15px rgba(99,102,241,0.4); }
        .price-card.popular .btn-price:hover { box-shadow: 0 6px 25px rgba(99,102,241,0.55); }

        /* ── TESTIMONIALS ── */
        .testimonials-section { padding: 5rem 0; }
        .testimonial-card { background: var(--glass); border: 1px solid var(--border); border-radius: 20px; padding: 1.8rem; height: 100%; }
        .testimonial-stars { color: #f59e0b; font-size: 0.8rem; margin-bottom: 1rem; }
        .testimonial-text { color: rgba(255,255,255,0.65); font-size: 0.9rem; line-height: 1.7; margin-bottom: 1.2rem; font-style: italic; }
        .testimonial-author { display: flex; align-items: center; gap: 0.8rem; }
        .testimonial-avatar { width: 38px; height: 38px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.85rem; flex-shrink: 0; }
        .testimonial-name { font-weight: 600; font-size: 0.88rem; color: #fff; }
        .testimonial-role { font-size: 0.75rem; color: rgba(255,255,255,0.35); }

        /* ── CTA ── */
        .cta-section { padding: 5rem 0; }
        .cta-box {
            background: linear-gradient(135deg, rgba(99,102,241,0.15) 0%, rgba(139,92,246,0.1) 100%);
            border: 1px solid rgba(99,102,241,0.25);
            border-radius: 28px; padding: 4rem 3rem; text-align: center; position: relative; overflow: hidden;
        }
        .cta-box::before { content: ''; position: absolute; inset: 0; background: radial-gradient(ellipse 60% 80% at 50% 120%, rgba(99,102,241,0.2), transparent); pointer-events: none; }
        .cta-title { font-size: clamp(1.8rem, 4vw, 2.6rem); font-weight: 900; letter-spacing: -0.03em; color: #fff; margin-bottom: 1rem; }
        .cta-sub { color: rgba(255,255,255,0.45); margin-bottom: 2rem; max-width: 500px; margin-left: auto; margin-right: auto; }

        /* ── FOOTER ── */
        .footer { 
            padding: 5rem 0 3rem; 
            border-top: 1px solid var(--border); 
            background: linear-gradient(180deg, var(--bg) 0%, #05070a 100%);
        }
        .footer-logo { font-weight: 900; font-size: 1.4rem; letter-spacing: -0.04em; margin-bottom: 1.2rem; display: block; text-decoration: none; color: #fff; }
        .footer-logo span { color: var(--indigo); }
        .footer-desc { color: rgba(255,255,255,0.35); font-size: 0.88rem; line-height: 1.7; margin-bottom: 2rem; }
        .footer-title { font-weight: 700; font-size: 0.95rem; color: #fff; margin-bottom: 1.5rem; letter-spacing: 0.05em; text-transform: uppercase; }
        .footer-links { list-style: none; padding: 0; margin: 0; }
        .footer-links li { margin-bottom: 0.8rem; }
        .footer-links a { color: rgba(255,255,255,0.45); font-size: 0.88rem; text-decoration: none; transition: all 0.2s; }
        .footer-links a:hover { color: var(--indigo); padding-left: 4px; }
        .footer-social { display: flex; gap: 1rem; margin-top: 1.5rem; }
        .social-link { 
            width: 36px; height: 36px; border-radius: 50%; 
            background: rgba(255,255,255,0.04); border: 1px solid var(--border);
            display: flex; align-items: center; justify-content: center;
            color: rgba(255,255,255,0.4); text-decoration: none; transition: all 0.3s;
        }
        .social-link:hover { background: var(--indigo); color: #fff; border-color: var(--indigo); transform: translateY(-3px); }
        .footer-bottom { margin-top: 5rem; padding-top: 2rem; border-top: 1px solid var(--border); }
        .footer-copy { color: rgba(255,255,255,0.25); font-size: 0.82rem; }
        .footer-legal { display: flex; gap: 1.5rem; }
        .footer-legal a { color: rgba(255,255,255,0.25); font-size: 0.82rem; text-decoration: none; transition: color 0.2s; }
        .footer-legal a:hover { color: #fff; }

        /* ── FLOATING GLOW ── */
        .glow-orb { position: absolute; border-radius: 50%; filter: blur(80px); pointer-events: none; }
        .glow-1 { width: 400px; height: 400px; background: rgba(99,102,241,0.12); top: -100px; right: -100px; }
        .glow-2 { width: 300px; height: 300px; background: rgba(139,92,246,0.1); bottom: 100px; left: -80px; }

        /* Scroll fade-in */
        .fade-in { opacity: 0; transform: translateY(24px); transition: opacity 0.7s ease, transform 0.7s ease; }
        .fade-in.visible { opacity: 1; transform: translateY(0); }
        .delay-1 { transition-delay: 0.1s; }
        .delay-2 { transition-delay: 0.2s; }
        .delay-3 { transition-delay: 0.3s; }
        .delay-4 { transition-delay: 0.4s; }
        section { scroll-margin-top: 90px; }
    </style>
</head>
<body>

<!-- ══ NAVBAR ══ -->
<nav class="navbar navbar-expand-lg fixed-top" id="navbar">
    <div class="container">
        <a class="navbar-brand nav-logo text-white d-flex align-items-center gap-2" href="{{ route('landing') }}">
            <img src="{{ asset('brand-assets/images/logo.png') }}" alt="SmartHR Logo" style="height: 32px;">
            <div>SmartHR<span>SaaS</span></div>
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu" style="color:white;">
            <i class="fa-solid fa-bars" style="color:rgba(255,255,255,0.6);"></i>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav mx-auto gap-1">
                <li class="nav-item"><a class="nav-link px-3" href="#hero">Home</a></li>
                <li class="nav-item"><a class="nav-link px-3" href="#features">Features</a></li>
                <li class="nav-item"><a class="nav-link px-3" href="#how">How it Works</a></li>
                <li class="nav-item"><a class="nav-link px-3" href="#pricing">Pricing</a></li>
                <li class="nav-item"><a class="nav-link px-3" href="#testimonials">Reviews</a></li>
            </ul>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('login') }}" class="btn-nav-sign">Sign In</a>
                <a href="{{ route('register') }}" class="btn-nav-cta">Get Started Free</a>
            </div>
        </div>
    </div>
</nav>

<!-- ══ HERO ══ -->
<section class="hero" id="hero">
    <div class="glow-orb glow-1"></div>
    <div class="glow-orb glow-2"></div>
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6" style="position:relative;z-index:1;">
                <div class="hero-eyebrow fade-in">
                    <span class="dot"></span> Built for Cambodia's Workforce
                </div>
                <h1 class="hero-title fade-in delay-1">
                    Run Your Entire<br><span class="grad">HR Operations</span><br>in One Place.
                </h1>
                <p class="hero-sub fade-in delay-2">
                    SmartHR SaaS is the modern workforce platform for Cambodian companies — GPS attendance, payroll, recruitment, training, and Telegram-powered onboarding.
                </p>
                <div class="d-flex flex-wrap gap-3 fade-in delay-3">
                    <a href="{{ route('register') }}" class="btn-hero-primary">
                        Start Free Trial <i class="fa-solid fa-arrow-right"></i>
                    </a>
                    <a href="#features" class="btn-hero-ghost">
                        <i class="fa-solid fa-play-circle"></i> Explore Features
                    </a>
                </div>
                <div class="hero-trust fade-in delay-4">
                    <i class="fa-solid fa-shield-check me-1" style="color:#10b981;"></i>
                    <strong>14-day free trial</strong> · No credit card required · Cancel anytime
                </div>
            </div>
            <div class="col-lg-6 fade-in delay-2">
                <div class="dashboard-mockup">
                    <div class="mockup-bar">
                        <div class="dot-r"></div><div class="dot-y"></div><div class="dot-g"></div>
                        <div class="mockup-url">smarthr.app/dashboard</div>
                    </div>
                    <div class="mockup-body">
                        <div class="mockup-sidebar">
                            <div style="font-size:0.6rem;color:rgba(255,255,255,0.15);font-weight:600;letter-spacing:0.08em;text-transform:uppercase;margin-bottom:0.6rem;">Menu</div>
                            <div class="mock-sidebar-item active"><i class="fa-solid fa-gauge" style="font-size:0.65rem;"></i> Dashboard</div>
                            <div class="mock-sidebar-item"><i class="fa-solid fa-users" style="font-size:0.65rem;"></i> Employees</div>
                            <div class="mock-sidebar-item"><i class="fa-solid fa-clock" style="font-size:0.65rem;"></i> Attendance</div>
                            <div class="mock-sidebar-item"><i class="fa-solid fa-money-bill" style="font-size:0.65rem;"></i> Payroll</div>
                            <div class="mock-sidebar-item"><i class="fa-solid fa-calendar" style="font-size:0.65rem;"></i> Leaves</div>
                            <div class="mock-sidebar-item"><i class="fa-solid fa-briefcase" style="font-size:0.65rem;"></i> Recruit</div>
                        </div>
                        <div class="mockup-main">
                            <div class="mock-stat-grid">
                                <div class="mock-stat"><div class="mock-stat-val" style="color:#818cf8;">142</div><div class="mock-stat-label">Employees</div></div>
                                <div class="mock-stat"><div class="mock-stat-val" style="color:#34d399;">98%</div><div class="mock-stat-label">On Time</div></div>
                                <div class="mock-stat"><div class="mock-stat-val" style="color:#fbbf24;">$52k</div><div class="mock-stat-label">Payroll</div></div>
                                <div class="mock-stat"><div class="mock-stat-val" style="color:#f87171;">4</div><div class="mock-stat-label">Pending</div></div>
                            </div>
                            <div class="mock-chart">
                                <div class="mock-chart-title">MONTHLY ATTENDANCE RATE</div>
                                <div class="mock-bars">
                                    <div class="mock-bar" style="height:60%;"></div>
                                    <div class="mock-bar" style="height:75%;"></div>
                                    <div class="mock-bar" style="height:55%;"></div>
                                    <div class="mock-bar" style="height:90%;"></div>
                                    <div class="mock-bar" style="height:80%;"></div>
                                    <div class="mock-bar" style="height:95%;"></div>
                                    <div class="mock-bar" style="height:85%;background:linear-gradient(180deg,rgba(52,211,153,0.6),rgba(52,211,153,0.1));"></div>
                                    <div class="mock-bar" style="height:88%;"></div>
                                    <div class="mock-bar" style="height:70%;"></div>
                                    <div class="mock-bar" style="height:92%;"></div>
                                    <div class="mock-bar" style="height:78%;"></div>
                                    <div class="mock-bar" style="height:99%;"></div>
                                </div>
                            </div>
                            <div class="mock-row">
                                <div class="mock-mini">Leave Requests<br><div class="mock-mini-val">8 Pending</div></div>
                                <div class="mock-mini">Next Payroll<br><div class="mock-mini-val" style="color:#38bdf8;">Mar 31</div></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ══ TRUSTED LOGOS ══ -->
<div class="logos-strip">
    <div class="container text-center">
        <div class="logos-label">Trusted by businesses across Cambodia</div>
        <div class="d-flex flex-wrap justify-content-center align-items-center gap-4 gap-lg-5">
            <span class="logo-item">ABA Bank</span>
            <span class="logo-item">ACLEDA</span>
            <span class="logo-item">Wing</span>
            <span class="logo-item">TrueMoney</span>
            <span class="logo-item">Cellcard</span>
            <span class="logo-item">Smart</span>
        </div>
    </div>
</div>

<!-- ══ STATS ══ -->
<section class="stats-section">
    <div class="container">
        <div class="row g-4">
            <div class="col-6 col-lg-3 fade-in"><div class="stat-card"><div class="stat-number">500+</div><div class="stat-label">Companies Onboarded</div></div></div>
            <div class="col-6 col-lg-3 fade-in delay-1"><div class="stat-card"><div class="stat-number">25K+</div><div class="stat-label">Employees Managed</div></div></div>
            <div class="col-6 col-lg-3 fade-in delay-2"><div class="stat-card"><div class="stat-number">99.9%</div><div class="stat-label">Platform Uptime</div></div></div>
            <div class="col-6 col-lg-3 fade-in delay-3"><div class="stat-card"><div class="stat-number">4.9★</div><div class="stat-label">Average Rating</div></div></div>
        </div>
    </div>
</section>

<!-- ══ FEATURES ══ -->
<section class="features-section" id="features">
    <div class="container">
        <div class="text-center mb-5 fade-in">
            <div class="section-eyebrow">Everything You Need</div>
            <h2 class="section-title">A Complete HR Ecosystem,<br>Built for the Modern Workplace.</h2>
            <p class="section-sub mx-auto">From hiring to payroll — every HR function in one unified, intelligent platform.</p>
        </div>
        <div class="row g-3">
            <div class="col-lg-4 col-md-6 fade-in">
                <div class="feature-card">
                    <div class="f-icon" style="background:rgba(99,102,241,0.15);"><i class="fa-solid fa-users" style="color:#818cf8;"></i></div>
                    <div class="f-title">Employee Management</div>
                    <div class="f-desc">Manage profiles, departments, roles, and digital ID cards. Full employee lifecycle from hire to retire.</div>
                    <span class="f-tag"><i class="fa-solid fa-circle" style="font-size:0.5rem;"></i> Core Feature</span>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 fade-in delay-1">
                <div class="feature-card">
                    <div class="f-icon" style="background:rgba(16,185,129,0.15);"><i class="fa-solid fa-map-pin" style="color:#34d399;"></i></div>
                    <div class="f-title">GPS Geofenced Attendance</div>
                    <div class="f-desc">Real-time clock-in/out with GPS location validation. Prevent buddy-punching with geofence zones.</div>
                    <span class="f-tag"><i class="fa-solid fa-circle" style="font-size:0.5rem;"></i> Popular</span>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 fade-in delay-2">
                <div class="feature-card">
                    <div class="f-icon" style="background:rgba(245,158,11,0.15);"><i class="fa-solid fa-file-invoice-dollar" style="color:#fbbf24;"></i></div>
                    <div class="f-title">Automated Payroll</div>
                    <div class="f-desc">Auto-calculate salaries, deductions, and bonuses. Multi-currency with KHR/USD exchange rates.</div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 fade-in">
                <div class="feature-card">
                    <div class="f-icon" style="background:rgba(239,68,68,0.15);"><i class="fa-solid fa-briefcase" style="color:#f87171;"></i></div>
                    <div class="f-title">Recruitment Pipeline</div>
                    <div class="f-desc">Post jobs, track applicants through stages, schedule interviews, and onboard hires seamlessly.</div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 fade-in delay-1">
                <div class="feature-card">
                    <div class="f-icon" style="background:rgba(14,165,233,0.15);"><i class="fa-solid fa-graduation-cap" style="color:#38bdf8;"></i></div>
                    <div class="f-title">Training & Skill Matrix</div>
                    <div class="f-desc">Assign training programs, track completion, and visualize skill gaps across your workforce.</div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 fade-in delay-2">
                <div class="feature-card">
                    <div class="f-icon" style="background:rgba(168,85,247,0.15);"><i class="fa-solid fa-chart-line" style="color:#c084fc;"></i></div>
                    <div class="f-title">Performance Reviews</div>
                    <div class="f-desc">360° appraisal cycles, KPI tracking, and goal management to drive employee growth.</div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 fade-in">
                <div class="feature-card">
                    <div class="f-icon" style="background:rgba(20,184,166,0.15);"><i class="fa-solid fa-calendar-check" style="color:#2dd4bf;"></i></div>
                    <div class="f-title">Leave Management</div>
                    <div class="f-desc">Multi-type leave policies, approval workflows, and automatic balance tracking with Khmer holiday calendar.</div>
                    <span class="f-tag"><i class="fa-solid fa-circle" style="font-size:0.5rem;"></i> Cambodia Ready</span>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 fade-in delay-1">
                <div class="feature-card">
                    <div class="f-icon" style="background:rgba(0,136,204,0.15);"><i class="fa-brands fa-telegram" style="color:#38bdf8;"></i></div>
                    <div class="f-title">Telegram Integration</div>
                    <div class="f-desc">Deliver login credentials, alerts, and notifications directly via Telegram. Zero email dependency.</div>
                    <span class="f-tag"><i class="fa-solid fa-circle" style="font-size:0.5rem;"></i> Unique Feature</span>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 fade-in delay-2">
                <div class="feature-card">
                    <div class="f-icon" style="background:rgba(251,191,36,0.15);"><i class="fa-solid fa-boxes-stacked" style="color:#fbbf24;"></i></div>
                    <div class="f-title">Asset Tracking</div>
                    <div class="f-desc">Track company assets assigned to employees, maintenance schedules, and equipment lifecycle.</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ══ HOW IT WORKS ══ -->
<section class="how-section" id="how" style="background: linear-gradient(180deg, rgba(99,102,241,0.04) 0%, transparent 100%);">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-5 fade-in">
                <div class="section-eyebrow">How It Works</div>
                <h2 class="section-title">Up and running in<br>under 30 minutes.</h2>
                <p class="section-sub">A guided onboarding flow gets your entire company operational fast — no IT team required.</p>
            </div>
            <div class="col-lg-7 fade-in delay-1">
                <div class="step-card">
                    <div class="step-num">1</div>
                    <div class="step-line"></div>
                    <div class="step-title">Register Your Organization</div>
                    <div class="step-desc">Enter your company details and admin account. Connect your Telegram for credential delivery.</div>
                </div>
                <div class="step-card">
                    <div class="step-num">2</div>
                    <div class="step-line"></div>
                    <div class="step-title">Choose Your Plan</div>
                    <div class="step-desc">Select Starter, Pro, or Enterprise based on your team size and feature needs.</div>
                </div>
                <div class="step-card">
                    <div class="step-num">3</div>
                    <div class="step-line"></div>
                    <div class="step-title">Pay via KHQR or ABA</div>
                    <div class="step-desc">Scan the QR code with any Cambodian banking app and upload your receipt proof.</div>
                </div>
                <div class="step-card" style="margin-bottom:0;">
                    <div class="step-num" style="background:linear-gradient(135deg,#10b981,#059669);">4</div>
                    <div class="step-title">Get Login via Telegram 🎉</div>
                    <div class="step-desc">After approval (5–30 min), your credentials arrive on Telegram. Log in and start managing your HR.</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ══ PRICING ══ -->
<section class="pricing-section" id="pricing">
    <div class="container">
        <div class="text-center mb-5 fade-in">
            <div class="section-eyebrow">Pricing</div>
            <h2 class="section-title">Simple, Transparent Pricing</h2>
            <p class="section-sub mx-auto">All plans include a 14-day free trial. No hidden fees. Priced for Cambodian SMEs.</p>
        </div>
        <div class="row g-4 justify-content-center">
            <div class="col-lg-4 col-md-6 fade-in">
                <div class="price-card h-100 d-flex flex-column">
                    <div class="price-name">Starter</div>
                    <div class="price-amount"><sup>$</sup>19<span>/year</span></div>
                    <div class="price-limits"><i class="fa-solid fa-users me-1"></i> Up to 10 employees</div>
                    <ul class="price-features flex-grow-1">
                        <li><i class="fa-solid fa-circle-check check"></i> Employee Management</li>
                        <li><i class="fa-solid fa-circle-check check"></i> GPS Attendance</li>
                        <li><i class="fa-solid fa-circle-check check"></i> Leave Management</li>
                        <li><i class="fa-solid fa-circle-check check"></i> Basic Payroll</li>
                        <li><i class="fa-solid fa-circle-check check"></i> Telegram Onboarding</li>
                        <li class="dim"><i class="fa-solid fa-circle-xmark"></i> Performance Reviews</li>
                        <li class="dim"><i class="fa-solid fa-circle-xmark"></i> Recruitment</li>
                    </ul>
                    <a href="{{ route('register') }}" class="btn-price">Get Started</a>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 fade-in delay-1">
                <div class="price-card popular h-100 d-flex flex-column">
                    <div class="popular-badge">⭐ Most Popular</div>
                    <div class="price-name" style="color:#a5b4fc;">Pro</div>
                    <div class="price-amount"><sup>$</sup>49<span>/year</span></div>
                    <div class="price-limits" style="background:rgba(99,102,241,0.1);color:rgba(255,255,255,0.5);"><i class="fa-solid fa-users me-1"></i> Up to 50 employees</div>
                    <ul class="price-features flex-grow-1">
                        <li><i class="fa-solid fa-circle-check check"></i> Everything in Starter</li>
                        <li><i class="fa-solid fa-circle-check check"></i> Performance Reviews</li>
                        <li><i class="fa-solid fa-circle-check check"></i> Training Management</li>
                        <li><i class="fa-solid fa-circle-check check"></i> Skill Matrix</li>
                        <li><i class="fa-solid fa-circle-check check"></i> Recruitment Module</li>
                        <li><i class="fa-solid fa-circle-check check"></i> Document Management</li>
                        <li><i class="fa-solid fa-circle-check check"></i> Asset Tracking</li>
                    </ul>
                    <a href="{{ route('register') }}" class="btn-price">Start Pro Trial</a>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 fade-in delay-2">
                <div class="price-card h-100 d-flex flex-column">
                    <div class="price-name">Enterprise</div>
                    <div class="price-amount"><sup>$</sup>99<span>/year</span></div>
                    <div class="price-limits"><i class="fa-solid fa-infinity me-1"></i> Unlimited employees</div>
                    <ul class="price-features flex-grow-1">
                        <li><i class="fa-solid fa-circle-check check"></i> Everything in Pro</li>
                        <li><i class="fa-solid fa-circle-check check"></i> GPS Geofencing</li>
                        <li><i class="fa-solid fa-circle-check check"></i> Shift Management</li>
                        <li><i class="fa-solid fa-circle-check check"></i> Audit Logs</li>
                        <li><i class="fa-solid fa-circle-check check"></i> Multi-currency Payroll</li>
                        <li><i class="fa-solid fa-circle-check check"></i> API Access</li>
                        <li><i class="fa-solid fa-circle-check check"></i> Priority Support</li>
                    </ul>
                    <a href="{{ route('register') }}" class="btn-price">Contact Sales</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ══ TESTIMONIALS ══ -->
<section class="testimonials-section" id="testimonials">
    <div class="container">
        <div class="text-center mb-5 fade-in">
            <div class="section-eyebrow">Customer Reviews</div>
            <h2 class="section-title">Loved by HR teams<br>across Cambodia.</h2>
        </div>
        <div class="row g-4">
            <div class="col-lg-4 col-md-6 fade-in">
                <div class="testimonial-card">
                    <div class="testimonial-stars">★★★★★</div>
                    <div class="testimonial-text">"SmartHR replaced 3 separate tools for us. GPS attendance and Telegram notifications are game-changers for managing remote staff."</div>
                    <div class="testimonial-author">
                        <div class="testimonial-avatar" style="background:rgba(99,102,241,0.2);color:#818cf8;">SK</div>
                        <div><div class="testimonial-name">Sophea Kim</div><div class="testimonial-role">HR Manager · Phnom Penh Logistics</div></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 fade-in delay-1">
                <div class="testimonial-card">
                    <div class="testimonial-stars">★★★★★</div>
                    <div class="testimonial-text">"The KHQR payment and Telegram delivery is brilliant. Setup took under an hour and our payroll is now fully automated."</div>
                    <div class="testimonial-author">
                        <div class="testimonial-avatar" style="background:rgba(16,185,129,0.2);color:#34d399;">RV</div>
                        <div><div class="testimonial-name">Rithy Vann</div><div class="testimonial-role">CEO · Vann Construction Group</div></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 fade-in delay-2">
                <div class="testimonial-card">
                    <div class="testimonial-stars">★★★★★</div>
                    <div class="testimonial-text">"The recruitment pipeline and skill matrix helped us scale from 30 to 120 employees in 6 months. Incredible platform."</div>
                    <div class="testimonial-author">
                        <div class="testimonial-avatar" style="background:rgba(245,158,11,0.2);color:#fbbf24;">LC</div>
                        <div><div class="testimonial-name">Linda Chan</div><div class="testimonial-role">Operations Director · TechCambo</div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ══ CTA ══ -->
<section class="cta-section">
    <div class="container">
        <div class="cta-box fade-in">
            <div class="section-eyebrow" style="color:#c084fc;">Ready to Get Started?</div>
            <h2 class="cta-title">Transform your HR today.<br>Your team will thank you.</h2>
            <p class="cta-sub">Join 500+ Cambodian companies who trust SmartHR SaaS to manage their most important asset — their people.</p>
            <div class="d-flex flex-wrap justify-content-center gap-3">
                <a href="{{ route('register') }}" class="btn-hero-primary" style="font-size:1.05rem; padding: 1rem 2.5rem;">
                    Start Your Free Trial <i class="fa-solid fa-arrow-right"></i>
                </a>
                <a href="#pricing" class="btn-hero-ghost" style="font-size:1.05rem; padding: 1rem 2.5rem;">
                    View Pricing
                </a>
            </div>
            <p style="color:rgba(255,255,255,0.25);font-size:0.8rem;margin-top:1.5rem;">
                <i class="fa-solid fa-lock me-1"></i>Secure · <i class="fa-solid fa-shield-halved me-1 ms-1"></i>No credit card · <i class="fa-solid fa-rotate-left me-1 ms-1"></i>Cancel anytime
            </p>
        </div>
    </div>
</section>

<!-- ══ FOOTER ══ -->
<footer class="footer">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-4">
                <a href="#" class="footer-logo">
                    <img src="{{ asset('brand-assets/images/logo.png') }}" alt="SmartHR Logo" style="height: 48px;" class="mb-3">
                    <div class="mt-2">SmartHR<span>SaaS</span></div>
                </a>
                <p class="footer-desc">
                    The next-generation HR management ecosystem tailored for Cambodian businesses. Empowering your workforce with cutting-edge technology and seamless automation.
                </p>
                <div class="footer-social">
                    <a href="#" class="social-link"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" class="social-link"><i class="fa-brands fa-x-twitter"></i></a>
                    <a href="#" class="social-link"><i class="fa-brands fa-linkedin-in"></i></a>
                    <a href="#" class="social-link"><i class="fa-brands fa-telegram"></i></a>
                </div>
            </div>
            
            <div class="col-6 col-md-3 col-lg-2">
                <h4 class="footer-title">Platform</h4>
                <ul class="footer-links">
                    <li><a href="#features">Features</a></li>
                    <li><a href="#pricing">Pricing</a></li>
                    <li><a href="{{ route('register') }}">For Companies</a></li>
                    <li><a href="#">Mobile App</a></li>
                    <li><a href="#">Integrations</a></li>
                </ul>
            </div>
            
            <div class="col-6 col-md-3 col-lg-2">
                <h4 class="footer-title">Company</h4>
                <ul class="footer-links">
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Our Vision</a></li>
                    <li><a href="#">Careers</a></li>
                    <li><a href="#">Contact</a></li>
                    <li><a href="#testimonials">Success Stories</a></li>
                </ul>
            </div>
            
            <div class="col-6 col-md-3 col-lg-2">
                <h4 class="footer-title">Resources</h4>
                <ul class="footer-links">
                    <li><a href="#">Documentation</a></li>
                    <li><a href="#">Help Center</a></li>
                    <li><a href="#">HR Blog</a></li>
                    <li><a href="#">Community</a></li>
                    <li><a href="#">System Status</a></li>
                </ul>
            </div>

            <div class="col-6 col-md-3 col-lg-2">
                <h4 class="footer-title">Legal</h4>
                <ul class="footer-links">
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Terms of Service</a></li>
                    <li><a href="#">Cookie Policy</a></li>
                    <li><a href="#">Audit & Security</a></li>
                </ul>
            </div>
        </div>
        
        <div class="footer-bottom">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    <div class="footer-copy">
                        © {{ date('Y') }} SmartHR SaaS. All rights reserved. 
                        <span class="ms-1 d-none d-md-inline text-muted">| Built with <i class="fa-solid fa-heart text-danger"></i> in Cambodia.</span>
                    </div>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <div class="footer-legal justify-content-center justify-content-md-end">
                        <a href="#">Privacy</a>
                        <a href="#">Terms</a>
                        <a href="#">Security</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Navbar scroll effect (+ active link highlight)
    const navbar = document.getElementById('navbar');
    const navLinks = document.querySelectorAll('.nav-link');
    const sections = document.querySelectorAll('section[id]');

    window.addEventListener('scroll', () => {
        // Scrolled background
        navbar.classList.toggle('scrolled', window.scrollY > 30);

        // Active link tracking
        let current = '';
        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.clientHeight;
            if (window.scrollY >= (sectionTop - 120)) {
                current = section.getAttribute('id');
            }
        });

        navLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href') === `#${current}`) {
                link.classList.add('active');
            }
        });
    });

    // Scroll fade-in
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
    }, { threshold: 0.12 });
    document.querySelectorAll('.fade-in').forEach(el => observer.observe(el));

    // Animated bar heights on scroll
    const barObserver = new IntersectionObserver((entries) => {
        entries.forEach(e => {
            if (e.isIntersecting) {
                e.target.querySelectorAll('.mock-bar').forEach((bar, i) => {
                    setTimeout(() => { bar.style.opacity = '1'; }, i * 60);
                });
            }
        });
    }, { threshold: 0.3 });
    document.querySelectorAll('.mock-bars').forEach(el => { el.querySelectorAll('.mock-bar').forEach(b => b.style.opacity = '0'); barObserver.observe(el); });
</script>
</body>
</html>
