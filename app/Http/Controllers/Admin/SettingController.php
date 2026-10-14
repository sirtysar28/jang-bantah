<?php

namespace App\Http\Controllers\Admin;

use App\AdminNotificationService;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\SmtpSettingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Crypt;

class SettingController extends Controller
{
    public function index()
    {
        $smtp = [
            'smtp_enabled' => Setting::get('smtp_enabled') === '1',
            'smtp_host' => Setting::get('smtp_host', ''),
            'smtp_port' => Setting::get('smtp_port', '587'),
            'smtp_encryption' => Setting::get('smtp_encryption', 'tls'),
            'smtp_username' => Setting::get('smtp_username', ''),
            'smtp_from_address' => Setting::get('smtp_from_address', ''),
            'smtp_from_name' => Setting::get('smtp_from_name', config('app.name')),
            'notification_email' => Setting::get('notification_email', ''),
        ];

        return view('admin.setting.index', compact('smtp'));
    }

    /**
     * Simpan pengaturan SMTP (password hanya diupdate jika diisi ulang).
     */
    public function updateSmtp(Request $request)
    {
        $validated = $request->validate([
            'smtp_enabled' => 'nullable|boolean',
            'smtp_host' => 'required_with:smtp_enabled|string|max:255',
            'smtp_port' => 'required|integer|min:1|max:65535',
            'smtp_encryption' => 'required|in:tls,ssl,none',
            'smtp_username' => 'nullable|string|max:255',
            'smtp_password' => 'nullable|string|max:255',
            'smtp_from_address' => 'required|email',
            'smtp_from_name' => 'required|string|max:255',
            'notification_email' => 'nullable|string|max:1000',
        ], [
            'smtp_host.required_with' => 'Host SMTP wajib diisi saat SMTP diaktifkan.',
            'smtp_port.required' => 'Port SMTP wajib diisi.',
            'smtp_encryption.in' => 'Enkripsi harus tls, ssl, atau none.',
            'smtp_from_address.required' => 'Alamat pengirim wajib diisi.',
            'smtp_from_address.email' => 'Format alamat pengirim tidak valid.',
        ]);

        Setting::set('smtp_enabled', $request->boolean('smtp_enabled') ? '1' : '0');
        Setting::set('smtp_host', $validated['smtp_host'] ?? '');
        Setting::set('smtp_port', $validated['smtp_port']);
        Setting::set('smtp_encryption', $validated['smtp_encryption']);
        Setting::set('smtp_username', $validated['smtp_username'] ?? '');

        // Password: hanya timpa yang lama jika diisi (disimpan terenkripsi)
        if (! empty($validated['smtp_password'])) {
            Setting::set('smtp_password', Crypt::encryptString($validated['smtp_password']));
        }

        Setting::set('smtp_from_address', $validated['smtp_from_address']);
        Setting::set('smtp_from_name', $validated['smtp_from_name']);
        Setting::set('notification_email', $validated['notification_email'] ?? '');

        return redirect()->route('admin.setting.index')
            ->with('success', 'Pengaturan SMTP berhasil disimpan.');
    }

    /**
     * Kirim email percobaan untuk memastikan koneksi SMTP bekerja.
     */
    public function testMail(Request $request)
    {
        $request->validate(['test_email' => 'nullable|email']);

        $target = $request->test_email ?: ($request->user()->email ?? null);
        if (! $target) {
            return redirect()->route('admin.setting.index')
                ->with('error', 'Alamat email tujuan uji tidak ditemukan.');
        }

        $ok = AdminNotificationService::sendTo(
            $target,
            'Email Percobaan (Test SMTP)',
            [
                'Ini adalah email percobaan dari sistem JANG BANTAH.',
                ['label' => 'Waktu Kirim', 'value' => now()->format('d/m/Y H:i')],
                ['label' => 'Tujuan', 'value' => $target],
                'Jika email ini sampai ke inbox Anda, konfigurasi SMTP sudah benar.',
            ],
            config('app.url'),
            'Buka Aplikasi'
        );

        return $ok
            ? redirect()->route('admin.setting.index')->with('success', "Email percobaan berhasil dikirim ke {$target}.")
            : redirect()->route('admin.setting.index')->with('error', 'Gagal mengirim email percobaan. Periksa kembali pengaturan SMTP dan log (storage/logs/laravel.log).');
    }

    public function backupDatabase()
    {
        try {
            Artisan::call('db:backup');
            return redirect()->back()->with('success', 'Backup database berhasil.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Backup gagal: ' . $e->getMessage());
        }
    }
}
