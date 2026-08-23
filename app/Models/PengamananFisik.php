<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengamananFisik extends Model
{
    use HasFactory;

    protected $fillable = [
        'aset_id', 'patok', 'pagar', 'plang_pemda', 'kondisi',
        'foto_sebelum', 'foto_sesudah', 'tanggal_monitoring', 'keterangan', 'checked_by',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_monitoring' => 'date',
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
