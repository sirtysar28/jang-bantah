@extends('layouts.app')
@section('title', 'Data Aset — JANG BANTAH')

@push('styles')
<style>
    /* ════════ HERO KECIL ══════════ */
    .page-hero {
        background:
            radial-gradient(ellipse at 80% 20%, rgba(77,163,232,.25), transparent 55%),
            linear-gradient(120deg, var(--blue-900) 0%, var(--blue-700) 60%, var(--blue-600) 100%);
        padding: 3.2rem 0 3.6rem;
        position: relative;
        overflow: hidden;
    }
    .page-hero::after {
        content: '';
        position: absolute; inset: 0;
        background-image: radial-gradient(rgba(255,255,255,.14) 1px, transparent 1px);
        background-size: 22px 22px;
        opacity: .35;
        pointer-events: none;
    }
    .page-hero-title { color: #fff; font-weight: 800; font-size: clamp(1.6rem, 3.4vw, 2.3rem); }
    .page-hero-sub { color: rgba(255,255,255,.78); font-size: .95rem; max-width: 640px; margin: .6rem auto 0; }
    .page-hero-stats { color: #fff; }
    .page-hero-stats .num { font-weight: 800; font-size: 1.25rem; }
    .page-hero-stats .lbl { font-size: .72rem; opacity: .75; letter-spacing: .4px; }

    /* ════════ KARTU ASET (sama seperti homepage) ══════════ */
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
        position: absolute; top: 14px; right: 14px;
    }
    .aset-body { padding: 22px 24px 26px; display: flex; flex-direction: column; flex: 1; }
    .aset-title {
        color: var(--blue-900);
        font-size: .98rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 2.6em;
    }
    .aset-meta { display: flex; flex-direction: column; gap: .45rem; font-size: .8rem; color: #64748b; }
    .aset-meta i { color: var(--blue-500); width: 16px; }
    .status-aktif { background: #e5f8ed; color: #157347; }
    .status-warna { background: #fff3cd; color: #99740f; }

    /* ════════ FOOTER SOCIAL ══════════ */
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

    /* ════════ FILTER BAR ══════════ */
    .filter-bar {
        background: #fff;
        border-radius: 18px;
        box-shadow: 0 10px 34px rgba(0,42,92,.09);
        padding: 18px 20px;
        margin-top: -46px;
        position: relative;
        z-index: 5;
    }
</style>
@endpush

@section('content')
<!-- ══════════════════ NAVBAR ══════════════════ -->
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
                <li class="nav-item"><a class="nav-link nav-link-sd active" href="{{ route('aset.publik') }}"><i class="fas fa-layer-group me-1"></i>Data Aset</a></li>
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

<!-- ══════════════════ HERO KECIL ══════════════════ -->
<section class="page-hero text-center" style="padding-top:7rem;">
    <div class="container position-relative" style="z-index:2;">
        <span class="section-eyebrow mb-3" style="background:rgba(255,255,255,.14); color:#fff;">Data Publik</span>
        <h1 class="page-hero-title">Data Aset Tanah Publik</h1>
        <p class="page-hero-sub">
            Seluruh aset tanah Pemerintah Kabupaten Halmahera Barat yang telah
            terverifikasi dan dipublikasikan melalui sistem JANG BANTAH.
        </p>
        <div class="page-hero-stats d-flex justify-content-center flex-wrap gap-4 gap-md-5 mt-4">
            <div><div class="num">{{ number_format($totalAsetPublik) }}</div><div class="lbl">BIDANG TANAH</div></div>
            <div><div class="num">{{ number_format($totalLuasPublik / 10000, 1) }} Ha</div><div class="lbl">TOTAL LUAS</div></div>
            <div><div class="num">Rp {{ number_format($totalNilaiPublik, 0, ',', '.') }}</div><div class="lbl">NILAI ASET</div></div>
            <div><div class="num">{{ number_format($sudahSertifikatPublik) }}</div><div class="lbl">BERSERTIFIKAT</div></div>
        </div>
    </div>
</section>

<!-- ══════════════════ FILTER ══════════════════ -->
<div class="container">
    <div class="filter-bar">
        <form method="GET" class="row g-2 align-items-center">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" name="q" class="form-control border-start-0" placeholder="Cari nama aset, nomor register, atau alamat..." value="{{ request('q') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="kecamatan_id" class="form-select">
                    <option value="">Semua Kecamatan</option>
                    @foreach ($kecamatans as $k)
                    <option value="{{ $k->id }}" {{ request('kecamatan_id') == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="sengketa" {{ request('status') === 'sengketa' ? 'selected' : '' }}>Sengketa</option>
                    <option value="dipinjamkan" {{ request('status') === 'dipinjamkan' ? 'selected' : '' }}>Dipinjamkan</option>
                    <option value="disediakan" {{ request('status') === 'disediakan' ? 'selected' : '' }}>Disediakan</option>
                </select>
            </div>
            <div class="col-md-2 d-grid d-md-flex">
                <button type="submit" class="btn btn-primary flex-fill"><i class="fas fa-filter me-1"></i>Tampilkan</button>
            </div>
        </form>
    </div>
</div>

<!-- ══════════════════ GRID ASET ══════════════════ -->
<section class="py-5 aset-section">
    <div class="container">
        @if ($asets->count() > 0)
        <p class="text-muted mb-4" style="font-size:.85rem;">
            Menampilkan <b>{{ $asets->firstItem() }}–{{ $asets->lastItem() }}</b> dari <b>{{ number_format($asets->total()) }}</b> aset terpublikasi
        </p>
        <div class="row g-4">
            @foreach ($asets as $aset)
            <div class="col-lg-4 col-md-6">
                <div class="card-soft aset-modern h-100">
                    <div class="aset-thumb">
                        @if ($aset->foto_lokasi)
                        <img src="{{ Storage::url($aset->foto_lokasi) }}" alt="{{ $aset->nama_aset }}" loading="lazy">
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
                            <span><i class="fas fa-map-marker-alt"></i> {{ $aset->kecamatan?->nama ?? '-' }}{{ $aset->desa ? ', ' . $aset->desa->nama : '' }}</span>
                            <span><i class="fas fa-building"></i> {{ $aset->opd?->nama ?? '-' }}</span>
                            <span><i class="fas fa-ruler-combined"></i> {{ number_format($aset->luas, 2) }} m²</span>
                            <span><i class="fas fa-money-bill"></i> Rp {{ number_format($aset->nilai_aset, 0, ',', '.') }}</span>
                            <span><i class="fas fa-hashtag"></i> {{ $aset->nomor_register }}</span>
                        </div>
                        @if ($aset->sertifikasi && in_array($aset->sertifikasi->status, ['terbit_sertifikat', 'selesai']))
                        <span class="pill mt-3 align-self-start" style="background:var(--blue-100); color:var(--blue-700);">
                            <i class="fas fa-check-circle"></i> Bersertifikat
                        </span>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $asets->links('pagination::bootstrap-5') }}
        </div>
        @else
        <div class="text-center py-5">
            <i class="fas fa-search text-muted mb-3" style="font-size:3rem; opacity:.3;"></i>
            <h5 class="fw-600">Tidak ada aset yang cocok</h5>
            <p class="text-muted" style="font-size:.9rem;">Coba ubah kata kunci pencarian atau reset filter.</p>
            <a href="{{ route('aset.publik') }}" class="btn btn-outline-primary btn-sm"><i class="fas fa-redo me-1"></i>Reset Filter</a>
        </div>
        @endif
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

<script>
    /* Navbar: shadow saat scroll */
    const navSd = document.querySelector('.navbar-sd');
    window.addEventListener('scroll', () => {
        if (navSd) navSd.classList.toggle('scrolled', window.scrollY > 24);
    }, { passive: true });
    document.addEventListener('DOMContentLoaded', () => navSd && navSd.classList.toggle('scrolled', window.scrollY > 24));
</script>
@endsection
