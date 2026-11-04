<?php

namespace App\Http\Controllers\Admin;

use App\AdminNotificationService;
use App\AuditLogService;
use App\Exports\AsetTemplateExport;
use App\Http\Controllers\Controller;
use App\Imports\AsetImport;
use App\Models\Aset;
use App\Models\Desa;
use App\Models\Kecamatan;
use App\Models\Opd;
use App\OpdAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AsetController extends Controller
{
    /**
     * Generate Nomor Register berikutnya dengan format REG-{TAHUN}-{URUT}.
     * Contoh: REG-2026-0007. Urutan dihitung dari nomor tertinggi
     * tahun berjalan yang sudah tersimpan di sistem.
     */
    public static function generateNomorRegister(): string
    {
        $prefix = 'REG-' . now()->format('Y') . '-';
        $max = Aset::where('nomor_register', 'like', $prefix . '%')->max('nomor_register');
        $seq = $max ? ((int) substr($max, strlen($prefix))) + 1 : 1;

        return $prefix . str_pad((string) $seq, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Generate Kode Barang berikutnya dengan format KB-{TAHUN}-{URUT}.
     */
    public static function generateKodeBarang(): string
    {
        $prefix = 'KB-' . now()->format('Y') . '-';
        $max = Aset::where('kode_barang', 'like', $prefix . '%')->max('kode_barang');
        $seq = $max ? ((int) substr($max, strlen($prefix))) + 1 : 1;

        return $prefix . str_pad((string) $seq, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Generate pasangan nomor yang dijamin unik (antisipasi dua petugas
     * membuka form bersamaan — nomor dihitung ulang saat simpan).
     */
    private static function uniquePair(): array
    {
        for ($i = 0; $i < 10; $i++) {
            $nomor = self::generateNomorRegister();
            $kode = self::generateKodeBarang();
            $bentrok = Aset::where('nomor_register', $nomor)->orWhere('kode_barang', $kode)->exists();
            if (! $bentrok) {
                return [$nomor, $kode];
            }
        }

        // Fallback amat sangat jarang: tambahkan detik sebagai pembeda
        $sfx = now()->format('His');
        return ['REG-' . now()->format('Y') . '-' . $sfx, 'KB-' . now()->format('Y') . '-' . $sfx];
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Aset::with(['kecamatan', 'desa', 'opd', 'sertifikasi']);

        // OPD hanya melihat aset milik OPD-nya sendiri
        OpdAccess::scopeAset($query, $user);

        if ($request->filled('search')) {
            $query->where('nama_aset', 'like', '%' . $request->search . '%')
                ->orWhere('nomor_register', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('kecamatan_id')) {
            $query->where('kecamatan_id', $request->kecamatan_id);
        }
        if ($request->filled('desa_id')) {
            $query->where('desa_id', $request->desa_id);
        }
        // Filter OPD hanya untuk admin (OPD sudah terkunci ke OPD-nya sendiri)
        if ($request->filled('opd_id') && !OpdAccess::limited($user)) {
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
        $desas = Desa::all();
        $opds = Opd::all();

        return view('admin.aset.index', compact('asets', 'kecamatans', 'desas', 'opds'));
    }

    public function create()
    {
        $kecamatans = Kecamatan::orderBy('nama')->get();
        $desas = Desa::orderBy('nama')->get();
        // OPD hanya bisa memilih OPD-nya sendiri
        $opds = OpdAccess::limited(Auth::user())
            ? Opd::where('id', Auth::user()->opd_id)->get()
            : Opd::all();

        // Nomor Register & Kode Barang dibuat otomatis oleh sistem (preview;
        // nomor final dihitung ulang saat disimpan agar selalu unik)
        [$nextNomor, $nextKode] = self::uniquePair();

        return view('admin.aset.create', compact('kecamatans', 'desas', 'opds', 'nextNomor', 'nextKode'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // Dibuat otomatis sistem — nullable: controller yang mengisi
            'nomor_register' => 'nullable|string|unique:asets|max:100',
            'kode_barang' => 'nullable|string|max:100',
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
            'polygon' => [
                'nullable',
                'string',
                function ($attribute, $value, $fail) {
                    if ($value === null || $value === '') {
                        return; // kosong = tanpa polygon
                    }
                    $data = json_decode($value, true);
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        $fail('Data polygon tidak valid (bukan JSON yang valid).');
                        return;
                    }
                    if (($data['type'] ?? null) !== 'Polygon' || empty($data['coordinates'][0]) || count($data['coordinates'][0]) < 4) {
                        $fail('Polygon harus GeoJSON Polygon dengan minimal 3 titik sudut.');
                    }
                },
            ],
            'foto_lokasi' => 'nullable|image|max:5120',
            'keterangan' => 'nullable|string',
        ]);

        // Polygon dikirim sebagai string JSON dari editor peta — decode agar
        // tersimpan sebagai JSON native oleh cast "array" di model Aset.
        $validated['polygon'] = $request->filled('polygon') ? json_decode($request->input('polygon'), true) : null;

        if ($request->hasFile('foto_lokasi')) {
            $validated['foto_lokasi'] = $request->file('foto_lokasi')->store('aset/foto', 'public');
        }
        $validated['created_by'] = Auth::id();

        // ── Nomor Register & Kode Barang OTOMATIS dari sistem ──
        // Form mengirim flag auto_nomor=1 (field readonly). Nomor dihitung
        // ulang di sini agar selalu unik walau dua petugas submit bersamaan.
        if ($request->boolean('auto_nomor') || empty($validated['nomor_register'])) {
            [$validated['nomor_register'], $validated['kode_barang']] = self::uniquePair();
        } elseif (empty($validated['kode_barang'])) {
            $validated['kode_barang'] = self::generateKodeBarang();
        }

        // ── Alur persetujuan (approval) ──
        // Aset dari OPD berstatus "menunggu validasi" (belum tampil publik)
        // hingga disetujui Admin Aset / Super Admin.
        // Inputan Admin Aset / Super Admin langsung terpublikasi.
        $user = Auth::user();
        $validated['is_validated'] = $user->isOpd() ? false : true;

        // OPD tidak bisa mendaftarkan aset atas nama OPD lain
        $validated = OpdAccess::forceOpdId(Auth::user(), $validated);

        Aset::create($validated);

        // Notifikasi: pengajuan aset baru dari OPD masuk ke email admin
        $aset = Aset::where('nomor_register', $validated['nomor_register'])->first();
        if ($user && $user->isOpd()) {
            AdminNotificationService::sendEmail(
                'Pengajuan Aset Baru Menunggu Validasi',
                [
                    ['label' => 'Nama Aset', 'value' => $validated['nama_aset']],
                    ['label' => 'Nomor Register', 'value' => $validated['nomor_register']],
                    ['label' => 'Diajukan Oleh', 'value' => $user->name . ($user->opd?->nama_opd ? ' (' . $user->opd->nama_opd . ')' : '')],
                    ['label' => 'Waktu', 'value' => now()->format('d/m/Y H:i')],
                ],
                $aset ? route('admin.aset.show', $aset) : route('admin.aset.index'),
                'Review Pengajuan'
            );
            AdminNotificationService::notifyUsers(
                AdminNotificationService::adminUserIds(),
                'Pengajuan Aset Baru',
                "{$user->name} mengajukan aset baru \"{$validated['nama_aset']}\" (No. Reg: {$validated['nomor_register']}) yang menunggu validasi.",
                'warning',
                ['aset_id' => $aset?->id]
            );
        }

        return redirect()->route('admin.aset.index')->with('success', $user->isOpd()
            ? 'Aset berhasil diajukan dengan nomor register ' . $validated['nomor_register'] . '. Menunggu persetujuan Admin Aset / Super Admin agar tampil publik.'
            : 'Aset berhasil ditambahkan (No. Reg: ' . $validated['nomor_register'] . ') dan langsung terpublikasi.');
    }

    public function show(Aset $aset)
    {
        OpdAccess::authorize(Auth::user(), $aset);

        $aset->load(['kecamatan', 'desa', 'opd', 'sertifikasi', 'pengamananAdministrasi', 'pengamananFisik', 'pengamananHukum', 'dokumen', 'monitoring', 'pemanfaatan']);
        return view('admin.aset.show', compact('aset'));
    }

    public function edit(Aset $aset)
    {
        OpdAccess::authorize(Auth::user(), $aset);

        $kecamatans = Kecamatan::orderBy('nama')->get();
        $desas = Desa::orderBy('nama')->get();
        // OPD hanya bisa memilih OPD-nya sendiri
        $opds = OpdAccess::limited(Auth::user())
            ? Opd::where('id', Auth::user()->opd_id)->get()
            : Opd::all();
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
            'polygon' => [
                'nullable',
                'string',
                function ($attribute, $value, $fail) {
                    if ($value === null || $value === '') {
                        return; // kosong = hapus polygon
                    }
                    $data = json_decode($value, true);
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        $fail('Data polygon tidak valid (bukan JSON yang valid).');
                        return;
                    }
                    if (($data['type'] ?? null) !== 'Polygon' || empty($data['coordinates'][0]) || count($data['coordinates'][0]) < 4) {
                        $fail('Polygon harus GeoJSON Polygon dengan minimal 3 titik sudut.');
                    }
                },
            ],
            'foto_lokasi' => 'nullable|image|max:5120',
            'keterangan' => 'nullable|string',
            'is_validated' => 'boolean',
            'is_digital' => 'boolean',
        ]);

        // Polygon dikirim sebagai string JSON dari editor peta — decode agar
        // tersimpan sebagai JSON native oleh cast "array" di model Aset.
        $validated['polygon'] = $request->filled('polygon') ? json_decode($request->input('polygon'), true) : null;

        if ($request->hasFile('foto_lokasi')) {
            if ($aset->foto_lokasi) Storage::disk('public')->delete($aset->foto_lokasi);
            $validated['foto_lokasi'] = $request->file('foto_lokasi')->store('aset/foto', 'public');
        }
        $validated['updated_by'] = Auth::id();

        // OPD tidak bisa memindahkan asetnya ke OPD lain
        $validated = OpdAccess::forceOpdId(Auth::user(), $validated);

        // Hanya admin yang boleh mengubah status validasi aset
        if (OpdAccess::limited(Auth::user())) {
            unset($validated['is_validated']);
        }

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

    /**
     * Form import data aset dari Excel (fitur RAB: import data Excel).
     */
    public function importForm()
    {
        return view('admin.aset.import');
    }

    /**
     * Proses import file Excel/CSV berisi data aset.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240', // maks 10MB
        ], [
            'file.required' => 'Pilih file Excel terlebih dahulu.',
            'file.mimes'    => 'Format file harus .xlsx, .xls, atau .csv.',
            'file.max'      => 'Ukuran file maksimal 10MB.',
        ]);

        $import = new AsetImport();

        try {
            Excel::import($import, $request->file('file')->getRealPath());
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            return back()->withErrors(['file' => 'Struktur file tidak valid. Pastikan menggunakan template resmi.'])
                ->with('error', 'Import gagal: struktur file tidak dikenali.');
        } catch (\Throwable $e) {
            return back()->withErrors(['file' => 'File tidak dapat dibaca: ' . $e->getMessage()])
                ->with('error', 'Import gagal diproses.');
        }

        // Ringkasan hasil import
        $summary = "Import selesai — {$import->inserted} data tersimpan, {$import->skipped} dilewati (duplikat).";

        if ($import->errors) {
            $detail = collect($import->errors)->map(fn ($msg, $row) => "Baris {$row}: {$msg}")->all();
            return back()->with('import_result', [
                'summary' => $summary . ' ' . count($detail) . ' baris gagal.',
                'errors'  => $detail,
                'inserted' => $import->inserted,
                'skipped' => $import->skipped,
            ]);
        }

        return back()->with('import_result', [
            'summary' => $summary,
            'errors'  => [],
            'inserted' => $import->inserted,
            'skipped' => $import->skipped,
        ]);
    }

    /**
     * Download template Excel untuk import data aset.
     */
    public function template()
    {
        return Excel::download(new AsetTemplateExport(), 'template-import-aset.xlsx');
    }

    /**
     * QR Code identifikasi aset (fitur RAB) — berisi URL peta publik
     * yang otomatis menitik-kan & membuka popup bidang tanah terkait.
     * Tambahkan ?download=1 untuk mengunduh sebagai file gambar.
     */
    public function qrCode(Request $request, Aset $aset)
    {
        $url = route('map.public') . '?aset=' . urlencode($aset->nomor_register);

        // PNG bila ekstensi imagick tersedia, selain itu SVG (selalu didukung,
        // tidak butuh ekstensi PHP tambahan dan tetap tajam saat dicetak)
        if (extension_loaded('imagick')) {
            $content = QrCode::format('png')->size(400)->margin(2)->errorCorrection('M')->generate($url);
            $mime    = 'image/png';
            $ext     = 'png';
        } else {
            $content = QrCode::format('svg')->size(400)->margin(2)->errorCorrection('M')->generate($url);
            $mime    = 'image/svg+xml';
            $ext     = 'svg';
        }

        $headers = [
            'Content-Type'  => $mime,
            'Cache-Control' => 'public, max-age=86400',
        ];

        if ($request->boolean('download')) {
            $headers['Content-Disposition'] = 'attachment; filename="QR-' . $aset->nomor_register . '.' . $ext . '"';
        }

        return response($content, 200, $headers);
    }

    public function validateAset(Aset $aset)
    {
        $aset->update(['is_validated' => true]);

        // Email balik ke pengaju (OPD) bahwa asetnya sudah divalidasi
        $pengaju = $aset->creator;
        if ($pengaju && $pengaju->email && $pengaju->id !== Auth::id()) {
            AdminNotificationService::sendTo(
                $pengaju->email,
                'Aset Telah Divalidasi',
                [
                    ['label' => 'Nama Aset', 'value' => $aset->nama_aset],
                    ['label' => 'Nomor Register', 'value' => $aset->nomor_register],
                    ['label' => 'Divalidasi Oleh', 'value' => Auth::user()->name],
                    ['label' => 'Waktu', 'value' => now()->format('d/m/Y H:i')],
                ],
                route('admin.aset.show', $aset),
                'Lihat Aset'
            );
        }

        return redirect()->back()->with('success', 'Aset berhasil divalidasi.');
    }
}
