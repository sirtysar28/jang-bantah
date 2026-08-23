<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use App\Models\Kecamatan;
use App\Models\MonitoringAset;
use App\Models\Notifikasi;
use App\Models\Opd;
use App\Models\PemanfaatanAset;
use App\Models\Sertifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController
{
    public function index()
    {
        $user = Auth::user();

        // withTrashed() agar semua aset dihitung, termasuk yang pernah dihapus (soft-delete)
        $totalAset = Aset::withTrashed()->count();
        $totalNilai = Aset::withTrashed()->sum('nilai_aset');
        $sudahSertifikat = Aset::withTrashed()->whereHas('sertifikasi', fn($q) => $q->whereIn('status', ['terbit_sertifikat', 'selesai']))->count();
        $belumSertifikat = $totalAset - $sudahSertifikat;
        $sengketa = Aset::withTrashed()->where('status', 'sengketa')->count();
        $prosesBPN = Aset::withTrashed()->whereHas('sertifikasi', fn($q) => $q->whereIn('status', ['diajukan_bpn', 'proses_ukur', 'proses_validasi']))->count();

        // Grafik: sebaran kecamatan
        $sebaranKecamatan = Kecamatan::withCount(['asets' => fn($q) => $q->withTrashed()])->get();
        $sebaranOpd = Opd::withCount(['asets' => fn($q) => $q->withTrashed()])->take(10)->get();

        // Grafik status sertifikasi — termasuk "belum sertifikasi" (aset tanpa record)
        $belumAdaSertifikasi = Aset::withTrashed()->whereDoesntHave('sertifikasi')->count();
        $sertifikasiStatus = [
            'Belum Ada Record' => $belumAdaSertifikasi,
            'Belum Diajukan'   => Sertifikasi::where('status', 'belum_diajukan')->count(),
            'Persiapan Berkas'=> Sertifikasi::where('status', 'persiapan_berkas')->count(),
            'Diajukan BPN'    => Sertifikasi::where('status', 'diajukan_bpn')->count(),
            'Proses Ukur'     => Sertifikasi::where('status', 'proses_ukur')->count(),
            'Proses Validasi' => Sertifikasi::where('status', 'proses_validasi')->count(),
            'Terbit Sertifikat'=> Sertifikasi::where('status', 'terbit_sertifikat')->count(),
            'Selesai'         => Sertifikasi::where('status', 'selesai')->count(),
        ];

        // Monitoring progress
        $monitoringData = [
            'validasi' => MonitoringAset::where('jenis', 'validasi')->avg('progress') ?? 0,
            'sertifikasi' => MonitoringAset::where('jenis', 'sertifikasi')->avg('progress') ?? 0,
            'digitalisasi' => MonitoringAset::where('jenis', 'digitalisasi')->avg('progress') ?? 0,
            'dokumen' => MonitoringAset::where('jenis', 'dokumen')->avg('progress') ?? 0,
        ];

        // Notifikasi
        $notifikasis = Notifikasi::where('user_id', $user->id)->unread()->latest()->take(5)->get();
        $unreadCount = Notifikasi::where('user_id', $user->id)->unread()->count();

        // Riwayat penambahan aset per tahun (dari data real, termasuk tahun dgn 0 aset)
        $tahunMin = Aset::withTrashed()->min('tahun_perolehan') ?? (int) date('Y') - 5;
        $tahunMax = (int) date('Y');
        $tahunRange = range($tahunMin, $tahunMax);

        $riwayatAsetLabels = [];
        $riwayatAsetData = [];
        foreach ($tahunRange as $tahun) {
            $riwayatAsetLabels[] = (string) $tahun;
            $riwayatAsetData[] = Aset::withTrashed()->where('tahun_perolehan', $tahun)->count();
        }

        return view('admin.dashboard', compact(
            'totalAset', 'totalNilai', 'sudahSertifikat', 'belumSertifikat',
            'sengketa', 'prosesBPN',
            'sebaranKecamatan', 'sebaranOpd', 'sertifikasiStatus', 'monitoringData',
            'notifikasis', 'unreadCount', 'riwayatAsetLabels', 'riwayatAsetData', 'user'
        ));
    }
}
