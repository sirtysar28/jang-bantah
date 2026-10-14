<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - JANG BANTAH</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Poppins', sans-serif; }
        body {
            background: linear-gradient(135deg, #0D2B3E 0%, #1B4965 50%, #5FA8D3 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 25px 60px rgba(0,0,0,.3);
            max-width: 420px;
            width: 100%;
            overflow: hidden;
        }
        .login-header {
            background: linear-gradient(135deg, #1B4965, #5FA8D3);
            padding: 30px;
            text-align: center;
        }
        .login-header h5 { color: white; font-weight: 700; margin: 0; }
        .login-header small { color: rgba(255,255,255,.7); font-size: .75rem; }
        .login-body { padding: 30px; }
        .form-control {
            border-radius: 0 10px 10px 0;
            padding: 12px 15px;
            border: 1px solid #e0e0e0;
            font-size: .9rem;
        }
        .form-control:focus {
            border-color: #1B4965;
            box-shadow: 0 0 0 3px rgba(27,73,101,.1);
        }
        .input-group-text {
            border: 1px solid #e0e0e0;
            background: #f8f9fa;
            color: #1B4965;
        }
        .btn-login {
            background: linear-gradient(135deg, #1B4965, #5FA8D3);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 10px;
            font-weight: 600;
            width: 100%;
            font-size: .95rem;
            transition: transform .2s, box-shadow .2s;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(27,73,101,.3);
            color: white;
        }
        .btn-toggle-pw {
            background: none;
            border: 1px solid #e0e0e0;
            border-left: none;
            border-radius: 0 10px 10px 0;
            color: #1B4965;
            cursor: pointer;
            padding: 0 14px;
            transition: color .2s;
        }
        .btn-toggle-pw:hover { color: #5FA8D3; }
        .back-link { font-size: .85rem; }

        /* Session expired error */
        .session-error {
            background: linear-gradient(135deg, #FDE8E8, #FFF);
            border: 1px solid #F5C6CB;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            text-align: center;
        }
        .session-error i { font-size: 2rem; color: #E74C3C; }
        .session-error h6 { color: #721C24; margin: 8px 0 4px; }
        .session-error p { color: #856404; font-size: .85rem; margin: 0; }
        .session-error a {
            color: #1B4965;
            font-weight: 600;
            text-decoration: underline;
        }

        /* ════════ CAPTCHA ════════ */
        .captcha-box {
            flex: 1;
            height: 52px;
            border-radius: 10px;
            background: repeating-linear-gradient(45deg, #1B4965 0, #1B4965 8px, #14506e 8px, #14506e 16px);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            font-family: 'Courier New', monospace;
            font-size: 1.7rem;
            font-weight: 800;
            color: #fff;
            text-shadow: 2px 2px 4px rgba(0,0,0,.4);
            user-select: none;
            cursor: default;
            position: relative;
            overflow: hidden;
            letter-spacing: 4px;
        }
        .captcha-box::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(transparent 46%, rgba(255,255,255,.3) 46%, rgba(255,255,255,.3) 54%, transparent 54%);
            pointer-events: none;
        }
        .captcha-box span {
            display: inline-block;
        }
        .btn-captcha-refresh {
            background: #f8f9fa;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            color: #1B4965;
            width: 52px;
            height: 52px;
            font-size: 1.05rem;
            cursor: pointer;
            transition: all .2s;
        }
        .btn-captcha-refresh:hover {
            background: #1B4965;
            color: #fff;
            transform: rotate(90deg);
        }

        /* ════════ LOGIN FOOTER ════════ */
        .login-footer {
            padding: 15px 30px;
            background: #f8f9fa;
            border-top: 1px solid #ececec;
            text-align: center;
            font-size: .78rem;
            color: #6c757d;
        }
        .login-footer a {
            color: #1B4965;
            font-weight: 600;
            text-decoration: none;
        }
        .login-footer a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-header">
            <img src="{{ asset('logo.png') }}" alt="Logo" style="height:60px;margin-bottom:10px;">
            <h5>JANG BANTAH</h5>
            <small>Jaringan Bank Tanah - Pemkab. Halmahera Barat</small>
        </div>
        <div class="login-body">
            <h6 class="fw-600 text-center mb-3">Masuk ke Dashboard Admin</h6>

            @if(session('error'))
            <div class="alert alert-danger py-2" style="font-size:.85rem;">{{ session('error') }}</div>
            @endif

            @if($errors->any())
            <div class="session-error">
                <i class="fas fa-exclamation-triangle"></i>
                <h6>Autentikasi Gagal</h6>
                <p>{{ $errors->first() }}</p>
                <a href="{{ route('login') }}" class="d-block mt-2"><i class="fas fa-redo me-1"></i>Coba Lagi</a>
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-500" style="font-size:.85rem;">Email</label>
                    <div class="input-group">
                        <span class="input-group-text" style="border-radius:10px 0 0 10px;"><i class="fas fa-envelope"></i></span>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus placeholder="email@halbarkab.go.id" autocomplete="email">
                    </div>
                    @error('email')
                    <small class="text-danger" style="font-size:.8rem;">{{ $message }}</small>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="form-label fw-500" style="font-size:.85rem;">Password</label>
                    <div class="input-group">
                        <span class="input-group-text" style="border-radius:10px 0 0 10px;"><i class="fas fa-lock"></i></span>
                        <input type="password" name="password" id="passwordInput" class="form-control" required placeholder="Masukkan password" autocomplete="current-password" style="border-radius:0;">
                        <button type="button" class="btn btn-toggle-pw" id="togglePw" aria-label="Tampilkan password">
                            <i class="fas fa-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                    @error('password')
                    <small class="text-danger" style="font-size:.8rem;">{{ $message }}</small>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="form-label fw-500" style="font-size:.85rem;">Kode Keamanan (Captcha)</label>
                    <div class="d-flex gap-2 align-items-center mb-2">
                        <div class="captcha-box" id="captchaBox" aria-label="Kode captcha">
                            @foreach($captcha as $c)
                                <span style="transform: rotate({{ $c['rotate'] }}deg) translateY({{ $c['offset'] }}px);">{{ $c['char'] }}</span>
                            @endforeach
                        </div>
                        <button type="button" class="btn-captcha-refresh" id="refreshCaptcha" title="Ganti kode captcha">
                            <i class="fas fa-rotate-right"></i>
                        </button>
                    </div>
                    <input type="text" name="captcha" class="form-control" style="border-radius:10px; letter-spacing:4px; text-transform:uppercase; font-weight:600;" maxlength="5" required placeholder="Masukkan 5 karakter di atas" autocomplete="off">
                    @error('captcha')
                    <small class="text-danger" style="font-size:.8rem;">{{ $message }}</small>
                    @enderror
                </div>
                <button type="submit" class="btn btn-login">
                    <i class="fas fa-sign-in-alt me-2"></i>Masuk
                </button>
            </form>

            <div class="text-center mt-3">
                <a href="{{ route('home') }}" class="back-link text-decoration-none" style="color:#1B4965;">
                    <i class="fas fa-arrow-left me-1"></i> Kembali ke Website
                </a>
            </div>
        </div>
        <div class="login-footer">
            <div>&copy; {{ date('Y') }} JANG BANTAH &mdash; Pemkab. Halmahera Barat. All rights reserved.</div>
            <div>Powered by <a href="https://ciptakaryadigital.id" target="_blank" rel="noopener">Cipta Karya Digital</a></div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle password visibility
        const toggleBtn = document.getElementById('togglePw');
        const passwordInput = document.getElementById('passwordInput');
        const eyeIcon = document.getElementById('eyeIcon');

        toggleBtn.addEventListener('click', function() {
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        });

        // Refresh captcha via AJAX
        const refreshCaptchaBtn = document.getElementById('refreshCaptcha');
        refreshCaptchaBtn.addEventListener('click', async function() {
            const icon = refreshCaptchaBtn.querySelector('i');
            icon.classList.add('fa-spin');
            try {
                const res = await fetch('{{ route('captcha.refresh') }}', { headers: { 'Accept': 'application/json' } });
                const data = await res.json();
                const box = document.getElementById('captchaBox');
                box.innerHTML = '';
                data.captcha.forEach(c => {
                    const span = document.createElement('span');
                    span.style.transform = `rotate(${c.rotate}deg) translateY(${c.offset}px)`;
                    span.textContent = c.char;
                    box.appendChild(span);
                });
                document.querySelector('input[name="captcha"]').value = '';
            } catch (e) {
                location.reload();
            } finally {
                icon.classList.remove('fa-spin');
            }
        });
    </script>
</body>
</html>
