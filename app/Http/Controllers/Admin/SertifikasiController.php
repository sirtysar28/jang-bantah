<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aset;
use App\Models\Sertifikasi;
use App\OpdAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SertifikasiController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Hanya tampilkan sertifikasi yang aset induknya masih ada (belum dihapus)
        $query = Sertifikasi::with(['aset.kecamatan', 'aset.opd'])->whereHas('aset');

        // OPD hanya melihat sertifikasi aset milik OPD-nya sendiri
        OpdAccess::scopeAsetRelation($query, $user);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $sertifikasis = $query->latest()->paginate(10);
        return view('admin.sertifikasi.index', compact('sertifikasis'));
    }

    public function update(Request $request, Sertifikasi $sertifikasi)
    {
        if ($sertifikasi->aset) {
            OpdAccess::authorize(Auth::user(), $sertifikasi->aset);
        }

        $validated = $request->validate([
            'status' => 'required|in:belum_diajukan,persiapan_berkas,diajukan_bpn,proses_ukur,proses_validasi,terbit_sertifikat,selesai',
            'nomor_sertifikat' => 'nullable|string|max:100',
            'tanggal_pengajuan' => 'nullable|date',
            'tanggal_terbit' => 'nullable|date',
            'nomor_hm_hgb' => 'nullable|string|max:100',
            'jenis_sertifikat' => 'nullable|in:hm,hgb,hak_pengelolaan,hak_use',
            'keterangan' => 'nullable|string',
            'progress' => 'nullable|numeric|min:0|max:100',
        ]);

        $old = $sertifikasi->toArray();
        $validated['updated_by'] = Auth::id();
        $sertifikasi->update($validated);

        // Auto set progress based on status
        $progressMap = [
            'belum_diajukan' => 0,
            'persiapan_berkas' => 16.67,
            'diajukan_bpn' => 33.33,
            'proses_ukur' => 50,
            'proses_validasi' => 66.67,
            'terbit_sertifikat' => 83.33,
            'selesai' => 100,
        ];
        $statusVal = $validated['status'] ?? $sertifikasi->status;
        $progressVal = $progressMap[$statusVal] ?? 0;
        $sertifikasi->update(['progress' => $progressVal]);

        return redirect()->back()->with('success', 'Status sertifikasi berhasil diperbarui.');
    }

    public function create(Aset $aset)
    {
        OpdAccess::authorize(Auth::user(), $aset);

        return view('admin.sertifikasi.create', compact('aset'));
    }

    public function store(Request $request, Aset $aset)
    {
        OpdAccess::authorize(Auth::user(), $aset);

        $validated = $request->validate([
            'status' => 'required|in:belum_diajukan,persiapan_berkas,diajukan_bpn,proses_ukur,proses_validasi,terbit_sertifikat,selesai',
            'nomor_sertifikat' => 'nullable|string|max:100',
            'tanggal_pengajuan' => 'nullable|date',
            'tanggal_terbit' => 'nullable|date',
            'nomor_hm_hgb' => 'nullable|string|max:100',
            'jenis_sertifikat' => 'nullable|in:hm,hgb,hak_pengelolaan,hak_use',
            'keterangan' => 'nullable|string',
        ]);

        $progressMap = [
            'belum_diajukan' => 0, 'persiapan_berkas' => 16.67, 'diajukan_bpn' => 33.33,
            'proses_ukur' => 50, 'proses_validasi' => 66.67, 'terbit_sertifikat' => 83.33, 'selesai' => 100,
        ];
        $validated['progress'] = $progressMap[$validated['status']] ?? 0;
        $validated['updated_by'] = Auth::id();

        $aset->sertifikasi()->create($validated);
        return redirect()->route('admin.aset.show', $aset)->with('success', 'Data sertifikasi berhasil ditambahkan.');
    }
}
