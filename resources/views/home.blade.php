@extends('layouts.app')

@section('content')
    <!-- ══════════════════ NAVBAR MODERN (putih ala satudata) ══════════════════ -->
    <nav class="navbar navbar-expand-lg navbar-sd fixed-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
                <img src="{{ asset('favicon.png') }}" alt="Logo" style="height:42px; border-radius:12px;">
                <div>
                    <div class="brand-name">JANG BANTAH</div>
                    <div class="brand-sub">JARINGAN BANK TANAH</div>
                </div>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <i class="fas fa-bars"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-1">
                    <li class="nav-item"><a class="nav-link nav-link-sd" href="{{ route('home') }}"><i class="fas fa-home me-1"></i>Beranda</a></li>
                    <li class="nav-item"><a class="nav-link nav-link-sd" href="{{ route('aset.publik') }}"><i class="fas fa-layer-group me-1"></i>Data Aset</a></li>
                    <li class="nav-item"><a class="nav-link nav-link-sd" href="{{ route('berita.publik') }}"><i class="fas fa-newspaper me-1"></i>Berita</a></li>
                    <li class="nav-item"><a class="nav-link nav-link-sd" href="{{ route('video.publik') }}"><i class="fab fa-youtube me-1"></i>Video</a></li>
                    <li class="nav-item"><a class="nav-link nav-link-sd" href="{{ route('about') }}"><i class="fas fa-info-circle me-1"></i>Tentang</a></li>
                    <li class="nav-item"><a class="nav-link nav-link-sd" href="{{ route('map.public') }}"><i class="fas fa-map-marked-alt me-1"></i>Peta Aset</a></li>
                    <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
                        <a class="btn btn-gold btn-sm px-4" href="{{ route('login') }}">
                            <i class="fas fa-sign-in-alt me-1"></i> Login Admin
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- ══════════════════ HERO (video/gambar + gradient biru + blob dekoratif) ══════════════════ -->
    <section id="heroVideo" class="hero-video">
        <!-- Background dikelola Super Admin: menu Kelola Website (video YouTube / 1 gambar statis / banyak gambar = slider) -->
        <div class="video-bg">
            @php $heroImages = \App\WebsiteContent::heroType() === 'image' ? \App\WebsiteContent::heroImages() : []; @endphp
            @if (count($heroImages) === 1)
            {{-- 1 gambar: background statis --}}
            <img src="{{ $heroImages[0] }}" alt="{{ \App\WebsiteContent::get('web_hero_title') }}" class="hero-image-bg">
            @elseif (count($heroImages) > 1)
            {{-- Lebih dari 1 gambar: slider crossfade otomatis --}}
            <div class="hero-slider" id="heroSlider">
                @foreach ($heroImages as $i => $url)
                <div class="hero-slide{{ $i === 0 ? ' active' : '' }}"><img src="{{ $url }}" alt="{{ \App\WebsiteContent::get('web_hero_title') }} {{ $i + 1 }}" class="hero-image-bg"></div>
                @endforeach
                <div class="hero-slider-nav" id="heroSliderNav">
                    @foreach ($heroImages as $i => $url)
                    <button type="button" class="hero-slider-dot{{ $i === 0 ? ' active' : '' }}" data-index="{{ $i }}" aria-label="Slide {{ $i + 1 }}"></button>
                    @endforeach
                </div>
            </div>
            @elseif (\App\WebsiteContent::heroType() === 'video')
            <div class="video-bg-wrapper">
                <iframe
                    src="{{ \App\WebsiteContent::youtubeEmbedUrl() }}"
                    title="{{ \App\WebsiteContent::get('web_hero_title') }}"
                    frameborder="0"
                    allow="autoplay; encrypted-media; picture-in-picture"
                    allowfullscreen></iframe>
            </div>
            @endif
        </div>
        <div class="slider-overlay"></div>

        <!-- Dekorasi modern: glow blob + dot grid -->
        <div class="hero-blob hero-blob-1"></div>
        <div class="hero-blob hero-blob-2"></div>
        <div class="hero-dots"></div>

        <!-- Hero Content -->
        <div class="container hero-content position-relative" style="z-index:2;">
            <div class="row align-items-center hero-row">
                <div class="col-lg-8 text-white animate-fade-up">
                    <span class="chip-glass mb-4">
                        <i class="fas fa-landmark" style="color:var(--gold);"></i>
                        {{ \App\WebsiteContent::get('web_hero_badge') }}
                    </span>
                    <h1 class="fw-800 mb-3 display-4" style="text-shadow: 2px 4px 20px rgba(0,20,60,.5);">
                        {{ \App\WebsiteContent::get('web_hero_title') }}
                    </h1>
                    <p class="lead mb-2 fw-500" style="opacity:.95; text-shadow: 1px 2px 10px rgba(0,0,0,.4);">
                        {{ \App\WebsiteContent::get('web_hero_subtitle') }}
                    </p>
                    <p style="opacity:.85; max-width:580px; font-size:.95rem; text-shadow: 1px 2px 10px rgba(0,0,0,.4); line-height:1.75;">
                        {{ \App\WebsiteContent::get('web_hero_description') }}
                    </p>
                    <div class="d-flex gap-3 mt-4 flex-wrap">
                        <a href="{{ route('about') }}" class="btn btn-gold">
                            <i class="fas fa-info-circle me-2"></i>{{ \App\WebsiteContent::get('web_hero_btn1_text') }}
                        </a>
                        <a href="{{ route('map.public') }}" class="btn btn-glass">
                            <i class="fas fa-map me-2"></i>{{ \App\WebsiteContent::get('web_hero_btn2_text') }}
                        </a>
                    </div>
                </div>

                <!-- Chip fitur mengambang (desktop) -->
                <div class="col-lg-4 d-none d-lg-block">
                    <div class="d-flex flex-column gap-3 align-items-end">
                        <div class="chip-glass animate-float">
                            <i class="fas fa-map-location-dot" style="color:var(--gold); font-size:1.05rem;"></i>
                            Peta Interaktif GIS
                        </div>
                        <div class="chip-glass animate-float" style="animation-delay:1s; margin-right:2.5rem;">
                            <i class="fas fa-file-shield" style="color:#7ee2a8; font-size:1.05rem;"></i>
                            Sertifikasi Digital
                        </div>
                        <div class="chip-glass animate-float" style="animation-delay:2s; margin-right:.8rem;">
                            <i class="fas fa-chart-line" style="color:#8ecbff; font-size:1.05rem;"></i>
                            Monitoring Real-time
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ══════════════════ STATS SECTION ══════════════════ -->
    <section class="stats-section">
        <div class="container">
            <!-- Strip statistik solid (di luar hero, background gradient biru) -->
            <div class="stat-strip animate-fade-up">
                <div class="row g-0 text-center text-white">
                    <div class="col-6 col-md-3 hero-strip-item">
                        <div class="d-flex align-items-center justify-content-center gap-2 gap-md-3">
                            <span class="strip-icon" style="color:#8ecbff;"><i class="fas fa-city"></i></span>
                            <span class="text-start">
                                <span class="fw-700 fs-5 d-block">{{ number_format($totalKecamatan) }}</span>
                                <span class="strip-label">Kecamatan</span>
                            </span>
                        </div>
                    </div>
                    <div class="col-6 col-md-3 hero-strip-item">
                        <div class="d-flex align-items-center justify-content-center gap-2 gap-md-3">
                            <span class="strip-icon" style="color:#7ee2a8;"><i class="fas fa-home"></i></span>
                            <span class="text-start">
                                <span class="fw-700 fs-5 d-block">{{ number_format($totalDesa) }}</span>
                                <span class="strip-label">Desa / Kelurahan</span>
                            </span>
                        </div>
                    </div>
                    <div class="col-6 col-md-3 hero-strip-item">
                        <div class="d-flex align-items-center justify-content-center gap-2 gap-md-3">
                            <span class="strip-icon" style="color:#ffd56a;"><i class="fas fa-vector-square"></i></span>
                            <span class="text-start">
                                <span class="fw-700 fs-5 d-block">{{ number_format($totalLuas / 10000, 1) }} Ha</span>
                                <span class="strip-label">Total Luas Tanah</span>
                            </span>
                        </div>
                    </div>
                    <div class="col-6 col-md-3 hero-strip-item">
                        <div class="d-flex align-items-center justify-content-center gap-2 gap-md-3">
                            <span class="strip-icon" style="color:#ffffff;"><i class="fas fa-circle-check"></i></span>
                            <span class="text-start">
                                <span class="fw-700 fs-5 d-block">{{ number_format($asetDipublikasikan) }}</span>
                                <span class="strip-label">Aset Dipublikasikan</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4 mt-4 pt-2">
                <div class="col-lg-3 col-md-6">
                    <div class="card-soft stat-modern reveal h-100">
                        <div class="icon-tile"><i class="fas fa-map-marked-alt"></i></div>
                        <div class="number" data-count="{{ $totalAset }}">0</div>
                        <div class="label">Total Bidang Tanah</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card-soft stat-modern reveal h-100" style="transition-delay:.08s;">
                        <div class="icon-tile" style="background:#e8f1ff; color:var(--blue-500);"><i class="fas fa-money-bill-wave"></i></div>
                        <div class="number" data-count="{{ $totalNilai }}" data-prefix="Rp ">Rp 0</div>
                        <div class="label">Total Nilai Aset</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card-soft stat-modern reveal h-100" style="transition-delay:.16s;">
                        <div class="icon-tile green"><i class="fas fa-certificate"></i></div>
                        <div class="number" data-count="{{ $sudahSertifikat }}">0</div>
                        <div class="label">Sudah Bersertifikat</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card-soft stat-modern reveal h-100" style="transition-delay:.24s;">
                        <div class="icon-tile gold"><i class="fas fa-building"></i></div>
                        <div class="number" data-count="{{ $totalOpd }}">0</div>
                        <div class="label">OPD Terdaftar</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ══════════════════ GALERI KEGIATAN ══════════════════ -->
    @php $galeriPhotos = \App\WebsiteContent::galeriPhotos(); @endphp
    @if (count($galeriPhotos) > 0)
    <section class="py-5 py-lg-6 galeri-section" id="galeriKegiatan">
        <div class="container">
            <div class="text-center mb-5 reveal">
                <span class="section-eyebrow mb-3">Dokumentasi</span>
                <h2 class="section-title mb-2 mt-2">{{ \App\WebsiteContent::get('web_galeri_title') }}</h2>
                <p class="section-desc">{{ \App\WebsiteContent::get('web_galeri_desc') }}</p>
            </div>
            <div class="row g-3 g-md-4">
                @foreach ($galeriPhotos as $i => $url)
                <div class="col-6 col-md-4 col-lg-3">
                    <a href="{{ $url }}" class="galeri-item reveal" data-galeri-index="{{ $i }}" style="transition-delay: {{ min($i, 8) * 0.05 }}s;">
                        <img src="{{ $url }}" alt="Foto kegiatan {{ $i + 1 }}" loading="lazy">
                        <span class="galeri-zoom"><i class="fas fa-expand"></i></span>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- ══════════════════ VIDEO YOUTUBE JANG BANTAH ══════════════════ -->
    {{-- Link video dikelola Super Admin (menu Kelola Website); jumlah tak dibatasi.
         Halaman depan menampilkan maks 3 thumbnail — sisanya via tombol ke halaman /video --}}
    @php
        $videoItems   = \App\WebsiteContent::videoItems();
        $videoOnHome  = array_slice($videoItems, 0, 3);
        $videoCount   = count(array_filter($videoItems, fn ($v) => $v['type'] === 'video'));
    @endphp
    @if (count($videoItems) > 0)
    <section class="py-5 py-lg-6 video-section" id="videoYoutube">
        <div class="container">
            <div class="text-center mb-5 reveal">
                <span class="section-eyebrow mb-3">Kanal Video</span>
                <h2 class="section-title mb-2 mt-2">{{ \App\WebsiteContent::get('web_video_title') }}</h2>
                <p class="section-desc">{{ \App\WebsiteContent::get('web_video_desc') }}</p>
            </div>
            <div class="row g-3 g-md-4 justify-content-center">
                @foreach ($videoOnHome as $i => $video)
                @if ($video['type'] === 'video')
                <div class="col-12 col-sm-10 col-md-6 col-lg-4">
                    <div class="video-item reveal" role="button" tabindex="0" data-video-id="{{ $video['id'] }}" title="Tonton video di YouTube" style="transition-delay: {{ $i * 0.08 }}s;">
                        <img src="{{ $video['thumb'] }}" alt="Video YouTube {{ $i + 1 }}" loading="lazy">
                        <span class="video-play"><i class="fas fa-play"></i></span>
                        <span class="video-badge"><i class="fab fa-youtube me-1"></i>YouTube</span>
                    </div>
                </div>
                @else
                <div class="col-12 col-sm-10 col-md-6 col-lg-4">
                    <a href="{{ $video['url'] }}" target="_blank" rel="noopener" class="video-item video-channel reveal d-flex align-items-center gap-3" title="Buka channel YouTube di tab baru" style="transition-delay: {{ $i * 0.08 }}s;">
                        <span class="video-channel-icon"><i class="fab fa-youtube"></i></span>
                        <span class="text-start flex-grow-1">
                            <span class="d-block fw-600 video-channel-title">Channel YouTube Kami</span>
                            <span class="d-block video-channel-sub">Tonton video lainnya di channel resmi JANG BANTAH</span>
                        </span>
                        <i class="fas fa-external-link-alt video-channel-arrow"></i>
                    </a>
                </div>
                @endif
                @endforeach
            </div>

            {{-- Lebih dari 3 item → tombol menuju halaman khusus kumpulan video --}}
            @if (count($videoItems) > 3)
            <div class="text-center mt-4 reveal">
                <a href="{{ route('video.publik') }}" class="btn btn-blue px-4">
                    <i class="fab fa-youtube me-2"></i>Lihat Semua Video
                    <span class="badge bg-light text-dark ms-1">{{ $videoCount }} video</span>
                </a>
            </div>
            @endif
        </div>
    </section>
    @endif

    <!-- ══════════════════ FITUR SECTION ══════════════════ -->
    <section class="py-5 py-lg-6 bg-white">
        <div class="container">
            <div class="text-center mb-5 reveal">
                <span class="section-eyebrow mb-3">Fitur Utama</span>
                <h2 class="section-title mb-2 mt-2">{{ \App\WebsiteContent::get('web_fitur_title') }}</h2>
                <p class="section-desc">
                    {{ \App\WebsiteContent::get('web_fitur_desc') }}
                </p>
            </div>
            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="card-soft feature-modern reveal h-100">
                        <div class="icon-tile mb-3"><i class="fas fa-database"></i></div>
                        <h6 class="fw-600 mb-2">{{ \App\WebsiteContent::get('web_fitur1_title') }}</h6>
                        <p class="mb-0">{{ \App\WebsiteContent::get('web_fitur1_desc') }}</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card-soft feature-modern reveal h-100" style="transition-delay:.08s;">
                        <div class="icon-tile green mb-3"><i class="fas fa-certificate"></i></div>
                        <h6 class="fw-600 mb-2">{{ \App\WebsiteContent::get('web_fitur2_title') }}</h6>
                        <p class="mb-0">{{ \App\WebsiteContent::get('web_fitur2_desc') }}</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card-soft feature-modern reveal h-100" style="transition-delay:.16s;">
                        <div class="icon-tile mb-3" style="background:#dbeafe;"><i class="fas fa-shield-halved"></i></div>
                        <h6 class="fw-600 mb-2">{{ \App\WebsiteContent::get('web_fitur3_title') }}</h6>
                        <p class="mb-0">{{ \App\WebsiteContent::get('web_fitur3_desc') }}</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card-soft feature-modern reveal h-100" style="transition-delay:.24s;">
                        <div class="icon-tile gold mb-3"><i class="fas fa-globe"></i></div>
                        <h6 class="fw-600 mb-2">{{ \App\WebsiteContent::get('web_fitur4_title') }}</h6>
                        <p class="mb-0">{{ \App\WebsiteContent::get('web_fitur4_desc') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ══════════════════ DATA ASET TERBARU ══════════════════ -->
    @if($asets->count() > 0)
    <section class="py-5 py-lg-6 aset-section">
        <div class="container">
            <div class="text-center mb-5 reveal">
                <span class="section-eyebrow mb-3">Data Terbaru</span>
                <h2 class="section-title mb-2 mt-2">{{ \App\WebsiteContent::get('web_aset_title') }}</h2>
                <p class="section-desc">
                    {{ \App\WebsiteContent::get('web_aset_desc') }}
                </p>
            </div>
            <div class="row g-4">
                @foreach($asets as $aset)
                <div class="col-lg-4 col-md-6">
                    <div class="card-soft aset-modern reveal h-100">
                        <div class="aset-thumb">
                            @if($aset->foto_lokasi)
                            <img src="{{ Storage::url($aset->foto_lokasi) }}" alt="{{ $aset->nama_aset }}">
                            @else
                            <div class="aset-thumb-placeholder">
                                <i class="fas fa-map-marked-alt"></i>
                            </div>
                            @endif
                            <span class="pill aset-status {{ $aset->status === 'aktif' ? 'status-aktif' : 'status-warna' }}">
                                {{ strtoupper(str_replace('_', ' ', $aset->status)) }}
                            </span>
                        </div>
                        <div class="aset-body">
                            <h6 class="fw-600 mb-3 aset-title">{{ $aset->nama_aset }}</h6>
                            <div class="aset-meta">
                                <span><i class="fas fa-map-marker-alt"></i> {{ $aset->kecamatan?->nama ?? '-' }}, {{ $aset->desa?->nama ?? '-' }}</span>
                                <span><i class="fas fa-building"></i> {{ $aset->opd?->nama ?? '-' }}</span>
                                <span><i class="fas fa-ruler-combined"></i> {{ number_format($aset->luas, 2) }} m²</span>
                                <span><i class="fas fa-money-bill"></i> Rp {{ number_format($aset->nilai_aset, 0, ',', '.') }}</span>
                            </div>
                            @if($aset->sertifikasi && in_array($aset->sertifikasi->status, ['terbit_sertifikat', 'selesai']))
                            <span class="pill mt-3" style="background:var(--blue-100); color:var(--blue-700);">
                                <i class="fas fa-check-circle"></i> Bersertifikat
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Tombol menuju halaman kumpulan seluruh aset terpublikasi --}}
            <div class="text-center mt-4 reveal">
                <a href="{{ route('aset.publik') }}" class="btn btn-gold px-4">
                    <i class="fas fa-layer-group me-2"></i>Aset Lainnya
                    @if ($totalAset > $asets->count())
                    <span class="badge bg-light text-dark ms-1">{{ number_format($asetDipublikasikan) }} data</span>
                    @endif
                </a>
            </div>
        </div>
    </section>
    @endif

    <!-- ══════════════════ WORKFLOW SECTION ══════════════════ -->
    <section class="py-5 py-lg-6 bg-white">
        <div class="container">
            <div class="text-center mb-5 reveal">
                <span class="section-eyebrow mb-3">Alur Kerja</span>
                <h2 class="section-title mb-2 mt-2">{{ \App\WebsiteContent::get('web_alur_title') }}</h2>
                <p class="section-desc">
                    {{ \App\WebsiteContent::get('web_alur_desc') }}
                </p>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-11">
                    <div class="workflow-wrap reveal">
                        @php $steps = \App\WebsiteContent::lines('web_alur_steps'); @endphp
                        @foreach($steps as $i => $step)
                        <div class="workflow-item">
                            <div class="workflow-step">{{ $i + 1 }}</div>
                            <div class="workflow-label">{{ $step }}</div>
                        </div>
                        @if($i < count($steps) - 1)
                        <div class="workflow-connector"><i class="fas fa-chevron-right"></i></div>
                        @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ══════════════════ CTA BANNER ══════════════════ -->
    <section class="pb-5">
        <div class="container reveal">
            <div class="cta-banner text-center text-white p-5">
                <h3 class="fw-700 mb-2">Jelajahi Sebaran Aset Tanah</h3>
                <p class="mb-4 mx-auto" style="opacity:.85; max-width:520px; font-size:.95rem;">
                    Lihat langsung lokasi bidang tanah milik Pemerintah Kabupaten Halmahera Barat
                    melalui peta interaktif kami.
                </p>
                <a href="{{ route('map.public') }}" class="btn btn-gold">
                    <i class="fas fa-map-marked-alt me-2"></i>Buka Peta Aset
                </a>
            </div>
        </div>
    </section>

    <!-- ══════════════════ BERITA TERBARU ══════════════════ -->
    {{-- Berita dikelola Super Admin (menu Kelola Website → tab Berita); jumlah tak dibatasi.
         Halaman depan menampilkan 3 berita terbaru — sisanya via tombol ke halaman khusus /berita --}}
    @if ($beritaTerbaru->count() > 0)
    <section class="py-5 py-lg-6 berita-section" id="beritaTerbaru">
        <div class="container">
            <div class="text-center mb-5 reveal">
                <span class="section-eyebrow mb-3">Kabar Terkini</span>
                <h2 class="section-title mb-2 mt-2">{{ \App\WebsiteContent::get('web_berita_title') }}</h2>
                <p class="section-desc">{{ \App\WebsiteContent::get('web_berita_desc') }}</p>
            </div>
            <div class="row g-4 justify-content-center">
                @foreach ($beritaTerbaru as $i => $berita)
                <div class="col-12 col-sm-10 col-md-6 col-lg-4">
                    <article class="card-soft berita-card reveal h-100" style="transition-delay: {{ $i * 0.08 }}s;">
                        <div class="berita-thumb">
                            @if ($berita->gambar)
                            <img src="{{ $berita->gambarUrl() }}" alt="{{ $berita->judul }}" loading="lazy">
                            @else
                            <div class="berita-thumb-placeholder"><i class="fas fa-newspaper"></i></div>
                            @endif
                            <span class="berita-date"><i class="fas fa-calendar-alt me-1"></i>{{ $berita->tanggalIndo() }}</span>
                        </div>
                        <div class="berita-body">
                            <h6 class="fw-600 mb-2 berita-title">{{ $berita->judul }}</h6>
                            <p class="mb-0 berita-excerpt">{{ \Illuminate\Support\Str::limit(strip_tags((string) $berita->deskripsi), 140) }}</p>
                        </div>
                    </article>
                </div>
                @endforeach
            </div>

            {{-- Tombol menuju halaman khusus kumpulan seluruh berita --}}
            <div class="text-center mt-4 reveal">
                <a href="{{ route('berita.publik') }}" class="btn btn-blue px-4">
                    <i class="fas fa-newspaper me-2"></i>Berita Lainnya
                    @if ($totalBerita > 3)
                    <span class="badge bg-light text-dark ms-1">{{ number_format($totalBerita) }} berita</span>
                    @endif
                </a>
            </div>
        </div>
    </section>
    @endif

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
                        {!! nl2br(e(\App\WebsiteContent::get('web_footer_desc'))) !!}
                    </p>
                    <div class="d-flex flex-wrap gap-3 mt-3">
                        @foreach (\App\WebsiteContent::socialLinks() as $link)
                        <a href="{{ $link['url'] }}" class="text-white footer-social" target="_blank" rel="noopener" title="{{ $link['url'] }}">
                            <i class="{{ $link['icon'] }}"></i>
                        </a>
                        @endforeach
                    </div>
                </div>
                <div class="col-lg-4 mb-4">
                    <h5><i class="fas fa-link me-2"></i>Link Terkait</h5>
                    <ul class="list-unstyled" style="font-size:.85rem; line-height:2.2;">
                        <li><a href="{{ \App\WebsiteContent::get('web_footer_link1_url') }}" class="text-white text-decoration-none" target="_blank" rel="noopener"><i class="fas fa-external-link-alt me-2" style="font-size:.7rem;"></i>{{ \App\WebsiteContent::get('web_footer_link1_name') }}</a></li>
                        <li><a href="{{ \App\WebsiteContent::get('web_footer_link2_url') }}" class="text-white text-decoration-none"><i class="fas fa-external-link-alt me-2" style="font-size:.7rem;"></i>{{ \App\WebsiteContent::get('web_footer_link2_name') }}</a></li>
                        <li><a href="{{ \App\WebsiteContent::get('web_footer_link3_url') }}" class="text-white text-decoration-none"><i class="fas fa-external-link-alt me-2" style="font-size:.7rem;"></i>{{ \App\WebsiteContent::get('web_footer_link3_name') }}</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 mb-4">
                    <h5><i class="fas fa-headset me-2"></i>Kontak</h5>
                    <div style="font-size:.85rem; line-height:1.9;">
                        <p class="mb-1 fw-600 text-white">{{ \App\WebsiteContent::get('web_footer_instansi') }}</p>
                        <p class="mb-0"><i class="fas fa-map-marker-alt me-2" style="color:var(--blue-400);"></i>{!! nl2br(e(\App\WebsiteContent::get('web_footer_address'))) !!}</p>
                        <p class="mb-0 mt-2"><i class="fas fa-globe me-2" style="color:var(--blue-400);"></i><a href="{{ \App\WebsiteContent::get('web_footer_website') }}" class="text-white text-decoration-none" target="_blank" rel="noopener">{{ \App\WebsiteContent::get('web_footer_website') }}</a></p>
                    </div>
                </div>
            </div>
            <hr style="border-color:rgba(255,255,255,.1);">
            <div class="text-center" style="font-size:.8rem;">
                &copy; {{ date('Y') }} {{ \App\WebsiteContent::get('web_footer_copyright') }}
            </div>
        </div>
    </footer>

    <!-- Tombol kembali ke atas -->
    {{-- Lightbox galeri kegiatan --}}
    <div class="galeri-lightbox" id="galeriLightbox" aria-hidden="true">
        <button class="galeri-lightbox-close" id="galeriLbClose" aria-label="Tutup"><i class="fas fa-times"></i></button>
        <button class="galeri-lightbox-prev" id="galeriLbPrev" aria-label="Sebelumnya"><i class="fas fa-chevron-left"></i></button>
        <img src="" alt="Foto kegiatan" id="galeriLbImg">
        <button class="galeri-lightbox-next" id="galeriLbNext" aria-label="Berikutnya"><i class="fas fa-chevron-right"></i></button>
        <div class="galeri-lightbox-counter" id="galeriLbCounter"></div>
    </div>

    {{-- Popup video YouTube (embed dimuat saat thumbnail diklik) --}}
    <div class="video-lightbox" id="videoLightbox" aria-hidden="true">
        <button class="video-lightbox-close" id="videoLbClose" aria-label="Tutup"><i class="fas fa-times"></i></button>
        <div class="video-lightbox-frame">
            <iframe id="videoLbFrame" src="" title="Video YouTube JANG BANTAH"
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                allowfullscreen></iframe>
        </div>
    </div>

    <button class="back-to-top" id="backToTop" aria-label="Kembali ke atas">
        <i class="fas fa-arrow-up"></i>
    </button>
@endsection

@push('styles')
<style>
    /* ════════ HERO BACKGROUND ════════ */
    .hero-video {
        position: relative;
        min-height: 100vh;
        overflow: hidden;
        background: linear-gradient(135deg, #002a5c 0%, #004d99 50%, #0062b8 100%);
        display: flex;
        align-items: center;
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
    /* Background gambar statis (mode "Gambar" dari Kelola Website) */
    .hero-image-bg {
        position: absolute;
        top: 0; left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
    }
    /* ── Slider hero (multi-gambar, crossfade otomatis) ── */
    .hero-slider {
        position: absolute;
        inset: 0;
    }
    .hero-slide {
        position: absolute;
        inset: 0;
        opacity: 0;
        transform: scale(1.05);
        transition: opacity 1.4s ease, transform 6s ease;
    }
    .hero-slide.active {
        opacity: 1;
        transform: scale(1);
    }
    .hero-slider-nav {
        position: absolute;
        bottom: 26px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 10px;
        z-index: 3;
    }
    .hero-slider-dot {
        width: 11px; height: 11px;
        border-radius: 999px;
        border: 2px solid rgba(255,255,255,.85);
        background: rgba(255,255,255,.35);
        padding: 0;
        cursor: pointer;
        transition: all .3s ease;
    }
    .hero-slider-dot:hover {
        background: rgba(255,255,255,.7);
    }
    .hero-slider-dot.active {
        background: #fff;
        width: 30px;
    }
    .slider-overlay {
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        /* Overlay: gelap di kiri (teks terbaca), transparan di kanan (video terlihat) */
        background: linear-gradient(
            90deg,
            rgba(0,42,92,.85) 0%,
            rgba(0,42,92,.62) 30%,
            rgba(0,77,153,.34) 55%,
            rgba(0,98,184,.14) 75%,
            rgba(0,98,184,.10) 100%
        );
        z-index: 1;
    }
    /* Layer tambahan gelap halus atas-bawah */
    .slider-overlay::after {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: linear-gradient(
            180deg,
            rgba(0,20,50,.38) 0%,
            rgba(0,0,0,0) 16%,
            rgba(0,0,0,0) 68%,
            rgba(0,20,50,.5) 100%
        );
    }

    /* ════════ DEKORASI HERO (blob glow + dot grid) ════════ */
    .hero-blob {
        position: absolute;
        border-radius: 50%;
        filter: blur(70px);
        z-index: 1;
        pointer-events: none;
    }
    .hero-blob-1 {
        width: 420px; height: 420px;
        top: -120px; right: -100px;
        background: radial-gradient(circle, rgba(77,163,232,.5), transparent 65%);
        animation: blobFloat 9s ease-in-out infinite;
    }
    .hero-blob-2 {
        width: 360px; height: 360px;
        bottom: -80px; left: -120px;
        background: radial-gradient(circle, rgba(240,192,64,.28), transparent 65%);
        animation: blobFloat 11s ease-in-out infinite reverse;
    }
    @keyframes blobFloat {
        0%, 100% { transform: translate(0, 0) scale(1); }
        50%      { transform: translate(24px, -28px) scale(1.08); }
    }
    .hero-dots {
        position: absolute;
        top: 110px; right: 60px;
        width: 220px; height: 140px;
        background-image: radial-gradient(rgba(255,255,255,.35) 1.5px, transparent 1.5px);
        background-size: 20px 20px;
        z-index: 1;
        pointer-events: none;
        mask-image: linear-gradient(200deg, rgba(0,0,0,.9), transparent 75%);
        -webkit-mask-image: linear-gradient(200deg, rgba(0,0,0,.9), transparent 75%);
    }
    @keyframes floatChip {
        0%, 100% { transform: translateY(0); }
        50%      { transform: translateY(-12px); }
    }
    .animate-float { animation: floatChip 5s ease-in-out infinite; }

    /* ════════ STRIP STATISTIK SOLID (di luar hero) ════════ */
    .stat-strip {
        background: linear-gradient(120deg, var(--blue-900) 0%, var(--blue-700) 55%, var(--blue-600) 100%);
        border-radius: 24px;
        box-shadow: 0 20px 46px rgba(2,20,51,.28);
        padding: 1.6rem 1rem;
        margin-top: 2.5rem;             /* jarak dari hero, tidak nempel */
        position: relative;
        z-index: 5;
    }
    .hero-strip-item { padding: .5rem .4rem; position: relative; }
    .hero-strip-item .strip-label {
        font-size: .72rem;
        opacity: .8;
        letter-spacing: .4px;
        line-height: 1.3;
        display: block;
    }
    .strip-icon {
        width: 48px; height: 48px;
        border-radius: 14px;
        display: inline-flex; align-items: center; justify-content: center;
        background: rgba(255,255,255,.14);
        color: var(--gold);
        font-size: 1.1rem;
        flex: 0 0 auto;
    }
    .hero-strip-item + .hero-strip-item::before {
        content: '';
        position: absolute;
        left: 0; top: 20%;
        height: 60%; width: 1px;
        background: rgba(255,255,255,.18);
    }
    @media (max-width: 767px) {
        .stat-strip { margin-top: 1.6rem; border-radius: 20px; }
        .hero-strip-item + .hero-strip-item::before { display: none; }
        .hero-strip-item { border-top: 1px solid rgba(255,255,255,.14); }
        .hero-strip-item:nth-child(-n+2) { border-top: none; }
        .hero-strip-item .fw-700 { font-size: 1.05rem !important; }
    }

    /* ════════ STATS SECTION ════════ */
    .stats-section {
        position: relative;
        z-index: 3;
        background: linear-gradient(180deg, var(--blue-50) 0%, #fff 100%);
        padding: 0 0 3.2rem;
    }
    @media (min-width: 768px) {
        .stat-strip { margin-top: 3rem; }
    }
    .stat-modern {
        padding: 30px 24px;
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
        position: relative;
        overflow: hidden;
    }
    .stat-modern::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 4px;
        border-radius: 4px 4px 0 0;
        background: linear-gradient(90deg, var(--blue-500), var(--blue-700));
        opacity: 0;
        transition: opacity .35s ease;
    }
    .stat-modern:hover::before { opacity: 1; }
    .stat-modern .number {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--blue-900);
        line-height: 1.2;
        font-variant-numeric: tabular-nums;
    }
    .stat-modern .label { font-size: .84rem; color: #64748b; font-weight: 500; }

    /* ════════ SECTION DESC ════════ */
    .section-desc {
        color: #64748b;
        max-width: 620px;
        margin: 0 auto;
        font-size: .95rem;
        line-height: 1.7;
    }

    /* ════════ GALERI KEGIATAN ══════════ */
    .galeri-section { background: linear-gradient(180deg, var(--blue-50) 0%, #fff 100%); }
    .galeri-item {
        position: relative;
        display: block;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 6px 18px rgba(0,42,92,.08);
        aspect-ratio: 4 / 3;
    }
    .galeri-item img {
        width: 100%; height: 100%;
        object-fit: cover;
        display: block;
        transition: transform .55s ease;
    }
    .galeri-item:hover img { transform: scale(1.08); }
    .galeri-zoom {
        position: absolute; inset: 0;
        display: flex; align-items: center; justify-content: center;
        background: rgba(0,42,92,0);
        color: transparent;
        font-size: 1.4rem;
        transition: all .3s ease;
    }
    .galeri-item:hover .galeri-zoom {
        background: rgba(0,42,92,.45);
        color: #fff;
    }

    /* ── Lightbox galeri ── */
    .galeri-lightbox {
        position: fixed; inset: 0;
        background: rgba(2,12,27,.92);
        display: none;
        align-items: center; justify-content: center;
        z-index: 2000;
        padding: 24px;
    }
    .galeri-lightbox.open { display: flex; }
    .galeri-lightbox img {
        max-width: min(1100px, 92vw);
        max-height: 82vh;
        border-radius: 12px;
        box-shadow: 0 24px 80px rgba(0,0,0,.6);
    }
    .galeri-lightbox-close,
    .galeri-lightbox-prev,
    .galeri-lightbox-next {
        position: absolute;
        background: rgba(255,255,255,.12);
        border: 1px solid rgba(255,255,255,.25);
        color: #fff;
        width: 46px; height: 46px;
        border-radius: 999px;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer;
        transition: background .25s ease;
        font-size: 1.05rem;
    }
    .galeri-lightbox-close:hover,
    .galeri-lightbox-prev:hover,
    .galeri-lightbox-next:hover { background: rgba(255,255,255,.3); }
    .galeri-lightbox-close { top: 22px; right: 22px; }
    .galeri-lightbox-prev  { left: 22px; top: 50%; transform: translateY(-50%); }
    .galeri-lightbox-next  { right: 22px; top: 50%; transform: translateY(-50%); }
    .galeri-lightbox-counter {
        position: absolute;
        bottom: 22px; left: 50%; transform: translateX(-50%);
        color: rgba(255,255,255,.8);
        font-size: .85rem;
        letter-spacing: 1px;
    }
    @media (max-width: 576px) {
        .galeri-lightbox-prev { left: 10px; }
        .galeri-lightbox-next { right: 10px; }
    }

    /* ════════ VIDEO YOUTUBE (thumbnail + popup) ════════ */
    .video-section { background: linear-gradient(180deg, #fff 0%, var(--blue-50) 100%); }
    .video-item {
        position: relative;
        display: block;
        width: 100%;
        border: 0;
        padding: 0;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 6px 18px rgba(0,42,92,.08);
        aspect-ratio: 16 / 9;
        cursor: pointer;
        background: var(--blue-100);
    }
    .video-item img {
        width: 100%; height: 100%;
        object-fit: cover;
        display: block;
        transition: transform .55s ease;
    }
    .video-item:hover img { transform: scale(1.06); }
    .video-play {
        position: absolute;
        top: 50%; left: 50%;
        transform: translate(-50%, -50%);
        width: 64px; height: 64px;
        border-radius: 999px;
        background: rgba(255,0,0,.88);
        color: #fff;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.35rem;
        padding-left: 4px;
        box-shadow: 0 10px 30px rgba(0,0,0,.35);
        transition: transform .3s ease, background .3s ease, box-shadow .3s ease;
    }
    .video-item:hover .video-play {
        transform: translate(-50%, -50%) scale(1.14);
        background: #e60000;
        box-shadow: 0 14px 36px rgba(230,0,0,.45);
    }
    .video-badge {
        position: absolute;
        left: 12px; bottom: 12px;
        display: inline-flex; align-items: center;
        padding: .28rem .7rem;
        border-radius: 999px;
        background: rgba(0,0,0,.55);
        color: #fff;
        font-size: .65rem;
        font-weight: 600;
        letter-spacing: .5px;
        backdrop-filter: blur(6px);
        -webkit-backdrop-filter: blur(6px);
    }
    /* Kartu link channel YouTube */
    .video-channel {
        aspect-ratio: auto;
        min-height: 100%;
        padding: 22px 22px;
        align-items: center;
        text-decoration: none;
        background: linear-gradient(135deg, var(--blue-900) 0%, var(--blue-700) 55%, var(--blue-600) 100%);
        cursor: pointer;
    }
    .video-channel:hover { text-decoration: none; }
    .video-channel-icon {
        width: 62px; height: 62px;
        border-radius: 16px;
        flex: 0 0 auto;
        display: flex; align-items: center; justify-content: center;
        background: rgba(255,255,255,.13);
        color: #ff4d4d;
        font-size: 2rem;
    }
    .video-channel-title { color: #fff; font-size: .98rem; }
    .video-channel-sub { color: rgba(255,255,255,.72); font-size: .78rem; line-height: 1.5; }
    .video-channel-arrow { color: rgba(255,255,255,.6); font-size: .9rem; transition: transform .3s ease, color .3s ease; }
    .video-channel:hover .video-channel-arrow { transform: translate(3px, -3px); color: var(--gold); }

    /* ── Popup video YouTube ── */
    .video-lightbox {
        position: fixed; inset: 0;
        background: rgba(2,12,27,.94);
        display: none;
        align-items: center; justify-content: center;
        z-index: 2100;
        padding: 24px;
    }
    .video-lightbox.open { display: flex; }
    .video-lightbox-frame {
        width: min(1000px, 94vw);
        aspect-ratio: 16 / 9;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 30px 90px rgba(0,0,0,.65);
        background: #000;
    }
    .video-lightbox-frame iframe {
        width: 100%; height: 100%;
        border: 0;
        display: block;
    }
    .video-lightbox-close {
        position: absolute;
        top: 22px; right: 22px;
        background: rgba(255,255,255,.12);
        border: 1px solid rgba(255,255,255,.25);
        color: #fff;
        width: 46px; height: 46px;
        border-radius: 999px;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer;
        transition: background .25s ease;
        font-size: 1.05rem;
    }
    .video-lightbox-close:hover { background: rgba(255,255,255,.3); }
    @media (max-width: 576px) {
        .video-lightbox { padding: 12px; }
        .video-lightbox-close { top: 12px; right: 12px; width: 40px; height: 40px; }
        .video-play { width: 52px; height: 52px; font-size: 1.1rem; }
    }

    /* ════════ FITUR CARD ════════ */
    .feature-modern { padding: 34px 26px; }
    .feature-modern h6 { color: var(--blue-900); font-size: 1rem; }
    .feature-modern p { font-size: .85rem; color: #64748b; line-height: 1.7; }

    /* ════════ ASET CARD ════════ */
    .aset-section { background: linear-gradient(180deg, #fff 0%, var(--blue-50) 45%, var(--blue-50) 100%); }
    .aset-modern { overflow: hidden; display: flex; flex-direction: column; }
    .aset-thumb { position: relative; overflow: hidden; }
    .aset-thumb img {
        width: 100%; height: 210px;
        object-fit: cover;
        transition: transform .55s ease;
        display: block;
    }
    .aset-modern:hover .aset-thumb img { transform: scale(1.08); }
    .aset-thumb-placeholder {
        height: 210px;
        display: flex; align-items: center; justify-content: center;
        background: linear-gradient(135deg, var(--blue-100), var(--blue-200));
    }
    .aset-thumb-placeholder i { font-size: 3.4rem; color: var(--blue-400); opacity: .55; }
    .aset-status {
        position: absolute;
        top: 14px; left: 14px;
        backdrop-filter: blur(6px);
    }
    .status-aktif   { background: rgba(39,174,96,.92); color: #fff; }
    .status-warna   { background: rgba(240,192,64,.94); color: #4a3505; }
    .aset-body { padding: 22px 24px 26px; display: flex; flex-direction: column; flex: 1; }
    .aset-title { color: var(--blue-800); font-size: .98rem; }
    .aset-meta { display: flex; flex-direction: column; gap: .45rem; font-size: .8rem; color: #64748b; }
    .aset-meta i { color: var(--blue-500); width: 16px; }

    /* ════════ WORKFLOW ════════ */
    .workflow-wrap {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        align-items: flex-start;
        gap: 14px 10px;
        background: var(--blue-50);
        border: 1px dashed rgba(0,98,184,.3);
        border-radius: 26px;
        padding: 38px 24px;
    }
    .workflow-item { text-align: center; width: 108px; }
    .workflow-step {
        width: 54px; height: 54px;
        border-radius: 18px;
        background: linear-gradient(135deg, var(--blue-700), var(--blue-500));
        color: #fff;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.05rem; font-weight: 700;
        margin: 0 auto 10px;
        box-shadow: 0 10px 24px rgba(0,77,153,.3);
        transition: all .3s ease;
        position: relative;
    }
    .workflow-step::after {
        content: '';
        position: absolute;
        inset: -6px;
        border-radius: 22px;
        border: 2px solid rgba(0,98,184,.18);
        transition: all .3s ease;
    }
    .workflow-item:hover .workflow-step {
        transform: translateY(-5px) scale(1.06);
        box-shadow: 0 14px 30px rgba(0,77,153,.4);
    }
    .workflow-item:hover .workflow-step::after { inset: -8px; border-color: rgba(0,98,184,.35); }
    .workflow-label { font-size: .7rem; font-weight: 600; color: var(--blue-800); line-height: 1.5; }
    .workflow-connector {
        align-self: center;
        margin-top: -26px;
        color: var(--blue-400);
        font-size: .8rem;
    }

    /* ════════ BERITA CARD ════════ */
    .berita-section { background: linear-gradient(180deg, #fff 0%, var(--blue-50) 55%, var(--blue-50) 100%); }
    .berita-card { overflow: hidden; display: flex; flex-direction: column; }
    .berita-thumb { position: relative; overflow: hidden; }
    .berita-thumb img {
        width: 100%; height: 200px;
        object-fit: cover;
        transition: transform .55s ease;
        display: block;
    }
    .berita-card:hover .berita-thumb img { transform: scale(1.08); }
    .berita-thumb-placeholder {
        height: 200px;
        display: flex; align-items: center; justify-content: center;
        background: linear-gradient(135deg, var(--blue-100), var(--blue-200));
    }
    .berita-thumb-placeholder i { font-size: 3rem; color: var(--blue-400); opacity: .55; }
    .berita-date {
        position: absolute;
        left: 12px; bottom: 12px;
        display: inline-flex; align-items: center;
        padding: .3rem .75rem;
        border-radius: 999px;
        background: rgba(0,42,92,.72);
        color: #fff;
        font-size: .68rem;
        font-weight: 600;
        letter-spacing: .4px;
        backdrop-filter: blur(6px);
        -webkit-backdrop-filter: blur(6px);
    }
    .berita-body { padding: 22px 24px 26px; display: flex; flex-direction: column; flex: 1; }
    .berita-title { color: var(--blue-800); font-size: .98rem; }
    .berita-excerpt { font-size: .84rem; color: #64748b; line-height: 1.7; }

    /* ════════ CTA BANNER ════════ */
    .cta-banner {
        border-radius: 28px;
        background: linear-gradient(120deg, var(--blue-900) 0%, var(--blue-700) 55%, var(--blue-600) 100%);
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 48px rgba(2,20,51,.22);
    }
    .cta-banner::before {
        content: '';
        position: absolute;
        top: -90px; right: -70px;
        width: 280px; height: 280px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(77,163,232,.35), transparent 65%);
        filter: blur(10px);
    }
    .cta-banner::after {
        content: '';
        position: absolute;
        bottom: -110px; left: -80px;
        width: 300px; height: 300px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(240,192,64,.22), transparent 65%);
        filter: blur(10px);
    }
    .cta-banner > * { position: relative; z-index: 1; }

    /* ════════ SPACING HELPER ════════ */
    .py-lg-6 { padding-top: 4.5rem !important; padding-bottom: 4.5rem !important; }
    @media (min-width: 992px) {
        .py-lg-6 { padding-top: 5.2rem !important; padding-bottom: 5.2rem !important; }
    }

    /* ════════ FOOTER SOCIAL ════════ */
    .footer-social {
        width: 38px; height: 38px;
        border-radius: 12px;
        background: rgba(255,255,255,.1);
        display: flex; align-items: center; justify-content: center;
        font-size: .85rem;
        transition: all .3s ease;
    }
    .footer-social:hover {
        background: var(--blue-500);
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,.25);
    }

    /* ════════ MOBILE RESPONSIVE ════════ */
    .hero-row { min-height: calc(100vh - 140px); }

    @media (max-width: 991px) {
        .hero-video { min-height: auto; }
        .hero-row { min-height: auto; align-items: flex-start !important; }
        .hero-content { padding-top: 100px; padding-bottom: 56px; }
        .slider-overlay {
            background: linear-gradient(
                180deg,
                rgba(0,42,92,.78) 0%,
                rgba(0,42,92,.45) 28%,
                rgba(0,42,92,.45) 72%,
                rgba(0,42,92,.72) 100%
            );
        }
        .slider-overlay::after { display: none; }
        .hero-dots { display: none; }
        .hero-content h1,
        .hero-content .lead,
        .hero-content p {
            text-shadow: 0 2px 10px rgba(0,0,0,.55), 0 0 2px rgba(0,0,0,.85) !important;
        }
    }

    @media (max-width: 575px) {
        .hero-content { padding-top: 92px; padding-bottom: 48px; }
        .hero-content h1 { font-size: 2rem; line-height: 1.18; }
        .hero-content .lead { font-size: 1rem; }
        .hero-content p { font-size: .88rem; }
        .hero-content .btn { padding: 9px 18px !important; font-size: .82rem !important; }
        .workflow-item { width: 42%; }
        .workflow-connector { display: none; }
        .stat-modern .number { font-size: 1.4rem; }
    }

    @media (max-width: 360px) {
        .hero-content h1 { font-size: 1.7rem; }
        .hero-content .btn { padding: 8px 14px !important; font-size: .78rem !important; }
    }
</style>
@endpush

@push('scripts')
<script>
    /* ── Scroll Reveal ── */
    function revealOnScroll() {
        document.querySelectorAll('.reveal').forEach(el => {
            if (el.getBoundingClientRect().top < window.innerHeight - 60) {
                el.classList.add('active');
            }
        });
    }
    window.addEventListener('scroll', revealOnScroll, { passive: true });
    window.addEventListener('load', revealOnScroll);

    /* ── Navbar: shadow saat scroll ── */
    const navSd = document.querySelector('.navbar-sd');
    window.addEventListener('scroll', () => {
        if (navSd) navSd.classList.toggle('scrolled', window.scrollY > 24);
    }, { passive: true });

    /* ── Count-up angka statistik ── */
    function animateCount(el) {
        const target   = parseInt(el.dataset.count, 10) || 0;
        const prefix   = el.dataset.prefix || '';
        const duration = 1600;
        const start    = performance.now();
        const fmt      = new Intl.NumberFormat('id-ID');
        function tick(now) {
            const p = Math.min((now - start) / duration, 1);
            const eased = 1 - Math.pow(1 - p, 3); /* ease-out cubic */
            el.textContent = prefix + fmt.format(Math.round(target * eased));
            if (p < 1) requestAnimationFrame(tick);
        }
        requestAnimationFrame(tick);
    }
    const countObserver = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateCount(entry.target);
                countObserver.unobserve(entry.target);
            }
        });
    }, { threshold: .4 });
    document.querySelectorAll('[data-count]').forEach(el => countObserver.observe(el));

    /* ── Tombol kembali ke atas ── */
    const backToTop = document.getElementById('backToTop');
    window.addEventListener('scroll', () => {
        backToTop && backToTop.classList.toggle('show', window.scrollY > 480);
    }, { passive: true });
    backToTop && backToTop.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    /* ── Lightbox galeri kegiatan ── */
    (function galeriLightbox() {
        const lb    = document.getElementById('galeriLightbox');
        if (!lb) return;

        const items = Array.from(document.querySelectorAll('.galeri-item'));
        const img   = document.getElementById('galeriLbImg');
        const ctr   = document.getElementById('galeriLbCounter');
        let index   = 0;

        function show(i) {
            if (!items.length) return;
            index = (i + items.length) % items.length;
            img.src = items[index].getAttribute('href');
            ctr.textContent = (index + 1) + ' / ' + items.length;
        }
        function open(i) { show(i); lb.classList.add('open'); lb.setAttribute('aria-hidden', 'false'); document.body.style.overflow = 'hidden'; }
        function close() { lb.classList.remove('open'); lb.setAttribute('aria-hidden', 'true'); document.body.style.overflow = ''; }

        items.forEach(item => item.addEventListener('click', e => {
            e.preventDefault();
            open(parseInt(item.dataset.galeriIndex, 10));
        }));

        document.getElementById('galeriLbClose').addEventListener('click', close);
        document.getElementById('galeriLbPrev').addEventListener('click', () => show(index - 1));
        document.getElementById('galeriLbNext').addEventListener('click', () => show(index + 1));
        lb.addEventListener('click', e => { if (e.target === lb) close(); });
        document.addEventListener('keydown', e => {
            if (!lb.classList.contains('open')) return;
            if (e.key === 'Escape') close();
            if (e.key === 'ArrowRight') show(index + 1);
            if (e.key === 'ArrowLeft') show(index - 1);
        });
    })();

    /* ── Popup video YouTube (embed dimuat hanya saat diklik) ── */
    (function videoLightbox() {
        const lb    = document.getElementById('videoLightbox');
        if (!lb) return;

        const frame = document.getElementById('videoLbFrame');
        const items = Array.from(document.querySelectorAll('.video-item[data-video-id]'));

        function open(id) {
            frame.src = 'https://www.youtube.com/embed/' + id + '?autoplay=1&rel=0&modestbranding=1';
            lb.classList.add('open');
            lb.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
        }
        function close() {
            lb.classList.remove('open');
            lb.setAttribute('aria-hidden', 'true');
            frame.src = ''; /* kosongkan src agar video berhenti */
            document.body.style.overflow = '';
        }

        items.forEach(item => {
            item.addEventListener('click', () => open(item.dataset.videoId));
            item.addEventListener('keydown', e => {
                if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); open(item.dataset.videoId); }
            });
        });

        document.getElementById('videoLbClose').addEventListener('click', close);
        lb.addEventListener('click', e => { if (e.target === lb) close(); });
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape' && lb.classList.contains('open')) close();
        });
    })();

    /* ── Slider hero multi-gambar (crossfade + ken-burns otomatis) ── */
    (function heroSlider() {
        const slider = document.getElementById('heroSlider');
        if (!slider) return;

        const slides = slider.querySelectorAll('.hero-slide');
        const dots   = slider.querySelectorAll('.hero-slider-dot');
        if (slides.length < 2) return;

        let current = 0;
        let timer   = null;
        const INTERVAL = 6000; /* 6 detik per slide */

        function goTo(index) {
            slides[current].classList.remove('active');
            dots[current] && dots[current].classList.remove('active');
            current = (index + slides.length) % slides.length;
            slides[current].classList.add('active');
            dots[current] && dots[current].classList.add('active');
        }

        function play()  { timer = setInterval(() => goTo(current + 1), INTERVAL); }
        function stop()  { clearInterval(timer); }

        /* Klik dot: langsung pindah slide & reset timer */
        dots.forEach(dot => dot.addEventListener('click', () => {
            stop();
            goTo(parseInt(dot.dataset.index, 10));
            play();
        }));

        /* Jeda saat kursor di atas hero agar tidak mengganggu dibaca */
        slider.addEventListener('mouseenter', stop);
        slider.addEventListener('mouseleave', play);

        /* Geser slide via keyboard (aksesibilitas) */
        document.addEventListener('keydown', e => {
            if (e.key === 'ArrowRight') { stop(); goTo(current + 1); play(); }
            if (e.key === 'ArrowLeft')  { stop(); goTo(current - 1); play(); }
        });

        play();
    })();

    /* Jalankan reveal awal */
    revealOnScroll();
</script>
@endpush
