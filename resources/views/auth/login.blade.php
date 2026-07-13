<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | SmartHR SaaS</title>
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
                    {{-- <a class="text-decoration-none d-flex flex-column align-items-center justify-content-center mb-2" href="{{ route('landing') }}">
                        <img src="{{ asset('brand-assets/images/logo.png') }}" alt="SmartHR Logo" style="height: 60px;" class="mb-2">
                        <span class="fw-bold fs-4 text-white">SmartHR<span class="text-primary">SaaS</span></span>
                    </a> --}}
                    <h2 class="fw-bold">Welcome Back</h2>
                    <p class="text-white-50">Sign in to manage your workforce.</p>
                </div>

                <div class="auth-card shadow-lg">
                    <form action="{{ route('login.post') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label small text-white-50">Email Address</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autofocus placeholder="admin@example.com">
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label small text-white-50">Password</label>
                            <div class="input-group">
                                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required placeholder="••••••••">
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword()">
                                    <i class="fa-solid fa-eye" id="password-icon"></i>
                                </button>
                            </div>
                            @error('password') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4 d-flex justify-content-between">
                            <div class="form-check">
                                <input type="checkbox" name="remember" class="form-check-input" id="remember">
                                <label class="form-check-label small text-white-50" for="remember">Remember me</label>
                            </div>
                            <a href="{{ route('password.request') }}" class="small text-indigo text-decoration-none">Forgot password?</a>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mb-3 shadow">SIGN IN</button>

                        <div class="text-center small text-white-50">
                            Don't have an account? <a href="{{ route('register') }}" class="text-indigo fw-bold">Get Started</a>
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
