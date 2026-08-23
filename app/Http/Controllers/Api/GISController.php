<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Aset;
use Illuminate\Http\Request;

class GISController extends Controller
{
    public function index(Request $request)
    {
        $query = Aset::with(['kecamatan', 'desa', 'opd', 'sertifikasi'])
            ->whereNotNull('latitude')->whereNotNull('longitude');
        if ($request->filled('kecamatan_id')) $query->where('kecamatan_id', $request->kecamatan_id);
        if ($request->filled('opd_id')) $query->where('opd_id', $request->opd_id);
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
