<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AsetExport;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function pdf(Request $request)
    {
        $asets = \App\Models\Aset::with(['kecamatan', 'desa', 'opd', 'sertifikasi'])->get();
        $pdf = Pdf::loadView('admin.laporan.pdf', compact('asets'));
        return $pdf->download('laporan-aset.pdf');
    }

    public function excel(Request $request)
    {
        return Excel::download(new AsetExport($request), 'laporan-aset.xlsx');
    }
}
