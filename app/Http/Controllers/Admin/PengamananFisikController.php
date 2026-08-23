<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aset;
use App\Models\PengamananFisik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PengamananFisikController extends Controller
{
    public function index(Request $request)
    {
        $query = Aset::with(['kecamatan', 'opd', 'pengamananFisik.checker']);

        if ($request->filled('filter') && $request->filter === 'belum') {
            $query->whereDoesntHave('pengamananFisik');
        } elseif ($request->filled('filter') && $request->filter === 'baik') {
            $query->whereHas('pengamananFisik', fn($q) => $q->where('kondisi', 'baik'));
        } elseif ($request->filled('filter') && $request->filter === 'rusak') {
            $query->whereHas('pengamananFisik', fn($q) => $q->whereIn('kondisi', ['rusak_ringan', 'rusak_berat']));
        } elseif ($request->filled('filter') && $request->filter === 'tidak_ditemukan') {
            $query->whereHas('pengamananFisik', fn($q) => $q->where('kondisi', 'tidak_ditemukan'));
        }

        if ($request->filled('search')) {
            $query->where('nama_aset', 'like', '%' . $request->search . '%')
                ->orWhere('nomor_register', 'like', '%' . $request->search . '%');
        }

        $asets = $query->latest()->paginate(10);

        $totalAset = Aset::count();
        $sudahDiisi = PengamananFisik::count();
        $belumDiisi = $totalAset - $sudahDiisi;
        $kondisiBaik = PengamananFisik::where('kondisi', 'baik')->count();
        $kondisiRusak = PengamananFisik::whereIn('kondisi', ['rusak_ringan', 'rusak_berat'])->count();

        return view('admin.pengamanan.fisik', compact('asets', 'totalAset', 'sudahDiisi', 'belumDiisi', 'kondisiBaik', 'kondisiRusak'));
    }

    public function updateOrCreate(Request $request, Aset $aset)
    {
        $validated = $request->validate([
            'patok' => 'required|in:ada,tidak_ada,rusak',
            'pagar' => 'required|in:ada,tidak_ada,rusak',
            'plang_pemda' => 'required|in:ada,tidak_ada,rusak',
            'kondisi' => 'required|in:baik,rusak_ringan,rusak_berat,tidak_ditemukan',
            'foto_sebelum' => 'nullable|image|max:5120',
            'foto_sesudah' => 'nullable|image|max:5120',
            'tanggal_monitoring' => 'nullable|date',
            'keterangan' => 'nullable|string',
        ]);

        if ($request->hasFile('foto_sebelum')) {
            $validated['foto_sebelum'] = $request->file('foto_sebelum')->store('pengamanan/fisik', 'public');
        }
        if ($request->hasFile('foto_sesudah')) {
            $validated['foto_sesudah'] = $request->file('foto_sesudah')->store('pengamanan/fisik', 'public');
        }

        $aset->pengamananFisik()->updateOrCreate(
            ['aset_id' => $aset->id],
            array_merge($validated, ['checked_by' => Auth::id()])
        );

        return redirect()->back()->with('success', 'Pengamanan fisik berhasil disimpan.');
    }
}
