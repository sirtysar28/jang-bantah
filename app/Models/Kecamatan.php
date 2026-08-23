<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'kode', 'deskripsi', 'latitude', 'longitude', 'is_active'];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function desas()
    {
        return $this->hasMany(Desa::class);
    }

    public function asets()
    {
        return $this->hasMany(Aset::class);
    }
}
