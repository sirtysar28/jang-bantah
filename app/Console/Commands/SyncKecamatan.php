<?php

namespace App\Console\Commands;

use App\Models\Kecamatan;
use Database\Seeders\KecamatanDesaSeeder;
use Illuminate\Console\Command;

/**
 * Sinkronisasi nama kecamatan di database agar sesuai daftar resmi
 * aplikasi Jang Bantah Kabupaten Halmahera Barat:
 *
 * Jailolo, Jailolo Selatan, Sahu, Sahu Timur, Ibu Selatan,
 * Ibu, Tabaru, Loloda, Loloda Tengah.
 *
 * Kecamatan yang sudah ada (dicocokkan via kode / nama) akan
 * DISESUAIKAN namanya; yang belum ada akan DITAMBAHKAN.
 */
class SyncKecamatan extends Command
{
    protected $signature = 'kecamatan:sync
                            {--without-desa : Hanya sinkronkan kecamatan, tanpa menambah desa}';

    protected $description = 'Tambahkan & sesuaikan nama kecamatan resmi Kab. Halmahera Barat (9 kecamatan) di database';

    public function handle(): int
    {
        if (! $this->confirm('Sinkronkan 9 kecamatan resmi Kab. Halmahera Barat ke database?', true)) {
            return self::SUCCESS;
        }

        $hasil = KecamatanDesaSeeder::sync(! $this->option('without-desa'));

        $this->info(sprintf(
            'Selesai. Kecamatan dibuat: %d, disesuaikan: %d. Total kecamatan: %d, desa: %d.',
            $hasil['dibuat'],
            $hasil['diperbarui'],
            $hasil['kecamatan'],
            $hasil['desa'],
        ));

        $this->table(
            ['Kode', 'Nama Kecamatan', 'Aktif'],
            Kecamatan::orderBy('kode')->get(['kode', 'nama', 'is_active'])
                ->map(fn ($k) => [$k->kode, $k->nama, $k->is_active ? 'Ya' : 'Tidak'])->all()
        );

        // Laporkan kecamatan di luar daftar resmi (tidak dihapus agar data aset tetap aman)
        $kodeResmi = collect(KecamatanDesaSeeder::data())->pluck('kode');
        $diLuar = Kecamatan::whereNotIn('kode', $kodeResmi)->pluck('nama');
        if ($diLuar->isNotEmpty()) {
            $this->warn('Kecamatan di luar daftar resmi (tidak dihapus): ' . $diLuar->implode(', '));
        }

        return self::SUCCESS;
    }
}
