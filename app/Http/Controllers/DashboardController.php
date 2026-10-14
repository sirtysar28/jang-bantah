<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use App\Models\Kecamatan;
use App\Models\MonitoringAset;
use App\Models\Notifikasi;
use App\Models\Opd;
use App\Models\Sertifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController
{
    public function index()
    {
        $user = Auth::user();

        // Semua statistik membaca master data tanah (asets) yang AKTIF saja.
        // Aset yang dihapus (soft-delete) tidak dihitung lagi.
        $totalAset = Aset::count();
        $totalNilai = Aset::sum('nilai_aset');
        $sudahSertifikat = Aset::whereHas('sertifikasi', fn($q) => $q->whereIn('status', ['terbit_sertifikat', 'selesai']))->count();
        $belumSertifikat = $totalAset - $sudahSertifikat;
        $sengketa = Aset::where('status', 'sengketa')->count();
        $prosesBPN = Aset::whereHas('sertifikasi', fn($q) => $q->whereIn('status', ['diajukan_bpn', 'proses_ukur', 'proses_validasi']))->count();

        // Grafik: sebaran kecamatan & OPD (hanya aset aktif)
        $sebaranKecamatan = Kecamatan::withCount('asets')->get();
        $sebaranOpd = Opd::withCount('asets')->take(10)->get();

        // Grafik status sertifikasi — hanya sertifikasi milik aset yang masih ada
        $sertifikasiAktif = fn($status) => Sertifikasi::where('status', $status)->whereHas('aset');
        $belumAdaSertifikasi = Aset::whereDoesntHave('sertifikasi')->count();
        $sertifikasiStatus = [
            'Belum Ada Record' => $belumAdaSertifikasi,
            'Belum Diajukan'   => $sertifikasiAktif('belum_diajukan')->count(),
            'Persiapan Berkas' => $sertifikasiAktif('persiapan_berkas')->count(),
            'Diajukan BPN'     => $sertifikasiAktif('diajukan_bpn')->count(),
            'Proses Ukur'      => $sertifikasiAktif('proses_ukur')->count(),
            'Proses Validasi'  => $sertifikasiAktif('proses_validasi')->count(),
            'Terbit Sertifikat' => $sertifikasiAktif('terbit_sertifikat')->count(),
            'Selesai'          => $sertifikasiAktif('selesai')->count(),
        ];

        // Monitoring progress — hanya monitoring milik aset yang masih ada
        $monitoringAsetAktif = fn($jenis) => MonitoringAset::where('jenis', $jenis)->whereHas('aset')->avg('progress') ?? 0;
        $monitoringData = [
            'validasi' => $monitoringAsetAktif('validasi'),
            'sertifikasi' => $monitoringAsetAktif('sertifikasi'),
            'digitalisasi' => $monitoringAsetAktif('digitalisasi'),
            'dokumen' => $monitoringAsetAktif('dokumen'),
        ];

        // Notifikasi
        $notifikasis = Notifikasi::where('user_id', $user->id)->unread()->latest()->take(5)->get();
        $unreadCount = Notifikasi::where('user_id', $user->id)->unread()->count();

        // Riwayat penambahan aset per tahun (hanya aset aktif, termasuk tahun dengan 0 aset)
        $tahunMin = Aset::min('tahun_perolehan') ?? (int) date('Y') - 5;
        $tahunMax = (int) date('Y');
        $tahunRange = range($tahunMin, $tahunMax);

        $riwayatAsetLabels = [];
        $riwayatAsetData = [];
        foreach ($tahunRange as $tahun) {
            $riwayatAsetLabels[] = (string) $tahun;
            $riwayatAsetData[] = Aset::where('tahun_perolehan', $tahun)->count();
        }

        return view('admin.dashboard', compact(
            'totalAset', 'totalNilai', 'sudahSertifikat', 'belumSertifikat',
            'sengketa', 'prosesBPN',
            'sebaranKecamatan', 'sebaranOpd', 'sertifikasiStatus', 'monitoringData',
            'notifikasis', 'unreadCount', 'riwayatAsetLabels', 'riwayatAsetData', 'user'
        ));
    }
}
