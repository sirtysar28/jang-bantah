@extends('layouts.app')

@section('title', 'Tentang - JANG BANTAH')

@section('content')
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-jb fixed-top">
        <div class="container">
            <a class="navbar-brand text-white fw-bold d-flex align-items-center gap-2" href="{{ route('home') }}">
                <img src="{{ asset('logo.png') }}" alt="Logo" style="height:40px;">
                <div>
                    <div style="font-size:.85rem;line-height:1;">JANG BANTAH</div>
                    <div style="font-size:.6rem;opacity:.7;">Jaringan Bank Tanah</div>
                </div>
            </a>
            <button class="navbar-toggler text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <i class="fas fa-bars"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto gap-1">
                    <li class="nav-item"><a class="nav-link text-white" href="{{ route('home') }}">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link text-white active" href="{{ route('about') }}">Tentang</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="{{ route('map.public') }}">Peta Aset</a></li>
                    <li class="nav-item"><a class="btn btn-gold btn-sm px-4" href="{{ route('login') }}"><i class="fas fa-sign-in-alt me-1"></i> Login Admin</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div style="padding-top:80px;"></div>

    <!-- About Section -->
    <section class="py-5">
        <div class="container">
            <div class="row align-items-center mb-5">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="p-5 rounded-4" style="background:linear-gradient(135deg,var(--primary-dark),var(--primary));">
                        <i class="fas fa-university text-white" style="font-size:8rem;opacity:.2;"></i>
                        <div class="position-relative" style="margin-top:-120px;">
                            <h2 class="text-white fw-800">JANG BANTAH</h2>
                            <p class="text-white" style="opacity:.9;font-size:.95rem;">
                                Jaringan Bank Tanah Pemerintah Kabupaten Halmahera Barat
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <span class="badge bg-warning text-dark px-3 py-2 mb-3" style="font-size:.75rem;">PROFIL SISTEM</span>
                    <h2 class="fw-700 mb-3">Tentang JANG BANTAH</h2>
                    <p style="color:#555;font-size:.95rem;">
                        <strong>JANG BANTAH</strong> adalah Sistem Informasi Manajemen Bank Tanah Pemerintah Kabupaten Halmahera Barat yang dirancang sebagai pusat pengelolaan, pengamanan, sertifikasi, monitoring, dan pemanfaatan aset tanah daerah secara digital dan terintegrasi.
                    </p>
                    <p style="color:#555;font-size:.95rem;">
                        Sistem ini mendukung program MCSP KPK dan menjadi instrumen penting dalam peningkatan Pendapatan Asli Daerah (PAD) melalui optimalisasi pemanfaatan aset tanah.
                    </p>
                </div>
            </div>

            <!-- Tujuan -->
            <div class="card border-0 shadow-sm mb-5">
                <div class="card-body p-4 p-lg-5">
                    <h3 class="fw-700 mb-4 text-center">Tujuan Sistem</h3>
                    <div class="row g-3">
                        @php
                            $tujuan = [
                                'Digitalisasi seluruh aset tanah Pemda Halmahera Barat.',
                                'Mempercepat proses sertifikasi tanah.',
                                'Mencegah sengketa aset tanah daerah.',
                                'Menjadi pusat data aset terintegrasi.',
                                'Menampilkan dashboard kondisi aset secara realtime.',
                                'Mendukung MCSP KPK.',
                                'Mendukung peningkatan PAD dari pemanfaatan aset.',
                            ];
                        @endphp
                        @foreach($tujuan as $t)
                        <div class="col-lg-6">
                            <div class="d-flex align-items-start gap-3 p-3 rounded-3" style="background:#f8fafc;">
                                <i class="fas fa-check-circle text-success mt-1"></i>
                                <span style="font-size:.9rem;">{{ $t }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Visi Misi -->
            <div class="row g-4 mb-5">
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4 p-lg-5 text-center">
                            <i class="fas fa-eye mb-3" style="font-size:2.5rem;color:var(--primary);"></i>
                            <h4 class="fw-700 mb-3">Visi</h4>
                            <p style="color:#555;font-size:.95rem;">
                                Mewujudkan pengelolaan aset tanah Pemerintah Kabupaten Halmahera Barat yang transparan, akuntabel, dan berbasis teknologi digital untuk mendukung tata kelola pemerintahan yang baik.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4 p-lg-5 text-center">
                            <i class="fas fa-bullseye mb-3" style="font-size:2.5rem;color:var(--gold);"></i>
                            <h4 class="fw-700 mb-3">Misi</h4>
                            <ul class="list-unstyled text-start" style="color:#555;font-size:.9rem;">
                                <li class="mb-2"><i class="fas fa-arrow-right me-2 text-primary"></i>Mendigitalisasi seluruh data aset tanah daerah.</li>
                                <li class="mb-2"><i class="fas fa-arrow-right me-2 text-primary"></i>Mempercepat sertifikasi tanah melalui monitoring BPN.</li>
                                <li class="mb-2"><i class="fas fa-arrow-right me-2 text-primary"></i>Mengamankan aset tanah secara administrasi, fisik, dan hukum.</li>
                                <li class="mb-2"><i class="fas fa-arrow-right me-2 text-primary"></i>Mengoptimalkan pemanfaatan aset untuk peningkatan PAD.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hak Akses -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 p-lg-5">
                    <h3 class="fw-700 mb-4 text-center">Hak Akses Sistem</h3>
                    <div class="row g-3">
                        <div class="col-lg-3 col-md-6">
                            <div class="text-center p-3 rounded-3" style="background:#f0f4f8;">
                                <i class="fas fa-user-shield mb-2" style="font-size:2rem;color:var(--primary);"></i>
                                <h6 class="fw-600">Super Admin</h6>
                                <p style="font-size:.75rem;color:#666;">BKAD/Diskominfo - Mengatur seluruh sistem</p>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="text-center p-3 rounded-3" style="background:#f0f4f8;">
                                <i class="fas fa-user-cog mb-2" style="font-size:2rem;color:var(--secondary);"></i>
                                <h6 class="fw-600">Admin Bidang Aset</h6>
                                <p style="font-size:.75rem;color:#666;">Mengelola seluruh data aset tanah</p>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="text-center p-3 rounded-3" style="background:#f0f4f8;">
                                <i class="fas fa-building mb-2" style="font-size:2rem;color:var(--green);"></i>
                                <h6 class="fw-600">OPD</h6>
                                <p style="font-size:.75rem;color:#666;">Melihat aset milik OPD masing-masing</p>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="text-center p-3 rounded-3" style="background:#f0f4f8;">
                                <i class="fas fa-user-tie mb-2" style="font-size:2rem;color:var(--gold);"></i>
                                <h6 class="fw-600">Pimpinan</h6>
                                <p style="font-size:.75rem;color:#666;">Dashboard Executive untuk pemantauan</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer-jb">
        <div class="container text-center">
            <p style="font-size:.85rem;">&copy; {{ date('Y') }} Pemerintah Kabupaten Halmahera Barat &mdash; JANG BANTAH - Jaringan Bank Tanah</p>
        </div>
    </footer>
@endsection
