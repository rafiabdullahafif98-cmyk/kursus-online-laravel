@extends('layouts.siswa')

@section('title', 'Profil Saya')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Profil Saya</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('siswa.dashboard') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <ul class="mb-0">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="row">
    <!-- Profile Info -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-person-circle me-2"></i>Informasi Profil</h5>
            </div>
            <div class="card-body text-center">
                <div class="mb-3">
                    <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center" 
                         style="width: 120px; height: 120px;">
                        <i class="bi bi-person display-4"></i>
                    </div>
                </div>
                
                <h4>{{ $user->name }}</h4>
                <p class="text-muted">{{ $user->email }}</p>
                
                <div class="mb-3">
                    <span class="badge bg-success fs-6 py-2 px-3">
                        <i class="bi bi-person-badge me-1"></i> Siswa
                    </span>
                </div>
                
                <div class="text-start mt-4">
                    <h6><i class="bi bi-info-circle me-2"></i>Informasi Akun</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="bi bi-calendar me-2"></i>
                            Bergabung: {{ $user->created_at->format('d M Y') }}
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-clock-history me-2"></i>
                            Terakhir login: {{ $lastLogin->diffForHumans() }}
                        </li>
                        
                        <!-- Tampilkan Bio jika ada -->
                        @if($user->bio)
                        <li class="mb-2">
                            <i class="bi bi-person-lines-fill me-2"></i>
                            <strong>Tentang:</strong> {{ $user->bio }}
                        </li>
                        @endif
                        
                        <!-- Tampilkan Alamat jika ada -->
                        @if($user->address)
                        <li class="mb-2">
                            <i class="bi bi-geo-alt me-2"></i>
                            <strong>Alamat:</strong> {{ $user->address }}
                        </li>
                        @endif
                        
                        <li>
                            <i class="bi bi-shield-check me-2"></i>
                            Verifikasi email: 
                            @if($user->email_verified_at)
                            <span class="badge bg-success">Terverifikasi</span>
                            @else
                            <span class="badge bg-warning">Belum diverifikasi</span>
                            @endif
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Profile Form -->
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-pencil-square me-2"></i>Edit Profil</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('siswa.profile.update') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Nama Lengkap *</label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $user->name) }}" 
                                   required>
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email', $user->email) }}" 
                                   required>
                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Bio Field -->
                    <div class="mb-3">
                        <label for="bio" class="form-label">Tentang Saya</label>
                        <textarea class="form-control @error('bio') is-invalid @enderror" 
                                  id="bio" 
                                  name="bio" 
                                  rows="3" 
                                  placeholder="Ceritakan sedikit tentang diri Anda...">{{ old('bio', $user->bio ?? '') }}</textarea>
                        @error('bio')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Opsional. Maksimal 1000 karakter.</small>
                    </div>
                    
                    <!-- Address Field -->
                    <div class="mb-3">
                        <label for="address" class="form-label">Alamat</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                  id="address" 
                                  name="address" 
                                  rows="2" 
                                  placeholder="Alamat tempat tinggal...">{{ old('address', $user->address ?? '') }}</textarea>
                        @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Opsional. Maksimal 500 karakter.</small>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Change Password -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-key me-2"></i>Ubah Password</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('siswa.profile.update') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Password Saat Ini *</label>
                        <input type="password" 
                               class="form-control @error('current_password') is-invalid @enderror" 
                               id="current_password" 
                               name="current_password">
                        @error('current_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Password Baru *</label>
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password">
                            @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Minimal 8 karakter</small>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password Baru *</label>
                            <input type="password" 
                                   class="form-control" 
                                   id="password_confirmation" 
                                   name="password_confirmation">
                        </div>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-shield-lock me-1"></i> Ubah Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Learning Stats -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-graph-up me-2"></i>Statistik Pembelajaran</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3 mb-3">
                        <div class="bg-light rounded p-3">
                            <i class="bi bi-book display-6 text-primary"></i>
                            <h3 class="mt-2">{{ $stats['total_courses'] }}</h3>
                            <small class="text-muted">Total Kursus</small>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="bg-light rounded p-3">
                            <i class="bi bi-play-circle display-6 text-success"></i>
                            <h3 class="mt-2">{{ $stats['active_courses'] }}</h3>
                            <small class="text-muted">Sedang Dipelajari</small>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="bg-light rounded p-3">
                            <i class="bi bi-check-circle display-6 text-warning"></i>
                            <h3 class="mt-2">{{ $stats['completed_courses'] }}</h3>
                            <small class="text-muted">Selesai</small>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="bg-light rounded p-3">
                            <i class="bi bi-clock-history display-6 text-info"></i>
                            <h3 class="mt-2">{{ $stats['total_hours'] }}h</h3>
                            <small class="text-muted">Total Jam Belajar</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Form validation
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                const password = document.getElementById('password');
                const confirmPassword = document.getElementById('password_confirmation');
                const currentPassword = document.getElementById('current_password');
                
                // Jika form password (ada field current_password dan sedang diisi)
                if (currentPassword && currentPassword.value) {
                    // Validasi password baru
                    if (password && confirmPassword && password.value && confirmPassword.value) {
                        if (password.value !== confirmPassword.value) {
                            e.preventDefault();
                            alert('Password baru dan konfirmasi password tidak cocok!');
                            return false;
                        }
                        
                        if (password.value.length < 8) {
                            e.preventDefault();
                            alert('Password baru minimal 8 karakter!');
                            return false;
                        }
                    } else if (password && password.value && (!confirmPassword || !confirmPassword.value)) {
                        e.preventDefault();
                        alert('Harap konfirmasi password baru!');
                        return false;
                    }
                }
                
                // Validasi bio maksimal 1000 karakter
                const bio = document.getElementById('bio');
                if (bio && bio.value && bio.value.length > 1000) {
                    e.preventDefault();
                    alert('Bio maksimal 1000 karakter!');
                    return false;
                }
                
                // Validasi address maksimal 500 karakter
                const address = document.getElementById('address');
                if (address && address.value && address.value.length > 500) {
                    e.preventDefault();
                    alert('Alamat maksimal 500 karakter!');
                    return false;
                }
            });
        });
    });
</script>
@endpush

@push('styles')
<style>
    .rounded-circle {
        transition: transform 0.3s;
    }
    
    .rounded-circle:hover {
        transform: scale(1.05);
    }
    
    .bg-light.rounded {
        transition: all 0.3s;
    }
    
    .bg-light.rounded:hover {
        background-color: #e9ecef !important;
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
</style>
@endpush
@endsection