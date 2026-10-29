@extends('layouts.admin')

@section('title', 'Dashboard - JANG BANTAH')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Monitoring Jaringan Bank Tanah — Pemerintah Kabupaten Halmahera Barat')

@section('content')

{{-- ================= KPI CARDS (gradient, gaya digitrans — warna JANG BANTAH) ================= --}}
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6 jb-reveal">
        <div class="kpi-card">
            <div class="kpi-icon"><i class="fas fa-money-bill-wave"></i></div>
            <div class="kpi-body">
                <span>Total Nilai Aset</span>
                <h1 data-count="{{ $totalNilai }}" data-prefix="Rp ">Rp 0</h1>
                <small class="d-block" style="opacity:.75">akumulasi seluruh bidang tanah</small>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 jb-reveal">
        <div class="kpi-card">
            <div class="kpi-icon"><i class="fas fa-map-marked-alt"></i></div>
            <div class="kpi-body">
                <span>Jumlah Bidang</span>
                <h1 data-count="{{ $totalAset }}">0</h1>
                <small class="d-block" style="opacity:.75">aset tanah terdata</small>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 jb-reveal">
        <div class="kpi-card">
            <div class="kpi-icon"><i class="fas fa-certificate"></i></div>
            <div class="kpi-body">
                <span>Sudah Sertifikat</span>
                <h1 data-count="{{ $sudahSertifikat }}">0</h1>
                <small class="d-block" style="opacity:.75">terbit sertifikat / selesai</small>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 jb-reveal">
        <div class="kpi-card">
            <div class="kpi-icon"><i class="fas fa-clock"></i></div>
            <div class="kpi-body">
                <span>Belum Sertifikat</span>
                <h1 data-count="{{ $belumSertifikat }}">0</h1>
                <small class="d-block" style="opacity:.75">proses / belum diajukan</small>
            </div>
        </div>
    </div>
</div>

{{-- ================= STAT CARDS (sengketa & proses BPN) ================= --}}
<div class="row g-3 mb-4">
    <div class="col-xl-6 col-md-6 jb-reveal">
        <a href="{{ route('admin.aset.index', ['status' => 'sengketa']) }}" class="stat-card-link">
            <div class="stat-card stat-clickable">
                <div class="stat-icon danger"><i class="fas fa-gavel"></i></div>
                <div>
                    <span>Sengketa</span>
                    <h2 data-count="{{ $sengketa }}">0</h2>
                    <small>bidang tanah berstatus sengketa</small>
                </div>
            </div>
        </a>
    </div>
    <div class="col-xl-6 col-md-6 jb-reveal">
        <a href="{{ route('admin.sertifikasi.index') }}" class="stat-card-link">
            <div class="stat-card stat-clickable">
                <div class="stat-icon info"><i class="fas fa-file-signature"></i></div>
                <div>
                    <span>Proses BPN</span>
                    <h2 data-count="{{ $prosesBPN }}">0</h2>
                    <small>diajukan BPN / ukur / validasi</small>
                </div>
            </div>
        </a>
    </div>
</div>

{{-- ================= CHARTS ROW 1 ================= --}}
<div class="row g-3 mb-4">
    <div class="col-lg-8 jb-reveal">
        <div class="chart-card h-100">
            <h5><i class="fas fa-chart-column me-2"></i>Riwayat Penambahan Aset</h5>
            <div class="chart-wrap"><canvas id="chartAset"></canvas></div>
            <small class="text-muted d-block mt-2">Berdasarkan tahun perolehan bidang tanah.</small>
        </div>
    </div>
    <div class="col-lg-4 jb-reveal">
        <div class="chart-card h-100">
            <h5><i class="fas fa-chart-pie me-2"></i>Status Sertifikasi</h5>
            <div class="chart-wrap chart-wrap-donut"><canvas id="chartSertifikasi"></canvas></div>
        </div>
    </div>
</div>

{{-- ================= PROGRESS MONITORING + SEBARAN KECAMATAN ================= --}}
<div class="row g-3 mb-4">
    <div class="col-lg-4 jb-reveal">
        <div class="chart-card h-100">
            <h5><i class="fas fa-tasks me-2"></i>Progress Monitoring</h5>
            @foreach($monitoringData as $key => $progress)
            <div class="mb-3">
                <div class="d-flex justify-content-between mb-1" style="font-size:.8rem;">
                    <span class="fw-600 text-capitalize">{{ str_replace('_', ' ', $key) }}</span>
                    <span class="fw-600" style="color:var(--jb-primary);">{{ number_format($progress, 1) }}%</span>
                </div>
                <div class="progress progress-jb">
                    <div class="progress-bar bg-{{ $progress > 70 ? 'success' : ($progress > 40 ? 'info' : 'warning') }}"
                         role="progressbar" aria-valuenow="{{ round($progress) }}" aria-valuemin="0" aria-valuemax="100"
                         data-width="{{ $progress }}"></div>
                </div>
            </div>
            @endforeach
            <small class="text-muted">Rata-rata progres jenis pemantauan aset.</small>
        </div>
    </div>
    <div class="col-lg-8 jb-reveal">
        <div class="chart-card h-100">
            <h5><i class="fas fa-map-location-dot me-2"></i>Sebaran Aset per Kecamatan</h5>
            <div class="chart-wrap" style="height:320px"><canvas id="chartKecamatan"></canvas></div>
        </div>
    </div>
</div>

{{-- ================= NOTIFIKASI TERBARU ================= --}}
@if($notifikasis->count() > 0)
<div class="jb-reveal">
    <div class="table-card">
        <div class="card-header-custom">
            <h5 class="mb-0"><i class="fas fa-bell me-2"></i>Notifikasi Terbaru</h5>
            <a href="{{ route('admin.notifikasi.index') }}" class="btn btn-sm btn-outline-jb">
                Lihat Semua <i class="fas fa-arrow-right ms-1"></i>
            </a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th width="70">#</th>
                        <th>Informasi</th>
                        <th width="170">Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($notifikasis as $n)
                    <tr>
                        <td>
                            <span class="badge bg-{{ $n->tipe === 'sertifikat' ? 'primary' : ($n->tipe === 'sengketa' ? 'danger' : 'info') }}">
                                {{ $n->tipe }}
                            </span>
                        </td>
                        <td class="fw-500">{{ $n->judul }}</td>
                        <td><small class="text-muted">{{ $n->created_at->diffForHumans() }}</small></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    /* Pengaman: bila Chart.js gagal dimuat dari CDN, tampilkan pesan —
       bukan mati senyap (grafik "tidak tampil"). */
    if (typeof Chart === 'undefined') {
        document.querySelectorAll('.chart-wrap').forEach(function (w) {
            w.innerHTML = '<div class="text-muted small text-center py-5">Pustaka grafik (Chart.js) gagal dimuat. Muat ulang halaman.</div>';
        });
    }
</script>
<script>
    if (typeof Chart !== 'undefined') {
        /* ================== PENGATURAN DASAR (gaya digitrans) ==================
           - font mengikuti tema
           - maintainAspectRatio: false -> tinggi dikontrol .chart-wrap (tetap),
             grafik tidak merambat turun / tidak tampil */
        Chart.defaults.font.family = "'Segoe UI', Arial, sans-serif";
        Chart.defaults.font.size = 11;
        Chart.defaults.color = '#6b7280';

        /* Warna tema JANG BANTAH (dipertahankan) */
        const JB_PRIMARY = '#1B4965';
        const JB_DARK    = '#0D2B3E';
        const JB_LIGHT   = '#5FA8D3';
        const JB_ACCENT  = '#F39C12';

        const baseOptions = {
            responsive: true,
            maintainAspectRatio: false,
            resizeDelay: 150,
            animation: { duration: 900, easing: 'easeOutQuart' },
            plugins: { legend: { display: false } }
        };

        const gridStyle = { color: 'rgba(27, 73, 101, .08)' };
        const tooltipStyle = {
            backgroundColor: JB_DARK,
            titleFont: { weight: '700' },
            padding: 10,
            cornerRadius: 8,
            displayColors: false
        };

        /* Helper anti-gagal: satu chart error tidak mematikan chart lain */
        function safeChart(id, config) {
            try {
                var el = document.getElementById(id);
                if (el) new Chart(el, config);
            } catch (e) {
                console.warn('Chart "' + id + '" gagal dirender:', e);
            }
        }

        /* Gradien vertikal khas JANG BANTAH (biru gelap -> biru muda) */
        function jbGradient(ctx, from, to) {
            var chartArea = ctx.chart.chartArea;
            if (!chartArea) return from;
            var g = ctx.chart.ctx.createLinearGradient(0, chartArea.bottom, 0, chartArea.top);
            g.addColorStop(0, from);
            g.addColorStop(1, to);
            return g;
        }

        /* ================== CHART: RIWAYAT PENAMBAHAN ASET (bar) ================== */
        safeChart('chartAset', {
            type: 'bar',
            data: {
                labels: {{ Illuminate\Support\Js::from($riwayatAsetLabels) }},
                datasets: [{
                    label: 'Jumlah Bidang Aset',
                    data: {{ Illuminate\Support\Js::from($riwayatAsetData) }},
                    backgroundColor: function (ctx) { return jbGradient(ctx, 'rgba(27, 73, 101, .45)', 'rgba(95, 168, 211, .9)'); },
                    hoverBackgroundColor: JB_PRIMARY,
                    borderRadius: 6,
                    borderSkipped: false,
                    maxBarThickness: 42
                }]
            },
            options: {
                ...baseOptions,
                plugins: {
                    legend: { display: false },
                    tooltip: tooltipStyle
                },
                scales: {
                    x: { grid: { display: false } },
                    y: { beginAtZero: true, ticks: { precision: 0 }, grid: gridStyle }
                }
            }
        });

        /* ================== CHART: STATUS SERTIFIKASI (donut) ================== */
        safeChart('chartSertifikasi', {
            type: 'doughnut',
            data: {
                labels: {{ Illuminate\Support\Js::from(array_keys($sertifikasiStatus)) }},
                datasets: [{
                    data: {{ Illuminate\Support\Js::from(array_values($sertifikasiStatus)) }},
                    backgroundColor: [
                        'rgba(27, 73, 101, .18)',   /* Belum Ada Record */
                        '#bdc3c7',                   /* Belum Diajukan */
                        '#95a5a6',                   /* Persiapan Berkas */
                        '#3498db',                   /* Diajukan BPN */
                        '#5FA8D3',                   /* Proses Ukur */
                        '#F39C12',                   /* Proses Validasi */
                        '#27AE60',                   /* Terbit Sertifikat */
                        '#1B4965'                    /* Selesai */
                    ],
                    borderWidth: 2,
                    borderColor: '#fff',
                    hoverOffset: 6
                }]
            },
            options: {
                ...baseOptions,
                cutout: '60%',
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels: { usePointStyle: true, boxWidth: 8, padding: 10, font: { size: 10.5 } }
                    },
                    tooltip: {
                        ...tooltipStyle,
                        callbacks: {
                            label: function (item) {
                                var total = item.dataset.data.reduce(function (a, b) { return a + b; }, 0);
                                var pct = total ? Math.round(item.parsed / total * 100) : 0;
                                return ' ' + item.label + ': ' + item.parsed + ' bidang (' + pct + '%)';
                            }
                        }
                    }
                }
            }
        });

        /* ================== CHART: SEBARAN ASET PER KECAMATAN (bar horizontal) ================== */
        safeChart('chartKecamatan', {
            type: 'bar',
            data: {
                labels: [{!! $sebaranKecamatan->pluck('nama')->toJson() !!}],
                datasets: [{
                    label: 'Jumlah Bidang',
                    data: [{!! $sebaranKecamatan->pluck('asets_count')->toJson() !!}],
                    backgroundColor: function (ctx) { return jbGradient(ctx, 'rgba(95, 168, 211, .35)', 'rgba(27, 73, 101, .9)'); },
                    hoverBackgroundColor: JB_ACCENT,
                    borderRadius: 6,
                    borderSkipped: false,
                    maxBarThickness: 22
                }]
            },
            options: {
                ...baseOptions,
                indexAxis: 'y',
                plugins: { legend: { display: false }, tooltip: tooltipStyle },
                scales: {
                    x: { beginAtZero: true, ticks: { precision: 0 }, grid: gridStyle },
                    y: { grid: { display: false } }
                }
            }
        });
    }
</script>
@endpush
