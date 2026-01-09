@extends('layouts.main')

@section('title', 'Beranda')

@section('content')

<section class="hero-section text-center">
    <div class="container">
        <h1 class="display-4 fw-bold mb-3">Tingkatkan Keahlianmu Sekarang</h1>
        <p class="lead mb-4 opacity-75">Bergabung dengan ribuan siswa lainnya dan pelajari skill baru dari para ahli.</p>
        
        @guest
            <a href="{{ route('register') }}" class="btn btn-light btn-lg px-5 fw-bold text-primary shadow-sm">
                Mulai Belajar Gratis
            </a>
        @else
            <a href="{{ route('siswa.all-courses') }}" class="btn btn-light btn-lg px-5 fw-bold text-primary shadow-sm">
                Cari Kursus
            </a>
        @endguest
    </div>
</section>

<div class="bg-light py-4 border-bottom">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-4">
                <h3 class="fw-bold text-primary">100+</h3>
                <p class="text-muted">Kursus Berkualitas</p>
            </div>
            <div class="col-md-4">
                <h3 class="fw-bold text-primary">50+</h3>
                <p class="text-muted">Pengajar Ahli</p>
            </div>
            <div class="col-md-4">
                <h3 class="fw-bold text-primary">1000+</h3>
                <p class="text-muted">Siswa Aktif</p>
            </div>
        </div>
    </div>
</div>

<section id="courses" class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Kursus Terbaru</h2>
            @auth
            <a href="{{ route('siswa.all-courses') }}" class="text-decoration-none fw-bold">
                Lihat Semua <i class="bi bi-arrow-right"></i>
            </a>
            @endauth
        </div>

        <div class="row">
            @forelse($courses as $course)
            <div class="col-md-4 mb-4">
                <div class="card h-100 border-0 shadow-sm course-card">
                    
                    <div class="position-relative">
                        @if($course->thumbnail)
                            <img src="{{ asset('storage/' . $course->thumbnail) }}" class="card-img-top" alt="{{ $course->title }}" style="height: 200px; object-fit: cover;">
                        @else
                            <div class="bg-secondary bg-opacity-10 d-flex align-items-center justify-content-center" style="height: 200px;">
                                <i class="bi bi-image text-muted display-4"></i>
                            </div>
                        @endif
                        <span class="badge bg-primary position-absolute top-0 end-0 m-3">
                            {{ $course->category->name ?? 'Umum' }}
                        </span>
                    </div>

                    <div class="card-body">
                        <h5 class="card-title fw-bold text-truncate" title="{{ $course->title }}">
                            {{ $course->title }}
                        </h5>
                        <p class="card-text text-muted small">
                            {{ Str::limit($course->short_description ?? $course->description, 80) }}
                        </p>
                        
                        <div class="d-flex align-items-center mt-3">
                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 30px; height: 30px;">
                                <i class="bi bi-person-fill text-secondary"></i>
                            </div>
                            <small class="text-muted">{{ $course->pengajar->name ?? 'Pengajar' }}</small>
                        </div>
                    </div>

                    <div class="card-footer bg-white border-top-0 pb-3 d-flex justify-content-between align-items-center">
                        <div>
                            @if($course->price == 0)
                                <span class="badge bg-success">Gratis</span>
                            @else
                                <span class="fw-bold text-primary">Rp {{ number_format($course->price, 0, ',', '.') }}</span>
                            @endif
                        </div>
                        
                        @auth
                            <a href="{{ route('siswa.course.show', $course->slug ?? $course->id) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-sm btn-outline-primary">Masuk</a>
                        @endauth
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <div class="alert alert-info border-0 shadow-sm">
                    <i class="bi bi-info-circle me-2"></i> Belum ada kursus yang tersedia saat ini.
                </div>
            </div>
            @endforelse
        </div>
    </div>
</section>

<section id="features" class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Kenapa Memilih Kami?</h2>
            <p class="text-muted">Platform belajar yang didesain untuk kemajuan karir Anda.</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4 text-center">
                <div class="bg-white p-4 rounded shadow-sm h-100">
                    <i class="bi bi-laptop display-4 text-primary mb-3"></i>
                    <h5 class="fw-bold">Akses Fleksibel</h5>
                    <p class="text-muted">Belajar kapan saja dan di mana saja sesuai kecepatanmu sendiri.</p>
                </div>
            </div>
            <div class="col-md-4 text-center">
                <div class="bg-white p-4 rounded shadow-sm h-100">
                    <i class="bi bi-patch-check display-4 text-primary mb-3"></i>
                    <h5 class="fw-bold">Materi Berkualitas</h5>
                    <p class="text-muted">Kurikulum disusun oleh praktisi ahli di bidangnya.</p>
                </div>
            </div>
            <div class="col-md-4 text-center">
                <div class="bg-white p-4 rounded shadow-sm h-100">
                    <i class="bi bi-award display-4 text-primary mb-3"></i>
                    <h5 class="fw-bold">Sertifikat</h5>
                    <p class="text-muted">Dapatkan sertifikat kelulusan untuk menunjang portofoliomu.</p>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection