<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard - JANG BANTAH')</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
        :root {
            --sidebar-bg: #0D2B3E;
            --sidebar-hover: #1B4965;
            --sidebar-active: #5FA8D3;
            --header-bg: #FFFFFF;
            --body-bg: #F0F2F5;
            --primary: #1B4965;
            --primary-light: #5FA8D3;
            --success: #27AE60;
            --warning: #F39C12;
            --danger: #E74C3C;
            --info: #3498DB;
        }
        * { font-family: 'Inter', sans-serif; margin: 0; padding: 0; box-sizing: border-box; }
        body { background: var(--body-bg); overflow-x: hidden; }

        /* Sidebar */
        .sidebar {
            width: 260px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            background: var(--sidebar-bg);
            z-index: 1000;
            transition: transform .3s;
            overflow-y: auto;
        }
        .sidebar-brand {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,.1);
        }
        .sidebar-brand h5 {
            color: #fff;
            font-weight: 700;
            font-size: 1rem;
            margin: 8px 0 2px;
        }
        .sidebar-brand small { color: var(--sidebar-active); font-size: .7rem; }
        .sidebar-nav { padding: 15px 0; }
        .sidebar-nav .nav-section {
            padding: 10px 20px 5px;
            font-size: .7rem;
            color: rgba(255,255,255,.4);
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
        }
        .sidebar-nav .nav-link {
            display: flex;
            align-items: center;
            padding: 10px 20px;
            color: rgba(255,255,255,.7);
            text-decoration: none;
            font-size: .85rem;
            transition: all .2s;
            border-left: 3px solid transparent;
        }
        .sidebar-nav .nav-link:hover { background: var(--sidebar-hover); color: #fff; }
        .sidebar-nav .nav-link.active {
            background: var(--sidebar-hover);
            color: var(--sidebar-active);
            border-left-color: var(--sidebar-active);
        }
        .sidebar-nav .nav-link i { width: 20px; margin-right: 12px; text-align: center; }
        .sidebar-nav .nav-link .badge {
            margin-left: auto;
            font-size: .65rem;
            padding: 2px 6px;
        }

        /* Header */
        .main-header {
            position: fixed;
            top: 0;
            left: 260px;
            right: 0;
            height: 60px;
            background: var(--header-bg);
            box-shadow: 0 2px 10px rgba(0,0,0,.06);
            z-index: 999;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
        }
        .main-header .header-left { display: flex; align-items: center; gap: 15px; }
        .main-header .header-right { display: flex; align-items: center; gap: 20px; }
        .header-right .notification-bell {
            position: relative;
            cursor: pointer;
            font-size: 1.2rem;
            color: #555;
        }
        .header-right .notification-bell .badge-count {
            position: absolute; top: -5px; right: -8px;
            background: var(--danger);
            color: #fff;
            font-size: .6rem;
            padding: 1px 5px;
            border-radius: 10px;
        }
        .user-avatar {
            width: 35px; height: 35px;
            border-radius: 50%;
            background: var(--primary);
            color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-weight: 600; font-size: .85rem;
        }

        /* Main Content */
        .main-content {
            margin-left: 260px;
            padding-top: 60px;
            min-height: 100vh;
        }
        .content-wrapper { padding: 24px; }

        /* Cards */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,.06);
            margin-bottom: 24px;
        }
        .card-header {
            background: #fff;
            border-bottom: 1px solid #eee;
            border-radius: 12px 12px 0 0;
            padding: 16px 20px;
            font-weight: 600;
        }

        /* Stat Cards */
        .stat-card-admin {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,.06);
            position: relative;
            overflow: hidden;
        }
        .stat-card-admin::after {
            content: '';
            position: absolute;
            top: 0; right: 0;
            width: 80px; height: 80px;
            border-radius: 50%;
            opacity: .1;
            transform: translate(20px, -20px);
        }
        .stat-card-admin.blue::after { background: var(--primary); }
        .stat-card-admin.green::after { background: var(--success); }
        .stat-card-admin.orange::after { background: var(--warning); }
        .stat-card-admin.red::after { background: var(--danger); }
        .stat-card-admin .stat-icon {
            width: 45px; height: 45px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 1.2rem;
        }
        .stat-card-admin .stat-value { font-size: 1.5rem; font-weight: 700; }
        .stat-card-admin .stat-label { font-size: .8rem; color: #888; }

        /* Progress Bar */
        .progress-jb { height: 8px; border-radius: 4px; background: #e9ecef; }
        .progress-jb .progress-bar { border-radius: 4px; }

        /* Badge */
        .badge-sertifikasi { padding: 5px 12px; border-radius: 20px; font-size: .75rem; font-weight: 500; }

        /* Table */
        .table th { font-size: .8rem; text-transform: uppercase; letter-spacing: .5px; color: #888; }
        .table td { vertical-align: middle; font-size: .85rem; }

        /* ════════ PAGINATION (dinormalisasi di semua halaman admin) ════════ */
        .pagination {
            margin: 0;
            flex-wrap: wrap;
            gap: 4px;
        }
        .page-item { margin: 0; }
        .page-item .page-link {
            min-width: 34px;
            height: 34px;
            padding: 0 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: .85rem;
            font-weight: 500;
            line-height: 1;
            color: var(--primary);
            background: #fff;
            border: 1px solid #e3e6ec;
            border-radius: 8px !important;
            margin: 0;
            transition: all .15s ease;
        }
        .page-item .page-link:hover {
            background: var(--primary);
            color: #fff;
            border-color: var(--primary);
            z-index: 2;
        }
        .page-item.active .page-link,
        .page-item.active .page-link:hover {
            background: var(--primary);
            color: #fff;
            border-color: var(--primary);
            box-shadow: 0 2px 8px rgba(27,73,101,.3);
        }
        .page-item.disabled .page-link {
            color: #adb5bd;
            background: #f8f9fa;
            border-color: #e3e6ec;
            pointer-events: none;
        }
        /* Panah navigasi (‹ ›) tetap kompak & terpusat */
        .page-item:first-child .page-link,
        .page-item:last-child .page-link {
            font-size: .9rem;
        }
        /* Ringkasan "Showing X to Y of Z" */
        nav .small.text-muted { font-size: .78rem; margin: 0; }

        /* Responsive */
        @media (max-width: 992px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-header { left: 0; }
            .main-content { margin-left: 0; }
        }
        @media (max-width: 575.98px) {
            .page-item .page-link {
                min-width: 30px;
                height: 30px;
                padding: 0 8px;
                font-size: .8rem;
            }
            .page-item:first-child .page-link,
            .page-item:last-child .page-link {
                font-size: .82rem;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <img src="{{ asset('logo.png') }}" alt="Logo" style="height:45px;margin-bottom:8px;">
            <h5>JANG BANTAH</h5>
            <small>Jaringan Bank Tanah Pemkab. Halbar</small>
        </div>
        <nav class="sidebar-nav">
            <div class="nav-section">Menu Utama</div>
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>

            @if(auth()->user()->isSuperAdmin() || auth()->user()->isAdminAset())
            <div class="nav-section">Master Data</div>
            <a href="{{ route('admin.aset.index') }}" class="nav-link {{ request()->routeIs('admin.aset.*') ? 'active' : '' }}">
                <i class="fas fa-map-marked-alt"></i> Data Tanah
            </a>
            <a href="{{ route('admin.kecamatan.index') }}" class="nav-link {{ request()->routeIs('admin.kecamatan.*') ? 'active' : '' }}">
                <i class="fas fa-map"></i> Kecamatan
            </a>
            <a href="{{ route('admin.desa.index') }}" class="nav-link {{ request()->routeIs('admin.desa.*') ? 'active' : '' }}">
                <i class="fas fa-map-pin"></i> Desa
            </a>
            <a href="{{ route('admin.opd.index') }}" class="nav-link {{ request()->routeIs('admin.opd.*') ? 'active' : '' }}">
                <i class="fas fa-building"></i> OPD
            </a>

            <div class="nav-section">Pengamanan</div>
            <a href="{{ route('admin.pengamanan-admin.index') }}" class="nav-link {{ request()->routeIs('admin.pengamanan-admin.*') ? 'active' : '' }}">
                <i class="fas fa-file-shield"></i> Administrasi
            </a>
            <a href="{{ route('admin.pengamanan-fisik.index') }}" class="nav-link {{ request()->routeIs('admin.pengamanan-fisik.*') ? 'active' : '' }}">
                <i class="fas fa-shield-halved"></i> Fisik
            </a>
            <a href="{{ route('admin.pengamanan-hukum.index') }}" class="nav-link {{ request()->routeIs('admin.pengamanan-hukum.*') ? 'active' : '' }}">
                <i class="fas fa-gavel"></i> Hukum
            </a>

            <div class="nav-section">Layanan</div>
            <a href="{{ route('admin.sertifikasi.index') }}" class="nav-link {{ request()->routeIs('admin.sertifikasi.*') ? 'active' : '' }}">
                <i class="fas fa-certificate"></i> Sertifikasi
            </a>
            <a href="{{ route('admin.gis.index') }}" class="nav-link {{ request()->routeIs('admin.gis.*') ? 'active' : '' }}">
                <i class="fas fa-globe"></i> GIS / Peta Digital
            </a>
            <a href="{{ route('admin.monitoring.index') }}" class="nav-link {{ request()->routeIs('admin.monitoring.*') ? 'active' : '' }}">
                <i class="fas fa-chart-line"></i> Monitoring
            </a>
            <a href="{{ route('admin.pemanfaatan.index') }}" class="nav-link {{ request()->routeIs('admin.pemanfaatan.*') ? 'active' : '' }}">
                <i class="fas fa-handshake"></i> Pemanfaatan Aset
            </a>
            @endif

            @if(auth()->user()->isOpd())
            <div class="nav-section">Data Saya</div>
            <a href="{{ route('admin.aset.index') }}" class="nav-link">
                <i class="fas fa-map-marked-alt"></i> Aset OPD
            </a>
            <a href="{{ route('admin.sertifikasi.index') }}" class="nav-link">
                <i class="fas fa-certificate"></i> Sertifikasi
            </a>
            @endif

            <div class="nav-section">Lainnya</div>
            <a href="{{ route('admin.laporan.index') }}" class="nav-link {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
                <i class="fas fa-file-alt"></i> Laporan
            </a>
            <a href="{{ route('admin.notifikasi.index') }}" class="nav-link {{ request()->routeIs('admin.notifikasi.*') ? 'active' : '' }}">
                <i class="fas fa-bell"></i> Notifikasi
                <span class="badge bg-danger">{{ $unreadCount ?? 0 }}</span>
            </a>

            @if(auth()->user()->isSuperAdmin())
            <div class="nav-section">Pengaturan</div>
            <a href="{{ route('admin.user.index') }}" class="nav-link {{ request()->routeIs('admin.user.*') ? 'active' : '' }}">
                <i class="fas fa-users-cog"></i> Manajemen User
            </a>
            <a href="{{ route('admin.audit-log') }}" class="nav-link">
                <i class="fas fa-history"></i> Audit Log
            </a>
            <a href="{{ route('admin.setting.index') }}" class="nav-link">
                <i class="fas fa-cog"></i> Pengaturan Sistem
            </a>
            <a href="{{ route('admin.terminal.index') }}" class="nav-link {{ request()->routeIs('admin.terminal.*') ? 'active' : '' }}">
                <i class="fas fa-terminal"></i> Terminal
            </a>
            @endif

            <div class="mt-4 px-3">
                <a href="{{ route('home') }}" class="nav-link text-warning">
                    <i class="fas fa-globe"></i> Lihat Website
                </a>
            </div>
            <div class="px-3 mt-2">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="nav-link text-danger w-100 border-0 bg-transparent" style="cursor:pointer;">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
        </nav>
    </aside>

    <!-- Header -->
    <header class="main-header">
        <div class="header-left">
            <button class="btn btn-sm d-lg-none" onclick="document.getElementById('sidebar').classList.toggle('show')">
                <i class="fas fa-bars"></i>
            </button>
            <h6 class="mb-0 fw-600" style="color:var(--primary);">@yield('page-title', 'Dashboard')</h6>
        </div>
        <div class="header-right">
            <a href="{{ route('admin.notifikasi.index') }}" class="notification-bell">
                <i class="fas fa-bell"></i>
                @php $unread = \App\Models\Notifikasi::where('user_id', auth()->id())->unread()->count(); @endphp
                @if($unread > 0)<span class="badge-count">{{ $unread }}</span>@endif
            </a>
            <div class="dropdown">
                <a href="#" class="text-decoration-none d-flex align-items-center gap-2" data-bs-toggle="dropdown">
                    <div class="user-avatar">{{ substr(auth()->user()->name, 0, 1) }}</div>
                    <div>
                        <div class="fw-600" style="font-size:.85rem;">{{ auth()->user()->name }}</div>
                        <div style="font-size:.7rem;color:#888;">{{ strtoupper(auth()->user()->role) }}</div>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
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

    <!-- Main Content -->
    <div class="main-content">
        <div class="content-wrapper">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    @stack('scripts')
</body>
</html>
