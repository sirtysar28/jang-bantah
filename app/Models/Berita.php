<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Berita website publik JANG BANTAH.
 * Dikelola Super Admin lewat menu "Kelola Website" (tab Berita) —
 * jumlah berita tidak dibatasi. Halaman depan menampilkan 3 terbaru,
 * sisanya di halaman khusus /berita.
 */
class Berita extends Model
{
    protected $fillable = ['judul', 'deskripsi', 'gambar'];

    /**
     * URL gambar berita (null bila tidak ada gambar).
     */
    public function gambarUrl(): ?string
    {
        return $this->gambar ? asset('storage/' . $this->gambar) : null;
    }

    /**
     * Tanggal berita dalam format Indonesia, contoh: 9 November 2026.
     */
    public function tanggalIndo(): string
    {
        $bulan = [
            1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember',
        ];

        return $this->created_at->day . ' '
            . ($bulan[(int) $this->created_at->format('n')] ?? '')
            . ' ' . $this->created_at->year;
    }
}
