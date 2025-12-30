@extends('layouts.pengajar')

@section('title', 'Daftar Siswa Saya')

@section('content')
<div class="pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Siswa Saya</h1>
    <p class="text-muted">Daftar siswa yang mengikuti kursus Anda.</p>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Siswa</th>
                        <th>Email</th>
                        <th>Kursus yang Diikuti</th>
                        <th>Tanggal Bergabung</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentStudents as $enrollment)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px;">
                                    {{ substr($enrollment->user->name, 0, 1) }}
                                </div>
                                <span class="fw-bold">{{ $enrollment->user->name }}</span>
                            </div>
                        </td>
                        <td>{{ $enrollment->user->email }}</td>
                        <td>
                            <span class="badge bg-info text-dark">
                                {{ $enrollment->course->title }}
                            </span>
                        </td>
                        <td>{{ $enrollment->created_at->format('d M Y') }}</td>
                        <td>
                            <span class="badge bg-success">Aktif</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-people display-4"></i>
                            <p class="mt-2">Belum ada siswa yang mendaftar di kursus Anda.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection