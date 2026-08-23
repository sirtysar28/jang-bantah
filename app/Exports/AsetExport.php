<?php

namespace App\Exports;

use App\Models\Aset;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Http\Request;

class AsetExport implements FromCollection, WithHeadings, WithMapping, WithTitle, WithEvents
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function title(): string
    {
        return 'Laporan Data Aset Tanah';
    }

    public function collection()
    {
        $query = Aset::withTrashed()->with(['kecamatan', 'desa', 'opd', 'sertifikasi', 'pengamananHukum']);

        if ($this->request->filled('kecamatan_id')) {
            $query->where('kecamatan_id', $this->request->kecamatan_id);
        }
        if ($this->request->filled('opd_id')) {
            $query->where('opd_id', $this->request->opd_id);
        }
        if ($this->request->filled('status')) {
            $query->where('status', $this->request->status);
        }
        if ($this->request->filled('sertifikasi')) {
            if ($this->request->sertifikasi === 'ada') {
                $query->whereHas('sertifikasi', fn($q) => $q->whereIn('status', ['terbit_sertifikat', 'selesai']));
            } else {
                $query->whereDoesntHave('sertifikasi')
                    ->orWhereHas('sertifikasi', fn($q) => $q->whereNotIn('status', ['terbit_sertifikat', 'selesai']));
            }
        }
        if ($this->request->filled('tahun')) {
            $query->where('tahun_perolehan', $this->request->tahun);
        }

        return $query->orderBy('tahun_perolehan')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nomor Register',
            'Kode Barang',
            'Nama Aset',
            'Kecamatan',
            'Desa',
            'OPD',
            'Luas (m2)',
            'Nilai Aset (Rp)',
            'Status Aset',
            'Tahun Perolehan',
            'Sumber Perolehan',
            'Status Sertifikasi',
            'No. Sertifikat',
            'Tgl Terbit',
            'Status Hukum',
            'Latitude',
            'Longitude',
            'Keterangan',
        ];
    }

    public function map($aset): array
    {
        $isSengketa = $aset->pengamananHukum && $aset->pengamananHukum->status_sengketa !== 'tidak_sengketa';

        return [
            $aset->id,
            $aset->nomor_register,
            $aset->kode_barang,
            $aset->nama_aset,
            $aset->kecamatan?->nama,
            $aset->desa?->nama,
            $aset->opd?->nama,
            $aset->luas,
            $aset->nilai_aset,
            $aset->status,
            $aset->tahun_perolehan,
            $aset->sumber_perolehan,
            $aset->sertifikasi?->status_label ?? 'Belum Ada Record',
            $aset->sertifikasi?->nomor_sertifikat ?? '-',
            $aset->sertifikasi?->tanggal_terbit ? $aset->sertifikasi->tanggal_terbit->format('d/m/Y') : '-',
            $isSengketa ? strtoupper(str_replace('_', ' ', $aset->pengamananHukum->status_sengketa)) : 'Aman',
            $aset->latitude,
            $aset->longitude,
            $aset->keterangan,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Bold header
                $event->sheet->getDelegate()->getStyle('A1:S1')->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb(255,255,255)']],
                    'fill' => ['fillType' => 'solid', 'color' => ['rgb(27,73,101)']],
                ]);
                // Auto-width
                foreach (range('A', 'S') as $col) {
                    $event->sheet->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }
}
