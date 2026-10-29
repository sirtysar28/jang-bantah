<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - JANG BANTAH</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        /* ═══════════════════════════════════════════════════════
           JANG BANTAH — Tema Login (Sora untuk heading, Inter body)
           ═══════════════════════════════════════════════════════ */
        :root {
            --jb-950: #001b3d;
            --jb-900: #002a5c;
            --jb-800: #003d80;
            --jb-700: #004d99;
            --jb-600: #0062b8;
            --jb-500: #1a75d1;
            --jb-400: #4da3e8;
            --jb-300: #80bfff;
            --jb-100: #e6f2ff;
            --jb-50:  #f0f7ff;
            --jb-gold:      #f0c040;
            --jb-gold-dark: #d4a020;
            --jb-border: #e5e7eb;
            --jb-ease-out: cubic-bezier(.16, 1, .3, 1);
            --jb-ease: cubic-bezier(.22, .61, .36, 1);
        }

        * { box-sizing: border-box; }
        html, body { margin: 0; padding: 0; }

        body.auth-page {
            font-family: 'Inter', sans-serif;
            overflow: hidden;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            text-rendering: optimizeLegibility;
        }

        h1, h2, h3, h4, h5, h6, .font-header {
            font-family: 'Sora', 'Inter', sans-serif;
            letter-spacing: -.01em;
        }

        /* ═══════════ LOADER HALAMAN ═══════════ */
        .page-loader {
            position: fixed;
            inset: 0;
            z-index: 3000;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 14px;
            background: rgba(0, 27, 61, .92);
            backdrop-filter: blur(8px);
            color: #fff;
            opacity: 0;
            visibility: hidden;
            transition: opacity .35s ease, visibility .35s ease;
        }
        .page-loader.show { opacity: 1; visibility: visible; }
        .loader-spinner {
            width: 44px; height: 44px;
            border-radius: 50%;
            border: 3px solid rgba(255,255,255,.18);
            border-top-color: var(--jb-gold);
            animation: loader-rotate .8s linear infinite;
        }
        @keyframes loader-rotate { to { transform: rotate(360deg); } }
        .loader-text {
            font-family: 'Sora', sans-serif;
            font-size: .85rem;
            font-weight: 500;
            letter-spacing: .4px;
            margin: 0;
            opacity: .9;
        }
        .loader-dots span {
            display: inline-block;
            width: 5px; height: 5px;
            margin-left: 4px;
            border-radius: 50%;
            background: var(--jb-gold);
            animation: loader-dot 1.2s ease-in-out infinite;
        }
        .loader-dots span:nth-child(2) { animation-delay: .15s; }
        .loader-dots span:nth-child(3) { animation-delay: .3s; }
        @keyframes loader-dot { 0%, 60%, 100% { transform: translateY(0); opacity: .5; } 30% { transform: translateY(-6px); opacity: 1; } }
        .loader-brand {
            font-family: 'Sora', sans-serif;
            font-size: .7rem;
            letter-spacing: 3px;
            text-transform: uppercase;
            opacity: .55;
        }

        /* ═══════════ LATAR GRADIENT BERGERAK ═══════════ */
        .auth-wrapper {
            position: fixed;
            inset: 0;
            height: 100vh;
            height: 100dvh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #001530;
            overflow: hidden;
            padding: clamp(12px, 2.2vh, 26px) 16px;
        }
        .auth-wrapper::before {
            content: '';
            position: absolute;
            inset: -40%;
            background:
                radial-gradient(45% 45% at 30% 35%, rgba(0, 98, 184, .5), transparent 70%),
                radial-gradient(40% 40% at 75% 25%, rgba(240, 192, 64, .16), transparent 70%),
                radial-gradient(50% 50% at 65% 80%, rgba(77, 163, 232, .32), transparent 70%),
                linear-gradient(135deg, #001b3d 0%, #00234c 45%, #00346b 100%);
            animation: auth-bg-drift 26s ease-in-out infinite alternate;
        }
        .auth-wrapper::after {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255, 255, 255, .035) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, .035) 1px, transparent 1px);
            background-size: 44px 44px;
            pointer-events: none;
        }
        @keyframes auth-bg-drift {
            0%   { transform: translate(0, 0) scale(1); }
            50%  { transform: translate(3%, -3%) scale(1.06); }
            100% { transform: translate(-3%, 3%) scale(1.03); }
        }

        /* ═══════════ SHELL / KARTU LOGIN ═══════════ */
        .auth-shell {
            position: relative;
            z-index: 1;
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(0, 1.05fr);
            width: 100%;
            max-width: 1020px;
            height: 100%;
            max-height: 780px;
            background: rgba(255, 255, 255, .97);
            border: 1px solid rgba(255, 255, 255, .55);
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 40px 100px rgba(0, 21, 48, .6);
            animation: auth-shell-in .7s var(--jb-ease-out) both;
        }
        @keyframes auth-shell-in {
            from { opacity: 0; transform: translateY(26px) scale(.97); }
            to   { opacity: 1; transform: none; }
        }

        /* ---------- Panel kiri : form ---------- */
        .auth-main {
            display: flex;
            flex-direction: column;
            justify-content: center;
            justify-content: safe center;
            padding: 44px 48px 32px;
            background: #fff;
            min-height: 0;
            overflow-y: auto;
            scrollbar-width: thin;
        }

        .auth-main-head {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 26px;
            animation: auth-field-in .6s var(--jb-ease-out) both;
            animation-delay: .15s;
        }
        .auth-main-logo {
            width: 48px; height: 48px;
            flex-shrink: 0;
            border-radius: 14px;
            background: #fff;
            border: 1px solid var(--jb-border);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 18px rgba(0, 42, 92, .35);
            padding: 4px;
        }
        .auth-main-logo img { width: 100%; height: 100%; object-fit: contain; }
        .auth-main-head strong {
            display: block;
            font-family: 'Sora', sans-serif;
            font-size: 14px;
            color: var(--jb-900);
            line-height: 1.25;
        }
        .auth-main-head span {
            display: block;
            font-size: 10.5px;
            color: #9ca3af;
            letter-spacing: 1.6px;
            text-transform: uppercase;
            margin-top: 2px;
        }

        .auth-title {
            font-size: 23px;
            font-weight: 700;
            color: var(--jb-900);
            margin: 0 0 5px;
            animation: auth-field-in .6s var(--jb-ease-out) both;
            animation-delay: .22s;
        }
        .auth-subtitle {
            font-size: 13px;
            color: #6b7280;
            margin: 0 0 24px;
            line-height: 1.6;
            animation: auth-field-in .6s var(--jb-ease-out) both;
            animation-delay: .28s;
        }

        @keyframes auth-field-in {
            from { opacity: 0; transform: translateY(12px); }
            to   { opacity: 1; transform: none; }
        }
        .auth-form-anim { animation: auth-field-in .6s var(--jb-ease-out) both; animation-delay: .34s; }

        /* ---------- Input group ---------- */
        .auth-input-group .input-group-text {
            background: var(--jb-50);
            border: 1.5px solid var(--jb-border);
            border-right: 0;
            border-radius: 12px 0 0 12px;
            color: var(--jb-700);
            font-size: 14px;
            transition: border-color .2s ease, background .2s ease;
        }
        .auth-input-group .form-control {
            border: 1.5px solid var(--jb-border);
            border-left: 0;
            padding: .68rem .9rem;
            font-size: .9rem;
            color: var(--jb-950);
            transition: border-color .2s ease, box-shadow .2s ease;
        }
        .auth-input-group .form-control:focus {
            box-shadow: none;
            border-color: var(--jb-500);
        }
        .auth-input-group:focus-within .input-group-text {
            border-color: var(--jb-500);
            background: var(--jb-100);
        }
        .auth-input-group .form-control::placeholder { color: #a3b6c9; }
        .form-label { font-size: .8rem; font-weight: 600; color: #374151; }

        .password-toggle {
            border: 1.5px solid var(--jb-border);
            border-left: 0;
            border-radius: 0 12px 12px 0;
            color: #6b7280;
            min-width: 46px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all .2s ease;
        }
        .password-toggle:hover, .password-toggle:active, .password-toggle:focus {
            color: var(--jb-700) !important;
            background: var(--jb-50);
            box-shadow: none !important;
        }

        /* ---------- Tombol masuk ---------- */
        .btn-auth-submit {
            position: relative;
            width: 100%;
            padding: .82rem 1rem;
            border: none;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--jb-700) 0%, var(--jb-500) 100%);
            color: #fff;
            font-family: 'Sora', sans-serif;
            font-weight: 600;
            font-size: .92rem;
            letter-spacing: .3px;
            box-shadow: 0 10px 22px rgba(0, 77, 153, .35);
            transition: transform .18s var(--jb-ease), box-shadow .18s var(--jb-ease), filter .18s ease;
        }
        .btn-auth-submit:hover {
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 14px 30px rgba(0, 77, 153, .45);
            filter: brightness(1.07);
        }
        .btn-auth-submit:active { transform: translateY(0); }
        .btn-auth-submit:disabled { opacity: .8; transform: none; }
        .btn-spinner {
            display: none;
            width: 15px; height: 15px;
            border-radius: 50%;
            border: 2px solid rgba(255,255,255,.35);
            border-top-color: #fff;
            animation: loader-rotate .7s linear infinite;
            vertical-align: -2px;
        }
        .btn-auth-submit.loading .btn-spinner { display: inline-block; }
        .btn-auth-submit.loading .btn-label { opacity: .85; }

        /* ---------- Captcha ---------- */
        .captcha-box {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            padding: 12px 14px;
            border: 1.5px solid rgba(0, 77, 153, .35);
            border-radius: 12px;
            background:
                repeating-linear-gradient(-45deg, rgba(0, 77, 153, .07) 0 9px, transparent 9px 18px),
                linear-gradient(180deg, #f8fbff, #ecf3fa);
            user-select: none;
            position: relative;
            overflow: hidden;
        }
        .captcha-box::after {
            content: '';
            position: absolute;
            left: 8px; right: 52px;
            top: 50%;
            height: 2px;
            background: linear-gradient(90deg, transparent, rgba(0, 77, 153, .4), transparent);
            transform: rotate(-2.5deg);
            pointer-events: none;
        }
        .captcha-code {
            display: inline-flex;
            gap: 6px;
            align-items: baseline;
        }
        .captcha-code span {
            display: inline-block;
            font-family: 'Courier New', monospace;
            font-size: 24px;
            font-weight: 800;
            line-height: 1;
            color: var(--jb-900);
            text-shadow: 1px 1px 0 rgba(255, 255, 255, .8);
            padding: 2px 4px;
        }
        .captcha-code span:nth-child(4n+1) { color: #001b3d; }
        .captcha-code span:nth-child(4n+2) { color: #0062b8; }
        .captcha-code span:nth-child(4n+3) { color: #003d80; font-style: italic; }
        .captcha-code span:nth-child(4n)   { color: #1a75d1; }
        .captcha-refresh {
            flex-shrink: 0;
            width: 40px; height: 40px;
            padding: 0;
            border: 1px solid var(--jb-border);
            border-radius: 10px;
            background: #fff;
            color: var(--jb-700);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            transition: all .25s var(--jb-ease);
        }
        .captcha-refresh:hover { background: var(--jb-50); transform: rotate(120deg); }
        .captcha-refresh:disabled { opacity: .6; }
        .captcha-input {
            text-align: center;
            font-weight: 700;
            letter-spacing: .35em;
            text-transform: uppercase;
            font-size: 15px;
            border-radius: 12px !important;
            border: 1.5px solid var(--jb-border) !important;
        }
        .captcha-input:focus { border-color: var(--jb-500) !important; box-shadow: 0 0 0 3px rgba(26, 117, 209, .12) !important; }
        @keyframes captcha-spin { to { transform: rotate(360deg); } }
        .captcha-spinning { display: inline-flex; animation: captcha-spin .6s linear infinite; }
        @keyframes captcha-shake {
            10%, 90% { transform: translateX(-2px); }
            20%, 80% { transform: translateX(3px); }
            30%, 50%, 70% { transform: translateX(-4px); }
            40%, 60% { transform: translateX(4px); }
        }
        .captcha-box.captcha-invalid { animation: captcha-shake .5s ease; border-color: #dc3545; }

        /* ---------- Error / alert ---------- */
        .auth-alert {
            display: flex;
            gap: 12px;
            align-items: flex-start;
            padding: 13px 16px;
            border-radius: 12px;
            font-size: .82rem;
            line-height: 1.5;
            margin-bottom: 18px;
            animation: auth-field-in .5s var(--jb-ease-out) both;
        }
        .auth-alert.danger {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
        }
        .auth-alert i { margin-top: 2px; font-size: .95rem; }

        /* ---------- Footer form ---------- */
        .auth-back {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            font-size: .82rem;
            font-weight: 600;
            color: var(--jb-700);
            text-decoration: none;
            margin-top: 20px;
            transition: gap .2s ease, color .2s ease;
        }
        .auth-back:hover { color: var(--jb-500); gap: 11px; }
        .auth-main-footer {
            margin-top: 22px;
            padding-top: 14px;
            border-top: 1px solid #eef2f6;
            font-size: .7rem;
            color: #9ca3af;
            text-align: center;
            line-height: 1.7;
        }
        .auth-main-footer a { color: var(--jb-700); font-weight: 600; text-decoration: none; }
        .auth-main-footer a:hover { text-decoration: underline; }

        /* ═══════════ PANEL KANAN : INFORMASI ═══════════ */
        .auth-info {
            position: relative;
            padding: 48px 44px 32px;
            color: #fff;
            background:
                linear-gradient(160deg, rgba(0, 27, 61, .94) 0%, rgba(0, 42, 92, .95) 55%, rgba(0, 77, 153, .96) 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            overflow: hidden;
        }
        .auth-orbs {
            position: absolute;
            inset: 0;
            pointer-events: none;
            overflow: hidden;
        }
        .orb-parallax {
            position: absolute;
            will-change: transform;
            transition: transform .9s var(--jb-ease-out);
        }
        .orb-parallax.pos-gold { right: -120px; top: -120px; width: 320px; height: 320px; }
        .orb-parallax.pos-blue { left: -170px; bottom: -190px; width: 420px; height: 420px; }
        .auth-orb { width: 100%; height: 100%; border-radius: 50%; }
        .orb-gold {
            background: radial-gradient(circle at 35% 35%, rgba(240, 192, 64, .3), rgba(240, 192, 64, 0) 65%);
            animation: auth-float 12s ease-in-out infinite alternate;
        }
        .orb-blue {
            background: radial-gradient(circle at 60% 40%, rgba(77, 163, 232, .35), rgba(77, 163, 232, 0) 65%);
            animation: auth-float 15s ease-in-out infinite alternate-reverse;
        }
        @keyframes auth-float {
            from { transform: translate(0, 0); }
            to   { transform: translate(24px, -28px); }
        }
        .auth-info > * { position: relative; z-index: 1; }
        .auth-info > .auth-orbs { position: absolute; inset: 0; z-index: 0; }

        .auth-info-logo {
            width: 58px; height: 58px;
            margin-bottom: 16px;
            padding: 8px;
            border-radius: 16px;
            background: rgba(255,255,255,.95);
            box-shadow: 0 10px 24px rgba(0, 10, 25, .4);
        }
        .auth-info-logo img { width: 100%; height: 100%; object-fit: contain; }

        .auth-info-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            width: fit-content;
            background: rgba(240, 192, 64, .16);
            border: 1px solid rgba(240, 192, 64, .45);
            color: #f7d478;
            padding: 6px 14px;
            border-radius: 999px;
            font-family: 'Sora', sans-serif;
            font-size: 10.5px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 18px;
        }

        .auth-info h1 {
            font-size: 27px;
            font-weight: 800;
            letter-spacing: .3px;
            line-height: 1.25;
            margin: 0 0 8px;
        }
        .auth-info h1 em {
            font-style: normal;
            color: var(--jb-gold);
        }
        .auth-info-lead {
            font-size: 13px;
            font-weight: 600;
            opacity: .95;
            margin: 0 0 10px;
        }
        .auth-info-desc {
            font-size: 12.5px;
            opacity: .82;
            line-height: 1.75;
            margin: 0 0 24px;
        }
        .auth-info-features {
            list-style: none;
            padding: 0;
            margin: 0 0 28px;
            display: grid;
            gap: 12px;
        }
        .auth-info-features li {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 12.5px;
            font-weight: 500;
            opacity: .95;
        }
        .auth-info-features i {
            width: 34px; height: 34px;
            flex-shrink: 0;
            border-radius: 10px;
            background: rgba(255, 255, 255, .13);
            border: 1px solid rgba(255, 255, 255, .14);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            color: var(--jb-300);
        }
        .auth-info-footer {
            font-size: 11px;
            opacity: .72;
            border-top: 1px solid rgba(255, 255, 255, .18);
            padding-top: 14px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        /* Efek parallax lembut saat kursor bergerak (desktop) */
        .parallax-layer { transition: transform .6s var(--jb-ease-out); }

        /* ═══════════ RESPONSIVE ═══════════ */
        @media (min-width: 992px) and (max-height: 820px) {
            .auth-main { padding: 22px 38px 24px; }
            .auth-main-head { margin-bottom: 16px; }
            .auth-main-logo { width: 42px; height: 42px; border-radius: 12px; }
            .auth-title { font-size: 19px; }
            .auth-subtitle { font-size: 12.5px; margin-bottom: 14px; }
            .auth-info { padding: 24px 34px 22px; }
            .auth-info-logo { width: 46px; height: 46px; margin-bottom: 10px; }
            .auth-info-badge { margin-bottom: 12px; padding: 5px 12px; }
            .auth-info h1 { font-size: 21px; }
            .auth-info-desc { margin-bottom: 16px; }
            .auth-info-features { gap: 9px; margin-bottom: 16px; }
            .auth-info-features li { font-size: 12px; }
            .auth-info-features i { width: 30px; height: 30px; font-size: 13px; }
        }

        @media (max-width: 991.98px) {
            body.auth-page { overflow: auto; }
            .auth-wrapper { position: static; height: auto; min-height: 100dvh; padding: 1.25rem .9rem; }
            .auth-shell { grid-template-columns: minmax(0, 1fr); max-width: 480px; margin: 0 auto; height: auto; max-height: none; }
            .auth-main { overflow-y: visible; padding: 28px 24px 26px; }
            .auth-main-head { margin-bottom: 20px; }
            .auth-title { font-size: 20px; }
            .auth-info { order: 2; padding: 26px 24px 22px; }
            .auth-info h1 { font-size: 20px; margin-bottom: 6px; }
            .auth-info-desc { display: none; }
            .auth-info-features { grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 20px; }
            .auth-info-features li { font-size: 11px; flex-direction: column; text-align: center; gap: 8px; }
            .auth-info-features i { width: 40px; height: 40px; font-size: 16px; }
            .auth-info-footer { padding-top: 12px; font-size: 10.5px; justify-content: center; text-align: center; }
        }

        @media (max-width: 575.98px) {
            .auth-wrapper { padding: 1rem .75rem; }
            .auth-main { padding: 24px 20px 22px; }
            .auth-info { padding: 22px 20px 20px; }
            .auth-info-features { grid-template-columns: 1fr; }
            .captcha-code span { font-size: 20px; }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { animation: none !important; transition: none !important; }
        }
    </style>
</head>
<body class="auth-page">

    <!-- ═══════════ LOADER ═══════════ -->
    <div class="page-loader" id="pageLoader" aria-live="polite">
        <div class="loader-spinner"></div>
        <p class="loader-text">Memuat<span class="loader-dots"><span></span><span></span><span></span></span></p>
        <div class="loader-brand">JANG BANTAH</div>
    </div>

    <div class="auth-wrapper">
        <div class="auth-shell">

            <!-- ═══════════ PANEL KIRI : FORM LOGIN ═══════════ -->
            <main class="auth-main">

                <div class="auth-main-head">
                    <div class="auth-main-logo">
                        <img src="{{ asset('logo.png') }}" alt="Logo JANG BANTAH">
                    </div>
                    <div>
                        <strong>JANG BANTAH</strong>
                        <span>Pemkab. Halmahera Barat</span>
                    </div>
                </div>

                <h2 class="auth-title">Selamat Datang Kembali</h2>
                <p class="auth-subtitle">Masuk menggunakan akun instansi Anda untuk mengakses dashboard pengelolaan aset tanah daerah.</p>

                @if(session('error'))
                <div class="auth-alert danger">
                    <i class="fas fa-circle-exclamation"></i>
                    <div>{{ session('error') }}</div>
                </div>
                @endif

                @if($errors->any())
                <div class="auth-alert danger">
                    <i class="fas fa-triangle-exclamation"></i>
                    <div>
                        <strong>Autentikasi Gagal</strong><br>
                        {{ $errors->first() }}
                    </div>
                </div>
                @endif

                <form method="POST" action="{{ route('login') }}" id="loginForm" class="auth-form-anim" novalidate>
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <div class="input-group auth-input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}"
                                   required autofocus autocomplete="username" placeholder="email@halbarkab.go.id">
                        </div>
                        @error('email')
                        <small class="text-danger d-block mt-1" style="font-size:.75rem;">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="passwordInput" class="form-label">Password</label>
                        <div class="input-group auth-input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" id="passwordInput" name="password" class="form-control" required
                                   autocomplete="current-password" placeholder="Masukkan password">
                            <button class="btn password-toggle" type="button" id="togglePw"
                                    aria-label="Tampilkan password" aria-pressed="false" tabindex="-1">
                                <i class="fas fa-eye" id="eyeIcon"></i>
                            </button>
                        </div>
                        @error('password')
                        <small class="text-danger d-block mt-1" style="font-size:.75rem;">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="captchaInput" class="form-label">Verifikasi Keamanan</label>
                        <div class="captcha-box" id="captchaBox" role="group" aria-label="Kode captcha">
                            <span class="captcha-code" id="captchaCode" aria-hidden="true">
                                @foreach($captcha as $c)
                                    <span style="transform: rotate({{ $c['rotate'] }}deg) translateY({{ $c['offset'] }}px);">{{ $c['char'] }}</span>
                                @endforeach
                            </span>
                            <button type="button" class="captcha-refresh" id="refreshCaptcha"
                                    title="Ganti kode" aria-label="Ganti kode captcha">
                                <i class="fas fa-rotate-right" id="captchaRefreshIcon"></i>
                            </button>
                        </div>
                        <input type="text" id="captchaInput" name="captcha" class="form-control captcha-input mt-2"
                               maxlength="5" autocomplete="off" spellcheck="false" required
                               placeholder="Ketik 5 karakter di atas" aria-describedby="captchaHelp">
                        <div id="captchaHelp" class="form-text small text-muted" style="font-size:.72rem;">
                            Ketik 5 karakter yang tampil di atas (huruf besar/kecil diabaikan).
                        </div>
                        @error('captcha')
                        <small class="text-danger d-block mt-1" style="font-size:.75rem;">{{ $message }}</small>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-auth-submit" id="loginSubmitBtn">
                        <span class="btn-spinner me-2"></span>
                        <span class="btn-label"><i class="fas fa-right-to-bracket me-1"></i> Masuk</span>
                    </button>
                </form>

                <div class="text-center">
                    <a href="{{ route('home') }}" class="auth-back">
                        <i class="fas fa-arrow-left"></i> Kembali ke Website
                    </a>
                </div>

                <div class="auth-main-footer">
                    <div>&copy; {{ date('Y') }} JANG BANTAH &mdash; Pemkab. Halmahera Barat</div>
                    <div>Powered by <a href="https://ciptakaryadigital.id" target="_blank" rel="noopener">Cipta Karya Digital</a></div>
                </div>

            </main>

            <!-- ═══════════ PANEL KANAN : INFORMASI APLIKASI ═══════════ -->
            <aside class="auth-info" id="authInfo">
                <!-- Orb dekoratif + parallax kursor -->
                <div class="auth-orbs" aria-hidden="true">
                    <div class="orb-parallax pos-gold" data-depth="1.6"><div class="auth-orb orb-gold"></div></div>
                    <div class="orb-parallax pos-blue" data-depth="-1.1"><div class="auth-orb orb-blue"></div></div>
                </div>
                <div class="parallax-layer" id="infoParallax">
                    <div class="auth-info-logo">
                        <img src="{{ asset('logo.png') }}" alt="Logo JANG BANTAH">
                    </div>

                    <span class="auth-info-badge"><i class="fas fa-shield-halved"></i> Bank Tanah Digital</span>

                    <h1>Dashboard <em>JANG BANTAH</em></h1>
                    <p class="auth-info-lead">Jaringan Bank Tanah &mdash; Pemerintah Kabupaten Halmahera Barat</p>

                    <p class="auth-info-desc">
                        Sistem informasi terintegrasi untuk pengelolaan data tanah, pengamanan aset,
                        sertifikasi, hingga pemetaan digital aset daerah &mdash; cepat, aman, dan akuntabel.
                    </p>

                    <ul class="auth-info-features">
                        <li><i class="fas fa-map-marked-alt"></i> Data tanah &amp; inventarisasi aset daerah</li>
                        <li><i class="fas fa-shield-halved"></i> Pengamanan administrasi, fisik &amp; hukum</li>
                        <li><i class="fas fa-certificate"></i> Manajemen sertifikasi tanah (BPN)</li>
                        <li><i class="fas fa-globe"></i> Peta digital GIS &amp; monitoring real-time</li>
                    </ul>
                </div>

                <div class="auth-info-footer">
                    <span>&copy; {{ date('Y') }} JANG BANTAH</span>
                    <span>Jaringan Bank Tanah Pemkab. Halbar</span>
                </div>
            </aside>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        /* ═══════════ INTIP PASSWORD ═══════════ */
        const toggleBtn = document.getElementById('togglePw');
        const passwordInput = document.getElementById('passwordInput');
        const eyeIcon = document.getElementById('eyeIcon');

        toggleBtn.addEventListener('click', function () {
            const isHidden = passwordInput.type === 'password';
            passwordInput.type = isHidden ? 'text' : 'password';
            eyeIcon.classList.toggle('fa-eye', !isHidden);
            eyeIcon.classList.toggle('fa-eye-slash', isHidden);
            toggleBtn.setAttribute('aria-label', isHidden ? 'Sembunyikan password' : 'Tampilkan password');
            toggleBtn.setAttribute('aria-pressed', isHidden ? 'true' : 'false');
        });

        /* ═══════════ REFRESH CAPTCHA (AJAX) ═══════════ */
        const refreshCaptchaBtn = document.getElementById('refreshCaptcha');
        refreshCaptchaBtn.addEventListener('click', async function () {
            const icon = document.getElementById('captchaRefreshIcon');
            icon.classList.add('captcha-spinning');
            refreshCaptchaBtn.disabled = true;
            try {
                const res = await fetch('{{ route('captcha.refresh') }}', { headers: { 'Accept': 'application/json' } });
                const data = await res.json();
                const code = document.getElementById('captchaCode');
                code.innerHTML = '';
                data.captcha.forEach(c => {
                    const span = document.createElement('span');
                    span.style.transform = `rotate(${c.rotate}deg) translateY(${c.offset}px)`;
                    span.textContent = c.char;
                    code.appendChild(span);
                });
                document.getElementById('captchaInput').value = '';
            } catch (e) {
                location.reload();
            } finally {
                icon.classList.remove('captcha-spinning');
                refreshCaptchaBtn.disabled = false;
            }
        });

        /* ═══════════ SPINNER SAAT SUBMIT ═══════════ */
        const loginForm = document.getElementById('loginForm');
        const submitBtn = document.getElementById('loginSubmitBtn');
        loginForm.addEventListener('submit', function () {
            submitBtn.classList.add('loading');
            submitBtn.disabled = true;
        });

        /* ═══════════ PARALLAX LEMBUT (kursor) ═══════════
           Panel info & orb bergeser mengikuti kursor secara halus */
        const authInfo = document.getElementById('authInfo');
        const infoParallax = document.getElementById('infoParallax');
        const orbs = document.querySelectorAll('.orb-parallax');
        const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        if (!reduceMotion && window.matchMedia('(pointer: fine)').matches) {
            document.addEventListener('mousemove', function (e) {
                const x = (e.clientX / window.innerWidth - .5);
                const y = (e.clientY / window.innerHeight - .5);
                // layer konten info bergeser sedikit (kedalaman kecil)
                infoParallax.style.transform = `translate(${x * 10}px, ${y * 10}px)`;
                // tiap orb bergeser sesuai kedalaman masing-masing (efek parallax)
                orbs.forEach(orb => {
                    const depth = parseFloat(orb.dataset.depth || 1);
                    orb.style.transform = `translate(${x * 30 * depth}px, ${y * 30 * depth}px)`;
                });
            });
        }
    </script>
</body>
</html>
