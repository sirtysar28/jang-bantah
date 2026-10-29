<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aset;
use App\Models\Kecamatan;
use App\Models\Opd;
use App\OpdAccess;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AsetExport;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $kecamatans = Kecamatan::orderBy('nama')->get();
        // OPD hanya melihat laporan aset OPD-nya sendiri
        $opds = OpdAccess::limited($user)
            ? Opd::where('id', $user->opd_id)->orderBy('nama')->get()
            : Opd::orderBy('nama')->get();

        $query = Aset::with(['kecamatan', 'desa', 'opd', 'sertifikasi', 'pengamananHukum']);
        OpdAccess::scopeAset($query, $user);
        $this->applyFilters($request, $query, $user);

        $asets = $query->get();

        // Basis query ringkasan (terbatas ke OPD sendiri jika login OPD)
        $ringkasanAset = OpdAccess::scopeAset(Aset::query(), $user);

        // Ringkasan sesuai filter
        $ringkasan = [
            'total'        => (clone $ringkasanAset)->count(),
            'nilai'        => (clone $ringkasanAset)->sum('nilai_aset'),
            'luas'         => (clone $ringkasanAset)->sum('luas'),
            'sertifikat'   => (clone $ringkasanAset)->whereHas('sertifikasi', fn($q) => $q->whereIn('status', ['terbit_sertifikat', 'selesai']))->count(),
            'belum_sertifikat' => (clone $ringkasanAset)->whereDoesntHave('sertifikasi')->count(),
            'proses_sertifikasi' => (clone $ringkasanAset)->whereHas('sertifikasi', fn($q) => $q->whereNotIn('status', ['terbit_sertifikat', 'selesai']))->count(),
            'sengketa'     => (clone $ringkasanAset)->whereHas('pengamananHukum', fn($q) => $q->where('status_sengketa', '!=', 'tidak_sengketa'))->count(),
            'aktif'        => (clone $ringkasanAset)->where('status', 'aktif')->count(),
        ];

        // Ringkasan sesuai filter aktif
        $filteredSummary = [
            'total'  => $asets->count(),
            'nilai'  => $asets->sum('nilai_aset'),
            'luas'   => $asets->sum('luas'),
        ];

        return view('admin.laporan.index', compact('kecamatans', 'opds', 'asets', 'ringkasan', 'filteredSummary'));
    }

    public function exportPdf(Request $request)
    {
        $user = Auth::user();

        $query = Aset::with(['kecamatan', 'desa', 'opd', 'sertifikasi', 'pengamananHukum']);
        OpdAccess::scopeAset($query, $user);
        $this->applyFilters($request, $query, $user);
        $asets = $query->get();

        $filterInfo = [];
        if ($request->filled('kecamatan_id')) $filterInfo[] = 'Kecamatan: ' . Kecamatan::find($request->kecamatan_id)?->nama;
        if ($request->filled('opd_id')) $filterInfo[] = 'OPD: ' . Opd::find($request->opd_id)?->nama;
        if ($request->filled('status')) $filterInfo[] = 'Status: ' . str_replace('_', ' ', $request->status);
        if ($request->filled('sertifikasi')) $filterInfo[] = 'Sertifikasi: ' . ($request->sertifikasi === 'ada' ? 'Sudah' : 'Belum');
        if ($request->filled('tahun')) $filterInfo[] = 'Tahun: ' . $request->tahun;

        $filteredSummary = [
            'total' => $asets->count(),
            'nilai' => $asets->sum('nilai_aset'),
            'luas'  => $asets->sum('luas'),
            'sertifikat' => $asets->filter(fn($a) => $a->sertifikasi && in_array($a->sertifikasi->status, ['terbit_sertifikat', 'selesai']))->count(),
        ];

        $title = 'Laporan Data Aset Tanah - JANG BANTAH Pemkab. Halmahera Barat';

        $pdf = Pdf::loadView('admin.laporan.pdf', compact('asets', 'title', 'filterInfo', 'filteredSummary'));
        $pdf->setPaper('a4', 'landscape');
        return $pdf->stream('laporan-aset.pdf');
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(new AsetExport($request), 'laporan-aset.xlsx');
    }

    private function applyFilters(Request $request, $query, ?\App\Models\User $user = null)
    {
        if ($request->filled('kecamatan_id')) {
            $query->where('kecamatan_id', $request->kecamatan_id);
        }
        // Filter OPD hanya untuk admin (OPD sudah terkunci ke OPD-nya sendiri)
        if ($request->filled('opd_id') && !OpdAccess::limited($user)) {
            $query->where('opd_id', $request->opd_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('sertifikasi')) {
            if ($request->sertifikasi === 'ada') {
                $query->whereHas('sertifikasi', fn($q) => $q->whereIn('status', ['terbit_sertifikat', 'selesai']));
            } else {
                $query->whereDoesntHave('sertifikasi')
                    ->orWhereHas('sertifikasi', fn($q) => $q->whereNotIn('status', ['terbit_sertifikat', 'selesai']));
            }
        }
        if ($request->filled('tahun')) {
            $query->where('tahun_perolehan', $request->tahun);
        }
    }
}
