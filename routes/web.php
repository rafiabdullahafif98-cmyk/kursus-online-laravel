<?php

use App\Http\Controllers\Pengajar\DashboardController as PengajarDashboardController;
use App\Http\Controllers\Pengajar\ProfileController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Siswa\DashboardController as SiswaDashboardController;
use App\Http\Controllers\Siswa\ProgressController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Route;

// ==================== PUBLIC ROUTES ====================

// Homepage
Route::get('/', [HomeController::class, 'index'])->name('home');

// Authentication Routes
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Login Routes
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// Post login
Route::post('/login', function (\Illuminate\Http\Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials, $request->remember)) {
        $request->session()->regenerate();

        // Redirect berdasarkan role
        $user = Auth::user();

        if ($user->role === 'admin') {
            return redirect('/admin');
        } elseif ($user->role === 'pengajar') {
            return redirect()->route('pengajar.dashboard');
        } else {
            return redirect()->route('siswa.dashboard');
        }
    }

    return back()->withErrors([
        'email' => 'Email atau password salah.',
    ])->onlyInput('email');
})->name('login.submit');

// ==================== PROTECTED ROUTES (AUTH REQUIRED) ====================

Route::middleware(['auth'])->group(function () {

    // ==================== SISWA ROUTES ====================
    Route::prefix('siswa')->name('siswa.')->group(function () {
        // Dashboard
        Route::get('/dashboard', [SiswaDashboardController::class, 'index'])->name('dashboard');

        // My Courses (already enrolled)
        Route::get('/courses', [SiswaDashboardController::class, 'courses'])->name('courses');

        // Browse all available courses
        Route::get('/all-courses', [SiswaDashboardController::class, 'allCourses'])->name('all-courses');

        // Course detail
        Route::get('/course/{slug}', [SiswaDashboardController::class, 'showCourse'])->name('course.show');

        // Enroll to course
        Route::post('/enroll/{courseId}', [SiswaDashboardController::class, 'enroll'])->name('enroll');

        // Progress Tracking - PERBAIKAN INI
        Route::post('/progress/complete', [ProgressController::class, 'complete'])->name('progress.complete');
        Route::post('/progress/incomplete', [ProgressController::class, 'incomplete'])->name('progress.incomplete');
        Route::get('/progress/{courseId}', [ProgressController::class, 'getUserProgress'])->name('progress.get');

        Route::post('/siswa/progress/complete', [\App\Http\Controllers\Siswa\ProgressController::class, 'complete'])->name('progress.complete');

        // Profile
        Route::get('/profile', [SiswaDashboardController::class, 'profile'])->name('profile');
        Route::post('/profile', [SiswaDashboardController::class, 'updateProfile'])->name('profile.update');
    });

    // ==================== PENGAJAR ROUTES ====================
    Route::prefix('pengajar')->name('pengajar.')->group(function () {
        // Dashboard
        Route::get('/dashboard', [PengajarDashboardController::class, 'index'])->name('dashboard');

        // Courses Management
        Route::get('/courses', [PengajarDashboardController::class, 'courses'])->name('courses');
        Route::get('/courses/create', [PengajarDashboardController::class, 'createCourse'])->name('courses.create');
        Route::post('/courses', [PengajarDashboardController::class, 'storeCourse'])->name('courses.store');
        Route::get('/courses/{id}/edit', [PengajarDashboardController::class, 'editCourse'])->name('courses.edit');
        Route::post('/courses/{id}', [PengajarDashboardController::class, 'updateCourse'])->name('courses.update');
        Route::delete('/courses/{id}', [PengajarDashboardController::class, 'deleteCourse'])->name('courses.delete');
        // ... route pengajar lainnya ...

        Route::get('/courses/{id}/materials', [PengajarDashboardController::class, 'manageMaterials'])->name('courses.materials');
        Route::post('/courses/{id}/materials', [PengajarDashboardController::class, 'storeMaterial'])->name('courses.materials.store');

        // === TAMBAHKAN INI (Route Hapus Material) ===
        Route::delete('/materials/{id}', [PengajarDashboardController::class, 'deleteMaterial'])->name('materials.delete');

        // Students
        Route::get('/students', [PengajarDashboardController::class, 'students'])->name('students');

        // Profile

        Route::get('/profile', [PengajarDashboardController::class, 'profile'])->name('profile');

        Route::put('/profile', [PengajarDashboardController::class, 'updateProfile'])->name('profile.update');

        Route::get('/courses/{id}/materials', [PengajarDashboardController::class, 'manageMaterials'])->name('courses.materials');
        Route::post('/courses/{id}/materials', [PengajarDashboardController::class, 'storeMaterial'])->name('courses.materials.store');
    });

    // ==================== LOGOUT ROUTE ====================
    Route::post('/logout', function () {
        Auth::logout();
        return redirect('/');
    })->name('logout');
});

// ==================== FALLBACK ROUTE ====================
Route::fallback(function () {
    return view('errors.404');
});
