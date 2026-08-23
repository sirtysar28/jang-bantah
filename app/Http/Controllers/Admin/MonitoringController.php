<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aset;
use App\Models\MonitoringAset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MonitoringController extends Controller
{
    public function index(Request $request)
    {
        $query = MonitoringAset::with(['aset.kecamatan', 'aset.opd', 'user']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('aset', function ($q) use ($search) {
                $q->where('nama_aset', 'like', "%{$search}%")
                  ->orWhere('nomor_register', 'like', "%{$search}%");
            })->orWhere('catatan', 'like', "%{$search}%");
        }
        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $monitorings = $query->latest()->paginate(10)->appends($request->query());
        return view('admin.monitoring.index', compact('monitorings'));
    }

    public function store(Request $request, Aset $aset)
    {
        $validated = $request->validate([
            'jenis' => 'required|in:validasi,sertifikasi,digitalisasi,dokumen,fisik',
            'status' => 'required|in:pending,berjalan,selesai,gagal',
            'progress' => 'nullable|numeric|min:0|max:100',
            'catatan' => 'nullable|string',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date',
        ]);

        $aset->monitoring()->create(array_merge($validated, ['user_id' => Auth::id()]));
        return redirect()->back()->with('success', 'Monitoring berhasil ditambahkan.');
    }

    public function update(Request $request, MonitoringAset $monitoring)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,berjalan,selesai,gagal',
            'progress' => 'nullable|numeric|min:0|max:100',
            'catatan' => 'nullable|string',
            'tanggal_selesai' => 'nullable|date',
        ]);

        $monitoring->update($validated);

        // Auto-complete: kalau status selesai, set progress 100
        if ($validated['status'] === 'selesai') {
            $monitoring->update(['progress' => 100]);
        }

        return redirect()->back()->with('success', 'Monitoring berhasil diperbarui.');
    }
}
