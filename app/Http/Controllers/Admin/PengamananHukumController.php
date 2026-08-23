<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aset;
use App\Models\PengamananHukum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengamananHukumController extends Controller
{
    public function index(Request $request)
    {
        $query = Aset::with(['kecamatan', 'opd', 'pengamananHukum.checker']);

        if ($request->filled('filter') && $request->filter === 'belum') {
            $query->whereDoesntHave('pengamananHukum');
        } elseif ($request->filled('filter') && $request->filter === 'aman') {
            $query->whereHas('pengamananHukum', fn($q) => $q->where('status_sengketa', 'tidak_sengketa'));
        } elseif ($request->filled('filter') && $request->filter === 'sengketa') {
            $query->whereHas('pengamananHukum', fn($q) => $q->where('status_sengketa', '!=', 'tidak_sengketa'));
        }

        if ($request->filled('search')) {
            $query->where('nama_aset', 'like', '%' . $request->search . '%')
                ->orWhere('nomor_register', 'like', '%' . $request->search . '%');
        }

        $asets = $query->latest()->paginate(10);

        $totalAset = Aset::count();
        $sudahDiisi = PengamananHukum::count();
        $belumDiisi = $totalAset - $sudahDiisi;
        $aman = PengamananHukum::where('status_sengketa', 'tidak_sengketa')->count();
        $bermasalah = PengamananHukum::where('status_sengketa', '!=', 'tidak_sengketa')->count();

        return view('admin.pengamanan.hukum', compact('asets', 'totalAset', 'sudahDiisi', 'belumDiisi', 'aman', 'bermasalah'));
    }

    public function updateOrCreate(Request $request, Aset $aset)
    {
        $validated = $request->validate([
            'status_sengketa' => 'required|in:tidak_sengketa,sengketa,perkara,gugatan',
            'nomor_perkara' => 'nullable|string|max:100',
            'pihak_lawan' => 'nullable|string|max:255',
            'status_pengadilan' => 'nullable|in:belum,berjalan,selesai,menang,kalah',
            'pendampingan_kejaksaan' => 'required|in:belum,berjalan,selesai',
            'tanggal_sengketa' => 'nullable|date',
            'riwayat_penyelesaian' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ]);

        $aset->pengamananHukum()->updateOrCreate(
            ['aset_id' => $aset->id],
            array_merge($validated, ['checked_by' => Auth::id()])
        );

        if ($validated['status_sengketa'] !== 'tidak_sengketa') {
            $aset->update(['status' => 'sengketa']);
        }

        return redirect()->back()->with('success', 'Pengamanan hukum berhasil disimpan.');
    }
}
