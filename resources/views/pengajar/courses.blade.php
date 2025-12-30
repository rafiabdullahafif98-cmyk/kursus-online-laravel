@extends('layouts.pengajar')

@section('title', 'Kursus Saya')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Kursus Saya</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('pengajar.courses.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Buat Kursus Baru
        </a>
    </div>
</div>

{{-- Cek Flash Message --}}
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4 py-3">Judul Kursus</th>
                        <th class="py-3">Kategori</th>
                        <th class="py-3">Siswa</th>
                        <th class="py-3">Status</th>
                        <th class="py-3">Tanggal</th>
                        <th class="py-3 text-end px-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($courses as $course)
                    <tr>
                        <td class="px-4 py-3">
                            <div class="fw-bold">{{ $course->title }}</div>
                            @if($course->price > 0)
                                <small class="text-success fw-bold">Rp {{ number_format($course->price, 0, ',', '.') }}</small>
                            @else
                                <small class="text-muted fw-bold">Gratis</small>
                            @endif
                        </td>
                        <td class="py-3">
                            <span class="badge bg-light text-dark border">{{ $course->category->name ?? 'Uncategorized' }}</span>
                        </td>
                        <td class="py-3">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-people me-2 text-muted"></i>
                                <span>{{ $course->enrollments_count ?? 0 }}</span>
                            </div>
                        </td>
                        <td class="py-3">
                            @if($course->is_published)
                                <span class="badge bg-success">Published</span>
                            @else
                                <span class="badge bg-secondary">Draft</span>
                            @endif
                        </td>
                        <td class="py-3 text-muted small">
                            {{ $course->created_at->format('d M Y') }}
                        </td>
                        <td class="py-3 text-end px-4">
                            <div class="btn-group btn-group-sm shadow-sm">
                                
                                {{-- === TOMBOL MATERI (Fitur Baru) === --}}
                                <a href="{{ route('pengajar.courses.materials', $course->id) }}" 
                                   class="btn btn-outline-info text-dark" 
                                   title="Kelola Materi & PDF">
                                    <i class="bi bi-file-earmark-arrow-up"></i>
                                </a>

                                <a href="{{ route('pengajar.courses.edit', $course->id) }}" 
                                   class="btn btn-outline-warning text-dark"
                                   title="Edit Kursus">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <button type="button" class="btn btn-outline-danger" 
                                        onclick="if(confirm('Hapus kursus ini beserta semua materinya?')) {
                                            document.getElementById('delete-form-{{ $course->id }}').submit();
                                        }" title="Hapus Kursus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>

                            <form id="delete-form-{{ $course->id }}" 
                                  action="{{ route('pengajar.courses.delete', $course->id) }}" 
                                  method="POST" class="d-none">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <i class="bi bi-journal-x display-4 text-muted opacity-25"></i>
                            <h5 class="mt-3 text-muted">Belum ada kursus</h5>
                            <p class="small text-muted mb-3">Mulai bagikan ilmu Anda dengan membuat kursus pertama.</p>
                            <a href="{{ route('pengajar.courses.create') }}" class="btn btn-primary btn-sm">
                                <i class="bi bi-plus-lg"></i> Buat Kursus Pertama
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="d-flex justify-content-center mt-4">
    {{ $courses->links() }}
</div>
@endsection