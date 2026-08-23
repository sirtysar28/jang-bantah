<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aset;
use App\Models\PemanfaatanAset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PemanfaatanController extends Controller
{
    public function index(Request $request)
    {
        $query = PemanfaatanAset::with(['aset.kecamatan', 'aset.opd', 'creator']);

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pemanfaatans = $query->latest()->paginate(10)->appends($request->query());
        return view('admin.pemanfaatan.index', compact('pemanfaatans'));
    }

    public function store(Request $request, Aset $aset)
    {
        $validated = $request->validate([
            'kategori' => 'required|in:disewakan,kerjasama,pinjam_pakai,bangun_guna_serah,idle_asset',
            'nama_pihak' => 'nullable|string|max:255',
            'alamat_pihak' => 'nullable|string',
            'kontak_pihak' => 'nullable|string|max:20',
            'nilai_potensi_pad' => 'nullable|numeric',
            'nilai_realisasi' => 'nullable|numeric',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date',
            'dokumen_perjanjian' => 'nullable|file|mimes:pdf|max:5120',
            'keterangan' => 'nullable|string',
        ]);

        if ($request->hasFile('dokumen_perjanjian')) {
            $validated['dokumen_perjanjian'] = $request->file('dokumen_perjanjian')->store('pemanfaatan', 'public');
        }
        $validated['created_by'] = Auth::id();

        $aset->pemanfaatan()->create($validated);
        return redirect()->back()->with('success', 'Data pemanfaatan berhasil ditambahkan.');
    }

    public function update(Request $request, PemanfaatanAset $pemanfaatan)
    {
        $validated = $request->validate([
            'kategori' => 'required|in:disewakan,kerjasama,pinjam_pakai,bangun_guna_serah,idle_asset',
            'nama_pihak' => 'nullable|string|max:255',
            'alamat_pihak' => 'nullable|string',
            'kontak_pihak' => 'nullable|string|max:20',
            'nilai_potensi_pad' => 'nullable|numeric',
            'nilai_realisasi' => 'nullable|numeric',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date',
            'status' => 'required|in:aktif,tidak_aktif,selesai',
            'keterangan' => 'nullable|string',
        ]);

        $pemanfaatan->update($validated);
        return redirect()->back()->with('success', 'Data pemanfaatan berhasil diperbarui.');
    }
}
