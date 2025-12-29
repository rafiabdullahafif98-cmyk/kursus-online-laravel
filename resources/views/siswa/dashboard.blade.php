@extends('layouts.siswa')

@section('title', 'Dashboard Siswa')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('siswa.all-courses') }}" class="btn btn-primary">
                <i class="bi bi-search me-1"></i> Cari Kursus Baru
            </a>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="card stat-card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2">Total Kursus</h6>
                        <h2 class="card-title">{{ $totalEnrolled }}</h2>
                    </div>
                    <i class="bi bi-collection-play display-6 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card stat-card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2">Sedang Dipelajari</h6>
                        <h2 class="card-title">{{ $activeCourses }}</h2>
                    </div>
                    <i class="bi bi-play-circle display-6 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card stat-card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2">Selesai</h6>
                        <h2 class="card-title">{{ $completedCourses }}</h2>
                    </div>
                    <i class="bi bi-check-circle display-6 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- My Courses -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Kursus Saya</h5>
                <a href="{{ route('siswa.courses') }}" class="btn btn-sm btn-outline-primary">
                    Lihat Semua <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            <div class="card-body">
                @if($enrolledCourses->count() > 0)
                <div class="row">
                    @foreach($enrolledCourses as $course)
                    <div class="col-md-6 col-lg-3 mb-3">
                        <div class="card course-card h-100">
                            @if($course->thumbnail)
                            <img src="{{ asset('storage/' . $course->thumbnail) }}" class="card-img-top" alt="{{ $course->title }}" style="height: 150px; object-fit: cover;">
                            @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 150px;">
                                <i class="bi bi-journal-text display-4 text-muted"></i>
                            </div>
                            @endif
                            <div class="card-body">
                                <h6 class="card-title">{{ Str::limit($course->title, 50) }}</h6>
                                <p class="card-text small text-muted">
                                    <i class="bi bi-person"></i> {{ $course->pengajar->name }}
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-primary">{{ $course->category->name }}</span>
                                    <a href="{{ route('siswa.course.show', $course->slug) }}" class="btn btn-sm btn-outline-primary">
                                        Lanjut
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-5">
                    <i class="bi bi-journal-x display-1 text-muted"></i>
                    <h5 class="mt-3">Belum ada kursus</h5>
                    <p class="text-muted">Mulai belajar dengan mendaftar ke kursus pertama Anda</p>
                    <a href="{{ route('siswa.all-courses') }}" class="btn btn-primary">
                        <i class="bi bi-search me-1"></i> Cari Kursus
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Recommended Courses -->
@if($recommendedCourses->count() > 0)
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Rekomendasi Kursus</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($recommendedCourses as $course)
                    <div class="col-md-4 mb-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <h6 class="card-title">{{ $course->title }}</h6>
                                <p class="card-text small text-muted">
                                    {{ Str::limit($course->short_description ?? $course->description, 100) }}
                                </p>
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <div>
                                        <span class="badge bg-secondary">{{ $course->category->name }}</span>
                                        <small class="text-muted d-block">
                                            <i class="bi bi-person"></i> {{ $course->pengajar->name }}
                                        </small>
                                    </div>
                                    <form action="{{ route('siswa.enroll', $course->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-primary">
                                            Daftar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection