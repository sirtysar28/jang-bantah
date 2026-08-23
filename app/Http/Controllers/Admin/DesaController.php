<?php

namespace App\Http\Controllers\Admin;

use App\AuditLogService;
use App\Http\Controllers\Controller;
use App\Models\Desa;
use App\Models\Kecamatan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DesaController extends Controller
{
    public function index(Request $request)
    {
        $kecamatans = Kecamatan::orderBy('nama')->get();
        $query = Desa::with('kecamatan');

        if ($request->filled('kecamatan_id')) {
            $query->where('kecamatan_id', $request->kecamatan_id);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nama', 'like', "%{$search}%")
                  ->orWhere('kode', 'like', "%{$search}%");
        }

        $desas = $query->latest()->paginate(10)->appends($request->query());

        return view('admin.desa.index', compact('desas', 'kecamatans'));
    }

    public function create()
    {
        $kecamatans = Kecamatan::all();
        return view('admin.desa.create', compact('kecamatans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|unique:desas|max:50',
            'kecamatan_id' => 'required|exists:kecamatans,id',
            'deskripsi' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);
        Desa::create($validated);
        return redirect()->route('admin.desa.index')->with('success', 'Desa berhasil ditambahkan.');
    }

    public function edit(Desa $desa)
    {
        $kecamatans = Kecamatan::all();
        return view('admin.desa.edit', compact('desa', 'kecamatans'));
    }

    public function update(Request $request, Desa $desa)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => ['required', 'string', 'max:50', Rule::unique('desas')->ignore($desa->id)],
            'kecamatan_id' => 'required|exists:kecamatans,id',
            'deskripsi' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);
        $desa->update($validated);
        return redirect()->route('admin.desa.index')->with('success', 'Desa berhasil diperbarui.');
    }

    public function destroy(Desa $desa)
    {
        $desa->delete();
        return redirect()->route('admin.desa.index')->with('success', 'Desa berhasil dihapus.');
    }
}
