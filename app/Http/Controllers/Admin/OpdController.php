<?php

namespace App\Http\Controllers\Admin;

use App\AuditLogService;
use App\Http\Controllers\Controller;
use App\Models\Opd;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OpdController extends Controller
{
    public function index()
    {
        $opds = Opd::withCount('asets', 'users')->latest()->paginate(10);
        return view('admin.opd.index', compact('opds'));
    }

    public function create()
    {
        return view('admin.opd.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|unique:opds|max:50',
            'singkatan' => 'nullable|string|max:50',
            'kepala_opd' => 'nullable|string|max:255',
            'nip_kepala' => 'nullable|string|max:50',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);
        Opd::create($validated);
        return redirect()->route('admin.opd.index')->with('success', 'OPD berhasil ditambahkan.');
    }

    public function edit(Opd $opd)
    {
        return view('admin.opd.edit', compact('opd'));
    }

    public function update(Request $request, Opd $opd)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => ['required', 'string', 'max:50', Rule::unique('opds')->ignore($opd->id)],
            'singkatan' => 'nullable|string|max:50',
            'kepala_opd' => 'nullable|string|max:255',
            'nip_kepala' => 'nullable|string|max:50',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);
        $opd->update($validated);
        return redirect()->route('admin.opd.index')->with('success', 'OPD berhasil diperbarui.');
    }

    public function destroy(Opd $opd)
    {
        $opd->delete();
        return redirect()->route('admin.opd.index')->with('success', 'OPD berhasil dihapus.');
    }
}
