@extends('layouts.pengajar')

@section('title', 'Profil Saya')

@section('content')
<div class="pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Pengaturan Profil</h1>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-body text-center">
                <div class="mb-3">
                    @if(auth()->user()->photo)
                        <img src="{{ asset('storage/'.auth()->user()->photo) }}" alt="avatar" class="rounded-circle img-fluid" style="width: 150px; height: 150px; object-fit: cover;">
                    @else
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto" style="width: 150px; height: 150px; font-size: 64px;">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                    @endif
                </div>
                <h5 class="my-3">{{ auth()->user()->name }}</h5>
                <p class="text-muted mb-1">Pengajar EduCourse</p>
                <p class="text-muted small">{{ auth()->user()->email }}</p>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Informasi Pribadi</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('pengajar.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', auth()->user()->name) }}">
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', auth()->user()->email) }}">
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Ganti Foto Profil</label>
                        <input type="file" name="photo" class="form-control @error('photo') is-invalid @enderror">
                        <small class="text-muted">Format: JPG, PNG (Max: 2MB)</small>
                        @error('photo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <hr>

                    <div class="mb-3">
                        <label class="form-label">Password Baru (Kosongkan jika tidak ingin ganti)</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection