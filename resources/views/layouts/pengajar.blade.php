<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Pengajar - EduCourse')</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #2d3748 0%, #4a5568 100%);
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
        .badge-published {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
            color: white;
        }
        .badge-draft {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important;
            color: white;
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
                        <i class="bi bi-person-badge"></i> EduCourse
                        <small class="d-block text-white-50">Pengajar Panel</small>
                    </h4>
                    
                    <div class="mb-4">
                        <div class="text-white mb-2">Halo,</div>
                        <h5 class="text-white">{{ auth()->user()->name }}</h5>
                        <small class="text-white-50">{{ auth()->user()->email }}</small>
                    </div>
                    
                    <nav class="nav flex-column">
                        <a href="{{ route('pengajar.dashboard') }}" class="{{ request()->routeIs('pengajar.dashboard') ? 'active' : '' }}">
                            <i class="bi bi-speedometer2 me-2"></i> Dashboard
                        </a>
                        <a href="{{ route('pengajar.courses') }}" class="{{ request()->routeIs('pengajar.courses') ? 'active' : '' }}">
                            <i class="bi bi-journal-text me-2"></i> Kursus Saya
                        </a>
                        <a href="{{ route('pengajar.courses.create') }}" class="{{ request()->routeIs('pengajar.courses.create') ? 'active' : '' }}">
                            <i class="bi bi-plus-circle me-2"></i> Buat Kursus Baru
                        </a>
                        <a href="{{ route('pengajar.students') }}" class="{{ request()->routeIs('pengajar.students') ? 'active' : '' }}">
                            <i class="bi bi-people me-2"></i> Siswa Saya
                        </a>
                        <a href="{{ route('pengajar.profile') }}" class="{{ request()->routeIs('pengajar.profile') ? 'active' : '' }}">
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
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>