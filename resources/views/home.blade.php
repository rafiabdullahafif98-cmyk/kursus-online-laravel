@extends('layouts.main')

@section('title', 'Platform Belajar Online Terbaik')

@section('content')

<section class="hero-section d-flex align-items-center">
    <div class="container">
        <div class="row align-items-center flex-column-reverse flex-lg-row">
            <div class="col-lg-6 mt-5 mt-lg-0 text-center text-lg-start">
                <span class="badge bg-primary bg-opacity-10 text-primary mb-3 px-3 py-2 rounded-pill fw-bold">
                    🚀 Mulai Karir Digitalmu
                </span>
                <h1 class="display-4 fw-bold mb-3 lh-sm">
                    Bangun Masa Depanmu Dengan <span class="text-gradient">Skill Baru</span>
                </h1>
                <p class="lead text-muted mb-4">
                    Akses ratusan materi berkualitas dari mentor berpengalaman. Belajar fleksibel kapan saja, di mana saja.
                </p>
                <div class="d-flex gap-3 justify-content-center justify-content-lg-start">
                    @guest
                        <a href="{{ route('register') }}" class="btn btn-primary btn-lg rounded-pill px-5 shadow fw-bold">Daftar Gratis</a>
                        <a href="#courses" class="btn btn-outline-secondary btn-lg rounded-pill px-4 fw-bold">Lihat Kursus</a>
                    @else
                        <a href="{{ route('siswa.all-courses') }}" class="btn btn-primary btn-lg rounded-pill px-5 shadow fw-bold">Cari Kursus</a>
                    @endguest
                </div>
                
                <div class="mt-4 pt-3 d-flex align-items-center justify-content-center justify-content-lg-start gap-4">
                    <div>
                        <h4 class="fw-bold mb-0">10k+</h4>
                        <small class="text-muted">Siswa Aktif</small>
                    </div>
                    <div class="vr"></div>
                    <div>
                        <h4 class="fw-bold mb-0">4.8/5</h4>
                        <small class="text-muted">Rating Kursus</small>
                    </div>
                    <div class="vr"></div>
                    <div>
                        <h4 class="fw-bold mb-0">50+</h4>
                        <small class="text-muted">Mentor Ahli</small>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6 text-center">
                <img src="https://cdn.dribbble.com/users/2063032/screenshots/6253683/working.png" 
                     class="img-fluid rounded-4 shadow-lg w-75" 
                     alt="Belajar Online">
            </div>
        </div>
    </div>
</section>

<div class="py-4 bg-white border-bottom border-top">
    <div class="container">
        <p class="text-center small text-muted mb-3 fw-bold">DIPERCAYA OLEH PERUSAHAAN TERKEMUKA</p>
        <div class="d-flex flex-wrap justify-content-center gap-4 gap-md-5 opacity-50 grayscale">
            <h4 class="fw-bold text-muted"><i class="bi bi-google"></i> Google</h4>
            <h4 class="fw-bold text-muted"><i class="bi bi-microsoft"></i> Microsoft</h4>
            <h4 class="fw-bold text-muted"><i class="bi bi-spotify"></i> Spotify</h4>
            <h4 class="fw-bold text-muted"><i class="bi bi-amazon"></i> Amazon</h4>
        </div>
    </div>
</div>

<section id="courses" class="py-5 bg-light">
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <h2 class="fw-bold">Kursus Pilihan <span class="text-primary">Terpopuler</span></h2>
                <p class="text-muted mb-0">Materi yang paling banyak diminati minggu ini.</p>
            </div>
            @auth
            <a href="{{ route('siswa.all-courses') }}" class="btn btn-outline-primary rounded-pill px-4 d-none d-md-block">
                Lihat Semua <i class="bi bi-arrow-right"></i>
            </a>
            @endauth
        </div>

        <div class="row g-4">
            @forelse($courses as $course)
            <div class="col-md-6 col-lg-4">
                <div class="card course-card h-100 shadow-sm">
                    <div class="course-img-wrapper position-relative bg-light">
                        @if($course->thumbnail)
                            <img src="{{ asset('storage/' . $course->thumbnail) }}" class="card-img-top h-100 w-100 object-fit-cover" alt="{{ $course->title }}">
                        @else
                            <div class="d-flex align-items-center justify-content-center h-100 text-secondary bg-secondary bg-opacity-10">
                                <i class="bi bi-card-image display-4"></i>
                            </div>
                        @endif
                        <span class="badge bg-white text-primary fw-bold position-absolute top-0 start-0 m-3 py-2 px-3 shadow-sm rounded-pill">
                            {{ $course->category->name ?? 'Umum' }}
                        </span>
                    </div>

                    <div class="card-body p-4 d-flex flex-column">
                        <div class="mb-2 d-flex align-items-center text-muted small">
                            <i class="bi bi-play-circle me-1"></i> 12 Video
                            <span class="mx-2">•</span>
                            <i class="bi bi-clock me-1"></i> 5 Jam
                        </div>
                        
                        <h5 class="card-title fw-bold text-dark mb-3">
                            <a href="{{ route('siswa.course.show', $course->slug ?? $course->id) }}" class="text-decoration-none text-dark stretched-link">
                                {{ $course->title }}
                            </a>
                        </h5>
                        
                        <div class="mt-auto pt-3 border-top d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center text-primary fw-bold" style="width: 35px; height: 35px;">
                                    {{ substr($course->pengajar->name ?? 'P', 0, 1) }}
                                </div>
                                <div class="ms-2 lh-1">
                                    <small class="text-muted d-block" style="font-size: 10px;">Pengajar</small>
                                    <small class="fw-bold text-dark">{{ explode(' ', $course->pengajar->name)[0] }}</small>
                                </div>
                            </div>
                            <div>
                                @if($course->price == 0)
                                    <span class="badge bg-success bg-opacity-10 text-success fs-6 px-3">Gratis</span>
                                @else
                                    <span class="fw-bold text-primary fs-5">Rp {{ number_format($course->price, 0, ',', '.') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="text-center py-5 bg-white rounded-4 shadow-sm">
                    <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="100" class="mb-3 opacity-50">
                    <h5 class="fw-bold text-muted">Belum ada kursus tersedia</h5>
                    <p class="text-muted">Nantikan materi menarik segera hadir!</p>
                </div>
            </div>
            @endforelse
        </div>
        
        <div class="d-block d-md-none text-center mt-4">
             <a href="{{ route('siswa.all-courses') }}" class="btn btn-outline-primary rounded-pill w-100">
                Lihat Semua Kursus
            </a>
        </div>
    </div>
</section>

<section id="features" class="py-5 bg-white">
    <div class="container py-4">
        <div class="row g-5 align-items-center">
            <div class="col-lg-5">
                <span class="text-primary fw-bold text-uppercase ls-1">Keunggulan Kami</span>
                <h2 class="display-6 fw-bold mt-2 mb-4">Belajar Lebih Efektif Dengan Metode Terbaru</h2>
                <p class="text-muted mb-4">Kami menyediakan platform belajar yang didesain agar kamu bisa menyerap materi lebih cepat dan langsung praktik.</p>
                
                <div class="d-flex mb-4">
                    <div class="flex-shrink-0">
                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle text-primary">
                            <i class="bi bi-camera-video fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5 class="fw-bold">Video Kualitas HD</h5>
                        <p class="text-muted small">Materi video yang jernih dan audio yang jelas untuk kenyamanan belajar.</p>
                    </div>
                </div>
                
                <div class="d-flex mb-4">
                    <div class="flex-shrink-0">
                        <div class="bg-success bg-opacity-10 p-3 rounded-circle text-success">
                            <i class="bi bi-patch-check fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5 class="fw-bold">Sertifikat Resmi</h5>
                        <p class="text-muted small">Dapatkan sertifikat digital yang bisa kamu lampirkan di CV.</p>
                    </div>
                </div>
                
                <div class="d-flex">
                    <div class="flex-shrink-0">
                        <div class="bg-warning bg-opacity-10 p-3 rounded-circle text-warning">
                            <i class="bi bi-life-preserver fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5 class="fw-bold">Support Mentor</h5>
                        <p class="text-muted small">Tanya jawab langsung dengan mentor jika mengalami kesulitan.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="bg-light rounded-4 p-4 p-lg-5 text-center">
                    <img src="https://cdni.iconscout.com/illustration/premium/thumb/online-education-4367375-3625732.png" class="img-fluid" alt="Features">
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="bg-primary rounded-4 p-5 text-center text-white position-relative overflow-hidden">
            <div class="position-relative z-1">
                <h2 class="fw-bold mb-3">Siap Memulai Perjalanan Belajarmu?</h2>
                <p class="lead mb-4 opacity-75">Bergabung sekarang dan dapatkan akses ke materi eksklusif.</p>
                @guest
                    <a href="{{ route('register') }}" class="btn btn-light text-primary fw-bold rounded-pill px-5 py-3 shadow">Daftar Akun Gratis</a>
                @else
                     <a href="{{ route('siswa.all-courses') }}" class="btn btn-light text-primary fw-bold rounded-pill px-5 py-3 shadow">Mulai Belajar</a>
                @endguest
            </div>
        </div>
    </div>
</section>

@endsection