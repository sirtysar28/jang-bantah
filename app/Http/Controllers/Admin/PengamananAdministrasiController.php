<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aset;
use App\Models\PengamananAdministrasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengamananAdministrasiController extends Controller
{
    public function index(Request $request)
    {
        $query = Aset::with(['kecamatan', 'opd', 'pengamananAdministrasi.checker']);

        // Filter: aset yang sudah punya data pengamanan admin
        if ($request->filled('filter') && $request->filter === 'belum') {
            $query->whereDoesntHave('pengamananAdministrasi');
        } elseif ($request->filled('filter') && $request->filter === 'lengkap') {
            $query->whereHas('pengamananAdministrasi', function ($q) {
                $q->where('dokumen_pendukung', 'lengkap')
                  ->where('sertifikat', 'ada')
                  ->where('ajb', 'ada');
            });
        } elseif ($request->filled('filter') && $request->filter === 'kurang') {
            $query->whereHas('pengamananAdministrasi', function ($q) {
                $q->where('dokumen_pendukung', 'kurang')
                  ->orWhere('dokumen_pendukung', 'belum_ada');
            });
        }

        if ($request->filled('search')) {
            $query->where('nama_aset', 'like', '%' . $request->search . '%')
                ->orWhere('nomor_register', 'like', '%' . $request->search . '%');
        }

        $asets = $query->latest()->paginate(10);

        $totalAset = Aset::count();
        $sudahDiisi = PengamananAdministrasi::count();
        $belumDiisi = $totalAset - $sudahDiisi;
        $lengkap = PengamananAdministrasi::where('dokumen_pendukung', 'lengkap')
            ->where('sertifikat', 'ada')->where('ajb', 'ada')->count();

        return view('admin.pengamanan.administrasi', compact('asets', 'totalAset', 'sudahDiisi', 'belumDiisi', 'lengkap'));
    }

    public function updateOrCreate(Request $request, Aset $aset)
    {
        $validated = $request->validate([
            'sertifikat' => 'required|in:ada,tidak_ada',
            'imb_pbg' => 'required|in:ada,tidak_ada',
            'ajb' => 'required|in:ada,tidak_ada',
            'hibah' => 'required|in:ada,tidak_ada',
            'pelepasan_hak' => 'required|in:ada,tidak_ada',
            'sk' => 'required|in:ada,tidak_ada',
            'berita_acara' => 'required|in:ada,tidak_ada',
            'dokumen_pendukung' => 'required|in:lengkap,kurang,belum_ada',
            'keterangan' => 'nullable|string',
        ]);

        $pengamanan = $aset->pengamananAdministrasi()->updateOrCreate(
            ['aset_id' => $aset->id],
            array_merge($validated, ['checked_by' => Auth::id()])
        );

        $status = $pengamanan->calculateStatus();
        $aset->update(['status_dokumen' => $status]);

        return redirect()->back()->with('success', 'Pengamanan administrasi berhasil disimpan.');
    }
}
