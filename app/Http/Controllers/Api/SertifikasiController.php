<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sertifikasi;
use Illuminate\Http\Request;

class SertifikasiController extends Controller
{
    public function index()
    {
        return response()->json(['success' => true, 'data' => Sertifikasi::with('aset')->paginate(20)]);
    }

    public function update(Request $request, $id)
    {
        $sertifikasi = Sertifikasi::findOrFail($id);
        $validated = $request->validate([
            'status' => 'required|in:belum_diajukan,persiapan_berkas,diajukan_bpn,proses_ukur,proses_validasi,terbit_sertifikat,selesai',
            'nomor_sertifikat' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ]);
        $sertifikasi->update($validated);
        return response()->json(['success' => true, 'data' => $sertifikasi]);
    }
}
