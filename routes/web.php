<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

// Import Controllers
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Pengajar\DashboardController as PengajarDashboardController;
use App\Http\Controllers\Siswa\DashboardController as SiswaDashboardController;
use App\Http\Controllers\Siswa\ProgressController;
use App\Http\Controllers\Pengajar\ProfileController; 

// ==================== PUBLIC ROUTES ====================

// 1. Homepage (Landing Page)
Route::get('/', [HomeController::class, 'index'])->name('home');

// 2. Register Routes
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// 3. Login Routes
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials, $request->remember)) {
        $request->session()->regenerate();
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

// ==================== PROTECTED ROUTES (BUTUH LOGIN) ====================

Route::middleware(['auth'])->group(function () {

    // SISWA
    Route::prefix('siswa')->name('siswa.')->group(function () {
        Route::get('/dashboard', [SiswaDashboardController::class, 'index'])->name('dashboard');
        Route::get('/courses', [SiswaDashboardController::class, 'courses'])->name('courses');
        Route::get('/all-courses', [SiswaDashboardController::class, 'allCourses'])->name('all-courses');
        Route::get('/course/{slug}', [SiswaDashboardController::class, 'showCourse'])->name('course.show');
        Route::post('/enroll/{courseId}', [SiswaDashboardController::class, 'enroll'])->name('enroll');
        Route::post('/progress/complete', [ProgressController::class, 'complete'])->name('progress.complete');
        Route::get('/profile', [SiswaDashboardController::class, 'profile'])->name('profile');
        Route::post('/profile', [SiswaDashboardController::class, 'updateProfile'])->name('profile.update');
    });

    // PENGAJAR
    Route::prefix('pengajar')->name('pengajar.')->group(function () {
        Route::get('/dashboard', [PengajarDashboardController::class, 'index'])->name('dashboard');
        Route::get('/courses', [PengajarDashboardController::class, 'courses'])->name('courses');
        Route::get('/courses/create', [PengajarDashboardController::class, 'createCourse'])->name('courses.create');
        Route::post('/courses', [PengajarDashboardController::class, 'storeCourse'])->name('courses.store');
        Route::get('/courses/{id}/edit', [PengajarDashboardController::class, 'editCourse'])->name('courses.edit');
        Route::post('/courses/{id}', [PengajarDashboardController::class, 'updateCourse'])->name('courses.update');
        Route::delete('/courses/{id}', [PengajarDashboardController::class, 'deleteCourse'])->name('courses.delete');
        
        // Material Management
        Route::get('/courses/{id}/materials', [PengajarDashboardController::class, 'manageMaterials'])->name('courses.materials');
        Route::post('/courses/{id}/materials', [PengajarDashboardController::class, 'storeMaterial'])->name('courses.materials.store');
        Route::delete('/materials/{id}', [PengajarDashboardController::class, 'deleteMaterial'])->name('materials.delete');

        Route::get('/students', [PengajarDashboardController::class, 'students'])->name('students');
        Route::get('/profile', [PengajarDashboardController::class, 'profile'])->name('profile');
        Route::put('/profile', [PengajarDashboardController::class, 'updateProfile'])->name('profile.update');
    });

    // LOGOUT
    Route::post('/logout', function () {
        Auth::logout();
        return redirect('/');
    })->name('logout');
});

Route::fallback(function () {
    return view('errors.404');
});