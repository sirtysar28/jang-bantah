<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Aset;
use App\Models\Kecamatan;
use App\Models\MonitoringAset;
use App\Models\PemanfaatanAset;
use App\Models\Sertifikasi;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => [
                'total_aset' => Aset::count(),
                'total_nilai' => Aset::sum('nilai_aset'),
                'sudah_sertifikat' => Aset::whereHas('sertifikasi', fn($q) => $q->whereIn('status', ['terbit_sertifikat', 'selesai']))->count(),
                'belum_sertifikat' => Aset::whereDoesntHave('sertifikasi')->count(),
                'sengketa' => Aset::where('status', 'sengketa')->count(),
                'proses_bpn' => Aset::whereHas('sertifikasi', fn($q) => $q->whereIn('status', ['diajukan_bpn', 'proses_ukur', 'proses_validasi']))->count(),
                'sudah_digital' => Aset::where('is_digital', true)->count(),
                'sebaran_kecamatan' => Kecamatan::withCount('asets')->get(),
                'sertifikasi_status' => Sertifikasi::selectRaw('status, count(*) as total')->groupBy('status')->get(),
            ],
        ]);
    }
}
