<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Aset extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nomor_register', 'kode_barang', 'nama_aset',
        'kecamatan_id', 'desa_id', 'opd_id',
        'luas', 'nilai_aset', 'status', 'tahun_perolehan', 'sumber_perolehan',
        'alamat', 'latitude', 'longitude', 'polygon', 'foto_lokasi',
        'keterangan', 'status_dokumen',
        'created_by', 'updated_by', 'is_validated', 'is_digital',
    ];

    protected function casts(): array
    {
        return [
            'luas' => 'decimal:2',
            'nilai_aset' => 'decimal:2',
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
            'polygon' => 'array',
            'is_validated' => 'boolean',
            'is_digital' => 'boolean',
        ];
    }

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class);
    }

    public function desa()
    {
        return $this->belongsTo(Desa::class);
    }

    public function opd()
    {
        return $this->belongsTo(Opd::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function dokumen()
    {
        return $this->hasMany(DokumenAset::class);
    }

    public function pengamananAdministrasi()
    {
        return $this->hasOne(PengamananAdministrasi::class);
    }

    public function pengamananFisik()
    {
        return $this->hasOne(PengamananFisik::class);
    }

    public function pengamananHukum()
    {
        return $this->hasOne(PengamananHukum::class);
    }

    public function sertifikasi()
    {
        return $this->hasOne(Sertifikasi::class);
    }

    public function monitoring()
    {
        return $this->hasMany(MonitoringAset::class);
    }

    public function pemanfaatan()
    {
        return $this->hasOne(PemanfaatanAset::class);
    }

    public function hasSertifikat(): bool
    {
        return $this->sertifikasi && in_array($this->sertifikasi->status, ['terbit_sertifikat', 'selesai']);
    }
}
