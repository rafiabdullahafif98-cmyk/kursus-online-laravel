@extends('layouts.siswa')

@section('title', 'Semua Kursus')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Semua Kursus</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('siswa.courses') }}" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left me-1"></i> Kursus Saya
            </a>
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Cari kursus..." id="searchInput">
                <button class="btn btn-outline-secondary" type="button" id="searchButton">
                    <i class="bi bi-search"></i>
                </button>
            </div>
        </div>
        <div class="col-md-3">
            <select class="form-select" id="categoryFilter">
                <option value="">Semua Kategori</option>
                @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }} ({{ $category->courses_count }})
                </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select class="form-select" id="sortFilter">
                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Terpopuler</option>
                <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Harga: Rendah ke Tinggi</option>
                <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Harga: Tinggi ke Rendah</option>
            </select>
        </div>
    </div>

    <!-- Courses Grid -->
    <div class="row" id="coursesContainer">
        @if($courses->count() > 0)
            @foreach($courses as $course)
            <div class="col-md-6 col-lg-4 mb-4 course-item" 
                 data-category="{{ $course->category_id }}"
                 data-title="{{ strtolower($course->title) }}">
                <div class="card h-100">
                    <!-- Thumbnail -->
                    @if($course->thumbnail)
                    <img src="{{ asset('storage/' . $course->thumbnail) }}" 
                         class="card-img-top" 
                         alt="{{ $course->title }}" 
                         style="height: 200px; object-fit: cover;">
                    @else
                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                         style="height: 200px;">
                        <i class="bi bi-journal-text display-4 text-muted"></i>
                    </div>
                    @endif
                    
                    <div class="card-body">
                        <h5 class="card-title">{{ $course->title }}</h5>
                        
                        <p class="card-text text-muted small mb-3">
                            @php
                                $description = $course->short_description ?? $course->description;
                                echo Illuminate\Support\Str::limit($description, 120);
                            @endphp
                        </p>
                        
                        <div class="mb-3">
                            <span class="badge bg-primary">{{ $course->category->name }}</span>
                            @if($course->price > 0)
                            <span class="badge bg-success">Rp {{ number_format($course->price, 0, ',', '.') }}</span>
                            @else
                            <span class="badge bg-info">Gratis</span>
                            @endif
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted">
                                    <i class="bi bi-person"></i> {{ $course->pengajar->name }}
                                </small>
                                <br>
                                <small class="text-muted">
                                    <i class="bi bi-people"></i> {{ $course->enrollments_count }} Siswa
                                </small>
                            </div>
                            
                            <div>
                                @if(in_array($course->id, $enrolledCourseIds ?? []))
                                <button class="btn btn-success btn-sm" disabled>
                                    <i class="bi bi-check-circle me-1"></i> Terdaftar
                                </button>
                                @else
                                <form action="{{ route('siswa.enroll', $course->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-sm enroll-btn">
                                        <i class="bi bi-plus-circle me-1"></i> Daftar
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer bg-transparent">
                        <div class="d-flex justify-content-between">
                            <small class="text-muted">
                                {{ $course->materials_count }} Materi
                            </small>
                            <small class="text-muted">
                                <i class="bi bi-clock"></i> {{ $course->created_at->diffForHumans() }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        @else
        <div class="col-12">
            <div class="text-center py-5">
                <i class="bi bi-journal-x display-1 text-muted"></i>
                <h3 class="mt-3">Tidak ada kursus tersedia</h3>
                <p class="text-muted">Belum ada kursus yang dipublikasikan</p>
            </div>
        </div>
        @endif
    </div>

    <!-- No Results Message -->
    <div id="noResults" class="text-center py-5" style="display: none;">
        <i class="bi bi-search display-1 text-muted"></i>
        <h3 class="mt-3">Kursus tidak ditemukan</h3>
        <p class="text-muted">Coba kata kunci atau filter yang berbeda</p>
    </div>

    <!-- Pagination -->
    @if($courses->count() > 0)
    <div class="d-flex justify-content-center mt-4">
        {{ $courses->links() }}
    </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const searchButton = document.getElementById('searchButton');
    const categoryFilter = document.getElementById('categoryFilter');
    const coursesContainer = document.getElementById('coursesContainer');
    const noResults = document.getElementById('noResults');
    const courseItems = document.querySelectorAll('.course-item');
    
    if (!courseItems.length) return;
    
    function filterCourses() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedCategory = categoryFilter.value;
        let visibleCount = 0;
        
        courseItems.forEach(item => {
            const title = item.getAttribute('data-title') || '';
            const category = item.getAttribute('data-category') || '';
            let isVisible = true;
            
            if (searchTerm && !title.includes(searchTerm)) {
                isVisible = false;
            }
            
            if (selectedCategory && category !== selectedCategory) {
                isVisible = false;
            }
            
            item.style.display = isVisible ? 'block' : 'none';
            if (isVisible) visibleCount++;
        });
        
        if (visibleCount === 0) {
            noResults.style.display = 'block';
            coursesContainer.style.display = 'none';
        } else {
            noResults.style.display = 'none';
            coursesContainer.style.display = 'block';
        }
    }
    
    if (searchInput) searchInput.addEventListener('keyup', filterCourses);
    if (searchButton) searchButton.addEventListener('click', filterCourses);
    if (categoryFilter) categoryFilter.addEventListener('change', filterCourses);
    
    // Initialize
    filterCourses();
});
</script>
@endsection