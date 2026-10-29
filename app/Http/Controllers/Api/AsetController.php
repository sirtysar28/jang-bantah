<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Aset;
use App\Models\DokumenAset;
use App\OpdAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AsetController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Aset::with(['kecamatan', 'desa', 'opd', 'sertifikasi']);

        // OPD hanya melihat aset milik OPD-nya sendiri
        OpdAccess::scopeAset($query, $user);

        if ($request->filled('search')) {
            $query->where('nama_aset', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('kecamatan_id')) $query->where('kecamatan_id', $request->kecamatan_id);
        // Filter OPD hanya untuk admin (OPD sudah terkunci ke OPD-nya sendiri)
        if ($request->filled('opd_id') && !OpdAccess::limited($user)) $query->where('opd_id', $request->opd_id);

        return response()->json(['success' => true, 'data' => $query->paginate(20)]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomor_register' => 'required|string|unique:asets',
            'kode_barang' => 'required|string',
            'nama_aset' => 'required|string',
            'kecamatan_id' => 'nullable|exists:kecamatans,id',
            'desa_id' => 'nullable|exists:desas,id',
            'opd_id' => 'nullable|exists:opds,id',
            'luas' => 'nullable|numeric',
            'nilai_aset' => 'nullable|numeric',
            'status' => 'required|in:aktif,tidak_aktif,sengketa,dipinjamkan,disediakan',
            'tahun_perolehan' => 'nullable|integer',
            'sumber_perolehan' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'keterangan' => 'nullable|string',
        ]);
        $validated['created_by'] = Auth::id();

        // OPD tidak bisa mendaftarkan aset atas nama OPD lain
        $validated = OpdAccess::forceOpdId(Auth::user(), $validated);

        $aset = Aset::create($validated);
        return response()->json(['success' => true, 'data' => $aset], 201);
    }

    public function show($id)
    {
        $aset = Aset::with(['kecamatan', 'desa', 'opd', 'sertifikasi', 'pengamananAdministrasi', 'pengamananFisik', 'pengamananHukum', 'dokumen'])->findOrFail($id);

        OpdAccess::authorize(Auth::user(), $aset);

        return response()->json(['success' => true, 'data' => $aset]);
    }

    public function update(Request $request, $id)
    {
        $aset = Aset::findOrFail($id);

        OpdAccess::authorize(Auth::user(), $aset);

        $validated = $request->validate([
            'nama_aset' => 'string',
            'luas' => 'nullable|numeric',
            'nilai_aset' => 'nullable|numeric',
            'status' => 'in:aktif,tidak_aktif,sengketa,dipinjamkan,disediakan',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'keterangan' => 'nullable|string',
        ]);
        $aset->update($validated);
        return response()->json(['success' => true, 'data' => $aset]);
    }

    public function destroy($id)
    {
        $aset = Aset::findOrFail($id);

        OpdAccess::authorize(Auth::user(), $aset);

        $aset->delete();
        return response()->json(['success' => true, 'message' => 'Aset berhasil dihapus.']);
    }

    public function dokumen($id)
    {
        $aset = Aset::findOrFail($id);

        OpdAccess::authorize(Auth::user(), $aset);

        $dokumen = DokumenAset::where('aset_id', $id)->get();
        return response()->json(['success' => true, 'data' => $dokumen]);
    }

    public function uploadDokumen(Request $request)
    {
        $validated = $request->validate([
            'aset_id' => 'required|exists:asets,id',
            'jenis_dokumen' => 'required|string',
            'file' => 'required|file|max:10240',
        ]);

        $aset = Aset::findOrFail($validated['aset_id']);

        OpdAccess::authorize(Auth::user(), $aset);

        $file = $request->file('file');
        $path = $file->store('dokumen/' . $validated['aset_id'], 'public');
        $dokumen = DokumenAset::create([
            'aset_id' => $validated['aset_id'],
            'jenis_dokumen' => $validated['jenis_dokumen'],
            'file_path' => $path,
            'file_name' => $file->getClientOriginalName(),
            'file_type' => $file->getClientOriginalExtension(),
            'file_size' => $file->getSize(),
            'uploaded_by' => Auth::id(),
        ]);
        return response()->json(['success' => true, 'data' => $dokumen], 201);
    }
}
