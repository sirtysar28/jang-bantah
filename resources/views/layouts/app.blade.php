<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="JANG BANTAH - Jaringan Bank Tanah Pemerintah Kabupaten Halmahera Barat. Sistem Informasi Manajemen Bank Tanah Digital Terintegrasi.">
    <title>@yield('title', 'JANG BANTAH - Jaringan Bank Tanah Pemkab. Halmahera Barat')</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    @stack('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --blue-900: #002a5c;
            --blue-800: #003d80;
            --blue-700: #004d99;
            --blue-600: #0062b8;
            --blue-500: #1a75d1;
            --blue-400: #4da3e8;
            --blue-300: #80bfff;
            --blue-200: #b3daff;
            --blue-100: #e6f2ff;
            --blue-50:  #f0f7ff;
            --gold:     #f0c040;
            --primary:   var(--blue-700);
            --primary-dark: var(--blue-900);
            --secondary: var(--blue-500);
            --accent:    var(--blue-400);
            --light:     var(--blue-50);
        }
        * { font-family: 'Poppins', sans-serif; }
        body { background: var(--light); }

        /* ── Navbar ── */
        .navbar-jb {
            background: linear-gradient(135deg, var(--blue-900) 0%, var(--blue-700) 50%, var(--blue-600) 100%);
            box-shadow: 0 4px 25px rgba(0,77,153,.35);
            padding: .7rem 0;
            transition: all .3s;
        }
        .navbar-jb .nav-link {
            font-weight: 500; font-size: .9rem;
            padding: .5rem 1rem !important;
            border-radius: 8px;
            transition: all .3s;
        }
        .navbar-jb .nav-link:hover,
        .navbar-jb .nav-link.active {
            background: rgba(255,255,255,.15);
        }

        /* ── Buttons ── */
        .btn-blue {
            background: linear-gradient(135deg, var(--blue-700), var(--blue-500));
            color: #fff; border: none;
            padding: 12px 32px; border-radius: 50px;
            font-weight: 600; transition: all .3s;
            box-shadow: 0 4px 15px rgba(0,77,153,.3);
        }
        .btn-blue:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,77,153,.45);
            color: #fff;
        }
        .btn-gold {
            background: linear-gradient(135deg, var(--gold), #d4a020);
            color: #1a1a2e; border: none;
            padding: 12px 32px; border-radius: 50px;
            font-weight: 600; transition: all .3s;
            box-shadow: 0 4px 15px rgba(240,192,64,.3);
        }
        .btn-gold:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(240,192,64,.45);
            color: #1a1a2e;
        }

        /* ── Section Titles ── */
        .section-title {
            position: relative; padding-bottom: 18px;
            color: var(--blue-900); font-weight: 700;
        }
        .section-title::after {
            content: '';
            position: absolute; bottom: 0; left: 50%;
            transform: translateX(-50%);
            width: 80px; height: 4px;
            background: linear-gradient(90deg, var(--blue-500), var(--blue-700));
            border-radius: 2px;
        }

        /* ── Stat Cards ── */
        .stat-card {
            background: #fff;
            border-radius: 18px;
            padding: 35px 20px; text-align: center;
            box-shadow: 0 8px 30px rgba(0,77,153,.08);
            border-bottom: 4px solid transparent;
            border-image: linear-gradient(90deg, var(--blue-500), var(--blue-700)) 1;
            transition: transform .3s, box-shadow .3s;
        }
        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 50px rgba(0,77,153,.15);
        }
        .stat-card .icon { font-size: 2.8rem; color: var(--blue-500); margin-bottom: 12px; }
        .stat-card .number { font-size: 1.8rem; font-weight: 700; color: var(--blue-800); }
        .stat-card .label  { font-size: .85rem; color: #666; margin-top: 5px; font-weight: 500; }

        /* ── Feature Cards ── */
        .feature-card {
            background: #fff; border-radius: 18px;
            padding: 40px 25px; text-align: center;
            box-shadow: 0 5px 20px rgba(0,77,153,.06);
            transition: all .3s; height: 100%;
            border: 1px solid var(--blue-100);
        }
        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 40px rgba(0,77,153,.12);
            border-color: var(--blue-300);
        }
        .feature-card .icon-box {
            width: 80px; height: 80px; border-radius: 50%;
            background: linear-gradient(135deg, var(--blue-700), var(--blue-400));
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 20px;
            box-shadow: 0 6px 20px rgba(0,77,153,.25);
        }
        .feature-card .icon-box i { font-size: 2rem; color: #fff; }

        /* ── Aset Cards ── */
        .aset-card {
            background: #fff; border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,77,153,.06);
            transition: all .3s;
            border: 1px solid var(--blue-100);
        }
        .aset-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 35px rgba(0,77,153,.12);
        }
        .aset-card img { height: 200px; object-fit: cover; width: 100%; }

        /* ── Footer ── */
        .footer-jb {
            background: linear-gradient(135deg, var(--blue-900) 0%, var(--blue-800) 50%, var(--blue-700) 100%);
            color: rgba(255,255,255,.85);
            padding: 60px 0 30px;
        }
        .footer-jb h5 { color: var(--gold); font-weight: 600; }
        .footer-jb a { transition: color .2s; }
        .footer-jb a:hover { color: var(--gold) !important; }

        /* ── Animations ── */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-up { animation: fadeInUp .6s ease-out forwards; }

        /* ── Misc ── */
        .text-blue-gradient {
            background: linear-gradient(135deg, var(--blue-700), var(--blue-400));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>
<body>
    @yield('content')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
