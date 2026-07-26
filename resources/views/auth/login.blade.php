<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'GSC Risk Intelligence') }} - Login</title>
    
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        :root {
            --primary: #4facfe;
            --primary-dark: #00f2fe;
            --text-dark: #1E2D1D;
            --text-muted: #6B7A68;
            --bg-light: #f8f9fa;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-light);
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
        }

        /* Split Screen Layout */
        .login-wrapper {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }

        /* Left Side: Graphic/Branding */
        .brand-section {
            flex: 1;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            position: relative;
            overflow: hidden;
            display: none;
            color: white;
            align-items: center;
            justify-content: center;
            padding: 4rem;
        }

        @media (min-width: 992px) {
            .brand-section {
                display: flex;
            }
        }

        /* Animated abstract shapes */
        .shape {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(5px);
            border-radius: 50%;
            animation: float 20s infinite linear;
        }
        
        .shape-1 { width: 300px; height: 300px; top: -100px; left: -100px; }
        .shape-2 { width: 500px; height: 500px; bottom: -150px; right: -100px; animation-direction: reverse; }
        .shape-3 { width: 200px; height: 200px; top: 40%; left: 60%; animation-duration: 15s; }

        @keyframes float {
            0% { transform: rotate(0deg) translate(0, 0); }
            50% { transform: rotate(180deg) translate(30px, 30px); }
            100% { transform: rotate(360deg) translate(0, 0); }
        }

        .brand-content {
            position: relative;
            z-index: 10;
            max-width: 500px;
        }

        .brand-content h1 {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            line-height: 1.2;
            letter-spacing: -1px;
        }

        .brand-content p {
            font-size: 1.15rem;
            opacity: 0.9;
            line-height: 1.6;
            margin-bottom: 2rem;
        }

        /* Glassmorphism card in brand section */
        .floating-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 2rem;
            transform: translateY(0);
            transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        }
        
        .floating-card:hover {
            transform: translateY(-10px);
        }

        /* Right Side: Login Form */
        .form-section {
            flex: 1;
            max-width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background-color: #ffffff;
            position: relative;
        }

        @media (min-width: 992px) {
            .form-section {
                max-width: 600px;
            }
        }

        .form-container {
            width: 100%;
            max-width: 420px;
        }

        .mobile-brand {
            display: block;
            text-align: center;
            margin-bottom: 2.5rem;
        }

        @media (min-width: 992px) {
            .mobile-brand { display: none; }
        }
        
        .mobile-brand-icon {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border-radius: 18px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            margin-bottom: 1.2rem;
            box-shadow: 0 10px 20px rgba(79, 172, 254, 0.3);
        }

        .login-header {
            margin-bottom: 2.5rem;
        }

        .login-header h2 {
            font-weight: 800;
            font-size: 2.2rem;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
            letter-spacing: -0.5px;
        }

        .login-header p {
            color: var(--text-muted);
            font-size: 1.05rem;
        }

        .form-control {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            color: var(--text-dark);
            padding: 0.85rem 1rem 0.85rem 3rem;
            border-radius: 14px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            background-color: #ffffff;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(79, 172, 254, 0.15);
            color: var(--text-dark);
        }

        .form-control::placeholder {
            color: #adb5bd;
        }

        .input-group-text {
            background: transparent;
            border: none;
            color: var(--text-muted);
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            z-index: 4;
            padding-left: 1.2rem;
            pointer-events: none;
        }

        .form-group {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .btn-login {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            font-weight: 600;
            padding: 0.9rem;
            border-radius: 14px;
            border: none;
            width: 100%;
            font-size: 1.05rem;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            box-shadow: 0 10px 20px -5px rgba(79, 172, 254, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 25px -5px rgba(79, 172, 254, 0.5);
            color: white;
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .bottom-links {
            text-align: center;
            margin-top: 2rem;
            font-size: 0.95rem;
            color: var(--text-muted);
        }

        .bottom-links a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s;
        }

        .bottom-links a:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }
        
        .credentials-box {
            background-color: #f8f9fa;
            border: 1px dashed #ced4da;
            border-radius: 14px;
            padding: 1.25rem;
            margin-top: 2.5rem;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }
        
        .credentials-box:hover {
            border-color: var(--primary);
            background-color: #f1f8ff;
        }
    </style>
</head>
<body>

    <div class="login-wrapper">
        <!-- Left Side: Branding -->
        <div class="brand-section">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
            <div class="shape shape-3"></div>
            
            <div class="brand-content">
                <div class="d-inline-flex align-items-center justify-content-center bg-white text-primary rounded-circle mb-4" style="width: 75px; height: 75px; box-shadow: 0 15px 35px rgba(0,0,0,0.1);">
                    <i data-lucide="globe-2" style="width: 40px; height: 40px;"></i>
                </div>
                <h1>Intelligence<br>Meets Supply Chain</h1>
                <p>Monitor global risks, analyze real-time data, and make informed decisions with our cutting-edge AI-powered platform designed for modern enterprises.</p>
                
                <div class="floating-card">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="bg-white bg-opacity-25 rounded p-2 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                            <i data-lucide="shield-alert" class="text-white" style="width: 24px; height: 24px;"></i>
                        </div>
                        <div>
                            <h6 class="mb-1 fw-bold" style="font-size: 1.1rem;">Live Risk Monitoring</h6>
                            <small class="opacity-75" style="font-size: 0.85rem;">Tracking 150+ countries in real-time</small>
                        </div>
                    </div>
                    <div class="progress mt-3" style="height: 8px; background: rgba(255,255,255,0.2);">
                        <div class="progress-bar bg-white rounded-pill" role="progressbar" style="width: 82%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: Form -->
        <div class="form-section">
            <div class="form-container">
                
                <div class="mobile-brand">
                    <div class="mobile-brand-icon">
                        <i data-lucide="globe-2" style="width: 34px; height: 34px;"></i>
                    </div>
                    <h3 class="fw-bold text-dark">GSC Risk Intelligence</h3>
                </div>

                <div class="login-header">
                    <h2>Welcome Back</h2>
                    <p>Enter your credentials to access your dashboard.</p>
                </div>

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    
                    <div class="form-group">
                        <span class="input-group-text"><i data-lucide="mail" style="width: 20px; height: 20px;"></i></span>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autofocus placeholder="Email Address">
                        @error('email')
                            <div class="invalid-feedback ps-2 mt-1" style="font-size:0.85rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-4">
                        <span class="input-group-text"><i data-lucide="lock" style="width: 20px; height: 20px;"></i></span>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required placeholder="Password">
                        @error('password')
                            <div class="invalid-feedback ps-2 mt-1" style="font-size:0.85rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label text-muted fw-medium" for="remember" style="font-size: 0.95rem;">
                                Remember me
                            </label>
                        </div>
                        <a href="#" class="text-primary text-decoration-none fw-semibold" style="font-size: 0.95rem;">Forgot Password?</a>
                    </div>

                    <button type="submit" class="btn-login">
                        Sign In <i data-lucide="arrow-right" style="width: 20px; height: 20px;"></i>
                    </button>
                    

                    <div class="bottom-links">
                        Don't have an account? <a href="{{ route('register') }}">Create an account</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            lucide.createIcons();
        });
    </script>
</body>
</html>