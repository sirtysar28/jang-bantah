<?php

namespace App\Http\Controllers\Admin;

use App\AuditLogService;
use App\Http\Controllers\Controller;
use App\Models\Kecamatan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KecamatanController extends Controller
{
    public function index()
    {
        $kecamatans = Kecamatan::withCount('asets', 'desas')->latest()->paginate(10);
        return view('admin.kecamatan.index', compact('kecamatans'));
    }

    public function create()
    {
        return view('admin.kecamatan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|unique:kecamatans|max:50',
            'deskripsi' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);
        Kecamatan::create($validated);
        AuditLogService::log('create', 'Kecamatan', Kecamatan::class, null, null, $validated);
        return redirect()->route('admin.kecamatan.index')->with('success', 'Kecamatan berhasil ditambahkan.');
    }

    public function edit(Kecamatan $kecamatan)
    {
        return view('admin.kecamatan.edit', compact('kecamatan'));
    }

    public function update(Request $request, Kecamatan $kecamatan)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => ['required', 'string', 'max:50', Rule::unique('kecamatans')->ignore($kecamatan->id)],
            'deskripsi' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);
        $old = $kecamatan->toArray();
        $kecamatan->update($validated);
        AuditLogService::log('update', 'Kecamatan', Kecamatan::class, $kecamatan->id, $old, $validated);
        return redirect()->route('admin.kecamatan.index')->with('success', 'Kecamatan berhasil diperbarui.');
    }

    public function destroy(Kecamatan $kecamatan)
    {
        AuditLogService::log('delete', 'Kecamatan', Kecamatan::class, $kecamatan->id, $kecamatan->toArray());
        $kecamatan->delete();
        return redirect()->route('admin.kecamatan.index')->with('success', 'Kecamatan berhasil dihapus.');
    }
}
