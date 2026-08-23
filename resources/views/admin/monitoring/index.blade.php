@extends('layouts.admin')
@section('page-title', 'Monitoring Aset')

@section('content')
<!-- Stats Ringkasan -->
<div class="row g-3 mb-4">
    <div class="col-md-2">
        <div class="stat-card-admin blue">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:var(--primary);"><i class="fas fa-list-check"></i></div>
                <div>
                    <div class="stat-value">{{ $monitorings->total() }}</div>
                    <div class="stat-label">Total Aktivitas</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="stat-card-admin" style="border-left:4px solid #F39C12;">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#F39C12;"><i class="fas fa-spinner"></i></div>
                <div>
                    <div class="stat-value text-warning">{{ \App\Models\MonitoringAset::where('status','berjalan')->count() }}</div>
                    <div class="stat-label">Berjalan</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="stat-card-admin" style="border-left:4px solid #27AE60;">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#27AE60;"><i class="fas fa-check-circle"></i></div>
                <div>
                    <div class="stat-value text-success">{{ \App\Models\MonitoringAset::where('status','selesai')->count() }}</div>
                    <div class="stat-label">Selesai</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="stat-card-admin" style="border-left:4px solid #8E44AD;">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#8E44AD;"><i class="fas fa-clock"></i></div>
                <div>
                    <div class="stat-value" style="color:#8E44AD;">{{ \App\Models\MonitoringAset::where('status','pending')->count() }}</div>
                    <div class="stat-label">Pending</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="stat-card-admin" style="border-left:4px solid #E74C3C;">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#E74C3C;"><i class="fas fa-times-circle"></i></div>
                <div>
                    <div class="stat-value text-danger">{{ \App\Models\MonitoringAset::where('status','gagal')->count() }}</div>
                    <div class="stat-label">Gagal</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="stat-card-admin" style="border-left:4px solid #5FA8D3;">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#5FA8D3;"><i class="fas fa-percentage"></i></div>
                <div>
                    <div class="stat-value" style="color:#5FA8D3;">{{ number_format(\App\Models\MonitoringAset::avg('progress') ?? 0, 0) }}%</div>
                    <div class="stat-label">Rata-rata Progress</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter & Tabel -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <span><i class="fas fa-chart-line me-2"></i>Monitoring Aktivitas Aset</span>
        <form method="GET" action="{{ route('admin.monitoring.index') }}" class="d-flex gap-2 flex-wrap">
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari aset..." value="{{ request('search') }}" style="width:160px;">
            <select name="jenis" class="form-select form-select-sm" style="width:150px;">
                <option value="">Semua Jenis</option>
                <option value="validasi" {{ request('jenis') === 'validasi' ? 'selected' : '' }}>Validasi</option>
                <option value="sertifikasi" {{ request('jenis') === 'sertifikasi' ? 'selected' : '' }}>Sertifikasi</option>
                <option value="digitalisasi" {{ request('jenis') === 'digitalisasi' ? 'selected' : '' }}>Digitalisasi</option>
                <option value="dokumen" {{ request('jenis') === 'dokumen' ? 'selected' : '' }}>Dokumen</option>
                <option value="fisik" {{ request('jenis') === 'fisik' ? 'selected' : '' }}>Fisik</option>
            </select>
            <select name="status" class="form-select form-select-sm" style="width:140px;">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="berjalan" {{ request('status') === 'berjalan' ? 'selected' : '' }}>Berjalan</option>
                <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                <option value="gagal" {{ request('status') === 'gagal' ? 'selected' : '' }}>Gagal</option>
            </select>
            <button class="btn btn-sm btn-outline-primary"><i class="fas fa-filter"></i></button>
            <a href="{{ route('admin.monitoring.index') }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-undo"></i></a>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-align-middle mb-0">
                <thead style="background:#f8f9fa;">
                    <tr>
                        <th style="width:40px;">#</th>
                        <th>Aset</th>
                        <th style="width:120px;">Jenis</th>
                        <th style="width:110px;">Status</th>
                        <th style="width:160px;">Progress</th>
                        <th>Catatan</th>
                        <th style="width:100px;">Petugas</th>
                        <th style="width:90px;">Mulai</th>
                        <th style="width:90px;">Selesai</th>
                        <th style="width:80px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $jenisBadge = [
                            'validasi' => 'primary',
                            'sertifikasi' => 'success',
                            'digitalisasi' => 'info',
                            'dokumen' => 'warning',
                            'fisik' => 'dark',
                        ];
                        $jenisIcon = [
                            'validasi' => 'fa-check-double',
                            'sertifikasi' => 'fa-certificate',
                            'digitalisasi' => 'fa-laptop',
                            'dokumen' => 'fa-file-alt',
                            'fisik' => 'fa-shield-halved',
                        ];
                        $statusBadge = [
                            'pending' => 'secondary',
                            'berjalan' => 'warning',
                            'selesai' => 'success',
                            'gagal' => 'danger',
                        ];
                    @endphp
                    @forelse($monitorings as $m)
                    <tr>
                        <td class="text-muted">{{ $monitorings->firstItem() + $loop->iteration - 1 }}</td>
                        <td>
                            <a href="{{ route('admin.aset.show', $m->aset) }}" class="text-decoration-none fw-500" style="font-size:.85rem;">
                                {{ $m->aset?->nama_aset ?? '-' }}
                            </a>
                            <br><small class="text-muted">{{ $m->aset?->nomor_register ?? '' }}</small>
                        </td>
                        <td>
                            <span class="badge bg-{{ $jenisBadge[$m->jenis] ?? 'secondary' }} text-capitalize">
                                <i class="fas {{ $jenisIcon[$m->jenis] ?? 'fa-circle' }} me-1"></i>{{ ucfirst($m->jenis) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $statusBadge[$m->status] ?? 'secondary' }} text-capitalize">
                                {{ ucfirst($m->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress progress-jb flex-grow-1" style="height:8px;">
                                    @php
                                        $progressColor = $m->progress >= 80 ? 'success' : ($m->progress >= 40 ? 'warning' : 'danger');
                                    @endphp
                                    <div class="progress-bar bg-{{ $progressColor }}" style="width:{{ $m->progress }}%;"></div>
                                </div>
                                <small class="fw-600" style="font-size:.8rem;min-width:35px;text-align:right;">{{ number_format($m->progress, 0) }}%</small>
                            </div>
                        </td>
                        <td style="font-size:.82rem;max-width:200px;" title="{{ $m->catatan ?? '' }}">
                            {{ \Illuminate\Support\Str::limit($m->catatan ?? '-', 60) }}
                        </td>
                        <td style="font-size:.8rem;">{{ $m->user?->name ?? '-' }}</td>
                        <td style="font-size:.8rem;">{{ $m->tanggal_mulai?->format('d/m/Y') ?? '-' }}</td>
                        <td style="font-size:.8rem;">{{ $m->tanggal_selesai?->format('d/m/Y') ?? '-' }}</td>
                        <td>
                            @if($m->status !== 'selesai' && $m->status !== 'gagal')
                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalUpdate{{ $m->id }}" title="Update">
                                <i class="fas fa-edit"></i>
                            </button>
                            @else
                            <span class="text-muted" style="font-size:.8rem;">-</span>
                            @endif
                        </td>
                    </tr>

                    <!-- Modal Update Monitoring -->
                    <div class="modal fade" id="modalUpdate{{ $m->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form method="POST" action="{{ route('admin.monitoring.update', $m) }}">
                                    @csrf @method('POST')
                                    <div class="modal-header">
                                        <h6 class="modal-title"><i class="fas fa-edit me-1"></i>Update Monitoring</h6>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-2">
                                            <label class="form-label" style="font-size:.8rem;">Aset</label>
                                            <input type="text" class="form-control form-control-sm" value="{{ $m->aset?->nama_aset }}" disabled>
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label" style="font-size:.8rem;">Jenis</label>
                                            <input type="text" class="form-control form-control-sm" value="{{ ucfirst($m->jenis) }}" disabled>
                                        </div>
                                        <div class="row g-2 mb-2">
                                            <div class="col-6">
                                                <label class="form-label" style="font-size:.8rem;">Status</label>
                                                <select name="status" class="form-select form-select-sm" required>
                                                    <option value="pending" {{ $m->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                                    <option value="berjalan" {{ $m->status === 'berjalan' ? 'selected' : '' }}>Berjalan</option>
                                                    <option value="selesai" {{ $m->status === 'selesai' ? 'selected' : '' }}>Selesai</option>
                                                    <option value="gagal" {{ $m->status === 'gagal' ? 'selected' : '' }}>Gagal</option>
                                                </select>
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label" style="font-size:.8rem;">Progress (%)</label>
                                                <input type="number" name="progress" class="form-control form-control-sm" min="0" max="100" value="{{ number_format($m->progress, 0) }}" required>
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label" style="font-size:.8rem;">Catatan</label>
                                            <textarea name="catatan" class="form-control form-control-sm" rows="3">{{ $m->catatan ?? '' }}</textarea>
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label" style="font-size:.8rem;">Tanggal Selesai</label>
                                            <input type="date" name="tanggal_selesai" class="form-control form-control-sm" value="{{ $m->tanggal_selesai?->format('Y-m-d') ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-save me-1"></i>Update</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-5">
                            <i class="fas fa-clipboard-list" style="font-size:3rem;opacity:.15;"></i>
                            <p class="text-muted mt-2 mb-0">Belum ada data monitoring.</p>
                            <small class="text-muted">Tambahkan monitoring melalui halaman detail aset.</small>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($monitorings->hasPages())
    <div class="card-footer d-flex justify-content-between align-items-center">
        <small class="text-muted">Menampilkan {{ $monitorings->firstItem() }}-{{ $monitorings->lastItem() }} dari {{ $monitorings->total() }}</small>
        {{ $monitorings->links() }}
    </div>
    @endif
</div>

<!-- Timeline Monitoring Terbaru -->
<div class="card mt-4">
    <div class="card-header"><i class="fas fa-stream me-2"></i>Timeline Monitoring Terbaru</div>
    <div class="card-body">
        @php $recentMonitors = \App\Models\MonitoringAset::with(['aset','user'])->latest()->take(5)->get(); @endphp
        @if($recentMonitors->count() > 0)
        <div class="list-group">
            @foreach($recentMonitors as $rm)
            <div class="list-group-item list-group-item-action">
                <div class="d-flex align-items-start gap-3">
                    <div class="d-flex flex-column align-items-center">
                        <div style="width:38px;height:38px;border-radius:50%;background:{{ $rm->status === 'selesai' ? '#27AE60' : ($rm->status === 'berjalan' ? '#F39C12' : '#8E44AD') }};color:#fff;display:flex;align-items:center;justify-content:center;font-size:.75rem;">
                            <i class="fas {{ $jenisIcon[$rm->jenis] ?? 'fa-circle' }}"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-500" style="font-size:.85rem;">
                            <span class="badge bg-{{ $jenisBadge[$rm->jenis] ?? 'secondary' }} me-1">{{ ucfirst($rm->jenis) }}</span>
                            {{ $rm->aset?->nama_aset ?? '-' }}
                        </div>
                        <div style="font-size:.8rem;color:#666;">{{ \Illuminate\Support\Str::limit($rm->catatan ?? '-', 80) }}</div>
                        <small class="text-muted">Oleh {{ $rm->user?->name ?? '-' }} — {{ $rm->updated_at->diffForHumans() }}</small>
                    </div>
                    <span class="badge bg-{{ $statusBadge[$rm->status] ?? 'secondary' }} text-capitalize">{{ ucfirst($rm->status) }}</span>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <p class="text-center text-muted py-3">Belum ada aktivitas monitoring.</p>
        @endif
    </div>
</div>
@endsection
