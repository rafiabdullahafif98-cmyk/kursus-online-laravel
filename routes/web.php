<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Siswa\DashboardController as SiswaDashboardController;
use App\Http\Controllers\Pengajar\DashboardController as PengajarDashboardController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth; // TAMBAHKAN INI
use App\Models\User;
use Illuminate\Support\Facades\Route;

// Homepage
Route::get('/', [HomeController::class, 'index'])->name('home');

// Authentication Routes
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Login Routes - Gunakan Laravel Auth Default
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// Post login - gunakan Laravel default
Route::post('/login', function (\Illuminate\Http\Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials, $request->remember)) {
        $request->session()->regenerate();
        
        // Redirect berdasarkan role
        /** @var User|null $user */
        $user = Auth::user();

        if ($user?->isAdmin()) {
            return redirect('/admin');
        } elseif ($user?->isPengajar()) {
            return redirect()->route('pengajar.dashboard');
        } else {
            return redirect()->route('siswa.dashboard');
        }
    }

    return back()->withErrors([
        'email' => 'Email atau password salah.',
    ])->onlyInput('email');
})->name('login.submit');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    // Siswa Routes
    Route::prefix('siswa')->name('siswa.')->middleware('role:siswa')->group(function () {
        Route::get('/dashboard', [SiswaDashboardController::class, 'index'])->name('dashboard');
        Route::get('/courses', [SiswaDashboardController::class, 'courses'])->name('courses');
        Route::get('/profile', [SiswaDashboardController::class, 'profile'])->name('profile');
    });

    // Pengajar Routes
    Route::prefix('pengajar')->name('pengajar.')->middleware('role:pengajar')->group(function () {
        Route::get('/dashboard', [PengajarDashboardController::class, 'index'])->name('dashboard');
        Route::get('/my-courses', [PengajarDashboardController::class, 'myCourses'])->name('courses');
        Route::get('/profile', [PengajarDashboardController::class, 'profile'])->name('profile');
    });
});

// Logout
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');

// Filament Admin Panel otomatis di /admin