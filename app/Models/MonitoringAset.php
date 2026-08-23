<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonitoringAset extends Model
{
    use HasFactory;

    protected $fillable = [
        'aset_id', 'jenis', 'status', 'progress', 'catatan',
        'tanggal_mulai', 'tanggal_selesai', 'user_id',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
            'progress' => 'decimal:2',
        ];
    }

    public function aset()
    {
        return $this->belongsTo(Aset::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
