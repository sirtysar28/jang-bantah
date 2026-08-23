@extends('layouts.admin')
@section('page-title', 'Pengamanan Administrasi')

@section('content')
<!-- Statistik -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card-admin blue">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:var(--primary);"><i class="fas fa-file-shield"></i></div>
                <div>
                    <div class="stat-value">{{ $totalAset }}</div>
                    <div class="stat-label">Total Aset</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card-admin" style="border-left:4px solid #27AE60;">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#27AE60;"><i class="fas fa-check-circle"></i></div>
                <div>
                    <div class="stat-value text-success">{{ $sudahDiisi }}</div>
                    <div class="stat-label">Sudah Diisi</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card-admin" style="border-left:4px solid #F39C12;">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#F39C12;"><i class="fas fa-exclamation-circle"></i></div>
                <div>
                    <div class="stat-value text-warning">{{ $belumDiisi }}</div>
                    <div class="stat-label">Belum Diisi</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card-admin" style="border-left:4px solid #5FA8D3;">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#5FA8D3;"><i class="fas fa-folder-check"></i></div>
                <div>
                    <div class="stat-value" style="color:#5FA8D3;">{{ $lengkap }}</div>
                    <div class="stat-label">Dokumen Lengkap</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter & Tabel -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <span><i class="fas fa-file-shield me-2"></i>Pengamanan Administrasi</span>
        <form method="GET" action="{{ route('admin.pengamanan-admin.index') }}" class="d-flex gap-2">
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari aset..." value="{{ request('search') }}" style="width:180px;">
            <select name="filter" class="form-select form-select-sm" style="width:160px;">
                <option value="">Semua Status</option>
                <option value="lengkap" {{ request('filter') === 'lengkap' ? 'selected' : '' }}>Dokumen Lengkap</option>
                <option value="kurang" {{ request('filter') === 'kurang' ? 'selected' : '' }}>Dokumen Kurang</option>
                <option value="belum" {{ request('filter') === 'belum' ? 'selected' : '' }}>Belum Diisi</option>
            </select>
            <button class="btn btn-sm btn-outline-primary"><i class="fas fa-filter"></i></button>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-align-middle mb-0">
                <thead style="background:#f8f9fa;">
                    <tr>
                        <th style="width:40px;">#</th>
                        <th>Aset</th>
                        <th>Kecamatan</th>
                        <th>OPD</th>
                        <th>Sertifikat</th>
                        <th>AJB</th>
                        <th>SK</th>
                        <th>IMB/PBG</th>
                        <th>Hibah</th>
                        <th>Berita Acara</th>
                        <th>Pendukung</th>
                        <th>Petugas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($asets as $aset)
                    @php $pa = $aset->pengamananAdministrasi; @endphp
                    <tr>
                        <td class="text-muted">{{ $asets->firstItem() + $loop->iteration - 1 }}</td>
                        <td>
                            <a href="{{ route('admin.aset.show', $aset) }}" class="text-decoration-none fw-500" style="font-size:.85rem;">
                                {{ $aset->nama_aset }}
                            </a>
                            <br><small class="text-muted">{{ $aset->nomor_register }}</small>
                        </td>
                        <td style="font-size:.85rem;">{{ $aset->kecamatan?->nama ?? '-' }}</td>
                        <td style="font-size:.85rem;">{{ $aset->opd?->singkatan ?? '-' }}</td>
                        @php
                            $items = ['sertifikat', 'ajb', 'sk', 'imb_pbg', 'hibah', 'berita_acara'];
                        @endphp
                        @foreach($items as $item)
                        <td>
                            @if($pa)
                                @if($pa->$item === 'ada')
                                    <span class="badge bg-success"><i class="fas fa-check"></i></span>
                                @else
                                    <span class="badge bg-danger"><i class="fas fa-times"></i></span>
                                @endif
                            @else
                                <span class="badge bg-secondary">-</span>
                            @endif
                        </td>
                        @endforeach
                        <td>
                            @if($pa)
                                @if($pa->dokumen_pendukung === 'lengkap')
                                    <span class="badge bg-success">Lengkap</span>
                                @elseif($pa->dokumen_pendukung === 'kurang')
                                    <span class="badge bg-warning">Kurang</span>
                                @else
                                    <span class="badge bg-danger">Belum</span>
                                @endif
                            @else
                                <span class="badge bg-secondary">-</span>
                            @endif
                        </td>
                        <td style="font-size:.8rem;">{{ $pa?->checker?->name ?? '-' }}</td>
                        <td>
                            <a href="{{ route('admin.aset.show', $aset) }}#pengamanan-admin" class="btn btn-sm btn-outline-primary" title="Kelola">
                                <i class="fas fa-cog"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="13" class="text-center py-4 text-muted">Belum ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($asets->hasPages())
    <div class="card-footer d-flex justify-content-between align-items-center">
        <small class="text-muted">{{ $asets->firstItem() }}-{{ $asets->lastItem() }} dari {{ $asets->total() }}</small>
        {{ $asets->appends(request()->query())->links() }}
    </div>
    @endif
</div>
@endsection
