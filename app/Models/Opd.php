<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Opd extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama', 'kode', 'singkatan', 'kepala_opd', 'nip_kepala', 'alamat', 'telepon', 'email', 'is_active'
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function asets()
    {
        return $this->hasMany(Aset::class);
    }

    public function users()
    {
        return $this->hasMany(User::class, 'opd_id');
    }
}
