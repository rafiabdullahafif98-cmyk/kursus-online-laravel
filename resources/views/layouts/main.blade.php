<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - Educourse</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }
        /* Navbar */
        .navbar {
            padding: 15px 0;
        }
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: #0d6efd !important;
        }
        
        /* Hero Section */
        .hero-section {
            padding: 100px 0;
            background: rgb(255,255,255);
            background: linear-gradient(90deg, rgba(255,255,255,1) 0%, rgba(240,248,255,1) 100%);
        }
        
        /* Cards */
        .course-card {
            border: none;
            border-radius: 15px;
            transition: all 0.3s ease;
            background: white;
            overflow: hidden;
        }
        .course-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
        }
        .course-img-wrapper {
            overflow: hidden;
            height: 200px;
        }
        .course-card:hover .card-img-top {
            transform: scale(1.05);
            transition: transform 0.3s ease;
        }

        /* Footer */
        footer {
            background-color: #1a1a2e;
        }
        
        /* Utilities */
        .text-gradient {
            background: -webkit-linear-gradient(45deg, #0d6efd, #00d2ff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="bi bi-mortarboard-fill me-2"></i>Educourse
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link fw-medium" href="#courses">Kursus</a></li>
                    <li class="nav-item"><a class="nav-link fw-medium" href="#features">Keunggulan</a></li>
                    
                    @auth
                        <li class="nav-item dropdown ms-lg-3">
                            <a class="nav-link dropdown-toggle btn btn-light px-4 rounded-pill border" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle me-1"></i> {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                                @if(Auth::user()->role === 'pengajar')
                                    <li><a class="dropdown-item" href="{{ route('pengajar.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a></li>
                                @elseif(Auth::user()->role === 'admin')
                                    <li><a class="dropdown-item" href="/admin"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a></li>
                                @else
                                    <li><a class="dropdown-item" href="{{ route('siswa.dashboard') }}"><i class="bi bi-journal-text me-2"></i> Belajar Saya</a></li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i> Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item ms-lg-3">
                            <a href="{{ route('login') }}" class="btn btn-outline-primary rounded-pill px-4 me-2">Masuk</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('register') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">Daftar Sekarang</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    @yield('content')

    <footer class="text-white pt-5 pb-3 mt-auto">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5 class="fw-bold mb-3"><i class="bi bi-mortarboard-fill text-primary"></i> Educourse</h5>
                    <p class="text-secondary small">Platform belajar online masa depan. Tingkatkan skill coding, desain, dan bisnis Anda bersama para ahli.</p>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-secondary fs-5"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="text-secondary fs-5"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="text-secondary fs-5"><i class="bi bi-youtube"></i></a>
                    </div>
                </div>
                <div class="col-md-2 mb-4">
                    <h6 class="fw-bold mb-3 text-white">Produk</h6>
                    <ul class="list-unstyled small text-secondary">
                        <li class="mb-2"><a href="#" class="text-decoration-none text-secondary">Kursus Coding</a></li>
                        <li class="mb-2"><a href="#" class="text-decoration-none text-secondary">Bootcamp</a></li>
                        <li class="mb-2"><a href="#" class="text-decoration-none text-secondary">Sertifikasi</a></li>
                    </ul>
                </div>
                <div class="col-md-2 mb-4">
                    <h6 class="fw-bold mb-3 text-white">Perusahaan</h6>
                    <ul class="list-unstyled small text-secondary">
                        <li class="mb-2"><a href="#" class="text-decoration-none text-secondary">Tentang Kami</a></li>
                        <li class="mb-2"><a href="#" class="text-decoration-none text-secondary">Karir</a></li>
                        <li class="mb-2"><a href="#" class="text-decoration-none text-secondary">Kontak</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h6 class="fw-bold mb-3 text-white">Berlangganan Newsletter</h6>
                    <form class="d-flex">
                        <input class="form-control me-2" type="email" placeholder="Email Anda" aria-label="Email">
                        <button class="btn btn-primary" type="submit">Kirim</button>
                    </form>
                </div>
            </div>
            <hr class="border-secondary mt-4">
            <div class="text-center small text-secondary">
                &copy; {{ date('Y') }} Educourse Indonesia. All rights reserved.
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>s