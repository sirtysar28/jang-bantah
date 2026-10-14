<?php

namespace App;

use App\Models\Setting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;

/**
 * Menerapkan konfigurasi SMTP dari database ke runtime Laravel,
 * sehingga super admin bisa mengubah pengaturan email lewat halaman
 * Setting tanpa perlu mengedit file .env di server.
 */
class SmtpSettingService
{
    public static function isEnabled(): bool
    {
        return Setting::get('smtp_enabled') === '1';
    }

    /**
     * Override config mail Laravel dengan nilai dari tabel settings.
     */
    public static function apply(): void
    {
        if (! static::isEnabled()) {
            return;
        }

        $password = Setting::get('smtp_password');
        try {
            $password = $password ? Crypt::decryptString($password) : '';
        } catch (\Throwable $e) {
            $password = (string) $password; // fallback: nilai plain (mis. dari .env)
        }

        Config::set('mail.default', 'smtp');
        Config::set('mail.mailers.smtp', [
            'transport' => 'smtp',
            'host' => Setting::get('smtp_host', config('mail.mailers.smtp.host')),
            'port' => (int) Setting::get('smtp_port', 587),
            'encryption' => Setting::get('smtp_encryption', 'tls'),
            'username' => Setting::get('smtp_username'),
            'password' => $password,
            'timeout' => 15,
        ]);
        Config::set('mail.from.address', Setting::get('smtp_from_address', config('mail.from.address')));
        Config::set('mail.from.name', Setting::get('smtp_from_name', config('mail.from.name')));

        // Reset mailer yang sudah ter-resolve sebelumnya agar memakai config baru
        Mail::purge();
    }

    /**
     * Email tujuan notifikasi admin (bisa lebih dari satu, dipisah koma).
     * Fallback: email semua user super_admin & admin_aset yang aktif.
     *
     * @return string[]
     */
    public static function notificationRecipients(): array
    {
        $configured = Setting::get('notification_email');
        if ($configured) {
            $emails = array_filter(array_map('trim', explode(',', $configured)));
            if (! empty($emails)) {
                return array_values($emails);
            }
        }

        try {
            return \App\Models\User::whereIn('role', ['super_admin', 'admin_aset'])
                ->where('is_active', true)
                ->pluck('email')
                ->filter()
                ->values()
                ->all();
        } catch (\Throwable $e) {
            return [];
        }
    }
}
