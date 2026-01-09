@extends('layouts.pengajar')

@section('title', 'Dashboard Pengajar')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard Pengajar</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('pengajar.courses.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Buat Kursus Baru
            </a>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4"></div>
    <div class="col-md-3 col-6 mb-3">
        <div class="card stat-card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2">Total Kursus</h6>
                        <h2 class="card-title">{{ $totalCourses }}</h2>
                    </div>
                    <i class="bi bi-journal-text display-6 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 col-6 mb-3">
        <div class="card stat-card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2">Published</h6>
                        <h2 class="card-title">{{ $publishedCourses }}</h2>
                    </div>
                    <i class="bi bi-check-circle display-6 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 col-6 mb-3">
        <div class="card stat-card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2">Draft</h6>
                        <h2 class="card-title">{{ $draftCourses }}</h2>
                    </div>
                    <i class="bi bi-pencil display-6 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 col-6 mb-3">
        <div class="card stat-card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2">Total Siswa</h6>
                        <h2 class="card-title">{{ $totalStudents }}</h2>
                    </div>
                    <i class="bi bi-people display-6 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Kursus Terbaru -->
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Kursus Terbaru</h5>
                <a href="{{ route('pengajar.courses') }}" class="btn btn-sm btn-outline-primary">
                    Lihat Semua <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            <div class="card-body">
                @if($recentCourses->count() > 0)
                <div class="list-group list-group-flush">
                    @foreach($recentCourses as $course)
                    <div class="list-group-item border-0 px-0 py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">{{ $course->title }}</h6>
                                <small class="text-muted">
                                    <i class="bi bi-tag me-1"></i> {{ $course->category->name }}
                                    • {{ $course->enrollments_count ?? 0 }} Siswa
                                </small>
                            </div>
                            <span class="badge {{ $course->is_published ? 'badge-published' : 'badge-draft' }}">
                                {{ $course->is_published ? 'Published' : 'Draft' }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-4">
                    <i class="bi bi-journal-x display-4 text-muted"></i>
                    <p class="mt-3 text-muted">Belum ada kursus</p>
                    <a href="{{ route('pengajar.courses.create') }}" class="btn btn-primary">
                        Buat Kursus Pertama
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Siswa Baru -->
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Siswa Baru (30 hari)</h5>
                <a href="{{ route('pengajar.students') }}" class="btn btn-sm btn-outline-primary">
                    Lihat Semua <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            <div class="card-body">
                @if($recentStudents->count() > 0)
                <div class="list-group list-group-flush">
                    @foreach($recentStudents as $enrollment)
                    <div class="list-group-item border-0 px-0 py-3">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    {{ substr($enrollment->user->name, 0, 1) }}
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">{{ $enrollment->user->name }}</h6>
                                <small class="text-muted">
                                    {{ $enrollment->user->email }}
                                    • {{ $enrollment->enrolled_at->diffForHumans() }}
                                </small>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-4">
                    <i class="bi bi-people display-4 text-muted"></i>
                    <p class="mt-3 text-muted">Belum ada siswa baru</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection