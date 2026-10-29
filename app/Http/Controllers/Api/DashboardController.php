<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Aset;
use App\Models\Kecamatan;
use App\Models\MonitoringAset;
use App\Models\PemanfaatanAset;
use App\Models\Sertifikasi;
use App\OpdAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // OPD hanya melihat statistik aset milik OPD-nya sendiri
        $baseAset = OpdAccess::scopeAset(Aset::query(), $user);
        $baseSertifikasi = fn() => OpdAccess::scopeAsetRelation(Sertifikasi::query(), $user);

        return response()->json([
            'success' => true,
            'data' => [
                'total_aset' => (clone $baseAset)->count(),
                'total_nilai' => (clone $baseAset)->sum('nilai_aset'),
                'sudah_sertifikat' => (clone $baseAset)->whereHas('sertifikasi', fn($q) => $q->whereIn('status', ['terbit_sertifikat', 'selesai']))->count(),
                'belum_sertifikat' => (clone $baseAset)->whereDoesntHave('sertifikasi')->count(),
                'sengketa' => (clone $baseAset)->where('status', 'sengketa')->count(),
                'proses_bpn' => (clone $baseAset)->whereHas('sertifikasi', fn($q) => $q->whereIn('status', ['diajukan_bpn', 'proses_ukur', 'proses_validasi']))->count(),
                'sudah_digital' => (clone $baseAset)->where('is_digital', true)->count(),
                'sebaran_kecamatan' => Kecamatan::withCount([
                    'asets' => fn($q) => OpdAccess::limited($user) ? $q->where('opd_id', $user->opd_id) : $q,
                ])->get(),
                'sertifikasi_status' => $baseSertifikasi()->selectRaw('status, count(*) as total')->groupBy('status')->get(),
            ],
        ]);
    }
}
