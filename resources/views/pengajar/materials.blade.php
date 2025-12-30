@extends('layouts.pengajar')

@section('title', 'Kelola Materi Kursus')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <div>
        <h1 class="h2">Materi: {{ $course->title }}</h1>
        <p class="text-muted small mb-0">Kelola file PDF dan modul untuk kursus ini.</p>
    </div>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('pengajar.courses') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Kursus
        </a>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white">
                <i class="bi bi-cloud-upload me-1"></i> Upload Materi Baru
            </div>
            <div class="card-body">
                <form action="{{ route('pengajar.courses.materials.store', $course->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Judul Materi</label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" placeholder="Contoh: Modul Bab 1" required>
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small">Deskripsi (Opsional)</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="Penjelasan singkat..."></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small">File PDF</label>
                        <input type="file" name="file" class="form-control @error('file') is-invalid @enderror" accept="application/pdf" required>
                        <div class="form-text text-muted">Maksimal 10MB (PDF Only)</div>
                        @error('file') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-plus-lg me-1"></i> Tambahkan Materi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white fw-bold">
                <i class="bi bi-list-ul me-1"></i> Daftar Materi Tersedia
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="px-4" style="width: 10%">No</th>
                                <th style="width: 60%">Detail Materi</th>
                                <th class="text-end px-4" style="width: 30%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($materials as $index => $material)
                            <tr>
                                <td class="px-4 text-center">
                                    <span class="badge bg-secondary rounded-pill">{{ $index + 1 }}</span>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $material->title }}</div>
                                    @if($material->description)
                                        <small class="text-muted d-block text-truncate" style="max-width: 300px;">
                                            {{ $material->description }}
                                        </small>
                                    @endif
                                    <span class="badge bg-light text-danger border mt-1">
                                        PDF
                                    </span>
                                </td>
                                <td class="text-end px-4">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ asset('storage/' . $material->file_path) }}" class="btn btn-outline-success" target="_blank" title="Lihat/Download">
                                            <i class="bi bi-download"></i>
                                        </a>

                                        <form action="{{ route('pengajar.materials.delete', $material->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Yakin ingin menghapus materi ini?')" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center py-5 text-muted">
                                    <i class="bi bi-folder2-open display-4 opacity-50"></i>
                                    <p class="mt-2 mb-0">Belum ada materi yang diunggah.</p>
                                    <small>Gunakan form di sebelah kiri untuk menambah materi.</small>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection