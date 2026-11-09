@extends('layouts.app')
@section('title', 'Berita — JANG BANTAH')

@push('styles')
<style>
    /* ══════════ HERO KECIL ══════════ */
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

    /* ══════════ KARTU BERITA ══════════ */
    .berita-section { background: linear-gradient(180deg, var(--blue-50) 0%, #fff 45%, var(--blue-50) 100%); }
    .berita-card {
        background: #fff;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 6px 18px rgba(0,42,92,.08);
        display: flex; flex-direction: column;
        transition: transform .3s ease, box-shadow .3s ease;
    }
    .berita-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 16px 38px rgba(0,42,92,.14);
    }
    .berita-thumb { position: relative; overflow: hidden; }
    .berita-thumb img {
        width: 100%; height: 210px;
        object-fit: cover;
        display: block;
        transition: transform .55s ease;
    }
    .berita-card:hover .berita-thumb img { transform: scale(1.06); }
    .berita-thumb-placeholder {
        height: 210px;
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
    .berita-title { color: var(--blue-800); font-size: 1rem; font-weight: 600; line-height: 1.45; }
    .berita-excerpt { font-size: .86rem; color: #64748b; line-height: 1.75; }

    /* ══════════ FOOTER SOCIAL ══════════ */
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

    /* ── Scroll reveal ── */
    .reveal { opacity: 0; transform: translateY(34px); transition: opacity .7s ease, transform .7s ease; }
    .reveal.active { opacity: 1; transform: translateY(0); }
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
                <li class="nav-item"><a class="nav-link nav-link-sd" href="{{ route('aset.publik') }}"><i class="fas fa-layer-group me-1"></i>Data Aset</a></li>
                <li class="nav-item"><a class="nav-link nav-link-sd active" href="{{ route('berita.publik') }}"><i class="fas fa-newspaper me-1"></i>Berita</a></li>
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
        <span class="section-eyebrow mb-3" style="background:rgba(255,255,255,.14); color:#fff;">Kabar Terkini</span>
        <h1 class="page-hero-title">{{ \App\WebsiteContent::get('web_berita_title') }}</h1>
        <p class="page-hero-sub">{{ \App\WebsiteContent::get('web_berita_desc') }}</p>
        <div class="page-hero-stats d-flex justify-content-center flex-wrap gap-4 gap-md-5 mt-4">
            <div><div class="num">{{ number_format($beritas->total()) }}</div><div class="lbl">BERITA</div></div>
            <div><div class="num">{{ $beritas->currentPage() }}</div><div class="lbl">DARI {{ $beritas->lastPage() }} HALAMAN</div></div>
            @if ($beritas->isNotEmpty())
            <div class="align-self-center">
                <span class="btn btn-gold btn-sm px-4" style="cursor:default;">
                    <i class="fas fa-calendar-alt me-2"></i>Terbaru: {{ $beritas->first()->tanggalIndo() }}
                </span>
            </div>
            @endif
        </div>
    </div>
</section>

<!-- ══════════════════ GRID BERITA ══════════════════ -->
<section class="py-5 berita-section">
    <div class="container">
        @if ($beritas->isNotEmpty())
        <p class="text-muted mb-4" style="font-size:.85rem;">
            Menampilkan <b>{{ $beritas->count() }}</b> dari <b>{{ number_format($beritas->total()) }}</b> berita — berita terbaru tampil paling depan.
        </p>
        <div class="row g-3 g-md-4">
            @foreach ($beritas as $i => $berita)
            <div class="col-12 col-sm-10 col-md-6 col-lg-4">
                <article class="berita-card reveal h-100" style="transition-delay: {{ min($i, 8) * 0.05 }}s;">
                    <div class="berita-thumb">
                        @if ($berita->gambar)
                        <img src="{{ $berita->gambarUrl() }}" alt="{{ $berita->judul }}" loading="lazy">
                        @else
                        <div class="berita-thumb-placeholder"><i class="fas fa-newspaper"></i></div>
                        @endif
                        <span class="berita-date"><i class="fas fa-calendar-alt me-1"></i>{{ $berita->tanggalIndo() }}</span>
                    </div>
                    <div class="berita-body">
                        <h6 class="berita-title mb-2">{{ $berita->judul }}</h6>
                        @if ($berita->deskripsi)
                        <p class="mb-0 berita-excerpt">{!! nl2br(e($berita->deskripsi)) !!}</p>
                        @endif
                    </div>
                </article>
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-4 d-flex justify-content-center">
            {{ $beritas->links() }}
        </div>
        @else
        <div class="text-center py-5">
            <i class="fas fa-newspaper text-muted mb-3" style="font-size:3rem; opacity:.3;"></i>
            <h5 class="fw-600">Belum ada berita</h5>
            <p class="text-muted" style="font-size:.9rem;">Berita akan tampil di sini setelah ditambahkan oleh administrator melalui menu Kelola Website.</p>
            <a href="{{ route('home') }}" class="btn btn-outline-primary btn-sm"><i class="fas fa-arrow-left me-1"></i>Kembali ke Beranda</a>
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

    /* Scroll reveal */
    function revealOnScroll() {
        document.querySelectorAll('.reveal').forEach(el => {
            if (el.getBoundingClientRect().top < window.innerHeight - 60) {
                el.classList.add('active');
            }
        });
    }
    window.addEventListener('scroll', revealOnScroll, { passive: true });
    window.addEventListener('load', revealOnScroll);

    revealOnScroll();
</script>
@endsection
