<?php

namespace Database\Seeders;

use App\Models\Aset;
use App\Models\Desa;
use App\Models\Kecamatan;
use App\Models\MonitoringAset;
use App\Models\Notifikasi;
use App\Models\Opd;
use App\Models\PemanfaatanAset;
use App\Models\PengamananAdministrasi;
use App\Models\PengamananFisik;
use App\Models\PengamananHukum;
use App\Models\Sertifikasi;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Super Admin (idempotent — aman dijalankan ulang)
        User::firstOrCreate(
            ['email' => 'admin@jangbantah.id'],
            [
                'name' => 'Super Administrator',
                'nip' => '000000000',
                'phone' => '081234567890',
                'jabatan' => 'Kepala BKAD',
                'role' => 'super_admin',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]
        );

        // Create Admin Aset
        User::firstOrCreate(
            ['email' => 'adminaset@jangbantah.id'],
            [
                'name' => 'Admin Bidang Aset',
                'nip' => '111111111',
                'role' => 'admin_aset',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]
        );

        // Create OPD
        $opd = Opd::firstOrCreate(
            ['kode' => 'OPD-001'],
            [
                'nama' => 'Badan Keuangan dan Aset Daerah',
                'singkatan' => 'BKAD',
                'kepala_opd' => 'Dr. H. Ahmad, S.E., M.M.',
                'nip_kepala' => '196001011990011001',
                'alamat' => 'Jl. Trans Halmahera, Jailolo',
                'telepon' => '(0916) 123456',
            ]
        );

        // Create OPD User
        User::firstOrCreate(
            ['email' => 'opd@jangbantah.id'],
            [
                'name' => 'User OPD BKAD',
                'opd_id' => $opd->id,
                'role' => 'opd',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]
        );

        // Create Pimpinan
        User::firstOrCreate(
            ['email' => 'pimpinan@jangbantah.id'],
            [
                'name' => 'Bupati Halmahera Barat',
                'role' => 'pimpinan',
                'jabatan' => 'Bupati',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]
        );

        // Master Kecamatan & Desa (data resmi Kab. Halmahera Barat)
        // Sumber: Wikipedia — 9 Kecamatan, 169 Desa
        $this->call(KecamatanDesaSeeder::class);

        // Ambil kecamatan & desa yang sudah diseed untuk relasi aset
        $kec1 = Kecamatan::where('nama', 'Jailolo')->first();
        $desaJailolo = Desa::where('nama', 'Gufasa')->first() ?? Desa::first();

        // Sample Aset
        $aset1 = Aset::firstOrCreate(
            ['nomor_register' => 'REG-2024-001'],
            [
                'kode_barang' => '1.3.1.01.01.01',
                'nama_aset' => 'Tanah Kantor Bupati',
                'kecamatan_id' => $kec1?->id,
                'desa_id' => $desaJailolo?->id,
                'opd_id' => $opd->id,
                'luas' => 2500.50,
                'nilai_aset' => 15000000000,
                'status' => 'aktif',
                'tahun_perolehan' => 1990,
                'sumber_perolehan' => 'beli',
                'alamat' => 'Jl. Trans Halmahera, Jailolo',
                'latitude' => 1.0515,
                'longitude' => 127.5306,
                'keterangan' => 'Tanah Kantor Bupati Halmahera Barat',
                'is_validated' => true,
                'is_digital' => true,
                'created_by' => 1,
            ]
        );

        $aset2 = Aset::firstOrCreate(
            ['nomor_register' => 'REG-2024-002'],
            [
                'kode_barang' => '1.3.1.01.01.02',
                'nama_aset' => 'Tanah Kantor BKAD',
                'kecamatan_id' => $kec1?->id,
                'opd_id' => $opd->id,
                'luas' => 1500.00,
                'nilai_aset' => 8500000000,
                'status' => 'aktif',
                'tahun_perolehan' => 1995,
                'sumber_perolehan' => 'hibah',
                'latitude' => 1.0520,
                'longitude' => 127.5310,
                'is_validated' => true,
                'is_digital' => true,
                'created_by' => 1,
            ]
        );

        $aset3 = Aset::firstOrCreate(
            ['nomor_register' => 'REG-2024-003'],
            [
                'kode_barang' => '1.3.1.01.01.03',
                'nama_aset' => 'Tanah Pasar Jailolo',
                'kecamatan_id' => $kec1?->id,
                'opd_id' => $opd->id,
                'luas' => 3000.00,
                'nilai_aset' => 20000000000,
                'status' => 'aktif',
                'tahun_perolehan' => 1985,
                'sumber_perolehan' => 'beli',
                'latitude' => 1.0530,
                'longitude' => 127.5320,
                'is_validated' => false,
                'created_by' => 1,
            ]
        );

        // Sample Sertifikasi
        Sertifikasi::firstOrCreate(
            ['aset_id' => $aset1->id],
            [
                'status' => 'selesai',
                'nomor_sertifikat' => 'HM-2024-001',
                'jenis_sertifikat' => 'hm',
                'tanggal_pengajuan' => '2024-01-15',
                'tanggal_terbit' => '2024-06-20',
                'progress' => 100,
                'updated_by' => 1,
            ]
        );

        Sertifikasi::firstOrCreate(
            ['aset_id' => $aset2->id],
            [
                'status' => 'proses_validasi',
                'progress' => 66.67,
                'updated_by' => 1,
            ]
        );

        // Sample Pengamanan
        PengamananAdministrasi::firstOrCreate(
            ['aset_id' => $aset1->id],
            [
                'sertifikat' => 'ada',
                'imb_pbg' => 'ada',
                'ajb' => 'ada',
                'hibah' => 'tidak_ada',
                'pelepasan_hak' => 'tidak_ada',
                'sk' => 'ada',
                'berita_acara' => 'ada',
                'dokumen_pendukung' => 'lengkap',
                'checked_by' => 1,
            ]
        );

        PengamananFisik::firstOrCreate(
            ['aset_id' => $aset1->id],
            [
                'patok' => 'ada',
                'pagar' => 'ada',
                'plang_pemda' => 'ada',
                'kondisi' => 'baik',
                'checked_by' => 1,
            ]
        );

        PengamananHukum::firstOrCreate(
            ['aset_id' => $aset1->id],
            [
                'status_sengketa' => 'tidak_sengketa',
                'pendampingan_kejaksaan' => 'belum',
                'checked_by' => 1,
            ]
        );

        // Sample Pemanfaatan
        PemanfaatanAset::firstOrCreate(
            ['aset_id' => $aset3->id],
            [
                'kategori' => 'idle_asset',
                'nilai_potensi_pad' => 500000000,
                'status' => 'aktif',
                'created_by' => 1,
            ]
        );

        // Sample Monitoring
        MonitoringAset::firstOrCreate(
            ['aset_id' => $aset1->id, 'jenis' => 'validasi'],
            [
                'status' => 'selesai',
                'progress' => 100,
                'catatan' => 'Validasi data Tanah Kantor Bupati selesai. Semua dokumen lengkap.',
                'tanggal_mulai' => '2024-01-10',
                'tanggal_selesai' => '2024-01-25',
                'user_id' => 1,
            ]
        );
        MonitoringAset::firstOrCreate(
            ['aset_id' => $aset1->id, 'jenis' => 'sertifikasi'],
            [
                'status' => 'selesai',
                'progress' => 100,
                'catatan' => 'Sertifikat HM-2024-001 telah terbit.',
                'tanggal_mulai' => '2024-01-15',
                'tanggal_selesai' => '2024-06-20',
                'user_id' => 1,
            ]
        );
        MonitoringAset::firstOrCreate(
            ['aset_id' => $aset2->id, 'jenis' => 'sertifikasi'],
            [
                'status' => 'berjalan',
                'progress' => 66.67,
                'catatan' => 'Proses validasi di BPN sedang berjalan. Menunggu pengukuran ulang.',
                'tanggal_mulai' => '2024-03-01',
                'user_id' => 1,
            ]
        );
        MonitoringAset::firstOrCreate(
            ['aset_id' => $aset2->id, 'jenis' => 'digitalisasi'],
            [
                'status' => 'berjalan',
                'progress' => 50,
                'catatan' => 'Pemindaian dokumen fisik ke format digital. 3 dari 6 dokumen selesai.',
                'tanggal_mulai' => '2024-04-15',
                'user_id' => 2,
            ]
        );
        MonitoringAset::firstOrCreate(
            ['aset_id' => $aset3->id, 'jenis' => 'dokumen'],
            [
                'status' => 'pending',
                'progress' => 0,
                'catatan' => 'Dokumen pendukung Tanah Pasar Jailolo belum diajukan oleh OPD.',
                'tanggal_mulai' => '2024-05-01',
                'user_id' => 3,
            ]
        );
        MonitoringAset::firstOrCreate(
            ['aset_id' => $aset3->id, 'jenis' => 'fisik'],
            [
                'status' => 'berjalan',
                'progress' => 40,
                'catatan' => 'Pagar dan patok perlu diperbaiki. Plang Pemda sudah terpasang.',
                'tanggal_mulai' => '2024-06-01',
                'user_id' => 2,
            ]
        );

        // Sample Notification
        Notifikasi::firstOrCreate(
            ['judul' => 'Sertifikat Terbit', 'user_id' => 1],
            [
                'pesan' => 'Sertifikat HM-2024-001 untuk Tanah Kantor Bupati telah terbit.',
                'tipe' => 'sertifikat',
            ]
        );

        Notifikasi::firstOrCreate(
            ['judul' => 'Dokumen Kurang', 'user_id' => 1],
            [
                'pesan' => 'Dokumen pendukung untuk REG-2024-003 belum lengkap.',
                'tipe' => 'dokumen',
            ]
        );
    }
}
