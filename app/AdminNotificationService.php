<?php

namespace App;

use App\Mail\AdminNotificationMail;
use App\Models\Notifikasi;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Notifikasi email + in-app untuk admin.
 * Semua pengiriman dibungkus try/catch supaya kegagalan SMTP
 * tidak pernah menggagalkan proses utama aplikasi.
 */
class AdminNotificationService
{
    /**
     * Kirim notifikasi ke email admin (sesuai pengaturan SMTP/tujuan).
     *
     * @param string $title     Judul email
     * @param array  $lines     Daftar isi. String biasa, atau array ['label' => ..., 'value' => ...]
     * @param string|null $actionUrl
     * @param string|null $actionText
     * @return bool berhasil atau tidak
     */
    public static function sendEmail(string $title, array $lines = [], ?string $actionUrl = null, ?string $actionText = null): bool
    {
        try {
            if (! SmtpSettingService::isEnabled()) {
                Log::info('Notifikasi email dilewati: SMTP belum diaktifkan di pengaturan. (' . $title . ')');
                return false;
            }

            SmtpSettingService::apply();

            $recipients = SmtpSettingService::notificationRecipients();
            if (empty($recipients)) {
                Log::warning('Notifikasi email dilewati: tidak ada email tujuan admin. (' . $title . ')');
                return false;
            }

            Mail::to($recipients)->send(
                new AdminNotificationMail($title, $lines, $actionUrl, $actionText)
            );

            return true;
        } catch (\Throwable $e) {
            Log::error('Gagal mengirim notifikasi email: ' . $e->getMessage(), [
                'title' => $title,
                'trace' => $e->getTraceAsString(),
            ]);
            return false;
        }
    }

    /**
     * Simpan notifikasi in-app untuk user tertentu (muncul di menu Notifikasi).
     */
    public static function notifyUsers(array $userIds, string $judul, string $pesan, string $tipe = 'info', array $data = []): void
    {
        try {
            foreach (array_unique($userIds) as $userId) {
                Notifikasi::create([
                    'user_id' => $userId,
                    'judul' => $judul,
                    'pesan' => $pesan,
                    'tipe' => $tipe,
                    'channel' => 'email',
                    'data' => $data,
                ]);
            }
        } catch (\Throwable $e) {
            Log::error('Gagal menyimpan notifikasi in-app: ' . $e->getMessage());
        }
    }

    /**
     * Kirim email ke satu alamat tertentu (mis. notifikasi balik ke user OPD).
     */
    public static function sendTo(string $email, string $title, array $lines = [], ?string $actionUrl = null, ?string $actionText = null): bool
    {
        try {
            if (! $email) {
                return false;
            }
            SmtpSettingService::apply();
            Mail::to($email)->send(new AdminNotificationMail($title, $lines, $actionUrl, $actionText));
            return true;
        } catch (\Throwable $e) {
            Log::error("Gagal mengirim email ke {$email}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * ID user admin (super_admin & admin_aset) yang aktif.
     *
     * @return int[]
     */
    public static function adminUserIds(): array
    {
        try {
            return User::whereIn('role', ['super_admin', 'admin_aset'])
                ->where('is_active', true)
                ->pluck('id')
                ->all();
        } catch (\Throwable $e) {
            return [];
        }
    }
}
