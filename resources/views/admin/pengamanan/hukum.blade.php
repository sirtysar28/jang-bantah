@extends('layouts.admin')
@section('page-title', 'Pengamanan Hukum')

@section('content')
<!-- Statistik -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card-admin blue">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:var(--primary);"><i class="fas fa-gavel"></i></div>
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
                <div class="stat-icon" style="background:#27AE60;"><i class="fas fa-shield-alt"></i></div>
                <div>
                    <div class="stat-value text-success">{{ $aman }}</div>
                    <div class="stat-label">Aman (Tanpa Sengketa)</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card-admin" style="border-left:4px solid #E74C3C;">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#E74C3C;"><i class="fas fa-exclamation-triangle"></i></div>
                <div>
                    <div class="stat-value text-danger">{{ $bermasalah }}</div>
                    <div class="stat-label">Bermasalah (Sengketa)</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card-admin" style="border-left:4px solid #F39C12;">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#F39C12;"><i class="fas fa-clock"></i></div>
                <div>
                    <div class="stat-value text-warning">{{ $belumDiisi }}</div>
                    <div class="stat-label">Belum Dicek</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter & Tabel -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <span><i class="fas fa-gavel me-2"></i>Pengamanan Hukum</span>
        <form method="GET" action="{{ route('admin.pengamanan-hukum.index') }}" class="d-flex gap-2">
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari aset..." value="{{ request('search') }}" style="width:180px;">
            <select name="filter" class="form-select form-select-sm" style="width:160px;">
                <option value="">Semua Status</option>
                <option value="aman" {{ request('filter') === 'aman' ? 'selected' : '' }}>Aman (Tidak Sengketa)</option>
                <option value="sengketa" {{ request('filter') === 'sengketa' ? 'selected' : '' }}>Sengketa / Bermasalah</option>
                <option value="belum" {{ request('filter') === 'belum' ? 'selected' : '' }}>Belum Dicek</option>
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
                        <th>Status Sengketa</th>
                        <th>No. Perkara</th>
                        <th>Pihak Lawan</th>
                        <th>Status Pengadilan</th>
                        <th>Pendampingan</th>
                        <th>Tgl Sengketa</th>
                        <th>Petugas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($asets as $aset)
                    @php $ph = $aset->pengamananHukum; @endphp
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
                        <td>
                            @if($ph)
                                @if($ph->status_sengketa === 'tidak_sengketa')
                                    <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Aman</span>
                                @elseif($ph->status_sengketa === 'sengketa')
                                    <span class="badge bg-warning text-dark">Sengketa</span>
                                @elseif($ph->status_sengketa === 'perkara')
                                    <span class="badge bg-danger">Perkara</span>
                                @elseif($ph->status_sengketa === 'gugatan')
                                    <span class="badge bg-dark">Gugatan</span>
                                @endif
                            @else
                                <span class="badge bg-light text-muted">Belum</span>
                            @endif
                        </td>
                        <td style="font-size:.85rem;">{{ $ph?->nomor_perkara ?? '-' }}</td>
                        <td style="font-size:.85rem;">{{ $ph?->pihak_lawan ?? '-' }}</td>
                        <td>
                            @if($ph && $ph->status_pengadilan)
                                @php
                                    $courtBadge = ['belum' => 'secondary', 'berjalan' => 'warning', 'selesai' => 'info', 'menang' => 'success', 'kalah' => 'danger'];
                                @endphp
                                <span class="badge bg-{{ $courtBadge[$ph->status_pengadilan] ?? 'secondary' }} text-capitalize">{{ ucfirst($ph->status_pengadilan) }}</span>
                            @else
                                <span class="text-muted" style="font-size:.8rem;">-</span>
                            @endif
                        </td>
                        <td>
                            @if($ph)
                                @if($ph->pendampingan_kejaksaan === 'selesai')
                                    <span class="badge bg-success">Selesai</span>
                                @elseif($ph->pendampingan_kejaksaan === 'berjalan')
                                    <span class="badge bg-warning text-dark">Berjalan</span>
                                @else
                                    <span class="badge bg-secondary">Belum</span>
                                @endif
                            @else
                                <span class="badge bg-light text-muted">-</span>
                            @endif
                        </td>
                        <td style="font-size:.8rem;">{{ $ph?->tanggal_sengketa?->format('d/m/Y') ?? '-' }}</td>
                        <td style="font-size:.8rem;">{{ $ph?->checker?->name ?? '-' }}</td>
                        <td>
                            <a href="{{ route('admin.aset.show', $aset) }}#pengamanan-hukum" class="btn btn-sm btn-outline-primary" title="Kelola">
                                <i class="fas fa-cog"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="12" class="text-center py-4 text-muted">Belum ada data.</td></tr>
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
