<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aset;
use App\Models\Kecamatan;
use App\Models\Opd;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AsetExport;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $kecamatans = Kecamatan::orderBy('nama')->get();
        $opds = Opd::orderBy('nama')->get();

        $query = Aset::withTrashed()->with(['kecamatan', 'desa', 'opd', 'sertifikasi', 'pengamananHukum']);
        $this->applyFilters($request, $query);

        $asets = $query->get();

        // Ringkasan sesuai filter
        $ringkasan = [
            'total'        => Aset::withTrashed()->count(),
            'nilai'        => Aset::withTrashed()->sum('nilai_aset'),
            'luas'         => Aset::withTrashed()->sum('luas'),
            'sertifikat'   => Aset::withTrashed()->whereHas('sertifikasi', fn($q) => $q->whereIn('status', ['terbit_sertifikat', 'selesai']))->count(),
            'belum_sertifikat' => Aset::withTrashed()->whereDoesntHave('sertifikasi')->count(),
            'proses_sertifikasi' => Aset::withTrashed()->whereHas('sertifikasi', fn($q) => $q->whereNotIn('status', ['terbit_sertifikat', 'selesai']))->count(),
            'sengketa'     => Aset::withTrashed()->whereHas('pengamananHukum', fn($q) => $q->where('status_sengketa', '!=', 'tidak_sengketa'))->count(),
            'aktif'        => Aset::withTrashed()->where('status', 'aktif')->count(),
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
        $query = Aset::withTrashed()->with(['kecamatan', 'desa', 'opd', 'sertifikasi', 'pengamananHukum']);
        $this->applyFilters($request, $query);
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

    private function applyFilters(Request $request, $query)
    {
        if ($request->filled('kecamatan_id')) {
            $query->where('kecamatan_id', $request->kecamatan_id);
        }
        if ($request->filled('opd_id')) {
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
