@extends('layouts.app')
@section('title', 'Video — JANG BANTAH')

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

    /* ══════════ KARTU VIDEO ══════════ */
    .video-section { background: linear-gradient(180deg, var(--blue-50) 0%, #fff 45%, var(--blue-50) 100%); }
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
        height: 100%;
        padding: 22px;
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
                <li class="nav-item"><a class="nav-link nav-link-sd" href="{{ route('berita.publik') }}"><i class="fas fa-newspaper me-1"></i>Berita</a></li>
                <li class="nav-item"><a class="nav-link nav-link-sd active" href="{{ route('video.publik') }}"><i class="fab fa-youtube me-1"></i>Video</a></li>
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
        <span class="section-eyebrow mb-3" style="background:rgba(255,255,255,.14); color:#fff;">Kanal Video</span>
        <h1 class="page-hero-title">{{ \App\WebsiteContent::get('web_video_title') }}</h1>
        <p class="page-hero-sub">{{ \App\WebsiteContent::get('web_video_desc') }}</p>
        <div class="page-hero-stats d-flex justify-content-center flex-wrap gap-4 gap-md-5 mt-4">
            <div><div class="num">{{ number_format(count(array_filter($videoItems, fn ($v) => $v['type'] === 'video'))) }}</div><div class="lbl">VIDEO</div></div>
            <div><div class="num">{{ $channelUrl ? '1' : '0' }}</div><div class="lbl">CHANNEL</div></div>
            @if ($channelUrl)
            <div class="align-self-center">
                <a href="{{ $channelUrl }}" target="_blank" rel="noopener" class="btn btn-gold btn-sm px-4">
                    <i class="fab fa-youtube me-2"></i>Kunjungi Channel
                </a>
            </div>
            @endif
        </div>
    </div>
</section>

<!-- ══════════════════ GRID VIDEO ══════════════════ -->
<section class="py-5 video-section">
    <div class="container">
        @if (count($videoItems) > 0)
        <p class="text-muted mb-4" style="font-size:.85rem;">
            Menampilkan <b>{{ count($videoItems) }}</b> video & channel YouTube JANG BANTAH — klik thumbnail untuk menonton.
        </p>
        <div class="row g-3 g-md-4">
            @foreach ($videoItems as $i => $video)
            @if ($video['type'] === 'video')
            <div class="col-12 col-sm-10 col-md-6 col-lg-4">
                <div class="video-item reveal" role="button" tabindex="0" data-video-id="{{ $video['id'] }}" title="Tonton video di YouTube" style="transition-delay: {{ min($i, 8) * 0.05 }}s;">
                    <img src="{{ $video['thumb'] }}" alt="Video YouTube {{ $i + 1 }}" loading="lazy">
                    <span class="video-play"><i class="fas fa-play"></i></span>
                    <span class="video-badge"><i class="fab fa-youtube me-1"></i>YouTube</span>
                </div>
            </div>
            @else
            <div class="col-12 col-sm-10 col-md-6 col-lg-4">
                <a href="{{ $video['url'] }}" target="_blank" rel="noopener" class="video-item video-channel reveal d-flex align-items-center gap-3" title="Buka channel YouTube di tab baru" style="transition-delay: {{ min($i, 8) * 0.05 }}s;">
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
        @else
        <div class="text-center py-5">
            <i class="fab fa-youtube text-muted mb-3" style="font-size:3rem; opacity:.3;"></i>
            <h5 class="fw-600">Belum ada video</h5>
            <p class="text-muted" style="font-size:.9rem;">Video YouTube akan tampil di sini setelah ditambahkan oleh administrator.</p>
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

    /* Popup video YouTube (embed dimuat hanya saat diklik) */
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

    revealOnScroll();
</script>
@endsection
