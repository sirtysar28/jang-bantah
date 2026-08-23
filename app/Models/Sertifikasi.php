<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sertifikasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'aset_id', 'status', 'nomor_sertifikat', 'tanggal_pengajuan',
        'tanggal_terbit', 'nomor_hm_hgb', 'jenis_sertifikat',
        'keterangan', 'progress', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_pengajuan' => 'date',
            'tanggal_terbit' => 'date',
            'progress' => 'decimal:2',
        ];
    }

    public static function statusOptions(): array
    {
        return [
            'belum_diajukan' => 'Belum Diajukan',
            'persiapan_berkas' => 'Persiapan Berkas',
            'diajukan_bpn' => 'Diajukan ke BPN',
            'proses_ukur' => 'Proses Ukur',
            'proses_validasi' => 'Proses Validasi',
            'terbit_sertifikat' => 'Terbit Sertifikat',
            'selesai' => 'Selesai',
        ];
    }

    public function aset()
    {
        return $this->belongsTo(Aset::class);
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function getStatusLabelAttribute(): string
    {
        return self::statusOptions()[$this->status] ?? $this->status;
    }

    public function getProgressColorAttribute(): string
    {
        return match($this->status) {
            'belum_diajukan' => 'secondary',
            'persiapan_berkas' => 'info',
            'diajukan_bpn' => 'warning',
            'proses_ukur' => 'warning',
            'proses_validasi' => 'primary',
            'terbit_sertifikat' => 'success',
            'selesai' => 'success',
            default => 'secondary',
        };
    }
}
