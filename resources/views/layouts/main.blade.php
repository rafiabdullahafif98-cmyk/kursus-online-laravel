<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - Educourse</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        .hero-section {
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
            color: white;
            padding: 80px 0;
        }
        .navbar-brand { font-weight: bold; font-size: 1.5rem; }
        .course-card { transition: transform 0.3s; }
        .course-card:hover { transform: translateY(-5px); }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand text-primary" href="{{ url('/') }}">
                <i class="bi bi-mortarboard-fill"></i> Educourse
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="#courses">Kursus</a></li>
                    <li class="nav-item"><a class="nav-link" href="#features">Fitur</a></li>
                    
                    @auth
                        <li class="nav-item dropdown ms-2">
                            <a class="nav-link dropdown-toggle btn btn-outline-primary px-4 rounded-pill" href="#" role="button" data-bs-toggle="dropdown">
                                Halo, {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                @if(Auth::user()->role === 'pengajar')
                                    <li><a class="dropdown-item" href="{{ route('pengajar.dashboard') }}">Dashboard Pengajar</a></li>
                                @elseif(Auth::user()->role === 'admin')
                                    <li><a class="dropdown-item" href="/admin">Dashboard Admin</a></li>
                                @else
                                    <li><a class="dropdown-item" href="{{ route('siswa.dashboard') }}">Dashboard Siswa</a></li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item ms-2">
                            <a href="{{ route('login') }}" class="btn btn-outline-primary me-2">Masuk</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('register') }}" class="btn btn-primary">Daftar</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    @yield('content')

    <footer class="bg-dark text-white pt-5 pb-3 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5 class="fw-bold"><i class="bi bi-mortarboard"></i> Educourse</h5>
                    <p class="small text-muted">Platform belajar online terbaik untuk meningkatkan keahlian Anda kapan saja dan di mana saja.</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h5 class="fw-bold">Link Cepat</h5>
                    <ul class="list-unstyled small">
                        <li><a href="#" class="text-decoration-none text-muted">Tentang Kami</a></li>
                        <li><a href="#" class="text-decoration-none text-muted">Syarat & Ketentuan</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h5 class="fw-bold">Kontak</h5>
                    <p class="small text-muted">support@educourse.com</p>
                </div>
            </div>
            <hr class="border-secondary">
            <div class="text-center small text-muted">
                &copy; {{ date('Y') }} Educourse. All rights reserved.
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>