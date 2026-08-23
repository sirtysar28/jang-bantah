<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengamananHukum extends Model
{
    use HasFactory;

    protected $fillable = [
        'aset_id', 'status_sengketa', 'nomor_perkara', 'pihak_lawan',
        'status_pengadilan', 'pendampingan_kejaksaan', 'tanggal_sengketa',
        'riwayat_penyelesaian', 'keterangan', 'checked_by',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_sengketa' => 'date',
        ];
    }

    public function aset()
    {
        return $this->belongsTo(Aset::class);
    }

    public function checker()
    {
        return $this->belongsTo(User::class, 'checked_by');
    }
}
