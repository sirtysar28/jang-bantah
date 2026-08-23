@extends('layouts.admin')
@section('page-title', 'Laporan')

@section('content')
<!-- Filter -->
<div class="card">
    <div class="card-header"><span><i class="fas fa-filter me-2"></i>Filter Laporan</span></div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.laporan.index') }}" id="filterForm">
            <div class="row g-3">
                <div class="col-md-2">
                    <label class="form-label">Kecamatan</label>
                    <select name="kecamatan_id" class="form-select form-select-sm">
                        <option value="">Semua</option>
                        @foreach($kecamatans as $k)
                        <option value="{{ $k->id }}" {{ request('kecamatan_id') == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">OPD</label>
                    <select name="opd_id" class="form-select form-select-sm">
                        <option value="">Semua</option>
                        @foreach($opds as $o)
                        <option value="{{ $o->id }}" {{ request('opd_id') == $o->id ? 'selected' : '' }}>{{ $o->singkatan ?? $o->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">Semua</option>
                        <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="tidak_aktif" {{ request('status') === 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                        <option value="sengketa" {{ request('status') === 'sengketa' ? 'selected' : '' }}>Sengketa</option>
                        <option value="dipinjamkan" {{ request('status') === 'dipinjamkan' ? 'selected' : '' }}>Dipinjamkan</option>
                        <option value="disediakan" {{ request('status') === 'disediakan' ? 'selected' : '' }}>Disediakan</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Sertifikasi</label>
                    <select name="sertifikasi" class="form-select form-select-sm">
                        <option value="">Semua</option>
                        <option value="ada" {{ request('sertifikasi') === 'ada' ? 'selected' : '' }}>Sudah Sertifikat</option>
                        <option value="belum" {{ request('sertifikasi') === 'belum' ? 'selected' : '' }}>Belum Sertifikat</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Tahun</label>
                    <select name="tahun" class="form-select form-select-sm">
                        <option value="">Semua</option>
                        @for($y = (int) date('Y'); $y >= 1990; $y--)
                        <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-search me-1"></i>Tampilkan</button>
                    <a href="{{ route('admin.laporan.index') }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-redo"></i></a>
                </div>
            </div>
        </form>
        <div class="mt-2 d-flex gap-2">
            <a href="{{ route('admin.laporan.pdf', request()->query()) }}" class="btn btn-danger btn-sm"><i class="fas fa-file-pdf me-1"></i>Export PDF</a>
            <a href="{{ route('admin.laporan.excel', request()->query()) }}" class="btn btn-success btn-sm"><i class="fas fa-file-excel me-1"></i>Export Excel</a>
        </div>
    </div>
</div>

<!-- Ringkasan Keseluruhan -->
<div class="card mt-3">
    <div class="card-header"><span><i class="fas fa-chart-bar me-2"></i>Ringkasan Data Keseluruhan</span></div>
    <div class="card-body">
        <div class="row g-2 text-center">
            <div class="col-lg-2 col-md-3 col-6">
                <div class="p-2 rounded" style="background:#eaf2f8;">
                    <div class="fs-5 fw-700" style="color:var(--primary);">{{ number_format($ringkasan['total']) }}</div>
                    <small style="color:#555;">Total Bidang</small>
                </div>
            </div>
            <div class="col-lg-2 col-md-3 col-6">
                <div class="p-2 rounded" style="background:#eafaf1;">
                    <div class="fs-5 fw-700" style="color:#27ae60;">{{ number_format($ringkasan['sertifikat']) }}</div>
                    <small style="color:#555;">Bersertifikat</small>
                </div>
            </div>
            <div class="col-lg-2 col-md-3 col-6">
                <div class="p-2 rounded" style="background:#fef9e7;">
                    <div class="fs-5 fw-700" style="color:#f39c12;">{{ number_format($ringkasan['proses_sertifikasi']) }}</div>
                    <small style="color:#555;">Proses Sertifikasi</small>
                </div>
            </div>
            <div class="col-lg-2 col-md-3 col-6">
                <div class="p-2 rounded" style="background:#ebf5fb;">
                    <div class="fs-5 fw-700" style="color:#3498db;">{{ number_format($ringkasan['belum_sertifikat']) }}</div>
                    <small style="color:#555;">Belum Sertifikasi</small>
                </div>
            </div>
            <div class="col-lg-2 col-md-3 col-6">
                <div class="p-2 rounded" style="background:#fdedec;">
                    <div class="fs-5 fw-700" style="color:#e74c3c;">{{ number_format($ringkasan['sengketa']) }}</div>
                    <small style="color:#555;">Sengketa</small>
                </div>
            </div>
            <div class="col-lg-2 col-md-3 col-6">
                <div class="p-2 rounded" style="background:#eaf2f8;">
                    <div class="fs-5 fw-700" style="color:var(--primary);">{{ 'Rp ' . number_format($ringkasan['nilai'], 0, ',', '.') }}</div>
                    <small style="color:#555;">Total Nilai</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Ringkasan Hasil Filter -->
<div class="card mt-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-table me-2"></i>Data Berdasarkan Filter ({{ number_format($filteredSummary['total']) }} Bidang)</span>
        <span class="badge bg-primary">Total Nilai: Rp {{ number_format($filteredSummary['nilai'], 0, ',', '.') }} | Total Luas: {{ number_format($filteredSummary['luas'], 2) }} m²</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nomor Register</th>
                        <th>Nama Aset</th>
                        <th>Kecamatan</th>
                        <th>Desa</th>
                        <th>OPD</th>
                        <th>Luas (m²)</th>
                        <th>Nilai (Rp)</th>
                        <th>Status</th>
                        <th>Sertifikasi</th>
                        <th>Hukum</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($asets as $i => $a)
                    @php
                        $isSertifikat = $a->sertifikasi && in_array($a->sertifikasi->status, ['terbit_sertifikat', 'selesai']);
                        $isSengketa = $a->pengamananHukum && $a->pengamananHukum->status_sengketa !== 'tidak_sengketa';
                    @endphp
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td><code>{{ $a->nomor_register }}</code></td>
                        <td>{{ $a->nama_aset }}</td>
                        <td>{{ $a->kecamatan?->nama ?? '-' }}</td>
                        <td>{{ $a->desa?->nama ?? '-' }}</td>
                        <td>{{ $a->opd?->singkatan ?? $a->opd?->nama ?? '-' }}</td>
                        <td class="text-end">{{ number_format($a->luas, 2) }}</td>
                        <td class="text-end">{{ number_format($a->nilai_aset, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge bg-{{ $a->status === 'aktif' ? 'success' : ($a->status === 'sengketa' ? 'danger' : 'secondary') }}">
                                {{ strtoupper(str_replace('_', ' ', $a->status)) }}
                            </span>
                        </td>
                        <td>
                            @if($a->sertifikasi)
                                @if($isSertifikat)
                                    <span class="badge bg-success"><i class="fas fa-certificate me-1"></i>{{ $a->sertifikasi->status_label }}</span>
                                @else
                                    <span class="badge bg-warning"><i class="fas fa-clock me-1"></i>{{ $a->sertifikasi->status_label }}</span>
                                @endif
                            @else
                                <span class="badge bg-info">Belum Ada</span>
                            @endif
                        </td>
                        <td>
                            @if($isSengketa)
                                <span class="badge bg-danger"><i class="fas fa-exclamation-triangle me-1"></i>{{ strtoupper(str_replace('_', ' ', $a->pengamananHukum->status_sengketa)) }}</span>
                            @else
                                <span class="badge bg-secondary">Aman</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="text-center py-4 text-muted">
                            <i class="fas fa-inbox fa-2x mb-2 d-block"></i>Tidak ada data sesuai filter
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
