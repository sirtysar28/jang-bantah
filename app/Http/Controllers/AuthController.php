<?php

namespace App\Http\Controllers;

use App\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('auth.login', ['captcha' => $this->generateCaptcha()]);
    }

    /**
     * Generate kode captcha 5 karakter (tanpa karakter ambigu: I, O, 0, 1)
     * dan simpan ke session.
     */
    private function generateCaptcha()
    {
        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $code = '';
        for ($i = 0; $i < 5; $i++) {
            $code .= $chars[random_int(0, strlen($chars) - 1)];
        }

        session(['login_captcha' => $code]);

        // Data per karakter untuk styling (rotasi & posisi acak) di view
        return collect(str_split($code))->map(fn ($char) => [
            'char'   => $char,
            'rotate' => random_int(-14, 14),
            'offset' => random_int(-4, 4),
        ])->all();
    }

    /**
     * Refresh captcha via AJAX
     */
    public function refreshCaptcha()
    {
        return response()->json(['captcha' => $this->generateCaptcha()]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'captcha' => 'required',
        ]);

        // Validasi captcha 5 karakter (case-insensitive)
        if (strtoupper(trim($request->input('captcha'))) !== session('login_captcha')) {
            return back()->withErrors(['captcha' => 'Kode keamanan (captcha) salah.'])->withInput($request->except('password', 'captcha'));
        }
        session()->forget('login_captcha');

        if (!Auth::attempt($request->only('email', 'password'))) {
            return back()->withErrors(['email' => 'Email atau password salah.'])->withInput();
        }

        if (!Auth::user()->is_active) {
            Auth::logout();
            return back()->withErrors(['email' => 'Akun Anda tidak aktif. Hubungi administrator.']);
        }

        $request->session()->regenerate();

        AuditLogService::log('login', 'Auth', null, null, null, null, 'User login: ' . Auth::user()->name);

        return redirect()->route('admin.dashboard');
    }

    public function logout(Request $request)
    {
        AuditLogService::log('logout', 'Auth', null, null, null, null, 'User logout: ' . Auth::user()->name);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}
