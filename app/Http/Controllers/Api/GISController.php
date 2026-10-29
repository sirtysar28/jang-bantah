<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Aset;
use App\OpdAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GISController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Aset::with(['kecamatan', 'desa', 'opd', 'sertifikasi'])
            ->whereNotNull('latitude')->whereNotNull('longitude');

        // OPD hanya melihat titik aset milik OPD-nya sendiri di peta
        OpdAccess::scopeAset($query, $user);

        if ($request->filled('kecamatan_id')) $query->where('kecamatan_id', $request->kecamatan_id);
        // Filter OPD hanya untuk admin (OPD sudah terkunci ke OPD-nya sendiri)
        if ($request->filled('opd_id') && !OpdAccess::limited($user)) $query->where('opd_id', $request->opd_id);
        $asets = $query->get();

        $features = $asets->map(fn($a) => [
            'type' => 'Feature',
            'geometry' => ['type' => 'Point', 'coordinates' => [$a->longitude, $a->latitude]],
            'properties' => [
                'id' => $a->id, 'nama_aset' => $a->nama_aset, 'kecamatan' => $a->kecamatan?->nama,
                'opd' => $a->opd?->nama, 'luas' => $a->luas, 'nilai_aset' => $a->nilai_aset,
                'status' => $a->status, 'sertifikat' => $a->sertifikasi?->status,
            ],
        ]);
        return response()->json(['type' => 'FeatureCollection', 'features' => $features]);
    }
}
