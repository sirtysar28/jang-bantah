<?php

namespace App\Http\Controllers\Admin;

use App\AuditLogService;
use App\Http\Controllers\Controller;
use App\Models\Aset;
use App\Models\Desa;
use App\Models\Kecamatan;
use App\Models\Opd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AsetController extends Controller
{
    public function index(Request $request)
    {
        $query = Aset::with(['kecamatan', 'desa', 'opd', 'sertifikasi']);

        if ($request->filled('search')) {
            $query->where('nama_aset', 'like', '%' . $request->search . '%')
                ->orWhere('nomor_register', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('kecamatan_id')) {
            $query->where('kecamatan_id', $request->kecamatan_id);
        }
        if ($request->filled('opd_id')) {
            $query->where('opd_id', $request->opd_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('sertifikasi')) {
            if ($request->sertifikasi === 'ada') {
                $query->whereHas('sertifikasi', fn($q) => $q->whereIn('status', ['terbit_sertifikat', 'selesai']));
            } else {
                $query->whereDoesntHave('sertifikasi')->orWhereHas('sertifikasi', fn($q) => $q->whereNotIn('status', ['terbit_sertifikat', 'selesai']));
            }
        }

        $asets = $query->latest()->paginate(10);
        $kecamatans = Kecamatan::all();
        $opds = Opd::all();

        return view('admin.aset.index', compact('asets', 'kecamatans', 'opds'));
    }

    public function create()
    {
        $kecamatans = Kecamatan::orderBy('nama')->get();
        $desas = Desa::orderBy('nama')->get();
        $opds = Opd::all();
        return view('admin.aset.create', compact('kecamatans', 'desas', 'opds'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomor_register' => 'required|string|unique:asets|max:100',
            'kode_barang' => 'required|string|max:100',
            'nama_aset' => 'required|string|max:255',
            'kecamatan_id' => 'nullable|exists:kecamatans,id',
            'desa_id' => [
                'nullable',
                'exists:desas,id',
                function ($attribute, $value, $fail) use ($request) {
                    if ($value && ! $request->filled('kecamatan_id')) {
                        $fail('Pilih kecamatan terlebih dahulu sebelum memilih desa.');
                        return;
                    }
                    if ($value && $request->filled('kecamatan_id')) {
                        $desa = Desa::find($value);
                        if ($desa && (string) $desa->kecamatan_id !== (string) $request->kecamatan_id) {
                            $fail('Desa yang dipilih tidak sesuai dengan kecamatan yang dipilih.');
                        }
                    }
                },
            ],
            'opd_id' => 'nullable|exists:opds,id',
            'luas' => 'nullable|numeric',
            'nilai_aset' => 'nullable|numeric',
            'status' => 'required|in:aktif,tidak_aktif,sengketa,dipinjamkan,disediakan',
            'tahun_perolehan' => 'nullable|integer',
            'sumber_perolehan' => 'nullable|in:beli,hibah,swap,masuk_karena_diambil_alih,hasil_pengembalian,hasil_pendapatan,lainnya',
            'alamat' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'foto_lokasi' => 'nullable|image|max:5120',
            'keterangan' => 'nullable|string',
        ]);

        if ($request->hasFile('foto_lokasi')) {
            $validated['foto_lokasi'] = $request->file('foto_lokasi')->store('aset/foto', 'public');
        }
        $validated['created_by'] = Auth::id();

        Aset::create($validated);
        return redirect()->route('admin.aset.index')->with('success', 'Aset berhasil ditambahkan.');
    }

    public function show(Aset $aset)
    {
        $aset->load(['kecamatan', 'desa', 'opd', 'sertifikasi', 'pengamananAdministrasi', 'pengamananFisik', 'pengamananHukum', 'dokumen', 'monitoring', 'pemanfaatan']);
        return view('admin.aset.show', compact('aset'));
    }

    public function edit(Aset $aset)
    {
        $kecamatans = Kecamatan::orderBy('nama')->get();
        $desas = Desa::orderBy('nama')->get();
        $opds = Opd::all();
        return view('admin.aset.edit', compact('aset', 'kecamatans', 'desas', 'opds'));
    }

    public function update(Request $request, Aset $aset)
    {
        $validated = $request->validate([
            'nomor_register' => 'required|string|max:100|unique:asets,nomor_register,' . $aset->id,
            'kode_barang' => 'required|string|max:100',
            'nama_aset' => 'required|string|max:255',
            'kecamatan_id' => 'nullable|exists:kecamatans,id',
            'desa_id' => [
                'nullable',
                'exists:desas,id',
                function ($attribute, $value, $fail) use ($request) {
                    if ($value && ! $request->filled('kecamatan_id')) {
                        $fail('Pilih kecamatan terlebih dahulu sebelum memilih desa.');
                        return;
                    }
                    if ($value && $request->filled('kecamatan_id')) {
                        $desa = Desa::find($value);
                        if ($desa && (string) $desa->kecamatan_id !== (string) $request->kecamatan_id) {
                            $fail('Desa yang dipilih tidak sesuai dengan kecamatan yang dipilih.');
                        }
                    }
                },
            ],
            'opd_id' => 'nullable|exists:opds,id',
            'luas' => 'nullable|numeric',
            'nilai_aset' => 'nullable|numeric',
            'status' => 'required|in:aktif,tidak_aktif,sengketa,dipinjamkan,disediakan',
            'tahun_perolehan' => 'nullable|integer',
            'sumber_perolehan' => 'nullable|in:beli,hibah,swap,masuk_karena_diambil_alih,hasil_pengembalian,hasil_pendapatan,lainnya',
            'alamat' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'foto_lokasi' => 'nullable|image|max:5120',
            'keterangan' => 'nullable|string',
            'is_validated' => 'boolean',
            'is_digital' => 'boolean',
        ]);

        if ($request->hasFile('foto_lokasi')) {
            if ($aset->foto_lokasi) Storage::disk('public')->delete($aset->foto_lokasi);
            $validated['foto_lokasi'] = $request->file('foto_lokasi')->store('aset/foto', 'public');
        }
        $validated['updated_by'] = Auth::id();

        $old = $aset->toArray();
        $aset->update($validated);
        AuditLogService::log('update', 'Aset', Aset::class, $aset->id, $old, $validated);

        return redirect()->route('admin.aset.show', $aset)->with('success', 'Aset berhasil diperbarui.');
    }

    public function destroy(Aset $aset)
    {
        if ($aset->foto_lokasi) Storage::disk('public')->delete($aset->foto_lokasi);
        AuditLogService::log('delete', 'Aset', Aset::class, $aset->id, $aset->toArray());
        $aset->delete();
        return redirect()->route('admin.aset.index')->with('success', 'Aset berhasil dihapus.');
    }

    public function validateAset(Aset $aset)
    {
        $aset->update(['is_validated' => true]);
        return redirect()->back()->with('success', 'Aset berhasil divalidasi.');
    }
}
