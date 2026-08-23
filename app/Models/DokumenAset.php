<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DokumenAset extends Model
{
    use HasFactory;

    protected $fillable = [
        'aset_id', 'jenis_dokumen', 'nomor_dokumen',
        'file_path', 'file_name', 'file_type', 'file_size',
        'tanggal_dokumen', 'masa_berlaku', 'keterangan', 'uploaded_by',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_dokumen' => 'date',
            'masa_berlaku' => 'date',
        ];
    }

    public function aset()
    {
        return $this->belongsTo(Aset::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
