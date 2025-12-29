@extends('layouts.siswa')

@section('title', 'Kursus Saya')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Kursus Saya</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('siswa.all-courses') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Cari Kursus Baru
        </a>
    </div>
</div>

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

<!-- ========== STATUS FILTER ========== -->
<div class="row mb-4">
    <div class="col-md-3">
        <label for="statusFilter" class="form-label">Filter Status</label>
        <select class="form-select" id="statusFilter">
            <option value="">Semua Status</option>
            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
        </select>
    </div>

    <div class="col-md-9 d-flex align-items-end justify-content-end">
        <div class="btn-group" role="group">
            <a href="{{ route('siswa.courses') }}?status=active"
                class="btn btn-outline-primary {{ !request('status') || request('status') == 'active' ? 'active' : '' }}">
                Aktif
            </a>
            <a href="{{ route('siswa.courses') }}?status=completed"
                class="btn btn-outline-success {{ request('status') == 'completed' ? 'active' : '' }}">
                Selesai
            </a>
            <a href="{{ route('siswa.courses') }}?status=cancelled"
                class="btn btn-outline-secondary {{ request('status') == 'cancelled' ? 'active' : '' }}">
                Dibatalkan
            </a>
        </div>
    </div>
</div>
<!-- ========== END STATUS FILTER ========== -->

@if($enrollments->count() > 0)
<div class="row">
    @foreach($enrollments as $enrollment)
    @php
    $course = $enrollment->course;
    if (!$course) continue;
    @endphp

    <div class="col-md-6 col-lg-4 mb-4">
        <div class="card course-card h-100">
            @if($course->thumbnail)
            <img src="{{ asset('storage/' . $course->thumbnail) }}"
                class="card-img-top"
                alt="{{ $course->title }}"
                style="height: 180px; object-fit: cover;">
            @else
            <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                style="height: 180px;">
                <i class="bi bi-journal-text display-3 text-muted"></i>
            </div>
            @endif

            <div class="card-body">
                <h5 class="card-title">{{ Illuminate\Support\Str::limit($course->title, 60) }}</h5>

                <p class="card-text text-muted small mb-3">
                    {{ Illuminate\Support\Str::limit($course->short_description ?? $course->description, 100) }}
                </p>

                <div class="mb-3">
                    <span class="badge bg-primary">{{ $course->category->name ?? 'Uncategorized' }}</span>
                    @php
                    $materialsCount = \App\Models\Material::where('course_id', $course->id)->count();
                    @endphp
                    @if($materialsCount > 0)
                    <span class="badge bg-secondary ms-1">{{ $materialsCount }} Materi</span>
                    @endif
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        <i class="bi bi-person"></i> {{ $course->pengajar->name ?? 'Pengajar' }}
                    </small>

                    <div>
                        <a href="{{ route('siswa.course.show', $course->slug) }}"
                            class="btn btn-primary btn-sm">
                            <i class="bi bi-play-circle me-1"></i> Lanjutkan
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-footer bg-transparent">
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        <i class="bi bi-calendar me-1"></i>
                        Terdaftar: {{ \Carbon\Carbon::parse($enrollment->enrolled_at)->format('d M Y') }}
                    </small>

                    <span class="badge bg-{{ $enrollment->status == 'active' ? 'success' : ($enrollment->status == 'completed' ? 'warning' : 'secondary') }}">
                        {{ $enrollment->status == 'active' ? 'Aktif' : ($enrollment->status == 'completed' ? 'Selesai' : 'Lainnya') }}
                    </span>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Pagination -->
<div class="d-flex justify-content-center mt-4">
    {{ $enrollments->links() }}
</div>

@else
<div class="text-center py-5">
    <i class="bi bi-journal-x display-1 text-muted"></i>
    <h3 class="mt-3">Belum ada kursus</h3>
    <p class="text-muted">Anda belum mendaftar ke kursus apapun</p>
    <a href="{{ route('siswa.all-courses') }}" class="btn btn-primary btn-lg">
        <i class="bi bi-search me-1"></i> Cari Kursus Pertama Anda
    </a>
</div>
@endif

@push('scripts')
<script>
    // Simple filter by status
    document.addEventListener('DOMContentLoaded', function() {
        const statusFilter = document.getElementById('statusFilter');
        if (statusFilter) {
            statusFilter.addEventListener('change', function() {
                window.location.href = "{{ route('siswa.courses') }}?status=" + this.value;
            });
        }
    });
</script>
@endpush
@endsection