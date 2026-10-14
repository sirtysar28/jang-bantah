@extends('layouts.admin')
@section('page-title', 'Sertifikasi')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <span><i class="fas fa-certificate me-2"></i>Data Sertifikasi</span>
        <form method="GET" action="{{ route('admin.sertifikasi.index') }}" class="d-flex gap-2">
            <select name="status" class="form-select form-select-sm" style="width:200px;" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                @php
                    $statusOptions = \App\Models\Sertifikasi::statusOptions();
                @endphp
                @foreach($statusOptions as $val => $lbl)
                <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                @endforeach
            </select>
            <a href="{{ route('admin.sertifikasi.index') }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-undo"></i></a>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-align-middle mb-0">
                <thead style="background:#f8f9fa;">
                    <tr>
                        <th style="width:50px;">#</th>
                        <th>Aset</th>
                        <th>Kecamatan</th>
                        <th>OPD</th>
                        <th>Status</th>
                        <th>Progress</th>
                        <th>No. Sertifikat</th>
                        <th>Tgl Terbit</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sertifikasis as $s)
                    <tr>
                        <td class="text-muted">{{ $sertifikasis->firstItem() + $loop->iteration - 1 }}</td>
                        <td>
                            <span class="fw-500" style="font-size:.85rem;">{{ $s->aset?->nama_aset ?? '-' }}</span>
                            @if($s->aset?->nomor_register)
                            <br><small class="text-muted">{{ $s->aset->nomor_register }}</small>
                            @endif
                        </td>
                        <td style="font-size:.85rem;">{{ $s->aset?->kecamatan?->nama ?? '-' }}</td>
                        <td style="font-size:.85rem;">{{ $s->aset?->opd?->singkatan ?? $s->aset?->opd?->nama ?? '-' }}</td>
                        <td><span class="badge badge-sertifikasi bg-{{ $s->progress_color }}">{{ $s->status_label }}</span></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress progress-jb" style="width:100px;height:8px;">
                                    <div class="progress-bar bg-{{ $s->progress_color }}" style="width:{{ $s->progress }}%"></div>
                                </div>
                                <small class="fw-600" style="font-size:.78rem;">{{ number_format($s->progress, 0) }}%</small>
                            </div>
                        </td>
                        <td style="font-size:.82rem;">{{ $s->nomor_sertifikat ?? '-' }}</td>
                        <td style="font-size:.82rem;">{{ $s->tanggal_terbit?->format('d/m/Y') ?? '-' }}</td>
                        <td>
                            @if($s->aset)
                                <a href="{{ route('admin.aset.show', $s->aset) }}" class="btn btn-sm btn-outline-primary" title="Detail Aset"><i class="fas fa-eye"></i></a>
                            @else
                                <button class="btn btn-sm btn-outline-secondary" disabled title="Aset tidak ditemukan"><i class="fas fa-eye-slash"></i></button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="text-center py-4 text-muted">
                        <i class="fas fa-certificate d-block mb-2" style="font-size:2rem;opacity:.2;"></i>
                        Belum ada data sertifikasi.
                    </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($sertifikasis->hasPages())
    <div class="card-footer d-flex justify-content-between align-items-center flex-wrap gap-2">
        <small class="text-muted">Menampilkan {{ $sertifikasis->firstItem() ?? 0 }}-{{ $sertifikasis->lastItem() ?? 0 }} dari {{ $sertifikasis->total() }}</small>
        {{ $sertifikasis->appends(request()->query())->links() }}
    </div>
    @endif
</div>
@endsection
