@extends('layouts.pengajar')

@section('title', 'Edit Kursus: ' . $course->title)

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Edit Kursus</h1>
    <span class="badge {{ $course->is_published ? 'bg-success' : 'bg-secondary' }}">
        Status: {{ $course->is_published ? 'Published' : 'Draft' }}
    </span>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <form action="{{ route('pengajar.courses.update', $course->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            {{-- Karena di web.php kamu menggunakan POST untuk update, kita ikuti POST --}}
            {{-- Jika nanti diubah ke PUT di web.php, tambahkan @method('PUT') di sini --}}

            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Judul Kursus</label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" 
                               value="{{ old('title', $course->title) }}">
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Ringkasan Singkat</label>
                        <textarea name="short_description" class="form-control @error('short_description') is-invalid @enderror" rows="2">{{ old('short_description', $course->short_description) }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Deskripsi Lengkap</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="8">{{ old('description', $course->description) }}</textarea>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card bg-light border-0 py-2">
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Kategori</label>
                                <select name="category_id" class="form-select @error('category_id') is-invalid @enderror">
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ (old('category_id', $course->category_id) == $category->id) ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Harga (Rp)</label>
                                <input type="number" name="price" class="form-control" value="{{ old('price', $course->price) }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Thumbnail Saat Ini</label>
                                @if($course->thumbnail)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $course->thumbnail) }}" class="img-thumbnail w-100" style="height: 150px; object-fit: cover;">
                                    </div>
                                @endif
                                <input type="file" name="thumbnail" class="form-control @error('thumbnail') is-invalid @enderror">
                                <small class="text-muted">Biarkan kosong jika tidak ingin mengganti gambar.</small>
                            </div>

                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_published" id="is_published" 
                                           {{ old('is_published', $course->is_published) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold" for="is_published">Publish Kursus</label>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Update Kursus</button>
                                <a href="{{ route('pengajar.courses') }}" class="btn btn-outline-secondary">Kembali</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection