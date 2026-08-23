<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aset;
use App\Models\Kecamatan;
use App\Models\Opd;
use Illuminate\Http\Request;

class GISController extends Controller
{
    public function index()
    {
        $kecamatans = Kecamatan::all();
        $opds = Opd::all();
        return view('admin.gis.index', compact('kecamatans', 'opds'));
    }

    public function getGeoJson(Request $request)
    {
        $query = Aset::with(['kecamatan', 'desa', 'opd', 'sertifikasi'])
            ->whereNotNull('latitude')
            ->whereNotNull('longitude');

        if ($request->filled('kecamatan_id')) {
            $query->where('kecamatan_id', $request->kecamatan_id);
        }
        if ($request->filled('opd_id')) {
            $query->where('opd_id', $request->opd_id);
        }
        if ($request->filled('status_sertifikat')) {
            if ($request->status_sertifikat === 'ada') {
                $query->whereHas('sertifikasi', fn($q) => $q->whereIn('status', ['terbit_sertifikat', 'selesai']));
            } elseif ($request->status_sertifikat === 'belum') {
                $query->whereDoesntHave('sertifikasi')->orWhereHas('sertifikasi', fn($q) => $q->whereNotIn('status', ['terbit_sertifikat', 'selesai']));
            }
        }

        $asets = $query->get();

        $features = $asets->map(function ($aset) {
            return [
                'type' => 'Feature',
                'geometry' => $aset->polygon ?: [
                    'type' => 'Point',
                    'coordinates' => [$aset->longitude, $aset->latitude],
                ],
                'properties' => [
                    'id' => $aset->id,
                    'nomor_register' => $aset->nomor_register,
                    'nama_aset' => $aset->nama_aset,
                    'kecamatan' => $aset->kecamatan?->nama,
                    'desa' => $aset->desa?->nama,
                    'opd' => $aset->opd?->nama,
                    'luas' => $aset->luas,
                    'nilai_aset' => $aset->nilai_aset,
                    'status' => $aset->status,
                    'sertifikat' => $aset->sertifikasi?->status,
                    'foto' => $aset->foto_lokasi ? asset('storage/' . $aset->foto_lokasi) : null,
                ],
            ];
        });

        return response()->json([
            'type' => 'FeatureCollection',
            'features' => $features,
        ]);
    }
}
