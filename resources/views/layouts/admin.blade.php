<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>@yield('title', 'Dashboard - JANG BANTAH')</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    {{-- Stylesheet gaya "digitrans" (Dashboard OSDMRB) — warna tetap JANG BANTAH --}}
    <link href="{{ asset('css/app-admin.css') }}?v={{ filemtime(public_path('css/app-admin.css')) }}" rel="stylesheet">

    {{-- Terapkan mode sidebar minimize SEBELUM render agar tidak ada kedipan --}}
    <script>
        document.documentElement.classList.add('js');
        try {
            if (localStorage.getItem('jb-sidebar-min') === '1') {
                document.documentElement.classList.add('sidebar-min');
            }
        } catch (e) {}
    </script>
</head>
<body>

{{-- ================= LOADER / SPINNER GLOBAL =================
     Muncul saat masuk halaman, pindah antar halaman, submit form, logout. --}}
<div class="page-loader" id="pageLoader" aria-live="polite">
    <div class="loader-spinner"></div>
    <p class="loader-text" id="pageLoaderText">Memuat<span class="loader-dots"><span></span><span></span><span></span></span></p>
    <div class="loader-brand">JANG BANTAH &middot; Pemkab. Halmahera Barat</div>
</div>

<div class="app-wrapper">

    {{-- Hitung notifikasi belum dibaca SEKALI memakai bentuk blok php-endphp —
       bentuk inline dengan tanda kurung bikin compiler blade macet di server --}}
    @php
        $unreadNotif = \App\Models\Notifikasi::where('user_id', auth()->id())->unread()->count();
    @endphp

    <!-- ============================ SIDEBAR ============================ -->
    <aside class="sidebar" id="sidebar">

        <div class="sidebar-brand">
            <div class="brand-logo">
                <img src="{{ asset('logo.png') }}" alt="Logo JANG BANTAH">
            </div>
            <div class="brand-text">
                <h4>JANG BANTAH</h4>
                <span>Jaringan Bank Tanah Pemkab. Halbar</span>
            </div>

            {{-- Tombol minimize sidebar (desktop) --}}
            <button type="button" class="nav-hamburger d-none d-lg-inline-flex" id="sidebarMinimize"
                    aria-expanded="true" title="Kecilkan sidebar (icon saja)">
                <i class="fas fa-bars"></i>
            </button>

            {{-- Tombol tutup drawer sidebar (khusus mobile) --}}
            <button class="sidebar-close d-lg-none" id="sidebarClose" type="button" aria-label="Tutup menu">
                <i class="fas fa-xmark"></i>
            </button>
        </div>

        <nav class="sidebar-menu">
            <div class="sidebar-section">Menu Utama</div>
            <a href="{{ route('admin.dashboard') }}" class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt"></i><span>Dashboard</span>
            </a>

            @if(auth()->user()->isSuperAdmin() || auth()->user()->isAdminAset())
            <div class="sidebar-section">Master Data</div>
            <a href="{{ route('admin.aset.index') }}" class="menu-item {{ request()->routeIs('admin.aset.*') ? 'active' : '' }}">
                <i class="fas fa-map-marked-alt"></i><span>Data Tanah</span>
            </a>
            <a href="{{ route('admin.kecamatan.index') }}" class="menu-item {{ request()->routeIs('admin.kecamatan.*') ? 'active' : '' }}">
                <i class="fas fa-map"></i><span>Kecamatan</span>
            </a>
            <a href="{{ route('admin.desa.index') }}" class="menu-item {{ request()->routeIs('admin.desa.*') ? 'active' : '' }}">
                <i class="fas fa-map-pin"></i><span>Desa</span>
            </a>
            <a href="{{ route('admin.opd.index') }}" class="menu-item {{ request()->routeIs('admin.opd.*') ? 'active' : '' }}">
                <i class="fas fa-building"></i><span>OPD</span>
            </a>

            <div class="sidebar-section">Pengamanan</div>
            <a href="{{ route('admin.pengamanan-admin.index') }}" class="menu-item {{ request()->routeIs('admin.pengamanan-admin.*') ? 'active' : '' }}">
                <i class="fas fa-file-shield"></i><span>Administrasi</span>
            </a>
            <a href="{{ route('admin.pengamanan-fisik.index') }}" class="menu-item {{ request()->routeIs('admin.pengamanan-fisik.*') ? 'active' : '' }}">
                <i class="fas fa-shield-halved"></i><span>Fisik</span>
            </a>
            <a href="{{ route('admin.pengamanan-hukum.index') }}" class="menu-item {{ request()->routeIs('admin.pengamanan-hukum.*') ? 'active' : '' }}">
                <i class="fas fa-gavel"></i><span>Hukum</span>
            </a>

            <div class="sidebar-section">Layanan</div>
            <a href="{{ route('admin.sertifikasi.index') }}" class="menu-item {{ request()->routeIs('admin.sertifikasi.*') ? 'active' : '' }}">
                <i class="fas fa-certificate"></i><span>Sertifikasi</span>
            </a>
            <a href="{{ route('admin.gis.index') }}" class="menu-item {{ request()->routeIs('admin.gis.*') ? 'active' : '' }}">
                <i class="fas fa-globe"></i><span>GIS / Peta Digital</span>
            </a>
            <a href="{{ route('admin.monitoring.index') }}" class="menu-item {{ request()->routeIs('admin.monitoring.*') ? 'active' : '' }}">
                <i class="fas fa-chart-line"></i><span>Monitoring</span>
            </a>
            <a href="{{ route('admin.pemanfaatan.index') }}" class="menu-item {{ request()->routeIs('admin.pemanfaatan.*') ? 'active' : '' }}">
                <i class="fas fa-handshake"></i><span>Pemanfaatan Aset</span>
            </a>
            @endif

            @if(auth()->user()->isOpd())
            <div class="sidebar-section">Data Saya</div>
            <a href="{{ route('admin.aset.index') }}" class="menu-item">
                <i class="fas fa-map-marked-alt"></i><span>Aset OPD</span>
            </a>
            <a href="{{ route('admin.sertifikasi.index') }}" class="menu-item">
                <i class="fas fa-certificate"></i><span>Sertifikasi</span>
            </a>
            @endif

            <div class="sidebar-section">Lainnya</div>
            <a href="{{ route('admin.laporan.index') }}" class="menu-item {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
                <i class="fas fa-file-alt"></i><span>Laporan</span>
            </a>
            <a href="{{ route('admin.notifikasi.index') }}" class="menu-item {{ request()->routeIs('admin.notifikasi.*') ? 'active' : '' }}">
                <i class="fas fa-bell"></i><span>Notifikasi</span>
                @if($unreadNotif > 0)<span class="badge bg-danger">{{ $unreadNotif }}</span>@endif
            </a>

            @if(auth()->user()->isSuperAdmin())
            <div class="sidebar-section">Pengaturan</div>
            <a href="{{ route('admin.user.index') }}" class="menu-item {{ request()->routeIs('admin.user.*') ? 'active' : '' }}">
                <i class="fas fa-users-cog"></i><span>Manajemen User</span>
            </a>
            <a href="{{ route('admin.audit-log') }}" class="menu-item">
                <i class="fas fa-history"></i><span>Audit Log</span>
            </a>
            <a href="{{ route('admin.setting.index') }}" class="menu-item">
                <i class="fas fa-cog"></i><span>Pengaturan Sistem</span>
            </a>
            <a href="{{ route('admin.website.index') }}" class="menu-item {{ request()->routeIs('admin.website.*') ? 'active' : '' }}">
                <i class="fas fa-globe"></i><span>Kelola Website</span>
            </a>
            <a href="{{ route('admin.terminal.index') }}" class="menu-item {{ request()->routeIs('admin.terminal.*') ? 'active' : '' }}">
                <i class="fas fa-terminal"></i><span>Terminal</span>
            </a>
            @endif

            <div class="sidebar-section">Navigasi</div>
            <a href="{{ route('home') }}" class="menu-item text-warning">
                <i class="fas fa-globe"></i><span>Lihat Website</span>
            </a>
            <form method="POST" action="{{ route('logout') }}" id="logoutForm">
                @csrf
                <button type="submit" class="menu-item text-danger">
                    <i class="fas fa-sign-out-alt"></i><span>Logout</span>
                </button>
            </form>
        </nav>

    </aside>

    <!-- Overlay gelap saat drawer sidebar terbuka (mobile) -->
    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

    <!-- ============================ MAIN ============================ -->
    <div class="main-content">

        <header class="top-header">

            <div class="d-flex align-items-center gap-2 min-width-0">
                {{-- Hamburger untuk MEMBUKA sidebar (mobile / tablet < 992px) --}}
                <button class="sidebar-toggle d-lg-none" id="sidebarToggle" type="button"
                        aria-label="Buka menu navigasi" aria-controls="sidebar">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="min-width-0">
                    <h3 class="text-truncate">@yield('page-title', 'Dashboard')</h3>
                    <p class="subtitle text-truncate d-none d-sm-block">
                        @yield('page-subtitle', 'Jaringan Bank Tanah &mdash; Pemerintah Kabupaten Halmahera Barat')
                    </p>
                </div>
            </div>

            <div class="d-flex align-items-center gap-3">

                {{-- Notifikasi --}}
                <a href="{{ route('admin.notifikasi.index') }}" class="notify-bell" title="Notifikasi">
                    <i class="fas fa-bell"></i>
                    @if($unreadNotif > 0)<span class="notify-badge">{{ $unreadNotif }}</span>@endif
                </a>

                {{-- User --}}
                <div class="dropdown user-chip">
                    <a href="#" class="d-flex align-items-center text-decoration-none gap-2" data-bs-toggle="dropdown">
                        <div class="meta d-none d-sm-block">
                            <strong>{{ auth()->user()->name }}</strong>
                            <small>{{ strtoupper(auth()->user()->role) }}</small>
                        </div>
                        <div class="avatar">{{ substr(auth()->user()->name, 0, 1) }}</div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow">
                        <li><a class="dropdown-item" href="{{ route('home') }}"><i class="fas fa-globe me-2"></i>Website</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger"><i class="fas fa-sign-out-alt me-2"></i>Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>

            </div>

        </header>

        <main class="content">

            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-x-circle me-1"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @yield('content')

        </main>

    </div>

</div>

{{-- ================= FOOTER ================= --}}
<footer class="app-footer">
    <div class="app-footer-inner">
        <p class="app-footer-copyright">Copyright &copy; {{ date('Y') }} JANG BANTAH &mdash; Pemerintah Kabupaten Halmahera Barat</p>
        <span class="app-footer-version">Jaringan Bank Tanah</span>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script>
    /* ================== LOADER GLOBAL ==================
       jbShowLoader(text) : tampilkan overlay spinner
       jbHideLoader()     : sembunyikan overlay spinner */
    window.jbLoaderTimer = null;

    window.jbShowLoader = function (text) {
        var loader = document.getElementById('pageLoader');
        var label  = document.getElementById('pageLoaderText');
        if (!loader) return;

        if (text) {
            label.innerHTML = text + '<span class="loader-dots"><span></span><span></span><span></span></span>';
        }

        loader.classList.add('show');
        document.body.style.overflow = 'hidden';

        clearTimeout(window.jbLoaderTimer);
        window.jbLoaderTimer = setTimeout(window.jbHideLoader, 12000);
    };

    window.jbHideLoader = function () {
        var loader = document.getElementById('pageLoader');
        if (!loader) return;

        loader.classList.remove('show');
        document.body.style.overflow = '';
        clearTimeout(window.jbLoaderTimer);
    };

    window.addEventListener('pageshow', window.jbHideLoader);

    /* Spinner saat masuk halaman */
    window.jbShowLoader();
    window.addEventListener('load', function () {
        setTimeout(window.jbHideLoader, 350);
    });

    /* Spinner saat berpindah halaman internal */
    document.addEventListener('click', function (event) {
        var link = event.target.closest('a[href]');
        if (!link) return;
        if (link.hasAttribute('data-no-loader')) return;
        if (link.target === '_blank' || link.hasAttribute('download')) return;
        if (link.dataset.bsToggle || link.hasAttribute('data-bs-dismiss')) return;

        var href = link.getAttribute('href');
        if (!href || href.charAt(0) === '#' || href.startsWith('javascript:') || href.startsWith('tel:') || href.startsWith('mailto:')) return;

        var url;
        try { url = new URL(link.href, window.location.href); } catch (e) { return; }

        if (url.origin !== window.location.origin) return;

        if (url.pathname !== window.location.pathname || url.search !== window.location.search) {
            window.jbShowLoader('Memuat...');
        }
    });

    /* Spinner saat submit form */
    document.addEventListener('submit', function (event) {
        var loader = document.getElementById('pageLoader');
        if (loader && loader.classList.contains('show')) return;

        var form = event.target;
        if (form.matches('[data-no-loader]') || form.matches('.no-loader')) return;

        if (form.id === 'logoutForm') {
            window.jbShowLoader('Sedang keluar dari aplikasi...');
            return;
        }

        if (form.getAttribute('method') && form.getAttribute('method').toUpperCase() !== 'POST') return;

        window.jbShowLoader('Menyimpan data');
    });

    /* ================== SIDEBAR MINIMIZE / PERBESAR (desktop) ================== */
    var htmlEl = document.documentElement;
    var minBtn = document.getElementById('sidebarMinimize');

    // tooltip native untuk tiap menu (berguna saat sidebar minimize)
    document.querySelectorAll('.sidebar .menu-item').forEach(function (link) {
        var span = link.querySelector('span');
        link.title = span ? span.textContent.trim() : '';
    });

    function syncMinimizeButton() {
        if (!minBtn) return;

        var minimized = htmlEl.classList.contains('sidebar-min');

        minBtn.classList.toggle('active', minimized);
        minBtn.title = minimized ? 'Tampilkan menu lengkap' : 'Kecilkan sidebar (icon saja)';
        minBtn.setAttribute('aria-expanded', minimized ? 'false' : 'true');
    }

    if (minBtn) {
        minBtn.addEventListener('click', function () {
            htmlEl.classList.toggle('sidebar-min');

            try {
                localStorage.setItem('jb-sidebar-min', htmlEl.classList.contains('sidebar-min') ? '1' : '0');
            } catch (e) {}

            syncMinimizeButton();
        });

        syncMinimizeButton();
    }

    /* ================== DRAWER SIDEBAR (mobile) ================== */
    (function () {
        var body = document.body;
        var toggle = document.getElementById('sidebarToggle');
        var backdrop = document.getElementById('sidebarBackdrop');
        var closeBtn = document.getElementById('sidebarClose');

        function openSidebar()  { body.classList.add('sidebar-open'); }
        function closeSidebar() { body.classList.remove('sidebar-open'); }

        if (toggle)   toggle.addEventListener('click', openSidebar);
        if (backdrop) backdrop.addEventListener('click', closeSidebar);
        if (closeBtn) closeBtn.addEventListener('click', closeSidebar);

        // Tutup drawer setelah menu dipilih (khusus mobile)
        document.querySelectorAll('.sidebar .menu-item').forEach(function (link) {
            link.addEventListener('click', function () {
                if (window.innerWidth < 992) closeSidebar();
            });
        });

        // Reset saat layar kembali ke ukuran desktop
        window.addEventListener('resize', function () {
            if (window.innerWidth >= 992) closeSidebar();
        });

        // Tutup dengan tombol ESC
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeSidebar();
        });
    })();

    /* ================== ANIMASI JANG BANTAH ==================
       Reveal bertahap, count-up statistik, dan animasi progress bar. */
    (function () {
        const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        /* 1. Reveal bertahap saat elemen masuk viewport */
        const revealEls = document.querySelectorAll('.jb-reveal');
        if (!reduceMotion && revealEls.length) {
            const io = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (!entry.isIntersecting) return;
                    const el = entry.target;
                    const siblings = Array.from(el.parentElement?.children || []).filter(c => c.classList.contains('jb-reveal'));
                    const idx = Math.max(0, siblings.indexOf(el));
                    el.style.setProperty('--jb-delay', (Math.min(idx, 6) * 0.09) + 's');
                    el.classList.add('jb-in');
                    io.unobserve(el);
                });
            }, { threshold: 0.12, rootMargin: '0px 0px -30px 0px' });
            revealEls.forEach(el => io.observe(el));
        } else {
            revealEls.forEach(el => el.classList.add('jb-in'));
        }

        /* 2. Count-up angka statistik */
        const counters = document.querySelectorAll('[data-count]');
        if (counters.length) {
            const fmt = new Intl.NumberFormat('id-ID');
            const animate = (el) => {
                if (reduceMotion) {
                    el.textContent = (el.dataset.prefix || '') + fmt.format(parseFloat(el.dataset.count)) + (el.dataset.suffix || '');
                    return;
                }
                const target = parseFloat(el.dataset.count);
                const prefix = el.dataset.prefix || '';
                const suffix = el.dataset.suffix || '';
                const dur = 1200;
                const start = performance.now();
                function tick(now) {
                    const p = Math.min((now - start) / dur, 1);
                    const eased = 1 - Math.pow(1 - p, 4);
                    el.textContent = prefix + fmt.format(Math.round(target * eased)) + suffix;
                    if (p < 1) requestAnimationFrame(tick);
                }
                requestAnimationFrame(tick);
            };
            const cio = new IntersectionObserver((entries) => {
                entries.forEach(en => {
                    if (en.isIntersecting) { animate(en.target); cio.unobserve(en.target); }
                });
            }, { threshold: 0.4 });
            counters.forEach(el => cio.observe(el));
        }

        /* 3. Progress bar animasi */
        const bars = document.querySelectorAll('.progress-jb .progress-bar[data-width]');
        if (bars.length) {
            const bio = new IntersectionObserver((entries) => {
                entries.forEach((en, i) => {
                    if (!en.isIntersecting) return;
                    const bar = en.target;
                    setTimeout(() => { bar.style.width = bar.dataset.width + '%'; }, 150 + i * 120);
                    bio.unobserve(bar);
                });
            }, { threshold: 0.3 });
            bars.forEach(b => bio.observe(b));
        }
    })();
</script>
@stack('scripts')
</body>
</html>
