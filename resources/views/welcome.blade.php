<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduCourse - Belajar Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="/">EduCourse</a>
            <div class="navbar-nav">
                <a class="nav-link" href="/register">Daftar</a>
                <a class="nav-link" href="/login">Login</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h1>Selamat Datang di EduCourse</h1>
        <p>Sistem pembelajaran online untuk siswa dan pengajar.</p>
        
        <div class="row mt-4">
            @foreach($courses as $course)
            <div class="col-md-4 mb-4">
                <div class="card">
                    @if($course->thumbnail)
                    <img src="{{ asset('storage/' . $course->thumbnail) }}" class="card-img-top" alt="{{ $course->title }}">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $course->title }}</h5>
                        <p class="card-text">{{ Str::limit($course->short_description ?? $course->description, 100) }}</p>
                        <span class="badge bg-primary">{{ $course->category->name ?? 'Uncategorized' }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</body>
</html>