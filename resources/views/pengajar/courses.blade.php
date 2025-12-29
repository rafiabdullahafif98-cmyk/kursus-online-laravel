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

<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Judul Kursus</th>
                <th>Kategori</th>
                <th>Siswa</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($courses as $course)
            <tr>
                <td>
                    <strong>{{ $course->title }}</strong>
                    @if($course->price > 0)
                    <br><small class="text-success">Rp {{ number_format($course->price, 0, ',', '.') }}</small>
                    @else
                    <br><small class="text-info">Gratis</small>
                    @endif
                </td>
                <td>{{ $course->category->name }}</td>
                <td>
                    <span class="badge bg-primary">{{ $course->enrollments_count ?? 0 }}</span>
                </td>
                <td>
                    @if($course->is_published)
                    <span class="badge bg-success">Published</span>
                    @else
                    <span class="badge bg-warning">Draft</span>
                    @endif
                </td>
                <td>{{ $course->created_at->format('d M Y') }}</td>
                <td>
                    <div class="btn-group btn-group-sm">
                        <a href="{{ route('pengajar.courses.edit', $course->id) }}" class="btn btn-outline-primary">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <button type="button" class="btn btn-outline-danger" 
                                onclick="if(confirm('Hapus kursus ini?')) {
                                    document.getElementById('delete-form-{{ $course->id }}').submit();
                                }">
                            <i class="bi bi-trash"></i>
                        </button>
                        <form id="delete-form-{{ $course->id }}" 
                              action="{{ route('pengajar.courses.delete', $course->id) }}" 
                              method="POST" class="d-none">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center py-4">
                    <i class="bi bi-journal-x display-4 text-muted"></i>
                    <h5 class="mt-3">Belum ada kursus</h5>
                    <p class="text-muted">Mulai dengan membuat kursus pertama Anda</p>
                    <a href="{{ route('pengajar.courses.create') }}" class="btn btn-primary">
                        Buat Kursus Pertama
                    </a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div class="d-flex justify-content-center">
    {{ $courses->links() }}
</div>
@endsection