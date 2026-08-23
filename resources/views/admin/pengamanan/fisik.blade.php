@extends('layouts.admin')
@section('page-title', 'Pengamanan Fisik')

@section('content')
<!-- Statistik -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card-admin blue">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:var(--primary);"><i class="fas fa-shield-halved"></i></div>
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
                    <div class="stat-label">Sudah Dicek</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card-admin" style="border-left:4px solid #F39C12;">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#27AE60;"><i class="fas fa-thumbs-up"></i></div>
                <div>
                    <div class="stat-value text-success">{{ $kondisiBaik }}</div>
                    <div class="stat-label">Kondisi Baik</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card-admin" style="border-left:4px solid #E74C3C;">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#E74C3C;"><i class="fas fa-exclamation-triangle"></i></div>
                <div>
                    <div class="stat-value text-danger">{{ $kondisiRusak }}</div>
                    <div class="stat-label">Kondisi Rusak</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter & Tabel -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <span><i class="fas fa-shield-halved me-2"></i>Pengamanan Fisik</span>
        <form method="GET" action="{{ route('admin.pengamanan-fisik.index') }}" class="d-flex gap-2">
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari aset..." value="{{ request('search') }}" style="width:180px;">
            <select name="filter" class="form-select form-select-sm" style="width:160px;">
                <option value="">Semua Kondisi</option>
                <option value="baik" {{ request('filter') === 'baik' ? 'selected' : '' }}>Kondisi Baik</option>
                <option value="rusak" {{ request('filter') === 'rusak' ? 'selected' : '' }}>Kondisi Rusak</option>
                <option value="tidak_ditemukan" {{ request('filter') === 'tidak_ditemukan' ? 'selected' : '' }}>Tidak Ditemukan</option>
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
                        <th>Patok</th>
                        <th>Pagar</th>
                        <th>Plang Pemda</th>
                        <th>Kondisi</th>
                        <th>Foto</th>
                        <th>Tgl Monitoring</th>
                        <th>Petugas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($asets as $aset)
                    @php $pf = $aset->pengamananFisik; @endphp
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
                        @php $items = ['patok', 'pagar', 'plang_pemda']; @endphp
                        @foreach($items as $item)
                        <td>
                            @if($pf)
                                @if($pf->$item === 'ada')
                                    <span class="badge bg-success"><i class="fas fa-check"></i> Ada</span>
                                @elseif($pf->$item === 'rusak')
                                    <span class="badge bg-danger"><i class="fas fa-tools"></i> Rusak</span>
                                @else
                                    <span class="badge bg-secondary"><i class="fas fa-times"></i> -</span>
                                @endif
                            @else
                                <span class="badge bg-light text-muted">Belum</span>
                            @endif
                        </td>
                        @endforeach
                        <td>
                            @if($pf)
                                @if($pf->kondisi === 'baik')
                                    <span class="badge bg-success">Baik</span>
                                @elseif($pf->kondisi === 'rusak_ringan')
                                    <span class="badge bg-warning">Rusak Ringan</span>
                                @elseif($pf->kondisi === 'rusak_berat')
                                    <span class="badge bg-danger">Rusak Berat</span>
                                @else
                                    <span class="badge bg-dark">Tidak Ditemukan</span>
                                @endif
                            @else
                                <span class="badge bg-light text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($pf && ($pf->foto_sebelum || $pf->foto_sesudah))
                                <span class="badge bg-info"><i class="fas fa-image"></i></span>
                            @else
                                <span class="text-muted" style="font-size:.8rem;">-</span>
                            @endif
                        </td>
                        <td style="font-size:.8rem;">{{ $pf?->tanggal_monitoring?->format('d/m/Y') ?? '-' }}</td>
                        <td style="font-size:.8rem;">{{ $pf?->checker?->name ?? '-' }}</td>
                        <td>
                            <a href="{{ route('admin.aset.show', $aset) }}#pengamanan-fisik" class="btn btn-sm btn-outline-primary" title="Kelola">
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
