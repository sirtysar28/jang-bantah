<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PemanfaatanAset extends Model
{
    use HasFactory;

    protected $fillable = [
        'aset_id', 'kategori', 'nama_pihak', 'alamat_pihak', 'kontak_pihak',
        'nilai_potensi_pad', 'nilai_realisasi', 'tanggal_mulai', 'tanggal_selesai',
        'dokumen_perjanjian', 'keterangan', 'status', 'created_by',
    ];

    protected function casts(): array
    {
        return [
            'nilai_potensi_pad' => 'decimal:2',
            'nilai_realisasi' => 'decimal:2',
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
        ];
    }

    public function aset()
    {
        return $this->belongsTo(Aset::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
