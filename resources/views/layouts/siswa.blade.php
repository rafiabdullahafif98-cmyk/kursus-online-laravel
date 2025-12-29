<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Siswa - EduCourse')</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .sidebar a {
            color: rgba(255,255,255,.8);
            text-decoration: none;
            padding: 10px 15px;
            display: block;
            border-radius: 5px;
            margin: 5px 0;
        }
        .sidebar a:hover, .sidebar a.active {
            background: rgba(255,255,255,.1);
            color: white;
        }
        .stat-card {
            border-radius: 10px;
            border: none;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .course-card {
            transition: transform 0.3s;
            height: 100%;
        }
        .course-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .progress-bar {
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0 sidebar">
                <div class="p-4">
                    <h4 class="mb-4">
                        <i class="bi bi-mortarboard"></i> EduCourse
                        <small class="d-block text-white-50">Siswa Panel</small>
                    </h4>
                    
                    <div class="mb-4">
                        <div class="text-white mb-2">Halo,</div>
                        <h5 class="text-white">{{ auth()->user()->name }}</h5>
                        <small class="text-white-50">{{ auth()->user()->email }}</small>
                    </div>
                    
                    <nav class="nav flex-column">
                        <a href="{{ route('siswa.dashboard') }}" class="{{ request()->routeIs('siswa.dashboard') ? 'active' : '' }}">
                            <i class="bi bi-speedometer2 me-2"></i> Dashboard
                        </a>
                        <a href="{{ route('siswa.courses') }}" class="{{ request()->routeIs('siswa.courses') ? 'active' : '' }}">
                            <i class="bi bi-collection-play me-2"></i> Kursus Saya
                        </a>
                        <a href="{{ route('siswa.all-courses') }}" class="{{ request()->routeIs('siswa.all-courses') ? 'active' : '' }}">
                            <i class="bi bi-search me-2"></i> Cari Kursus
                        </a>
                        <a href="{{ route('siswa.profile') }}" class="{{ request()->routeIs('siswa.profile') ? 'active' : '' }}">
                            <i class="bi bi-person me-2"></i> Profil
                        </a>
                        <hr class="text-white-50">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-light w-100">
                                <i class="bi bi-box-arrow-right me-2"></i> Logout
                            </button>
                        </form>
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 ml-sm-auto px-4 py-4">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>