@extends('layouts.app')

@section('content')
    <!-- ══════════════════ NAVBAR ══════════════════ -->
    <nav class="navbar navbar-expand-lg navbar-jb fixed-top">
        <div class="container">
            <a class="navbar-brand text-white fw-bold d-flex align-items-center gap-2" href="{{ route('home') }}">
                <img src="{{ asset('favicon.png') }}" alt="Logo" style="height:38px; border-radius:8px;">
                <div>
                    <div style="font-size:.9rem;line-height:1;letter-spacing:.5px;">JANG BANTAH</div>
                    <div style="font-size:.6rem;opacity:.7;letter-spacing:1px;">JARINGAN BANK TANAH</div>
                </div>
            </a>
            <button class="navbar-toggler text-white border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <i class="fas fa-bars"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto gap-1">
                    <li class="nav-item"><a class="nav-link text-white active" href="{{ route('home') }}"><i class="fas fa-home me-1"></i>Beranda</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="{{ route('about') }}"><i class="fas fa-info-circle me-1"></i>Tentang</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="{{ route('map.public') }}"><i class="fas fa-map-marked-alt me-1"></i>Peta Aset</a></li>
                    <li class="nav-item ms-lg-2">
                        <a class="btn btn-gold btn-sm px-4" href="{{ route('login') }}">
                            <i class="fas fa-sign-in-alt me-1"></i> Login Admin
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- ══════════════════ HERO VIDEO BACKGROUND ══════════════════ -->
    <section id="heroVideo" class="hero-video">
        <!-- Video Background dari YouTube -->
        <div class="video-bg">
            <div class="video-bg-wrapper">
                <iframe
                    src="https://www.youtube.com/embed/FwCIWtT9TiM?autoplay=1&mute=1&loop=1&playlist=FwCIWtT9TiM&controls=0&showinfo=0&modestbranding=1&rel=0&iv_load_policy=3&playsinline=1&disablekb=1&fs=0&cc_load_policy=0"
                    title="JANG BANTAH - Halmahera Barat"
                    frameborder="0"
                    allow="autoplay; encrypted-media; picture-in-picture"
                    allowfullscreen></iframe>
            </div>
        </div>
        <div class="slider-overlay"></div>

        <!-- Hero Content -->
        <div class="container hero-content position-relative" style="z-index:2;">
            <div class="row align-items-center hero-row">
                <div class="col-lg-12 text-white animate-fade-up">
                    <span class="badge mb-3 px-3 py-2" style="font-size:.75rem; background:rgba(255,255,255,.15); backdrop-filter:blur(10px); border:1px solid rgba(255,255,255,.25); color:#fff;">
                        <i class="fas fa-landmark me-1"></i> PEMERINTAH KABUPATEN HALMAHERA BARAT
                    </span>
                    <h1 class="fw-800 mb-3 display-4" style="text-shadow: 2px 4px 20px rgba(0,20,60,.5);">
                        JANG BANTAH
                    </h1>
                    <p class="lead mb-2 fw-500" style="opacity:.95; text-shadow: 1px 2px 10px rgba(0,0,0,.4);">
                        Jaringan Bank Tanah — Pemkab. Halmahera Barat
                    </p>
                    <p style="opacity:.85; max-width:550px; font-size:.95rem; text-shadow: 1px 2px 10px rgba(0,0,0,.4); line-height:1.7;">
                        Sistem Informasi Manajemen Bank Tanah Digital Terintegrasi untuk pengelolaan, pengamanan, dan sertifikasi aset tanah pemerintah daerah.
                    </p>
                    <div class="d-flex gap-3 mt-4 flex-wrap">
                        <a href="{{ route('about') }}" class="btn btn-gold">
                            <i class="fas fa-info-circle me-2"></i>Pelajari Lebih Lanjut
                        </a>
                        <a href="{{ route('map.public') }}" class="btn btn-outline-light" style="border-radius:50px; padding:12px 28px;">
                            <i class="fas fa-map me-2"></i>Lihat Peta Aset
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ══════════════════ STATS SECTION ══════════════════ -->
    <section class="py-5" style="position:relative; z-index:3; background: linear-gradient(180deg, #f0f7ff 0%, #fff 100%);">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card animate-fade-up">
                        <div class="icon"><i class="fas fa-map-marked-alt"></i></div>
                        <div class="number">{{ number_format($totalAset) }}</div>
                        <div class="label">Total Bidang Tanah</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card animate-fade-up" style="animation-delay:.1s;">
                        <div class="icon" style="color:var(--blue-400);"><i class="fas fa-money-bill-wave"></i></div>
                        <div class="number">{{ 'Rp ' . number_format($totalNilai, 0, ',', '.') }}</div>
                        <div class="label">Total Nilai Aset</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card animate-fade-up" style="animation-delay:.2s;">
                        <div class="icon" style="color:#27AE60;"><i class="fas fa-certificate"></i></div>
                        <div class="number">{{ number_format($sudahSertifikat) }}</div>
                        <div class="label">Sudah Bersertifikat</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card animate-fade-up" style="animation-delay:.3s;">
                        <div class="icon" style="color:var(--gold);"><i class="fas fa-building"></i></div>
                        <div class="number">{{ number_format($totalOpd) }}</div>
                        <div class="label">OPD Terdaftar</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ══════════════════ FITUR SECTION ══════════════════ -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center section-title mb-2">Fitur Unggulan Sistem</h2>
            <p class="text-center mb-5" style="color:#666; max-width:600px; margin-left:auto; margin-right:auto; font-size:.95rem;">
                Platform digital terintegrasi untuk pengelolaan aset tanah pemerintah daerah yang modern dan efisien.
            </p>
            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="feature-card">
                        <div class="icon-box"><i class="fas fa-database"></i></div>
                        <h6 class="fw-600 mb-2">Digitalisasi Aset</h6>
                        <p style="font-size:.85rem;color:#666;">Pendataan seluruh aset tanah secara digital dan terintegrasi.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="feature-card">
                        <div class="icon-box" style="background:linear-gradient(135deg,#27AE60,#2ECC71);"><i class="fas fa-certificate"></i></div>
                        <h6 class="fw-600 mb-2">Sertifikasi</h6>
                        <p style="font-size:.85rem;color:#666;">Monitoring proses sertifikasi tanah hingga terbit sertifikat.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="feature-card">
                        <div class="icon-box" style="background:linear-gradient(135deg,#004d99,#1a75d1);"><i class="fas fa-shield-halved"></i></div>
                        <h6 class="fw-600 mb-2">Pengamanan Aset</h6>
                        <p style="font-size:.85rem;color:#666;">Pengamanan administrasi, fisik, dan hukum aset tanah.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="feature-card">
                        <div class="icon-box" style="background:linear-gradient(135deg,#f0c040,#d4a020);"><i class="fas fa-globe"></i></div>
                        <h6 class="fw-600 mb-2">GIS / Peta Digital</h6>
                        <p style="font-size:.85rem;color:#666;">Visualisasi sebaran aset tanah pada peta digital interaktif.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ══════════════════ DATA ASET TERBARU ══════════════════ -->
    @if($asets->count() > 0)
    <section class="py-5" style="background: linear-gradient(180deg, var(--blue-50) 0%, #fff 100%);">
        <div class="container">
            <h2 class="text-center section-title mb-2">Data Aset Terbaru</h2>
            <p class="text-center mb-5" style="color:#666; max-width:600px; margin-left:auto; margin-right:auto; font-size:.95rem;">
                Informasi terkini mengenai aset tanah yang telah terdata dalam sistem.
            </p>
            <div class="row g-4">
                @foreach($asets as $aset)
                <div class="col-lg-4 col-md-6">
                    <div class="aset-card">
                        @if($aset->foto_lokasi)
                        <img src="{{ Storage::url($aset->foto_lokasi) }}" alt="{{ $aset->nama_aset }}">
                        @else
                        <div class="d-flex align-items-center justify-content-center" style="height:200px; background:linear-gradient(135deg, var(--blue-100), var(--blue-200));">
                            <i class="fas fa-map-marked-alt" style="font-size:4rem; color:var(--blue-400); opacity:.5;"></i>
                        </div>
                        @endif
                        <div class="p-4">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="fw-600 mb-0" style="color:var(--blue-800);">{{ $aset->nama_aset }}</h6>
                                <span class="badge bg-{{ $aset->status === 'aktif' ? 'success' : 'warning' }}">
                                    {{ strtoupper(str_replace('_', ' ', $aset->status)) }}
                                </span>
                            </div>
                            <div style="font-size:.8rem;color:#666;">
                                <p class="mb-1"><i class="fas fa-map-marker-alt me-1" style="color:var(--blue-500);"></i> {{ $aset->kecamatan?->nama ?? '-' }}, {{ $aset->desa?->nama ?? '-' }}</p>
                                <p class="mb-1"><i class="fas fa-building me-1" style="color:var(--blue-500);"></i> {{ $aset->opd?->nama ?? '-' }}</p>
                                <p class="mb-1"><i class="fas fa-ruler-combined me-1" style="color:var(--blue-500);"></i> {{ number_format($aset->luas, 2) }} m²</p>
                                <p class="mb-0"><i class="fas fa-money-bill me-1" style="color:var(--blue-500);"></i> Rp {{ number_format($aset->nilai_aset, 0, ',', '.') }}</p>
                            </div>
                            @if($aset->sertifikasi && in_array($aset->sertifikasi->status, ['terbit_sertifikat', 'selesai']))
                            <span class="badge mt-2" style="background:var(--blue-700);"><i class="fas fa-check-circle me-1"></i>Bersertifikat</span>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- ══════════════════ WORKFLOW SECTION ══════════════════ -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center section-title mb-2">Alur Kerja Sistem</h2>
            <p class="text-center mb-5" style="color:#666; max-width:600px; margin-left:auto; margin-right:auto; font-size:.95rem;">
                Proses end-to-end pengelolaan bank tanah dari pendataan hingga pemanfaatan.
            </p>
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="d-flex flex-wrap justify-content-center gap-2 align-items-center">
                        @php
                            $steps = ['Pendataan Aset', 'Validasi Data', 'Upload Dokumen', 'Verifikasi', 'Input GIS',
                                      'Pengamanan Administrasi', 'Pengamanan Fisik', 'Pengamanan Hukum',
                                      'Pengajuan Sertifikasi', 'Monitoring BPN', 'Terbit Sertifikat', 'Pemanfaatan Aset'];
                        @endphp
                        @foreach($steps as $i => $step)
                        <div class="text-center" style="min-width:100px;">
                            <div class="d-flex align-items-center justify-content-center mx-auto mb-2 workflow-step">
                                {{ $i + 1 }}
                            </div>
                            <div style="font-size:.7rem;font-weight:500;color:var(--blue-800);">{{ $step }}</div>
                        </div>
                        @if($i < count($steps) - 1)
                        <i class="fas fa-chevron-right" style="font-size:.7rem;margin-top:-15px;color:var(--blue-400);"></i>
                        @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ══════════════════ FOOTER ══════════════════ -->
    <footer class="footer-jb">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <img src="{{ asset('favicon.png') }}" alt="Logo" style="height:38px; border-radius:8px;">
                        <div>
                            <div class="text-white fw-bold" style="font-size:.95rem;">JANG BANTAH</div>
                            <div style="font-size:.65rem; opacity:.6; letter-spacing:1px;">JARINGAN BANK TANAH</div>
                        </div>
                    </div>
                    <p style="font-size:.85rem; line-height:1.7;">
                        Jaringan Bank Tanah Pemerintah Kabupaten Halmahera Barat.<br>
                        Sistem Informasi Manajemen Bank Tanah Digital Terintegrasi.
                    </p>
                    <div class="d-flex gap-3 mt-3">
                        <a href="#" class="text-white" style="width:36px;height:36px;border-radius:50%;background:rgba(255,255,255,.1);display:flex;align-items:center;justify-content:center;transition:all .3s;">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="text-white" style="width:36px;height:36px;border-radius:50%;background:rgba(255,255,255,.1);display:flex;align-items:center;justify-content:center;transition:all .3s;">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="text-white" style="width:36px;height:36px;border-radius:50%;background:rgba(255,255,255,.1);display:flex;align-items:center;justify-content:center;transition:all .3s;">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-4 mb-4">
                    <h5><i class="fas fa-link me-2"></i>Link Terkait</h5>
                    <ul class="list-unstyled" style="font-size:.85rem; line-height:2.2;">
                        <li><a href="https://halbarkab.go.id/" class="text-white text-decoration-none" target="_blank" rel="noopener"><i class="fas fa-external-link-alt me-2" style="font-size:.7rem;"></i>Website Pemkab. Halbar</a></li>
                        <li><a href="#" class="text-white text-decoration-none"><i class="fas fa-external-link-alt me-2" style="font-size:.7rem;"></i>BKAD</a></li>
                        <li><a href="#" class="text-white text-decoration-none"><i class="fas fa-external-link-alt me-2" style="font-size:.7rem;"></i>Diskominfo</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 mb-4">
                    <h5><i class="fas fa-headset me-2"></i>Kontak</h5>
                    <div style="font-size:.85rem; line-height:1.9;">
                        <p class="mb-1 fw-600 text-white">Pemerintah Daerah Kabupaten Halmahera Barat</p>
                        <p class="mb-0"><i class="fas fa-map-marker-alt me-2" style="color:var(--blue-400);"></i>Jl. Pengabdian No. 1 Desa Jati<br>Kec. Jailolo Kab. Halmahera Barat<br>Provinsi Maluku Utara</p>
                        <p class="mb-0 mt-2"><i class="fas fa-globe me-2" style="color:var(--blue-400);"></i><a href="https://halbarkab.go.id/" class="text-white text-decoration-none" target="_blank" rel="noopener">halbarkab.go.id</a></p>
                    </div>
                </div>
            </div>
            <hr style="border-color:rgba(255,255,255,.1);">
            <div class="text-center" style="font-size:.8rem;">
                &copy; {{ date('Y') }} Pemerintah Kabupaten Halmahera Barat &mdash; JANG BANTAH — Jaringan Bank Tanah
            </div>
        </div>
    </footer>
@endsection

@push('styles')
<style>
    /* ════════ HERO VIDEO BACKGROUND ════════ */
    .hero-video {
        position: relative;
        min-height: 100vh;
        overflow: hidden;
        background: linear-gradient(135deg, #002a5c 0%, #004d99 50%, #0062b8 100%);
    }
    .video-bg {
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        z-index: 0;
        overflow: hidden;
        pointer-events: none;
    }
    .video-bg-wrapper {
        position: absolute;
        top: 50%; left: 50%;
        transform: translate(-50%, -50%);
        width: 100vw;
        height: 56.25vw;            /* rasio 16:9 */
        min-height: 100vh;
        min-width: 177.78vh;        /* rasio 16:9 (lebar min agar selalu menutupi) */
    }
    .video-bg-wrapper iframe {
        position: absolute;
        top: 0; left: 0;
        width: 100%;
        height: 100%;
        border: 0;
        pointer-events: none;
    }
    .slider-overlay {
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        /* Overlay tipis menyamping: gelap di kiri (baca teks), transparan di kanan (video terlihat) */
        background: linear-gradient(
            90deg,
            rgba(0,42,92,.78) 0%,
            rgba(0,42,92,.55) 25%,
            rgba(0,77,153,.30) 50%,
            rgba(0,98,184,.15) 75%,
            rgba(0,98,184,.10) 100%
        );
        z-index: 1;
    }
    /* Layer tambahan: gelap halus di atas & bawah agar navbar + transisi wave tetap mulus */
    .slider-overlay::after {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: linear-gradient(
            180deg,
            rgba(0,20,50,.35) 0%,
            rgba(0,0,0,0) 18%,
            rgba(0,0,0,0) 75%,
            rgba(0,20,50,.45) 100%
        );
    }
    /* Bottom wave */
    .hero-video::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        right: 0;
        height: 100px;
        background: var(--blue-50);
        clip-path: ellipse(55% 100% at 50% 100%);
        z-index: 5;
    }

    /* ════════ WORKFLOW STEP ════════ */
    .workflow-step {
        width: 50px; height: 50px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--blue-700), var(--blue-500));
        color: #fff;
        font-size: .75rem;
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(0,77,153,.25);
        transition: all .3s;
    }
    .workflow-step:hover {
        transform: scale(1.15);
        box-shadow: 0 6px 20px rgba(0,77,153,.4);
    }

    /* ════════ SCROLL REVEAL ════════ */
    .reveal {
        opacity: 0;
        transform: translateY(30px);
        transition: opacity .6s ease, transform .6s ease;
    }
    .reveal.active {
        opacity: 1;
        transform: translateY(0);
    }

    /* ════════ MOBILE RESPONSIVE ════════ */
    /* Default (desktop): row setinggi viewport, konten di tengah */
    .hero-row { min-height: 100vh; }

    @media (max-width: 991px) {
        /* Mobile: konten mengalir dari atas, section boleh memanjang agar tidak terpotong */
        .hero-row {
            min-height: auto;
            align-items: flex-start !important;
        }
        .hero-content {
            padding-top: 90px;     /* clearance untuk navbar fixed */
            padding-bottom: 70px;
        }
        /* Overlay mobile: vertikal — gelap di atas (navbar) & bawah (wave),
           lebih tipis di tengah agar video background tetap terlihat jelas */
        .slider-overlay {
            background: linear-gradient(
                180deg,
                rgba(0,42,92,.72) 0%,
                rgba(0,42,92,.42) 28%,
                rgba(0,42,92,.42) 72%,
                rgba(0,42,92,.68) 100%
            );
        }
        .slider-overlay::after { display: none; }
        /* Perkuat text-shadow di mobile agar teks tetap terbaca di atas video */
        .hero-content h1,
        .hero-content .lead,
        .hero-content p {
            text-shadow: 0 2px 10px rgba(0,0,0,.55), 0 0 2px rgba(0,0,0,.85) !important;
        }
    }

    @media (max-width: 575px) {
        .hero-content { padding-top: 80px; padding-bottom: 60px; }
        .hero-content h1 { font-size: 2rem; line-height: 1.15; }
        .hero-content .lead { font-size: 1rem; }
        .hero-content p { font-size: .88rem; }
        .hero-content .btn { padding: 9px 18px !important; font-size: .82rem !important; }
    }

    @media (max-width: 360px) {
        .hero-content h1 { font-size: 1.7rem; }
        .hero-content .btn { padding: 8px 14px !important; font-size: .78rem !important; }
    }
</style>
@endpush

@push('scripts')
<script>
    /* ── Video Background (YouTube) ── */
    /* Video di-mute & autoplay via parameter URL iframe, tidak perlu JS tambahan. */

    /* ── Scroll Reveal ── */
    function revealOnScroll() {
        const reveals = document.querySelectorAll('.reveal');
        reveals.forEach(el => {
            const top = el.getBoundingClientRect().top;
            if (top < window.innerHeight - 80) {
                el.classList.add('active');
            }
        });
    }
    window.addEventListener('scroll', revealOnScroll);
    window.addEventListener('load', revealOnScroll);

    /* ── Navbar scroll effect ── */
    window.addEventListener('scroll', () => {
        const nav = document.querySelector('.navbar-jb');
        if (window.scrollY > 60) {
            nav.style.background = 'linear-gradient(135deg, rgba(0,42,92,.97), rgba(0,77,153,.97))';
            nav.style.padding = '.5rem 0';
        } else {
            nav.style.background = 'linear-gradient(135deg, #002a5c, #004d99, #0062b8)';
            nav.style.padding = '.7rem 0';
        }
    });
</script>
@endpush
