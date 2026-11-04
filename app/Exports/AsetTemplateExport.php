<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

/**
 * Template Excel untuk import data aset tanah (menu: Data Tanah → Import Excel).
 * Baris 1 = header (dibaca oleh AsetImport), baris 2-3 = contoh pengisian.
 */
class AsetTemplateExport implements FromArray, ShouldAutoSize, WithTitle
{
    public function array(): array
    {
        return [
            [
                'nomor_register', 'kode_barang', 'nama_aset', 'kecamatan', 'desa', 'opd',
                'luas', 'nilai_aset', 'status', 'tahun_perolehan', 'sumber_perolehan',
                'alamat', 'latitude', 'longitude', 'keterangan',
            ],
            [
                'AST-0001', '1.03.01.01.010001', 'Tanah Kantor Bupati',
                'Jailolo', 'Jati', 'BKAD',
                12500.50, 750000000, 'aktif', 2015, 'beli',
                'Jl. Pengabdian No. 1', 1.6512, 127.8012, 'Contoh baris 1 — silakan hapus',
            ],
            [
                'AST-0002', '1.03.01.01.010002', 'Tanah Taman Kota',
                'Jailolo', 'Momoji', 'Dinas PU',
                3000, 250000000, 'aktif', 2018, 'hibah',
                'Jl. Diponegoro', 1.6601, 127.8155, 'Contoh baris 2 — silakan hapus',
            ],
        ];
    }

    public function title(): string
    {
        return 'Template Import Aset';
    }
}
