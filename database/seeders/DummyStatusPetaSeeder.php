<?php

namespace Database\Seeders;

use App\Models\Aset;
use App\Models\Kecamatan;
use App\Models\PengamananHukum;
use App\Models\Sertifikasi;
use Illuminate\Database\Seeder;

class DummyStatusPetaSeeder extends Seeder
{
    /**
     * Seeder ini menambahkan aset dummy dengan 4 kategori status
     * untuk ditampilkan di peta publik.
     *
     * Kategori:
     * 1. BERSERTIFIKAT  → sudah ada sertifikat (selesai / terbit_sertifikat)
     * 2. PROSES SERTIFIKASI → ada record sertifikasi tapi status belum selesai
     * 3. BELUM SERTIFIKASI → belum ada record sertifikasi sama sekali (otomatis)
     * 4. SENGKETA → ada masalah hukum
     *
     * Catatan: aset "belum sertifikasi" TIDAK perlu dibuatkan record sertifikasi.
     * Cukup buat asetnya saja, maka otomatis masuk kategori "belum sertifikasi".
     */

    public function run(): void
    {
        // ─────────────────────────────────────────────────
        // 1. BERSERTIFIKAT (3 aset)
        //    → Buat aset + buat record sertifikasi dengan status "selesai" atau "terbit_sertifikat"
        // ─────────────────────────────────────────────────

        $kecJailolo = Kecamatan::where('nama', 'Jailolo')->first();
        $kecSahu    = Kecamatan::where('nama', 'Sahu')->first();
        $kecIbu     = Kecamatan::where('nama', 'Ibu')->first();
        $kecJailoloSelatan = Kecamatan::where('nama', 'Jailolo Selatan')->first();

        $opd = \App\Models\Opd::first();

        // --- Bersertifikat 1: Tanah Kantor DPRD (HM, selesai) ---
        $a1 = Aset::firstOrCreate(
            ['nomor_register' => 'REG-2024-101'],
            [
            'nomor_register'   => 'REG-2024-101',
            'kode_barang'      => '1.3.1.01.02.01',
            'nama_aset'        => 'Tanah Kantor DPRD Halmahera Barat',
            'kecamatan_id'     => $kecJailolo->id ?? 1,
            'opd_id'           => $opd->id,
            'luas'             => 3200.00,
            'nilai_aset'       => 18000000000,
            'status'           => 'aktif',
            'tahun_perolehan'  => 1992,
            'sumber_perolehan' => 'beli',
            'alamat'           => 'Jl. Pattimura, Jailolo',
            'latitude'         => 1.0545,
            'longitude'        => 127.5345,
            'keterangan'       => 'Tanah milik Pemkab untuk gedung DPRD',
            'is_validated'     => true,
            'is_digital'       => true,
            'created_by'       => 1,
        ]
        );
        Sertifikasi::firstOrCreate(
            ['aset_id' => $a1->id],
            [
            'aset_id'           => $a1->id,
            'status'            => 'selesai',
            'nomor_sertifikat'  => 'HM-2023-0001',
            'jenis_sertifikat'  => 'hm',
            'tanggal_pengajuan' => '2023-01-10',
            'tanggal_terbit'    => '2023-09-15',
            'progress'          => 100,
            'updated_by'        => 1,
        ]
        );
        PengamananHukum::firstOrCreate(
            ['aset_id' => $a1->id],
            [
            'aset_id' => $a1->id,
            'status_sengketa' => 'tidak_sengketa',
            'pendampingan_kejaksaan' => 'belum',
        ]
        );

        // --- Bersertifikat 2: Tanah SDN 1 Jailolo (HGB, terbit_sertifikat) ---
        $a2 = Aset::firstOrCreate(
            ['nomor_register' => 'REG-2024-102'],
            [
            'nomor_register'   => 'REG-2024-102',
            'kode_barang'      => '1.3.2.01.05.01',
            'nama_aset'        => 'Tanah SDN 1 Jailolo',
            'kecamatan_id'     => $kecJailolo->id ?? 1,
            'opd_id'           => $opd->id,
            'luas'             => 1500.00,
            'nilai_aset'       => 3000000000,
            'status'           => 'aktif',
            'tahun_perolehan'  => 2005,
            'sumber_perolehan' => 'hibah',
            'alamat'           => 'Jl. Pendidikan, Jailolo',
            'latitude'         => 1.0560,
            'longitude'        => 127.5370,
            'keterangan'       => 'Tanah hibah untuk SD negeri',
            'is_validated'     => true,
            'is_digital'       => true,
            'created_by'       => 1,
        ]
        );
        Sertifikasi::firstOrCreate(
            ['aset_id' => $a2->id],
            [
            'aset_id'           => $a2->id,
            'status'            => 'terbit_sertifikat',
            'nomor_sertifikat'  => 'HGB-2024-0012',
            'jenis_sertifikat'  => 'hgb',
            'tanggal_pengajuan' => '2024-02-01',
            'tanggal_terbit'    => '2024-11-20',
            'progress'          => 100,
            'updated_by'        => 1,
        ]
        );

        // --- Bersertifikat 3: Tanah Puskesmas Sidangoli (Hak Pengelolaan, selesai) ---
        $a3 = Aset::firstOrCreate(
            ['nomor_register' => 'REG-2024-103'],
            [
            'nomor_register'   => 'REG-2024-103',
            'kode_barang'      => '1.3.3.01.03.01',
            'nama_aset'        => 'Tanah Puskesmas Sidangoli',
            'kecamatan_id'     => $kecJailoloSelatan->id ?? 5,
            'opd_id'           => $opd->id,
            'luas'             => 800.00,
            'nilai_aset'       => 2100000000,
            'status'           => 'aktif',
            'tahun_perolehan'  => 2010,
            'sumber_perolehan' => 'beli',
            'alamat'           => 'Jl. Kesehatan, Sidangoli, Jailolo Selatan',
            'latitude'         => 0.9680,
            'longitude'        => 127.5360,
            'keterangan'       => 'Tanah milik Pemkab untuk Puskesmas',
            'is_validated'     => true,
            'is_digital'       => true,
            'created_by'       => 1,
        ]
        );
        Sertifikasi::firstOrCreate(
            ['aset_id' => $a3->id],
            [
            'aset_id'           => $a3->id,
            'status'            => 'selesai',
            'nomor_sertifikat'  => 'HP-2022-0045',
            'jenis_sertifikat'  => 'hak_pengelolaan',
            'tanggal_pengajuan' => '2022-05-10',
            'tanggal_terbit'    => '2023-01-18',
            'progress'          => 100,
            'updated_by'        => 1,
        ]
        );


        // ─────────────────────────────────────────────────
        // 2. PROSES SERTIFIKASI (3 aset)
        //    → Buat aset + buat record sertifikasi dengan status BELUM selesai
        //    → Status: belum_diajukan, persiapan_berkas, diajukan_bpn, proses_ukur, proses_validasi
        // ─────────────────────────────────────────────────

        // --- Proses 1: Tanah Pasar Sahu (proses_validasi) ---
        $a4 = Aset::firstOrCreate(
            ['nomor_register' => 'REG-2024-104'],
            [
            'nomor_register'   => 'REG-2024-104',
            'kode_barang'      => '1.3.1.01.02.02',
            'nama_aset'        => 'Tanah Pasar Sahu',
            'kecamatan_id'     => $kecSahu->id ?? 2,
            'opd_id'           => $opd->id,
            'luas'             => 2000.00,
            'nilai_aset'       => 5500000000,
            'status'           => 'aktif',
            'tahun_perolehan'  => 1998,
            'sumber_perolehan' => 'beli',
            'alamat'           => 'Jl. Pasar, Sahu',
            'latitude'         => 1.1370,
            'longitude'        => 127.3050,
            'keterangan'       => 'Tanah pasar tradisional, sedang proses validasi BPN',
            'is_validated'     => true,
            'is_digital'       => true,
            'created_by'       => 1,
        ]
        );
        Sertifikasi::firstOrCreate(
            ['aset_id' => $a4->id],
            [
            'aset_id'           => $a4->id,
            'status'            => 'proses_validasi',
            'tanggal_pengajuan' => '2024-03-15',
            'progress'          => 66.67,
            'keterangan'        => 'Sedang menunggu validasi dokumen di kantor BPN',
            'updated_by'        => 1,
        ]
        );

        // --- Proses 2: Tanah Kantor Camat Ibu (diajukan_bpn) ---
        $a5 = Aset::firstOrCreate(
            ['nomor_register' => 'REG-2024-105'],
            [
            'nomor_register'   => 'REG-2024-105',
            'kode_barang'      => '1.3.1.01.02.03',
            'nama_aset'        => 'Tanah Kantor Camat Ibu',
            'kecamatan_id'     => $kecIbu->id ?? 3,
            'opd_id'           => $opd->id,
            'luas'             => 600.00,
            'nilai_aset'       => 1200000000,
            'status'           => 'aktif',
            'tahun_perolehan'  => 2003,
            'sumber_perolehan' => 'hibah',
            'alamat'           => 'Jl. Camat, Ibu',
            'latitude'         => 1.2520,
            'longitude'        => 127.6530,
            'keterangan'       => 'Sudah diajukan ke BPN, menunggu jadwal pengukuran',
            'is_validated'     => true,
            'is_digital'       => true,
            'created_by'       => 1,
        ]
        );
        Sertifikasi::firstOrCreate(
            ['aset_id' => $a5->id],
            [
            'aset_id'           => $a5->id,
            'status'            => 'diajukan_bpn',
            'tanggal_pengajuan' => '2024-06-01',
            'progress'          => 33.33,
            'keterangan'        => 'Berkas sudah diterima BPN, menunggu penjadwalan ukur',
            'updated_by'        => 1,
        ]
        );

        // --- Proses 3: Tanah Gudang Logistik (proses_ukur) ---
        $a6 = Aset::firstOrCreate(
            ['nomor_register' => 'REG-2024-106'],
            [
            'nomor_register'   => 'REG-2024-106',
            'kode_barang'      => '1.3.1.01.02.04',
            'nama_aset'        => 'Tanah Gudang Logistik Pemkab',
            'kecamatan_id'     => $kecJailolo->id ?? 1,
            'opd_id'           => $opd->id,
            'luas'             => 4500.00,
            'nilai_aset'       => 9500000000,
            'status'           => 'aktif',
            'tahun_perolehan'  => 2008,
            'sumber_perolehan' => 'beli',
            'alamat'           => 'Jl. Pelabuhan, Jailolo',
            'latitude'         => 1.0480,
            'longitude'        => 127.5260,
            'keterangan'       => 'Sedang proses pengukuran oleh petugas BPN',
            'is_validated'     => true,
            'is_digital'       => true,
            'created_by'       => 1,
        ]
        );
        Sertifikasi::firstOrCreate(
            ['aset_id' => $a6->id],
            [
            'aset_id'           => $a6->id,
            'status'            => 'proses_ukur',
            'tanggal_pengajuan' => '2024-08-10',
            'progress'          => 50,
            'keterangan'        => 'Petugas BPN sudah melakukan pengukuran lapangan',
            'updated_by'        => 1,
        ]
        );


        // ─────────────────────────────────────────────────
        // 3. BELUM SERTIFIKASI (3 aset)
        //    → Buat aset saja, TANPA record sertifikasi
        //    → Otomatis masuk kategori "belum sertifikasi"
        // ─────────────────────────────────────────────────

        // --- Belum 1: Tanah Lapangan Olahraga ---
        Aset::firstOrCreate(
            ['nomor_register' => 'REG-2024-107'],
            [
            'nomor_register'   => 'REG-2024-107',
            'kode_barang'      => '1.3.1.01.03.01',
            'nama_aset'        => 'Tanah Lapangan Olahraga Jailolo',
            'kecamatan_id'     => $kecJailolo->id ?? 1,
            'opd_id'           => $opd->id,
            'luas'             => 5000.00,
            'nilai_aset'       => 7000000000,
            'status'           => 'aktif',
            'tahun_perolehan'  => 1995,
            'sumber_perolehan' => 'beli',
            'alamat'           => 'Jl. Olahraga, Jailolo',
            'latitude'         => 1.0580,
            'longitude'        => 127.5400,
            'keterangan'       => 'Belum ada rencana sertifikasi',
            'is_validated'     => true,
            'is_digital'       => true,
            'created_by'       => 1,
        ]
        );

        // --- Belum 2: Tanah Taman Kota ---
        Aset::firstOrCreate(
            ['nomor_register' => 'REG-2024-108'],
            [
            'nomor_register'   => 'REG-2024-108',
            'kode_barang'      => '1.3.1.01.03.02',
            'nama_aset'        => 'Tanah Taman Kota Jailolo',
            'kecamatan_id'     => $kecJailolo->id ?? 1,
            'opd_id'           => $opd->id,
            'luas'             => 1200.00,
            'nilai_aset'       => 1800000000,
            'status'           => 'aktif',
            'tahun_perolehan'  => 2015,
            'sumber_perolehan' => 'beli',
            'alamat'           => 'Jl. Taman, Jailolo',
            'latitude'         => 1.0525,
            'longitude'        => 127.5355,
            'keterangan'       => 'Ruang terbuka hijau, belum diurus sertifikatnya',
            'is_validated'     => true,
            'is_digital'       => true,
            'created_by'       => 1,
        ]
        );

        // --- Belum 3: Tanah Parkir Pemkab ---
        Aset::firstOrCreate(
            ['nomor_register' => 'REG-2024-109'],
            [
            'nomor_register'   => 'REG-2024-109',
            'kode_barang'      => '1.3.1.01.03.03',
            'nama_aset'        => 'Tanah Parkir Pemkab Sidangoli',
            'kecamatan_id'     => $kecJailoloSelatan->id ?? 5,
            'opd_id'           => $opd->id,
            'luas'             => 800.00,
            'nilai_aset'       => 2500000000,
            'status'           => 'aktif',
            'tahun_perolehan'  => 2012,
            'sumber_perolehan' => 'beli',
            'alamat'           => 'Jl. Parkir, Sidangoli, Jailolo Selatan',
            'latitude'         => 0.9700,
            'longitude'        => 127.5390,
            'keterangan'       => 'Lahan parkir, belum ada record sertifikasi',
            'is_validated'     => true,
            'is_digital'       => true,
            'created_by'       => 1,
        ]
        );


        // ─────────────────────────────────────────────────
        // 4. SENGKETA (2 aset)
        //    → Buat aset + buat record pengamanan_hukum dengan status_sengketa != "tidak_sengketa"
        // ─────────────────────────────────────────────────

        // --- Sengketa 1: Tanah eks Gudang Milik Warga ---
        $a10 = Aset::firstOrCreate(
            ['nomor_register' => 'REG-2024-110'],
            [
            'nomor_register'   => 'REG-2024-110',
            'kode_barang'      => '1.3.1.01.04.01',
            'nama_aset'        => 'Tanah Eks Gudang Milik Warga (Sengketa)',
            'kecamatan_id'     => $kecSahu->id ?? 2,
            'opd_id'           => $opd->id,
            'luas'             => 700.00,
            'nilai_aset'       => 4500000000,
            'status'           => 'sengketa',
            'tahun_perolehan'  => 2000,
            'sumber_perolehan' => 'beli',
            'alamat'           => 'Jl. Sengketa, Sahu',
            'latitude'         => 1.1400,
            'longitude'        => 127.3120,
            'keterangan'       => 'Klaim kepemilikan oleh warga setempat',
            'is_validated'     => true,
            'is_digital'       => true,
            'created_by'       => 1,
        ]
        );
        PengamananHukum::firstOrCreate(
            ['aset_id' => $a10->id],
            [
            'aset_id'            => $a10->id,
            'status_sengketa'    => 'sengketa',
            'nomor_perkara'      => 'PER-2024-015',
            'pihak_lawan'        => 'H. Said Abdullah',
            'status_pengadilan'  => 'berjalan',
            'tanggal_sengketa'  => '2024-02-20',
            'keterangan'         => 'Warga mengklaim tanah ini warisan keluarga. Sidang pertama sudah dilaksanakan.',
        ]
        );

        // --- Sengketa 2: Tanah Perbatasan Desa (Gugatan) ---
        $a11 = Aset::firstOrCreate(
            ['nomor_register' => 'REG-2024-111'],
            [
            'nomor_register'   => 'REG-2024-111',
            'kode_barang'      => '1.3.1.01.04.02',
            'nama_aset'        => 'Tanah Perbatasan Desa (Gugatan)',
            'kecamatan_id'     => $kecIbu->id ?? 3,
            'opd_id'           => $opd->id,
            'luas'             => 2500.00,
            'nilai_aset'       => 8700000000,
            'status'           => 'sengketa',
            'tahun_perolehan'  => 1997,
            'sumber_perolehan' => 'beli',
            'alamat'           => 'Jl. Perbatasan, Ibu',
            'latitude'         => 1.2560,
            'longitude'        => 127.6580,
            'keterangan'       => 'Gugatan batas tanah antara Pemkab dan masyarakat adat',
            'is_validated'     => true,
            'is_digital'       => true,
            'created_by'       => 1,
        ]
        );
        PengamananHukum::firstOrCreate(
            ['aset_id' => $a11->id],
            [
            'aset_id'            => $a11->id,
            'status_sengketa'    => 'gugatan',
            'nomor_perkara'      => 'GUG-2024-008',
            'pihak_lawan'        => 'Keluarga Adat Desa Tungute Sungi',
            'status_pengadilan'  => 'belum',
            'tanggal_sengketa'  => '2024-07-01',
            'keterangan'         => 'Gugatan batas tanah. Belum masuk sidang, masih proses mediasi.',
        ]
        );


        // ─────────────────────────────────────────────────
        // RINGKASAN DATA YANG DIBUAT:
        // ─────────────────────────────────────────────────
        $this->command->info('Dummy data peta berhasil dibuat!');
        $this->command->newLine();
        $this->command->info('  🟢 BERSERTIFIKAT (3 aset):');
        $this->command->info('     - Tanah Kantor DPRD (HM, selesai)');
        $this->command->info('     - Tanah SDN 1 Jailolo (HGB, terbit_sertifikat)');
        $this->command->info('     - Tanah Puskesmas Sidangoli (Hak Pengelolaan, selesai)');
        $this->command->newLine();
        $this->command->info('  🟡 PROSES SERTIFIKASI (3 aset):');
        $this->command->info('     - Tanah Pasar Sahu (proses_validasi)');
        $this->command->info('     - Tanah Kantor Camat Ibu (diajukan_bpn)');
        $this->command->info('     - Tanah Gudang Logistik (proses_ukur)');
        $this->command->newLine();
        $this->command->info('  🔵 BELUM SERTIFIKASI (3 aset):');
        $this->command->info('     - Tanah Lapangan Olahraga Jailolo');
        $this->command->info('     - Tanah Taman Kota Jailolo');
        $this->command->info('     - Tanah Parkir Pemkab Sidangoli');
        $this->command->newLine();
        $this->command->info('  🔴 SENGKETA (2 aset):');
        $this->command->info('     - Tanah Eks Gudang Sahu (sengketa)');
        $this->command->info('     - Tanah Perbatasan Desa Ibu (gugatan)');
        $this->command->newLine();
        $this->command->warn('TOTAL: 11 aset baru ditambahkan ke peta.');
    }
}
