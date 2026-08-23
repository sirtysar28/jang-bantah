<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aset;
use App\Models\DokumenAset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DokumenController extends Controller
{
    public function index(Aset $aset)
    {
        $dokumens = $aset->dokumen()->latest()->paginate(10);
        return view('admin.dokumen.index', compact('aset', 'dokumens'));
    }

    public function store(Request $request, Aset $aset)
    {
        $validated = $request->validate([
            'jenis_dokumen' => 'required|string|in:sertifikat,ajb,imb_pbg,hibah,pelepasan_hak,sk,berita_acara,peta_bidang,shp,dwg,lainnya',
            'nomor_dokumen' => 'nullable|string|max:100',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png,shp,dwg|max:10240',
            'tanggal_dokumen' => 'nullable|date',
            'masa_berlaku' => 'nullable|date',
            'keterangan' => 'nullable|string',
        ]);

        $file = $request->file('file');
        $filePath = $file->store('dokumen/' . $aset->id, 'public');

        $dokumen = $aset->dokumen()->create([
            ...$validated,
            'file_path' => $filePath,
            'file_name' => $file->getClientOriginalName(),
            'file_type' => $file->getClientOriginalExtension(),
            'file_size' => $file->getSize(),
            'uploaded_by' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Dokumen berhasil diupload.');
    }

    public function download(DokumenAset $dokumen)
    {
        return Storage::disk('public')->download($dokumen->file_path, $dokumen->file_name);
    }

    public function destroy(DokumenAset $dokumen)
    {
        Storage::disk('public')->delete($dokumen->file_path);
        $dokumen->delete();
        return redirect()->back()->with('success', 'Dokumen berhasil dihapus.');
    }
}
