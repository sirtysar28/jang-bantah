<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Desa extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'kode', 'kecamatan_id', 'deskripsi', 'latitude', 'longitude', 'is_active'];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class);
    }

    public function asets()
    {
        return $this->hasMany(Aset::class);
    }
}
