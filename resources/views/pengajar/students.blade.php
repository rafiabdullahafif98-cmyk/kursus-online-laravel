@extends('layouts.pengajar')

@section('title', 'Buat Kursus Baru')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Buat Kursus Baru</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('pengajar.courses') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('pengajar.courses.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Judul Kursus -->
                    <div class="mb-3">
                        <label for="title" class="form-label">Judul Kursus <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                               id="title" name="title" value="{{ old('title') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Judul yang menarik akan lebih banyak diminati siswa</small>
                    </div>

                    <!-- Kategori -->
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Kategori <span class="text-danger">*</span></label>
                        <select class="form-select @error('category_id') is-invalid @enderror" 
                                id="category_id" name="category_id" required>
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Deskripsi Singkat -->
                    <div class="mb-3">
                        <label for="short_description" class="form-label">Deskripsi Singkat</label>
                        <textarea class="form-control @error('short_description') is-invalid @enderror" 
                                  id="short_description" name="short_description" rows="3">{{ old('short_description') }}</textarea>
                        @error('short_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Maksimal 500 karakter. Akan muncul di halaman list kursus</small>
                    </div>

                    <!-- Deskripsi Lengkap -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi Lengkap <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="8" required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Jelaskan secara detail apa yang akan dipelajari dalam kursus ini</small>
                    </div>

                    <!-- Harga -->
                    <div class="mb-3">
                        <label for="price" class="form-label">Harga Kursus (Rp)</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                   id="price" name="price" value="{{ old('price', 0) }}" min="0" step="1000">
                        </div>
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Kosongkan atau isi 0 untuk kursus gratis</small>
                    </div>

                    <!-- Thumbnail -->
                    <div class="mb-4">
                        <label for="thumbnail" class="form-label">Thumbnail Kursus</label>
                        <input type="file" class="form-control @error('thumbnail') is-invalid @enderror" 
                               id="thumbnail" name="thumbnail" accept="image/*">
                        @error('thumbnail')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Format: JPG, PNG, GIF. Maksimal 2MB</small>
                        
                        <!-- Preview Thumbnail -->
                        <div class="mt-2" id="thumbnailPreview"></div>
                    </div>

                    <!-- Status Publish -->
                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" 
                                   id="is_published" name="is_published" value="1" {{ old('is_published') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_published">
                                <strong>Publikasikan Sekarang</strong>
                            </label>
                        </div>
                        <small class="form-text text-muted">
                            Jika dinonaktifkan, kursus akan disimpan sebagai draft dan tidak bisa dilihat siswa
                        </small>
                    </div>

                    <!-- Tombol Submit -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('pengajar.courses') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle me-1"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> Simpan Kursus
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Preview thumbnail sebelum upload
    document.getElementById('thumbnail').addEventListener('change', function(e) {
        const preview = document.getElementById('thumbnailPreview');
        preview.innerHTML = '';
        
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.classList.add('img-thumbnail', 'mt-2');
                img.style.maxWidth = '200px';
                img.style.maxHeight = '200px';
                preview.appendChild(img);
            }
            
            reader.readAsDataURL(this.files[0]);
        }
    });

    // Auto count characters for short description
    document.getElementById('short_description').addEventListener('input', function() {
        const charCount = this.value.length;
        const counter = document.getElementById('shortDescCounter') || (() => {
            const counter = document.createElement('small');
            counter.id = 'shortDescCounter';
            counter.className = 'form-text text-muted float-end';
            this.parentNode.appendChild(counter);
            return counter;
        })();
        
        counter.textContent = `${charCount}/500 karakter`;
        
        if (charCount > 500) {
            counter.classList.add('text-danger');
            counter.classList.remove('text-muted');
        } else {
            counter.classList.remove('text-danger');
            counter.classList.add('text-muted');
        }
    });

    // Auto count characters for full description
    document.getElementById('description').addEventListener('input', function() {
        const charCount = this.value.length;
        const counter = document.getElementById('descCounter') || (() => {
            const counter = document.createElement('small');
            counter.id = 'descCounter';
            counter.className = 'form-text text-muted float-end';
            this.parentNode.appendChild(counter);
            return counter;
        })();
        
        counter.textContent = `${charCount} karakter`;
    });
</script>
@endpush
@endsection