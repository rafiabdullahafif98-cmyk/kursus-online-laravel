@extends('layouts.siswa')

@section('title', $course->title)

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">{{ $course->title }}</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('siswa.courses') }}" class="btn btn-outline-secondary me-2">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
        
        @if($enrollment)
            @if($enrollment->status === 'active')
            <span class="badge bg-success me-2 py-2 px-3">
                <i class="bi bi-check-circle me-1"></i> Terdaftar
            </span>
            @elseif($enrollment->status === 'completed')
            <span class="badge bg-warning me-2 py-2 px-3">
                <i class="bi bi-award me-1"></i> Selesai
            </span>
            @endif
        @endif
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="row">
    <!-- Main Content -->
    <div class="col-lg-8">
        <!-- Course Overview -->
        <div class="card mb-4">
            @if($course->thumbnail)
            <img src="{{ asset('storage/' . $course->thumbnail) }}" 
                 class="card-img-top" 
                 alt="{{ $course->title }}"
                 style="max-height: 400px; object-fit: cover;">
            @endif
            
            <div class="card-body">
                <div class="mb-3">
                    <span class="badge bg-primary">{{ $course->category->name }}</span>
                    @if($course->price > 0)
                    <span class="badge bg-success">Rp {{ number_format($course->price, 0, ',', '.') }}</span>
                    @else
                    <span class="badge bg-info">Gratis</span>
                    @endif
                    <span class="badge bg-secondary ms-1">
                        <i class="bi bi-people"></i> {{ $course->enrollments_count }} Siswa
                    </span>
                </div>
                
                <h4 class="card-title">Deskripsi Kursus</h4>
                <p class="card-text">{!! nl2br(e($course->description)) !!}</p>
                
                <div class="row mt-4">
                    <div class="col-md-6">
                        <h5><i class="bi bi-person-circle text-primary me-2"></i>Pengajar</h5>
                        <div class="d-flex align-items-center mt-2">
                            <div class="flex-shrink-0">
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" 
                                     style="width: 50px; height: 50px;">
                                    <i class="bi bi-person fs-4"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">{{ $course->pengajar->name }}</h6>
                                <small class="text-muted">Pengajar</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <h5><i class="bi bi-info-circle text-primary me-2"></i>Informasi</h5>
                        <ul class="list-unstyled mt-2">
                            <li class="mb-2">
                                <i class="bi bi-calendar me-2"></i>
                                Dibuat: {{ $course->created_at->format('d M Y') }}
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-collection me-2"></i>
                                {{ $materials->count() }} Materi
                            </li>
                            <li>
                                <i class="bi bi-clock me-2"></i>
                                Terakhir update: {{ $course->updated_at->diffForHumans() }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Course Materials -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-journal-text me-2"></i>Materi Kursus
                    <span class="badge bg-secondary ms-2">{{ $materials->count() }}</span>
                </h5>
            </div>
            <div class="card-body">
                @if($materials->count() > 0)
                <div class="list-group">
                    @foreach($materials->sortBy('order') as $material)
                    <div class="list-group-item">
                        <div class="d-flex w-100 justify-content-between align-items-center">
                            <div class="d-flex align-items-center" style="flex: 1;">
                                <!-- Checkbox for completion -->
                                <div class="form-check me-3">
                                    <input class="form-check-input material-checkbox" 
                                           type="checkbox" 
                                           data-course-id="{{ $course->id }}"
                                           data-material-id="{{ $material->id }}"
                                           id="material_{{ $material->id }}"
                                           @if($material->isCompletedByUser(auth()->id() ?? 0))
                                           checked
                                           @endif>
                                    <label class="form-check-label" for="material_{{ $material->id }}"></label>
                                </div>
                                
                                @switch($material->file_type)
                                    @case('pdf')
                                        <i class="bi bi-file-earmark-pdf text-danger fs-4 me-3"></i>
                                        @break
                                    @case('video')
                                        <i class="bi bi-play-circle text-primary fs-4 me-3"></i>
                                        @break
                                    @case('ppt')
                                        <i class="bi bi-file-earmark-slides text-warning fs-4 me-3"></i>
                                        @break
                                    @case('doc')
                                        <i class="bi bi-file-earmark-word text-info fs-4 me-3"></i>
                                        @break
                                    @default
                                        <i class="bi bi-file-earmark text-secondary fs-4 me-3"></i>
                                @endswitch
                                
                                <div style="flex: 1;">
                                    <h6 class="mb-1">{{ $material->title }}</h6>
                                    @if($material->description)
                                    <p class="mb-1 text-muted small">{{ $material->description }}</p>
                                    @endif
                                    <small class="text-muted">
                                        <i class="bi bi-clock"></i> {{ $material->created_at->format('d M Y') }}
                                    </small>
                                </div>
                            </div>
                            
                            <div>
                                @if($material->file_path)
                                <a href="{{ asset('storage/' . $material->file_path) }}" 
                                   class="btn btn-sm btn-outline-primary" 
                                   target="_blank" 
                                   download
                                   onclick="markAsDownloaded({{ $course->id }}, {{ $material->id }})">
                                    <i class="bi bi-download me-1"></i> Unduh
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-4">
                    <i class="bi bi-journal-x display-1 text-muted"></i>
                    <p class="mt-3 text-muted">Belum ada materi untuk kursus ini</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Enrollment Status -->
        @if($enrollment)
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-check-circle me-2"></i>Status Pendaftaran</h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    @if($enrollment->status === 'active')
                    <div class="mb-3">
                        <i class="bi bi-check-circle-fill text-success display-4"></i>
                        <h5 class="mt-2">Anda terdaftar</h5>
                    </div>
                    @elseif($enrollment->status === 'completed')
                    <div class="mb-3">
                        <i class="bi bi-award-fill text-warning display-4"></i>
                        <h5 class="mt-2">Kursus Selesai</h5>
                    </div>
                    @endif
                </div>
                
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <i class="bi bi-calendar-check me-2"></i>
                        <strong>Tanggal Daftar:</strong>
                        {{ \Carbon\Carbon::parse($enrollment->enrolled_at)->format('d M Y') }}
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-clock me-2"></i>
                        <strong>Status:</strong>
                        <span class="badge bg-{{ $enrollment->status === 'active' ? 'success' : ($enrollment->status === 'completed' ? 'warning' : 'secondary') }}">
                            {{ $enrollment->status === 'active' ? 'Aktif' : ($enrollment->status === 'completed' ? 'Selesai' : 'Lainnya') }}
                        </span>
                    </li>
                    <li>
                        <i class="bi bi-book me-2"></i>
                        <strong>Progress:</strong>
                        <div class="progress mt-2" style="height: 10px;">
                            <div class="progress-bar bg-primary" 
                                 role="progressbar" 
                                 style="width: {{ $progress }}%"
                                 id="progressBar">
                            </div>
                        </div>
                        <small class="text-muted" id="progressText">
                            {{ $progress }}% selesai 
                            @php
                                $completedCount = 0;
                                if ($enrollment) {
                                    $completedCount = \App\Models\ProgressTracking::where('user_id', auth()->id())
                                        ->where('course_id', $course->id)
                                        ->where('is_completed', true)
                                        ->count();
                                }
                            @endphp
                            ({{ $completedCount }} dari {{ $materials->count() }} materi)
                        </small>
                    </li>
                </ul>
            </div>
        </div>
        @else
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Daftar Kursus</h5>
            </div>
            <div class="card-body">
                <p>Anda belum terdaftar di kursus ini.</p>
                <form action="{{ route('siswa.enroll', $course->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-plus-circle me-1"></i> Daftar Sekarang
                    </button>
                </form>
            </div>
        </div>
        @endif

        <!-- Course Stats -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-bar-chart me-2"></i>Statistik</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <div class="bg-light rounded p-3">
                            <i class="bi bi-people display-6 text-primary"></i>
                            <h4 class="mt-2">{{ $course->enrollments_count }}</h4>
                            <small class="text-muted">Siswa</small>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="bg-light rounded p-3">
                            <i class="bi bi-journal-text display-6 text-success"></i>
                            <h4 class="mt-2">{{ $materials->count() }}</h4>
                            <small class="text-muted">Materi</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Other Courses from Same Teacher -->
        @if($otherCourses->count() > 0)
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-book me-2"></i>Kursus Lainnya</h5>
            </div>
            <div class="card-body">
                <div class="list-group">
                    @foreach($otherCourses as $otherCourse)
                    <a href="{{ route('siswa.course.show', $otherCourse->slug) }}" 
                       class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">{{ $otherCourse->title }}</h6>
                            @if($otherCourse->price > 0)
                            <small class="text-success">Rp {{ number_format($otherCourse->price, 0, ',', '.') }}</small>
                            @else
                            <small class="text-info">Gratis</small>
                            @endif
                        </div>
                        <small class="text-muted">
                            {{ $otherCourse->enrollments_count }} siswa • 
                            {{ \Carbon\Carbon::parse($otherCourse->created_at)->format('M Y') }}
                        </small>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
// Update progress when checkbox is clicked
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.material-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const courseId = this.getAttribute('data-course-id');
            const materialId = this.getAttribute('data-material-id');
            const isChecked = this.checked;
            
            const endpoint = isChecked 
                ? '/siswa/progress/complete' 
                : '/siswa/progress/incomplete';
            
            fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    course_id: courseId,
                    material_id: materialId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update progress bar
                    const progressBar = document.getElementById('progressBar');
                    const progressText = document.getElementById('progressText');
                    
                    if (progressBar && progressText) {
                        progressBar.style.width = data.progress + '%';
                        progressText.textContent = data.progress + '% selesai (' + data.completed + ' dari ' + data.total + ' materi)';
                    }
                    
                    // Show success message
                    showToast('success', data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('error', 'Terjadi kesalahan');
                this.checked = !isChecked; // Revert checkbox
            });
        });
    });
    
    // Function to mark as downloaded
    window.markAsDownloaded = function(courseId, materialId) {
        // Auto-check the material when downloaded
        const checkbox = document.getElementById('material_' + materialId);
        if (checkbox && !checkbox.checked) {
            checkbox.checked = true;
            checkbox.dispatchEvent(new Event('change'));
        }
    };
    
    // Toast notification function
    function showToast(type, message) {
        // Create toast element
        const toastId = 'toast-' + Date.now();
        const toastHtml = `
            <div id="${toastId}" class="position-fixed top-0 end-0 p-3" style="z-index: 11">
                <div class="toast align-items-center text-white bg-${type === 'success' ? 'success' : 'danger'} border-0" role="alert">
                    <div class="d-flex">
                        <div class="toast-body">
                            ${type === 'success' ? '✓ ' : '✗ '}${message}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                    </div>
                </div>
            </div>
        `;
        
        // Add toast to body
        document.body.insertAdjacentHTML('beforeend', toastHtml);
        
        // Show and auto-remove toast
        const toastEl = document.getElementById(toastId);
        const toast = new bootstrap.Toast(toastEl.querySelector('.toast'));
        toast.show();
        
        // Remove toast after 3 seconds
        setTimeout(() => {
            toastEl.remove();
        }, 3000);
    }
});
</script>
@endpush

@push('styles')
<style>
    .list-group-item {
        border-left: none;
        border-right: none;
        border-radius: 0;
    }
    
    .list-group-item:first-child {
        border-top: none;
    }
    
    .list-group-item:last-child {
        border-bottom: none;
    }
    
    .progress {
        background-color: #e9ecef;
    }
    
    .material-checkbox {
        width: 20px;
        height: 20px;
        cursor: pointer;
    }
</style>
@endpush
@endsection