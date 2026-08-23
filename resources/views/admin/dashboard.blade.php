@extends('layouts.admin')
@section('page-title', 'Dashboard')

@section('content')
<!-- KPI Cards -->
<div class="row g-3 mb-4">
    <div class="col-lg-3 col-md-6">
        <div class="stat-card-admin blue">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-label">Total Nilai Aset</div>
                    <div class="stat-value" style="color:var(--primary);">{{ 'Rp ' . number_format($totalNilai, 0, ',', '.') }}</div>
                </div>
                <div class="stat-icon" style="background:var(--primary);"><i class="fas fa-money-bill-wave"></i></div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stat-card-admin green">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-label">Jumlah Bidang</div>
                    <div class="stat-value" style="color:var(--success);">{{ number_format($totalAset) }}</div>
                </div>
                <div class="stat-icon" style="background:var(--success);"><i class="fas fa-map-marked-alt"></i></div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stat-card-admin blue">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-label">Sudah Sertifikat</div>
                    <div class="stat-value" style="color:var(--primary);">{{ number_format($sudahSertifikat) }}</div>
                </div>
                <div class="stat-icon" style="background:var(--info);"><i class="fas fa-certificate"></i></div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stat-card-admin orange">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-label">Belum Sertifikat</div>
                    <div class="stat-value" style="color:var(--warning);">{{ number_format($belumSertifikat) }}</div>
                </div>
                <div class="stat-icon" style="background:var(--warning);"><i class="fas fa-clock"></i></div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-lg-3 col-md-6">
        <div class="stat-card-admin red">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-label">Sengketa</div>
                    <div class="stat-value" style="color:var(--danger);">{{ number_format($sengketa) }}</div>
                </div>
                <div class="stat-icon" style="background:var(--danger);"><i class="fas fa-gavel"></i></div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stat-card-admin blue">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-label">Proses BPN</div>
                    <div class="stat-value" style="color:var(--info);">{{ number_format($prosesBPN) }}</div>
                </div>
                <div class="stat-icon" style="background:var(--info);"><i class="fas fa-file-signature"></i></div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row g-3 mb-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-chart-bar me-2"></i>Riwayat Penambahan Aset</span>
            </div>
            <div class="card-body">
                <canvas id="chartAset" height="250"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <span><i class="fas fa-chart-pie me-2"></i>Status Sertifikasi</span>
            </div>
            <div class="card-body d-flex align-items-center justify-content-center">
                <canvas id="chartSertifikasi" height="250"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Monitoring & Sebaran -->
<div class="row g-3 mb-4">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><span><i class="fas fa-tasks me-2"></i>Progress Monitoring</span></div>
            <div class="card-body">
                @foreach($monitoringData as $key => $progress)
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1" style="font-size:.8rem;">
                        <span class="fw-500">{{ ucfirst(str_replace('_', ' ', $key)) }}</span>
                        <span>{{ number_format($progress, 1) }}%</span>
                    </div>
                    <div class="progress progress-jb">
                        <div class="progress-bar bg-{{ $progress > 70 ? 'success' : ($progress > 40 ? 'info' : 'warning') }}" style="width:{{ $progress }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><span><i class="fas fa-chart-bar me-2"></i>Sebaran Aset per Kecamatan</span></div>
            <div class="card-body">
                <canvas id="chartKecamatan" height="250"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Notifikasi -->
@if($notifikasis->count() > 0)
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-bell me-2"></i>Notifikasi Terbaru</span>
        <a href="{{ route('admin.notifikasi.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                @foreach($notifikasis as $n)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-{{ $n->tipe === 'sertifikat' ? 'primary' : ($n->tipe === 'sengketa' ? 'danger' : 'info') }}" style="font-size:.65rem;">{{ $n->tipe }}</span>
                            <span style="font-size:.85rem;">{{ $n->judul }}</span>
                        </div>
                        <small style="color:#888;">{{ $n->created_at->diffForHumans() }}</small>
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // Chart: Penambahan Aset per Tahun Perolehan
    new Chart(document.getElementById('chartAset'), {
        type: 'bar',
        data: {
            labels: {{ Illuminate\Support\Js::from($riwayatAsetLabels) }},
            datasets: [{
                label: 'Jumlah Bidang Aset',
                data: {{ Illuminate\Support\Js::from($riwayatAsetData) }},
                backgroundColor: function(ctx) {
                    var chart = ctx.chart;
                    var { ctx: c, chartArea } = chart;
                    if (!chartArea) return 'rgba(27,73,101,0.7)';
                    var gradient = c.createLinearGradient(0, chartArea.bottom, 0, chartArea.top);
                    gradient.addColorStop(0, 'rgba(27,73,101,0.5)');
                    gradient.addColorStop(1, 'rgba(95,168,211,0.8)');
                    return gradient;
                },
                borderRadius: 8,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                title: { display: true, text: 'Berdasarkan Tahun Perolehan', font: { size: 12, weight: '400' }, color: '#888', align: 'start' }
            },
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
        }
    });

    // Chart: Sertifikasi
    new Chart(document.getElementById('chartSertifikasi'), {
        type: 'doughnut',
        data: {
            labels: {{ Illuminate\Support\Js::from(array_keys($sertifikasiStatus)) }},
            datasets: [{
                data: {{ Illuminate\Support\Js::from(array_values($sertifikasiStatus)) }},
                backgroundColor: ['#bdc3c7','#95a5a6','#3498db','#f39c12','#e67e22','#9b59b6','#27ae60','#1abc9c'],
                borderWidth: 2,
                borderColor: '#fff',
            }]
        },
        options: {
            responsive: true,
            cutout: '55%',
            plugins: {
                legend: { position: 'bottom', labels: { boxWidth: 12, padding: 10, font: { size: 11 } } },
                title: { display: true, text: 'Total: {{ $totalAset }} Bidang', font: { size: 12, weight: '400' }, color: '#888' }
            }
        }
    });

    // Chart: Kecamatan
    new Chart(document.getElementById('chartKecamatan'), {
        type: 'bar',
        data: {
            labels: [{!! $sebaranKecamatan->pluck('nama')->toJson() !!}],
            datasets: [{
                label: 'Jumlah Bidang',
                data: [{!! $sebaranKecamatan->pluck('asets_count')->toJson() !!}],
                backgroundColor: 'rgba(95,168,211,0.7)',
                borderRadius: 8,
            }]
        },
        options: {
            responsive: true,
            indexAxis: 'y',
            plugins: { legend: { display: false } },
            scales: { x: { beginAtZero: true } }
        }
    });
</script>
@endpush
