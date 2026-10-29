<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sertifikasi;
use App\OpdAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SertifikasiController extends Controller
{
    public function index()
    {
        $query = Sertifikasi::with('aset');

        // OPD hanya melihat sertifikasi aset milik OPD-nya sendiri
        OpdAccess::scopeAsetRelation($query, Auth::user());

        return response()->json(['success' => true, 'data' => $query->paginate(20)]);
    }

    public function update(Request $request, $id)
    {
        $sertifikasi = Sertifikasi::findOrFail($id);

        if ($sertifikasi->aset) {
            OpdAccess::authorize(Auth::user(), $sertifikasi->aset);
        }
        $validated = $request->validate([
            'status' => 'required|in:belum_diajukan,persiapan_berkas,diajukan_bpn,proses_ukur,proses_validasi,terbit_sertifikat,selesai',
            'nomor_sertifikat' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ]);
        $sertifikasi->update($validated);
        return response()->json(['success' => true, 'data' => $sertifikasi]);
    }
}
