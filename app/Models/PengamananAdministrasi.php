<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengamananAdministrasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'aset_id', 'sertifikat', 'imb_pbg', 'ajb', 'hibah', 'pelepasan_hak',
        'sk', 'berita_acara', 'dokumen_pendukung', 'keterangan', 'checked_by',
    ];

    public function aset()
    {
        return $this->belongsTo(Aset::class);
    }

    public function checker()
    {
        return $this->belongsTo(User::class, 'checked_by');
    }

    public function calculateStatus(): string
    {
        $fields = ['sertifikat', 'imb_pbg', 'ajb', 'hibah', 'pelepasan_hak', 'sk', 'berita_acara'];
        $ada = 0;
        foreach ($fields as $field) {
            if ($this->$field === 'ada') $ada++;
        }
        $total = count($fields);
        if ($ada === $total) return 'lengkap';
        if ($ada > 0) return 'kurang';
        return 'belum_ada';
    }
}
