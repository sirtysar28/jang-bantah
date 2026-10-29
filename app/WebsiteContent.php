<?php

namespace App;

use App\Models\Setting;

/**
 * Konten website publik yang bisa dikelola Super Admin
 * dari dashboard (menu "Kelola Website").
 *
 * Semua teks disimpan di tabel settings dengan prefix "web_".
 * Jika kosong / belum diisi, otomatis memakai nilai default di bawah.
 */
class WebsiteContent
{
    /**
     * Nilai default seluruh konten website (key => default).
     */
    public static function defaults(): array
    {
        return [
            /* ── Hero (halaman depan) ── */
            'web_hero_type'       => 'video', // "video" atau "image"
            'web_hero_video'       => 'FwCIWtT9TiM', // ID video YouTube
            'web_hero_image'       => '', // path gambar hero (kosong = belum ada)
            'web_hero_badge'       => 'PEMERINTAH KABUPATEN HALMAHERA BARAT',
            'web_hero_title'       => 'JANG BANTAH',
            'web_hero_subtitle'    => 'Jaringan Bank Tanah — Pemkab. Halmahera Barat',
            'web_hero_description' => 'Sistem Informasi Manajemen Bank Tanah Digital Terintegrasi untuk pengelolaan, pengamanan, dan sertifikasi aset tanah pemerintah daerah.',
            'web_hero_btn1_text'   => 'Pelajari Lebih Lanjut',
            'web_hero_btn2_text'   => 'Lihat Peta Aset',

            /* ── Section Fitur ── */
            'web_fitur_title'   => 'Fitur Unggulan Sistem',
            'web_fitur_desc'    => 'Platform digital terintegrasi untuk pengelolaan aset tanah pemerintah daerah yang modern dan efisien.',
            'web_fitur1_title'  => 'Digitalisasi Aset',
            'web_fitur1_desc'   => 'Pendataan seluruh aset tanah secara digital dan terintegrasi.',
            'web_fitur2_title'  => 'Sertifikasi',
            'web_fitur2_desc'   => 'Monitoring proses sertifikasi tanah hingga terbit sertifikat.',
            'web_fitur3_title'  => 'Pengamanan Aset',
            'web_fitur3_desc'   => 'Pengamanan administrasi, fisik, dan hukum aset tanah.',
            'web_fitur4_title'  => 'GIS / Peta Digital',
            'web_fitur4_desc'   => 'Visualisasi sebaran aset tanah pada peta digital interaktif.',

            /* ── Section Data Aset Terbaru ── */
            'web_aset_title' => 'Data Aset Terbaru',
            'web_aset_desc'  => 'Informasi terkini mengenai aset tanah yang telah terdata dalam sistem.',

            /* ── Section Alur Kerja ── */
            'web_alur_title' => 'Alur Kerja Sistem',
            'web_alur_desc'  => 'Proses end-to-end pengelolaan bank tanah dari pendataan hingga pemanfaatan.',
            'web_alur_steps' => "Pendataan Aset\nValidasi Data\nUpload Dokumen\nVerifikasi\nInput GIS\nPengamanan Administrasi\nPengamanan Fisik\nPengamanan Hukum\nPengajuan Sertifikasi\nMonitoring BPN\nTerbit Sertifikat\nPemanfaatan Aset",

            /* ── Footer ── */
            'web_footer_desc'     => "Jaringan Bank Tanah Pemerintah Kabupaten Halmahera Barat.\nSistem Informasi Manajemen Bank Tanah Digital Terintegrasi.",
            'web_footer_instansi' => 'Pemerintah Daerah Kabupaten Halmahera Barat',
            'web_footer_address'  => "Jl. Pengabdian No. 1 Desa Jati\nKec. Jailolo Kab. Halmahera Barat\nProvinsi Maluku Utara",
            'web_footer_website'  => 'https://halbarkab.go.id/',
            'web_footer_link1_name' => 'Website Pemkab. Halbar',
            'web_footer_link1_url'  => 'https://halbarkab.go.id/',
            'web_footer_link2_name' => 'BKAD',
            'web_footer_link2_url'  => '#',
            'web_footer_link3_name' => 'Diskominfo',
            'web_footer_link3_url'  => '#',
            'web_social_facebook'   => '#',
            'web_social_instagram'  => '#',
            'web_social_youtube'    => '#',
            'web_footer_copyright'  => 'Pemerintah Kabupaten Halmahera Barat — JANG BANTAH — Jaringan Bank Tanah',

            /* ── Halaman Tentang ── */
            'web_about_badge'     => 'PROFIL SISTEM',
            'web_about_title'     => 'Tentang JANG BANTAH',
            'web_about_box_title' => 'JANG BANTAH',
            'web_about_box_text'  => 'Jaringan Bank Tanah Pemerintah Kabupaten Halmahera Barat',
            'web_about_p1'        => 'JANG BANTAH adalah Sistem Informasi Manajemen Bank Tanah Pemerintah Kabupaten Halmahera Barat yang dirancang sebagai pusat pengelolaan, pengamanan, sertifikasi, monitoring, dan pemanfaatan aset tanah daerah secara digital dan terintegrasi.',
            'web_about_p2'        => 'Sistem ini mendukung program MCSP KPK dan menjadi instrumen penting dalam peningkatan Pendapatan Asli Daerah (PAD) melalui optimalisasi pemanfaatan aset tanah.',
            'web_about_tujuan_title' => 'Tujuan Sistem',
            'web_about_tujuan'    => "Digitalisasi seluruh aset tanah Pemda Halmahera Barat.\nMempercepat proses sertifikasi tanah.\nMencegah sengketa aset tanah daerah.\nMenjadi pusat data aset terintegrasi.\nMenampilkan dashboard kondisi aset secara realtime.\nMendukung MCSP KPK.\nMendukung peningkatan PAD dari pemanfaatan aset.",
            'web_about_visi'      => 'Mewujudkan pengelolaan aset tanah Pemerintah Kabupaten Halmahera Barat yang transparan, akuntabel, dan berbasis teknologi digital untuk mendukung tata kelola pemerintahan yang baik.',
            'web_about_misi'      => "Mendigitalisasi seluruh data aset tanah daerah.\nMempercepat sertifikasi tanah melalui monitoring BPN.\nMengamankan aset tanah secara administrasi, fisik, dan hukum.\nMengoptimalkan pemanfaatan aset untuk peningkatan PAD.",
        ];
    }

    /**
     * Ambil satu konten (otomatis fallback ke default bila kosong).
     */
    public static function get(string $key): string
    {
        return (string) Setting::get($key, static::defaults()[$key] ?? '');
    }

    /**
     * Ambil konten multi-baris sebagai array (contoh: langkah alur kerja,
     * daftar tujuan/misi). Satu baris = satu item.
     */
    public static function lines(string $key): array
    {
        return array_values(array_filter(array_map('trim', explode("\n", static::get($key)))));
    }

    /**
     * Tipe background hero: "video" atau "image".
     */
    public static function heroType(): string
    {
        $type = strtolower(trim((string) Setting::get('web_hero_type', static::defaults()['web_hero_type'])));

        return in_array($type, ['video', 'image'], true) ? $type : 'video';
    }

    /**
     * URL gambar hero (null bila belum ada gambar yang diupload).
     */
    public static function heroImageUrl(): ?string
    {
        $path = trim((string) Setting::get('web_hero_image', ''));

        return $path !== '' ? asset('storage/' . $path) : null;
    }

    /**
     * Path relatif gambar hero di disk public (untuk hapus file).
     */
    public static function heroImagePath(): ?string
    {
        $path = trim((string) Setting::get('web_hero_image', ''));

        return $path !== '' ? $path : null;
    }

    /**
     * ID video YouTube hero yang valid (fallback ke default bila tidak valid).
     */
    public static function youtubeId(): string
    {
        $id = trim(static::get('web_hero_video'));
        return preg_match('/^[\w-]{11}$/', $id) ? $id : static::defaults()['web_hero_video'];
    }

    /**
     * URL embed YouTube untuk background hero (autoplay, mute, loop).
     */
    public static function youtubeEmbedUrl(): string
    {
        $id = static::youtubeId();

        return "https://www.youtube.com/embed/{$id}?autoplay=1&mute=1&loop=1&playlist={$id}"
            . '&controls=0&showinfo=0&modestbranding=1&rel=0&iv_load_policy=3'
            . '&playsinline=1&disablekb=1&fs=0&cc_load_policy=0';
    }

    /**
     * Ekstrak ID video YouTube dari berbagai bentuk input
     * (URL watch, URL share youtu.be, URL embed, atau ID mentah).
     * Mengembalikan null bila tidak dikenali.
     */
    public static function extractYoutubeId(?string $input): ?string
    {
        $input = trim((string) $input);
        if ($input === '') {
            return null;
        }
        if (preg_match('/^[\w-]{11}$/', $input)) {
            return $input;
        }
        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([\w-]{11})%i', $input, $m)) {
            return $m[1];
        }

        return null;
    }
}
