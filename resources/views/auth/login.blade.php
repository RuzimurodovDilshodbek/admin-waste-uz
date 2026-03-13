<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kirish — {{ trans('panel.site_title') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --primary: #16a34a;
            --primary-light: #22c55e;
            --primary-dark: #15803d;
            --dark: #0f172a;
            --dark-2: #1e293b;
        }

        html, body {
            height: 100%;
            font-family: 'Inter', -apple-system, sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        /* ===== LAYOUT ===== */
        .auth-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* ===== LEFT PANEL ===== */
        .auth-left {
            flex: 0 0 52%;
            background: var(--dark);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 48px;
        }

        /* Decorative blobs */
        .auth-left::before {
            content: '';
            position: absolute;
            top: -120px;
            left: -120px;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(22,163,74,0.18) 0%, transparent 65%);
            pointer-events: none;
        }

        .auth-left::after {
            content: '';
            position: absolute;
            bottom: -80px;
            right: -80px;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(34,197,94,0.12) 0%, transparent 65%);
            pointer-events: none;
        }

        .auth-left-grid {
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
            background-size: 48px 48px;
            pointer-events: none;
        }

        /* Brand */
        .auth-brand {
            display: flex;
            align-items: center;
            gap: 14px;
            position: relative;
            z-index: 2;
        }

        .auth-brand-icon {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            box-shadow: 0 8px 24px rgba(22,163,74,0.4);
        }

        .auth-brand-name {
            font-size: 18px;
            font-weight: 800;
            color: #fff;
            letter-spacing: -0.3px;
        }

        .auth-brand-sub {
            font-size: 11px;
            color: #475569;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-top: 2px;
        }

        /* Center content */
        .auth-left-center {
            position: relative;
            z-index: 2;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 40px 0;
        }

        .auth-tagline-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(22,163,74,0.15);
            border: 1px solid rgba(22,163,74,0.3);
            color: #86efac;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 28px;
            width: fit-content;
        }

        .auth-title {
            font-size: 42px;
            font-weight: 800;
            color: #fff;
            line-height: 1.15;
            letter-spacing: -1.5px;
            margin-bottom: 20px;
        }

        .auth-title span {
            color: var(--primary-light);
        }

        .auth-desc {
            font-size: 15px;
            color: #64748b;
            line-height: 1.7;
            max-width: 420px;
            margin-bottom: 48px;
        }

        /* Feature list */
        .auth-features {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .auth-feature {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .auth-feature-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
        }

        .auth-feature-text {
            font-size: 14px;
            color: #94a3b8;
            font-weight: 500;
        }

        .auth-feature-text strong {
            display: block;
            font-size: 14px;
            font-weight: 700;
            color: #e2e8f0;
            margin-bottom: 2px;
        }

        /* Bottom copyright */
        .auth-left-bottom {
            position: relative;
            z-index: 2;
            font-size: 12px;
            color: #334155;
            font-weight: 500;
        }

        /* ===== RIGHT PANEL ===== */
        .auth-right {
            flex: 1;
            background: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px 32px;
            position: relative;
        }

        .auth-right::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 1px;
            height: 100%;
            background: linear-gradient(to bottom, transparent, #e2e8f0 20%, #e2e8f0 80%, transparent);
        }

        .auth-form-wrap {
            width: 100%;
            max-width: 400px;
        }

        /* Form header */
        .auth-form-header {
            text-align: center;
            margin-bottom: 36px;
        }

        .auth-form-avatar {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            margin: 0 auto 20px;
            box-shadow: 0 12px 32px rgba(22,163,74,0.35);
        }

        .auth-form-title {
            font-size: 24px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.5px;
            margin-bottom: 8px;
        }

        .auth-form-subtitle {
            font-size: 14px;
            color: #94a3b8;
            font-weight: 500;
        }

        /* Input groups */
        .auth-field {
            margin-bottom: 16px;
        }

        .auth-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #475569;
            margin-bottom: 6px;
        }

        .auth-input-wrap {
            position: relative;
        }

        .auth-input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 14px;
            color: #94a3b8;
            pointer-events: none;
            transition: color 0.2s;
        }

        .auth-input {
            width: 100%;
            padding: 12px 14px 12px 42px;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            font-weight: 500;
            color: #0f172a;
            background: #fff;
            transition: all 0.2s;
            outline: none;
        }

        .auth-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(22,163,74,0.1);
        }

        .auth-input:focus ~ .auth-input-icon {
            color: var(--primary);
        }

        .auth-input::placeholder { color: #cbd5e1; }

        .auth-input.is-invalid {
            border-color: #ef4444;
            box-shadow: 0 0 0 4px rgba(239,68,68,0.08);
        }

        .auth-error {
            font-size: 12px;
            color: #ef4444;
            margin-top: 5px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* Password toggle */
        .auth-pwd-toggle {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 14px;
            color: #94a3b8;
            cursor: pointer;
            transition: color 0.2s;
            background: none;
            border: none;
            padding: 0;
        }
        .auth-pwd-toggle:hover { color: var(--primary); }

        /* Options row */
        .auth-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            margin-top: 4px;
        }

        .auth-remember {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }

        .auth-remember input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: var(--primary);
            cursor: pointer;
        }

        .auth-remember-label {
            font-size: 13px;
            color: #64748b;
            font-weight: 500;
            cursor: pointer;
            user-select: none;
        }

        .auth-forgot {
            font-size: 13px;
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
            transition: color 0.2s;
        }

        .auth-forgot:hover { color: var(--primary-dark); text-decoration: underline; }

        /* Submit button */
        .auth-submit {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 700;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 6px 20px rgba(22,163,74,0.35);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            letter-spacing: -0.2px;
        }

        .auth-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 28px rgba(22,163,74,0.45);
        }

        .auth-submit:active { transform: translateY(0); }

        /* Alert */
        .auth-alert {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #15803d;
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .auth-alert-err {
            background: #fef2f2;
            border-color: #fecaca;
            color: #dc2626;
        }

        /* Divider */
        .auth-divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 24px 0;
            color: #cbd5e1;
            font-size: 12px;
            font-weight: 600;
        }
        .auth-divider::before,
        .auth-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e2e8f0;
        }

        /* Security note */
        .auth-security {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            margin-top: 24px;
            font-size: 12px;
            color: #94a3b8;
            font-weight: 500;
        }

        .auth-security i { color: var(--primary); font-size: 13px; }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 900px) {
            .auth-left { display: none; }
            .auth-right { background: #fff; }
            .auth-right::before { display: none; }
        }

        @media (max-width: 480px) {
            .auth-right { padding: 32px 20px; }
            .auth-form-title { font-size: 20px; }
        }
    </style>
</head>
<body>
<div class="auth-wrapper">

    {{-- ===== LEFT PANEL ===== --}}
    <div class="auth-left">
        <div class="auth-left-grid"></div>

        {{-- Brand --}}
        <div class="auth-brand">
            <div class="auth-brand-icon">♻</div>
            <div>
                <div class="auth-brand-name">{{ trans('panel.site_title') }}</div>
                <div class="auth-brand-sub">Admin boshqaruv tizimi</div>
            </div>
        </div>

        {{-- Center --}}
        <div class="auth-left-center">
            <div class="auth-tagline-badge">
                <i class="fas fa-leaf"></i>
                Ekologik boshqaruv
            </div>
            <h1 class="auth-title">
                Chiqindilarni<br>
                <span>aqlli boshqarish</span><br>
                tizimi
            </h1>
            <p class="auth-desc">
                Yangiliklar, e'lonlar va kuzatuv kameralarini bir markazdan boshqaring. Tez, qulay va xavfsiz.
            </p>

            <div class="auth-features">
                <div class="auth-feature">
                    <div class="auth-feature-icon" style="background: rgba(22,163,74,0.15);">
                        <i class="fas fa-newspaper" style="color: #4ade80;"></i>
                    </div>
                    <div class="auth-feature-text">
                        <strong>Kontent boshqaruvi</strong>
                        Yangiliklar, e'lonlar va kamera ma'lumotlari
                    </div>
                </div>
                <div class="auth-feature">
                    <div class="auth-feature-icon" style="background: rgba(14,165,233,0.15);">
                        <i class="fas fa-chart-bar" style="color: #38bdf8;"></i>
                    </div>
                    <div class="auth-feature-text">
                        <strong>Statistika va tahlil</strong>
                        Real vaqtli ko'rsatkichlar va hisobotlar
                    </div>
                </div>
                <div class="auth-feature">
                    <div class="auth-feature-icon" style="background: rgba(168,85,247,0.15);">
                        <i class="fas fa-shield-alt" style="color: #c084fc;"></i>
                    </div>
                    <div class="auth-feature-text">
                        <strong>Rol va ruxsatlar</strong>
                        Xavfsiz foydalanuvchi boshqaruvi
                    </div>
                </div>
            </div>
        </div>

        {{-- Bottom --}}
        <div class="auth-left-bottom">
            &copy; {{ date('Y') }} {{ trans('panel.site_title') }}. Barcha huquqlar himoyalangan.
        </div>
    </div>

    {{-- ===== RIGHT PANEL ===== --}}
    <div class="auth-right">
        <div class="auth-form-wrap">

            {{-- Header --}}
            <div class="auth-form-header">
                <div class="auth-form-avatar">
                    <i class="fas fa-user" style="color: white; font-size: 26px;"></i>
                </div>
                <h2 class="auth-form-title">Tizimga kirish</h2>
                <p class="auth-form-subtitle">Hisob ma'lumotlaringizni kiriting</p>
            </div>

            {{-- Session message --}}
            @if(session()->has('message'))
                <div class="auth-alert">
                    <i class="fas fa-info-circle"></i>
                    {{ session()->get('message') }}
                </div>
            @endif

            {{-- Form --}}
            <form action="{{ route('login') }}" method="POST" autocomplete="off">
                @csrf

                {{-- Email --}}
                <div class="auth-field">
                    <label class="auth-label" for="email">
                        Elektron pochta
                    </label>
                    <div class="auth-input-wrap">
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email', null) }}"
                            required
                            autofocus
                            autocomplete="email"
                            placeholder="email@misol.com"
                            class="auth-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
                        >
                        <i class="fas fa-envelope auth-input-icon"></i>
                    </div>
                    @if($errors->has('email'))
                        <div class="auth-error">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $errors->first('email') }}
                        </div>
                    @endif
                </div>

                {{-- Password --}}
                <div class="auth-field">
                    <label class="auth-label" for="password">
                        Parol
                    </label>
                    <div class="auth-input-wrap">
                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            placeholder="••••••••"
                            class="auth-input {{ $errors->has('password') ? 'is-invalid' : '' }}"
                        >
                        <i class="fas fa-lock auth-input-icon"></i>
                        <button type="button" class="auth-pwd-toggle" onclick="togglePassword()" title="Parolni ko'rsatish">
                            <i class="fas fa-eye" id="pwd-eye"></i>
                        </button>
                    </div>
                    @if($errors->has('password'))
                        <div class="auth-error">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $errors->first('password') }}
                        </div>
                    @endif
                </div>

                {{-- Options --}}
                <div class="auth-options">
                    <label class="auth-remember">
                        <input type="checkbox" name="remember" id="remember">
                        <span class="auth-remember-label">{{ trans('global.remember_me') }}</span>
                    </label>
                    @if(Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="auth-forgot">
                            Parolni unutdingizmi?
                        </a>
                    @endif
                </div>

                {{-- Submit --}}
                <button type="submit" class="auth-submit">
                    <i class="fas fa-sign-in-alt"></i>
                    Kirish
                </button>
            </form>

            {{-- Security note --}}
            <div class="auth-security">
                <i class="fas fa-lock"></i>
                Barcha ma'lumotlar xavfsiz shifrlangan
            </div>

        </div>
    </div>

</div>

<script>
function togglePassword() {
    var input = document.getElementById('password');
    var icon  = document.getElementById('pwd-eye');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'fas fa-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'fas fa-eye';
    }
}
</script>
</body>
</html>
