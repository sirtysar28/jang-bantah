<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\OpdAccess;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AsetExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    public function pdf(Request $request)
    {
        $query = \App\Models\Aset::with(['kecamatan', 'desa', 'opd', 'sertifikasi']);

        // OPD hanya mengekspor laporan aset milik OPD-nya sendiri
        OpdAccess::scopeAset($query, Auth::user());

        $asets = $query->get();
        $pdf = Pdf::loadView('admin.laporan.pdf', compact('asets'));
        return $pdf->download('laporan-aset.pdf');
    }

    public function excel(Request $request)
    {
        return Excel::download(new AsetExport($request), 'laporan-aset.xlsx');
    }
}
