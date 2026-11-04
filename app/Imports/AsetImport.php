<?php

namespace App\Imports;

use App\Models\Aset;
use App\Models\Desa;
use App\Models\Kecamatan;
use App\Models\Opd;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

/**
 * Import data aset tanah dari file Excel (.xlsx / .xls / .csv).
 *
 * Kolom yang dikenali (heading row, tidak case-sensitive):
 * nomor_register*, kode_barang*, nama_aset*, kecamatan, desa, opd,
 * luas (m2), nilai_aset (rp), status, tahun_perolehan, sumber_perolehan,
 * alamat, latitude, longitude, keterangan
 *
 * Baris dengan nomor_register yang sudah ada otomatis DILEWATI (bukan overwrite).
 */
class AsetImport implements ToCollection, WithHeadingRow
{
    /** Jumlah baris berhasil disimpan */
    public int $inserted = 0;

    /** Jumlah baris dilewati (duplikat nomor_register) */
    public int $skipped = 0;

    /** Daftar error per baris: [nomor_excel_row => pesan] */
    public array $errors = [];

    private const STATUS_MAP = [
        'aktif', 'tidak_aktif', 'sengketa', 'dipinjamkan', 'disediakan',
    ];

    private const SUMBER_MAP = [
        'beli', 'hibah', 'swap', 'masuk_karena_diambil_alih',
        'hasil_pengembalian', 'hasil_pendapatan', 'lainnya',
    ];

    public function collection(Collection $rows): void
    {
        // Heading row = baris 1, sehingga data mulai baris 2 di Excel
        $excelRow = 1;

        foreach ($rows as $row) {
            $excelRow++;

            $data = $this->normalize($row);
            $register = $data['nomor_register'];

            if ($register === '') {
                $this->errors[$excelRow] = 'Nomor register kosong';
                continue;
            }

            if (Aset::where('nomor_register', $register)->withTrashed()->exists()) {
                $this->skipped++;
                continue;
            }

            $validator = \Validator::make($data, [
                'nomor_register' => 'required|string|max:100',
                'kode_barang'    => 'required|string|max:100',
                'nama_aset'      => 'required|string|max:255',
                'kecamatan_id'   => 'nullable|exists:kecamatans,id',
                'desa_id'        => 'nullable|exists:desas,id',
                'opd_id'         => 'nullable|exists:opds,id',
                'luas'           => 'nullable|numeric',
                'nilai_aset'     => 'nullable|numeric',
                'status'         => 'nullable|in:' . implode(',', self::STATUS_MAP),
                'tahun_perolehan' => 'nullable|integer',
                'sumber_perolehan' => 'nullable|in:' . implode(',', self::SUMBER_MAP),
                'alamat'         => 'nullable|string',
                'latitude'       => 'nullable|numeric',
                'longitude'      => 'nullable|numeric',
                'keterangan'     => 'nullable|string',
            ], [
                'nomor_register.required' => 'Nomor register wajib diisi',
                'kode_barang.required'    => 'Kode barang wajib diisi',
                'nama_aset.required'      => 'Nama aset wajib diisi',
                'status.in'               => 'Status tidak dikenali (pilih: aktif / tidak_aktif / sengketa / dipinjamkan / disediakan)',
                'sumber_perolehan.in'     => 'Sumber perolehan tidak dikenali',
                'luas.numeric'            => 'Luas harus berupa angka',
                'nilai_aset.numeric'      => 'Nilai aset harus berupa angka',
                'latitude.numeric'        => 'Latitude harus berupa angka desimal',
                'longitude.numeric'       => 'Longitude harus berupa angka desimal',
                'tahun_perolehan.integer' => 'Tahun perolehan harus berupa angka tahun',
            ]);

            if ($validator->fails()) {
                $this->errors[$excelRow] = implode('; ', $validator->errors()->all());
                continue;
            }

            $payload = array_filter($validator->validated(), fn ($v) => $v !== null && $v !== '');
            $payload['status']       = $payload['status'] ?? 'aktif';
            $payload['created_by']   = auth()->id();
            $payload['is_validated'] = false; // hasil import menunggu validasi admin

            Aset::create($payload);
            $this->inserted++;
        }
    }

    /**
     * Rapikan satu baris Excel: lowercase key, trim value,
     * dan resolve nama kecamatan/desa/opd menjadi ID.
     */
    private function normalize(Collection $row): array
    {
        $get = function (string ...$keys) use ($row) {
            foreach ($keys as $key) {
                $key = strtolower(trim($key));
                if ($row->has($key)) {
                    $value = trim((string) $row->get($key));
                    if ($value !== '') {
                        return $value;
                    }
                }
            }
            return null;
        };

        $kecamatanNama = $get('kecamatan', 'nama_kecamatan');
        $desaNama      = $get('desa', 'nama_desa', 'kelurahan');
        $opdNama       = $get('opd', 'nama_opd', 'pengguna');

        $kecamatan = $kecamatanNama
            ? Kecamatan::whereRaw('LOWER(nama) = ?', [strtolower($kecamatanNama)])->first()
            : null;

        $desa = null;
        if ($desaNama) {
            $desaQuery = Desa::whereRaw('LOWER(nama) = ?', [strtolower($desaNama)]);
            if ($kecamatan) {
                $desaQuery->where('kecamatan_id', $kecamatan->id);
            }
            $desa = $desaQuery->first();
        }

        $opd = $opdNama
            ? Opd::whereRaw('LOWER(nama) = ?', [strtolower($opdNama)])
                ->orWhereRaw('LOWER(singkatan) = ?', [strtolower($opdNama)])
                ->first()
            : null;

        // Angka: bersihkan pemisah ribuan koma/titik & "Rp"
        $numeric = function ($value) {
            if ($value === null) {
                return null;
            }
            $value = str_replace(['Rp', 'rp', ' '], '', (string) $value);
            // "1.234.567" atau "1.234.567,89" (format ID) => 1234567.89
            if (preg_match('/^-?\d{1,3}(\.\d{3})+(,\d+)?$/', $value)) {
                $value = str_replace('.', '', $value);
            }
            $value = str_replace(',', '.', $value);

            return is_numeric($value) ? (float) $value : null;
        };

        // Koordinat: SELALU desimal ("1,651" => 1.651, tanpa interpretasi ribuan —
        // titik pada latitude/longitude Indonesia selalu berarti desimal)
        $coordinate = function ($value) {
            if ($value === null) {
                return null;
            }
            $value = str_replace(',', '.', trim((string) $value));

            return is_numeric($value) ? (float) $value : null;
        };

        $status = $get('status');
        $status = $status ? str_replace([' ', '-'], '_', strtolower($status)) : null;
        if ($status === 'tidak aktif') {
            $status = 'tidak_aktif';
        }

        $sumber = $get('sumber_perolehan', 'sumber');
        $sumber = $sumber ? str_replace([' ', '-'], '_', strtolower($sumber)) : null;
        if ($sumber === 'diambil_alih') {
            $sumber = 'masuk_karena_diambil_alih';
        }

        return [
            'nomor_register'  => (string) ($get('nomor_register', 'no_register', 'noreg') ?? ''),
            'kode_barang'     => (string) ($get('kode_barang', 'kode') ?? ''),
            'nama_aset'       => (string) ($get('nama_aset', 'nama') ?? ''),
            'kecamatan_id'    => $kecamatan?->id,
            'desa_id'         => $desa?->id,
            'opd_id'          => $opd?->id,
            'luas'            => $numeric($get('luas', 'luas_m2', 'luas_m²')),
            'nilai_aset'      => $numeric($get('nilai_aset', 'nilai', 'nilai_rp')),
            'status'          => $status,
            'tahun_perolehan' => ($t = $get('tahun_perolehan', 'tahun')) !== null ? (int) $t : null,
            'sumber_perolehan' => $sumber,
            'alamat'          => $get('alamat', 'alamat_lokasi'),
            'latitude'        => $coordinate($get('latitude', 'lat')),
            'longitude'       => $coordinate($get('longitude', 'lng', 'long')),
            'keterangan'      => $get('keterangan', 'ket'),
        ];
    }
}
