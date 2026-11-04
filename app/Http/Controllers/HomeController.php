<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use App\Models\Kecamatan;
use App\Models\Opd;
use Illuminate\Http\Request;

class HomeController
{
    public function index()
    {
        $totalAset = Aset::count();
        $totalKecamatan = Kecamatan::count();
        $totalDesa = \App\Models\Desa::count();
        $totalOpd = Opd::count();
        $totalNilai = Aset::sum('nilai_aset');
        $totalLuas = (float) Aset::sum('luas');
        $asetDipublikasikan = Aset::where('is_validated', true)->count();
        $sudahSertifikat = Aset::whereHas('sertifikasi', function ($q) {
            $q->whereIn('status', ['terbit_sertifikat', 'selesai']);
        })->count();
        $asets = Aset::with(['kecamatan', 'desa', 'opd', 'sertifikasi'])
            ->where('is_validated', true)
            ->latest()
            ->take(3) // homepage hanya 3 terbaru — sisanya di halaman Data Aset
            ->get();

        return view('home', compact(
            'totalAset',
            'totalKecamatan',
            'totalDesa',
            'totalOpd',
            'totalNilai',
            'totalLuas',
            'asetDipublikasikan',
            'sudahSertifikat',
            'asets'
        ));
    }

    public function about()
    {
        return view('about');
    }

    /**
     * Halaman publik "Data Aset" — kumpulan seluruh aset yang sudah
     * dipublikasikan (tervalidasi), lengkap dengan pencarian &
     * filter kecamatan + pagination.
     */
    public function dataAset(Request $request)
    {
        $query = Aset::with(['kecamatan', 'desa', 'opd', 'sertifikasi'])
            ->where('is_validated', true);

        if ($request->filled('q')) {
            $q = trim($request->string('q'));
            $query->where(function ($w) use ($q) {
                $w->where('nama_aset', 'like', "%{$q}%")
                    ->orWhere('nomor_register', 'like', "%{$q}%")
                    ->orWhere('alamat', 'like', "%{$q}%");
            });
        }

        if ($request->filled('kecamatan_id')) {
            $query->where('kecamatan_id', $request->kecamatan_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $asets = $query->latest()->paginate(12)->withQueryString();

        // Statistik ringkas untuk header halaman
        $totalAsetPublik = Aset::where('is_validated', true)->count();
        $totalNilaiPublik = Aset::where('is_validated', true)->sum('nilai_aset');
        $totalLuasPublik = (float) Aset::where('is_validated', true)->sum('luas');
        $sudahSertifikatPublik = Aset::where('is_validated', true)
            ->whereHas('sertifikasi', fn ($s) => $s->whereIn('status', ['terbit_sertifikat', 'selesai']))
            ->count();
        $kecamatans = Kecamatan::orderBy('nama')->get();

        return view('data-aset', compact(
            'asets', 'kecamatans',
            'totalAsetPublik', 'totalNilaiPublik', 'totalLuasPublik', 'sudahSertifikatPublik'
        ));
    }

    public function mapPublic(Request $request)
    {
        // Parameter ?aset=NOMOR_REGISTER (dipakai QR Code identifikasi aset):
        // peta otomatis menitik-kan bidang terkait & membuka popup-nya.
        $focusRegister = $request->filled('aset') ? $request->string('aset')->trim() : null;

        // Aset tampil di peta: semua yang tervalidasi, plus (jika ada
        // parameter ?aset=) bidang terkait walau belum tervalidasi
        $asets = Aset::with(['kecamatan', 'desa', 'opd', 'sertifikasi', 'pengamananHukum'])
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->where(function ($q) use ($focusRegister) {
                $q->where('is_validated', true);
                if ($focusRegister) {
                    $q->orWhere('nomor_register', $focusRegister);
                }
            })
            ->get();

        $total = $asets->count();

        // Build marker data array in PHP (safe, no Blade parsing issues)
        $mapData = [];
        $bersertifikat = 0;
        $sengketa = 0;
        $belumSertifikasi = 0;
        $prosesSertifikasi = 0;

        foreach ($asets as $a) {
            $isSertifikat = $a->hasSertifikat();
            $isSengketa = $a->pengamananHukum && $a->pengamananHukum->status_sengketa !== 'tidak_sengketa';
            $isProses = $a->sertifikasi && !$isSertifikat;

            if ($isSertifikat) {
                $grp = 'bersertifikat'; $bersertifikat++;
            } elseif ($isSengketa) {
                $grp = 'sengketa'; $sengketa++;
            } elseif ($isProses) {
                $grp = 'proses'; $prosesSertifikasi++;
            } else {
                // Belum ada record sertifikasi sama sekali
                $grp = 'belum'; $belumSertifikasi++;
            }

            // Build popup HTML
            $h = '<div style="min-width:220px;max-width:300px;font-family:Poppins,sans-serif;">';

            // Status badge
            if ($isSertifikat) {
                $h .= '<div style="margin-bottom:6px;"><span style="display:inline-block;padding:3px 10px;border-radius:10px;font-size:11px;font-weight:600;background:#27ae60;color:#fff;">Bersertifikat</span></div>';
            } elseif ($isSengketa) {
                $label = strtoupper(str_replace('_', ' ', $a->pengamananHukum->status_sengketa));
                $h .= '<div style="margin-bottom:6px;"><span style="display:inline-block;padding:3px 10px;border-radius:10px;font-size:11px;font-weight:600;background:#e74c3c;color:#fff;">&#9888; ' . $label . '</span></div>';
            } elseif ($isProses) {
                $h .= '<div style="margin-bottom:6px;"><span style="display:inline-block;padding:3px 10px;border-radius:10px;font-size:11px;font-weight:600;background:#f39c12;color:#fff;">&#9202; ' . strtoupper($a->sertifikasi->status_label) . '</span></div>';
            } elseif (!$a->sertifikasi) {
                $h .= '<div style="margin-bottom:6px;"><span style="display:inline-block;padding:3px 10px;border-radius:10px;font-size:11px;font-weight:600;background:#3498db;color:#fff;">Belum Sertifikasi</span></div>';
            } else {
                $h .= '<div style="margin-bottom:6px;"><span style="display:inline-block;padding:3px 10px;border-radius:10px;font-size:11px;font-weight:600;background:#95a5a6;color:#fff;">' . strtoupper(str_replace('_', ' ', $a->status)) . '</span></div>';
            }

            $h .= '<h6 style="margin:0 0 6px;font-size:14px;">' . e($a->nama_aset) . '</h6>';
            $h .= '<p style="margin:2px 0;font-size:12px;"><i class="fas fa-map-marker-alt" style="width:14px;color:#666;"></i> <b>Kec:</b> ' . e($a->kecamatan->nama ?? '-') . '</p>';
            if ($a->desa) {
                $h .= '<p style="margin:2px 0;font-size:12px;"><i class="fas fa-home" style="width:14px;color:#666;"></i> <b>Desa:</b> ' . e($a->desa->nama) . '</p>';
            }
            $h .= '<p style="margin:2px 0;font-size:12px;"><i class="fas fa-building" style="width:14px;color:#666;"></i> <b>OPD:</b> ' . e($a->opd->singkatan ?? $a->opd->nama ?? '-') . '</p>';
            $h .= '<p style="margin:2px 0;font-size:12px;"><i class="fas fa-ruler-combined" style="width:14px;color:#666;"></i> <b>Luas:</b> ' . number_format($a->luas, 2) . ' m&sup2;</p>';
            $h .= '<p style="margin:2px 0;font-size:12px;"><i class="fas fa-money-bill" style="width:14px;color:#666;"></i> <b>Nilai:</b> Rp ' . number_format($a->nilai_aset, 0, ',', '.') . '</p>';

            // Foto lokasi
            if ($a->foto_lokasi) {
                $fotoUrl = \Storage::url($a->foto_lokasi);
                $h .= '<hr style="margin:6px 0;">';
                $h .= '<img src="' . $fotoUrl . '" alt="Foto Lokasi" style="width:100%;height:130px;object-fit:cover;border-radius:8px;border:1px solid #eee;">';
            }

            // Button Lihat Gmaps
            $gmapsUrl = 'https://www.google.com/maps?q=' . $a->latitude . ',' . $a->longitude . '&z=18';
            $h .= '<div style="margin-top:8px;">';
            $h .= '<a href="' . $gmapsUrl . '" target="_blank" rel="noopener" style="display:inline-flex;align-items:center;gap:6px;padding:6px 14px;border-radius:8px;font-size:11px;font-weight:600;text-decoration:none;background:linear-gradient(135deg,#4285f4,#34a853);color:#fff;box-shadow:0 2px 6px rgba(66,133,244,.3);">';
            $h .= '<svg viewBox="0 0 24 24" width="14" height="14" fill="#fff"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5S10.62 6.5 12 6.5s2.5 1.12 2.5 2.5S13.38 11.5 12 11.5z"/></svg>';
            $h .= ' Lihat Gmaps</a></div>';

            // Sertifikasi detail
            if ($a->sertifikasi) {
                $h .= '<hr style="margin:6px 0;">';
                $h .= '<p style="margin:2px 0;font-size:11px;"><i class="fas fa-certificate" style="color:#27ae60;"></i> <b>Sertifikasi:</b> ' . e($a->sertifikasi->status_label) . '</p>';
                if ($a->sertifikasi->progress) {
                    $color = $isSertifikat ? '#27ae60' : '#f39c12';
                    $h .= '<div style="margin:4px 0;"><div style="background:#eee;border-radius:10px;overflow:hidden;height:6px;"><div style="height:100%;width:' . $a->sertifikasi->progress . '%;background:' . $color . ';border-radius:10px;"></div></div><span style="font-size:10px;color:#666;">' . round($a->sertifikasi->progress) . '%</span></div>';
                }
                if ($a->sertifikasi->nomor_sertifikat) {
                    $h .= '<p style="margin:2px 0;font-size:11px;"><b>No. Sertifikat:</b> ' . e($a->sertifikasi->nomor_sertifikat) . '</p>';
                }
                if ($a->sertifikasi->tanggal_terbit) {
                    $h .= '<p style="margin:2px 0;font-size:11px;"><b>Tgl Terbit:</b> ' . $a->sertifikasi->tanggal_terbit->format('d/m/Y') . '</p>';
                }
            }

            // Sengketa detail
            if ($a->pengamananHukum && $a->pengamananHukum->status_sengketa !== 'tidak_sengketa') {
                $h .= '<hr style="margin:6px 0;">';
                $h .= '<p style="margin:2px 0;font-size:11px;color:#e74c3c;"><b><i class="fas fa-exclamation-triangle"></i> PENGIKAT HUKUM</b></p>';
                $h .= '<p style="margin:2px 0;font-size:11px;"><b>Status:</b> ' . strtoupper(str_replace('_', ' ', e($a->pengamananHukum->status_sengketa))) . '</p>';
                if ($a->pengamananHukum->pihak_lawan) {
                    $h .= '<p style="margin:2px 0;font-size:11px;"><b>Pihak Lawan:</b> ' . e($a->pengamananHukum->pihak_lawan) . '</p>';
                }
                if ($a->pengamananHukum->tanggal_sengketa) {
                    $h .= '<p style="margin:2px 0;font-size:11px;"><b>Tgl Sengketa:</b> ' . $a->pengamananHukum->tanggal_sengketa->format('d/m/Y') . '</p>';
                }
                if ($a->pengamananHukum->keterangan) {
                    $h .= '<p style="margin:2px 0;font-size:11px;"><b>Ket:</b> ' . e(\Illuminate\Support\Str::limit($a->pengamananHukum->keterangan, 80)) . '</p>';
                }
            }

            $h .= '</div>';

            $mapData[] = [
                'lat'   => (float) $a->latitude,
                'lng'   => (float) $a->longitude,
                'group' => $grp,
                'popup' => $h,
                'nr'    => (string) $a->nomor_register,
            ];
        }

        return view('map-public', compact('mapData', 'total', 'bersertifikat', 'sengketa', 'belumSertifikasi', 'prosesSertifikasi', 'focusRegister'));
    }
}
