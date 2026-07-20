<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'GSC Risk Intelligence') }} - Login</title>
    
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        :root {
            --space-bg: #030712;
            --planet-primary: #3b82f6; /* Blue planet */
            --planet-secondary: #8b5cf6; /* Purple planet */
            --glass-bg: rgba(17, 24, 39, 0.7);
            --glass-border: rgba(255, 255, 255, 0.1);
            --text-primary: #f9fafb;
            --text-secondary: #9ca3af;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--space-bg);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        /* Space/Planet Background Elements */
        .space-bg {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            z-index: 0;
            overflow: hidden;
            background-image: 
                radial-gradient(circle at 15% 50%, rgba(59, 130, 246, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 85% 30%, rgba(139, 92, 246, 0.15) 0%, transparent 50%);
        }

        /* Glowing Planet 1 */
        .planet-1 {
            position: absolute;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--planet-primary), #1d4ed8);
            box-shadow: 0 0 80px rgba(59, 130, 246, 0.4), inset -20px -20px 40px rgba(0,0,0,0.5);
            top: -100px;
            right: -50px;
            opacity: 0.8;
            animation: float 15s ease-in-out infinite alternate;
        }

        /* Glowing Planet 2 */
        .planet-2 {
            position: absolute;
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--planet-secondary), #6d28d9);
            box-shadow: 0 0 60px rgba(139, 92, 246, 0.3), inset -10px -10px 20px rgba(0,0,0,0.5);
            bottom: 10%;
            left: 5%;
            opacity: 0.6;
            animation: float 10s ease-in-out infinite alternate-reverse;
        }

        /* Stars / Particles overlay */
        .stars {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background-image: url('data:image/svg+xml;utf8,<svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg"><circle cx="20" cy="20" r="1" fill="white" opacity="0.3"/><circle cx="80" cy="50" r="1.5" fill="white" opacity="0.5"/><circle cx="150" cy="120" r="1" fill="white" opacity="0.2"/><circle cx="40" cy="160" r="2" fill="white" opacity="0.4"/></svg>');
            background-size: 300px 300px;
            z-index: 0;
            opacity: 0.6;
        }

        @keyframes float {
            0% { transform: translateY(0px) scale(1); }
            100% { transform: translateY(30px) scale(1.05); }
        }

        /* Glassmorphism Login Container */
        .login-container {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 420px;
            padding: 2rem;
            margin: 1rem;
            background: var(--glass-bg);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .login-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .login-header h2 {
            font-weight: 800;
            font-size: 1.75rem;
            letter-spacing: -0.5px;
            margin-bottom: 0.5rem;
            background: linear-gradient(to right, #fff, #9ca3af);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .login-header p {
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        .form-control {
            background-color: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--glass-border);
            color: #fff;
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            border-radius: 12px;
            font-size: 0.9rem;
        }

        .form-control:focus {
            background-color: rgba(255, 255, 255, 0.08);
            border-color: var(--planet-primary);
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
            color: #fff;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.3);
        }

        .input-group-text {
            background: transparent;
            border: none;
            color: var(--text-secondary);
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            z-index: 4;
            padding-left: 1rem;
            pointer-events: none;
        }

        .form-group {
            position: relative;
            margin-bottom: 1.25rem;
        }

        .btn-login {
            background: linear-gradient(135deg, var(--planet-primary), #2563eb);
            color: white;
            font-weight: 600;
            padding: 0.8rem;
            border-radius: 12px;
            border: none;
            width: 100%;
            transition: all 0.3s ease;
            box-shadow: 0 10px 25px -5px rgba(59, 130, 246, 0.4);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px -5px rgba(59, 130, 246, 0.5);
            background: linear-gradient(135deg, #4f46e5, var(--planet-primary));
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .bottom-links {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.85rem;
            color: var(--text-secondary);
        }

        .bottom-links a {
            color: var(--planet-primary);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s;
        }

        .bottom-links a:hover {
            color: #60a5fa;
            text-decoration: underline;
        }

        /* Brand Logo/Icon */
        .brand-icon {
            width: 60px;
            height: 60px;
            background: rgba(59, 130, 246, 0.1);
            border: 1px solid rgba(59, 130, 246, 0.3);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem auto;
            color: var(--planet-primary);
            box-shadow: 0 0 20px rgba(59, 130, 246, 0.2);
        }
    </style>
</head>
<body>

    <!-- Planet & Space Background -->
    <div class="space-bg">
        <div class="stars"></div>
        <div class="planet-1"></div>
        <div class="planet-2"></div>
    </div>

    <!-- Login Card -->
    <div class="login-container">
        <div class="login-header">
            <div class="brand-icon">
                <i data-lucide="globe-2" style="width: 32px; height: 32px;"></i>
            </div>
            <h2>GSC Risk Intelligence</h2>
            <p>Global Supply Chain Monitoring Platform</p>
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <div class="form-group">
                <span class="input-group-text"><i data-lucide="mail" style="width: 18px; height: 18px;"></i></span>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autofocus placeholder="Email Address">
                @error('email')<div class="invalid-feedback ps-2 mt-1" style="font-size:0.8rem;">{{ $message }}</div>@enderror
            </div>

            <div class="form-group mb-4">
                <span class="input-group-text"><i data-lucide="lock" style="width: 18px; height: 18px;"></i></span>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required placeholder="Password">
                @error('password')<div class="invalid-feedback ps-2 mt-1" style="font-size:0.8rem;">{{ $message }}</div>@enderror
            </div>

            <button type="submit" class="btn-login d-flex justify-content-center align-items-center gap-2">
                Sign In <i data-lucide="arrow-right" style="width: 18px; height: 18px;"></i>
            </button>
            
            <div class="mt-4 p-3 rounded" style="background-color: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); font-size: 0.8rem;">
                <p class="mb-1 text-white fw-bold">Default Credentials:</p>
                <div class="d-flex justify-content-between text-secondary">
                    <span>Admin: admin@gmail.com</span>
                    <span>Pass: admin123</span>
                </div>
            </div>

            <div class="bottom-links">
                Don't have an account? <a href="{{ route('register') }}">Create one now</a>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            lucide.createIcons();
        });
    </script>
</body>
</html>