<?php
// routes/api.php

use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\ApiCourseController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// ==================== PUBLIC API ====================
Route::post('/register', [ApiAuthController::class, 'register']);
Route::post('/login', [ApiAuthController::class, 'login']);

Route::get('/courses', [ApiCourseController::class, 'index']);
Route::get('/courses/{id}', [ApiCourseController::class, 'show']);

// ==================== PROTECTED API ====================
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/logout', [ApiAuthController::class, 'logout']);
    
    // Courses CRUD
    Route::post('/courses', [ApiCourseController::class, 'store']);
    Route::put('/courses/{id}', [ApiCourseController::class, 'update']);
    Route::delete('/courses/{id}', [ApiCourseController::class, 'destroy']);
    
    // Enrollment
    Route::post('/courses/{id}/enroll', [ApiCourseController::class, 'enroll']);
    Route::get('/my-courses', [ApiCourseController::class, 'myCourses']);
    
    // Profile
    Route::get('/profile', function (Request $request) {
        return response()->json([
            'success' => true,
            'data' => $request->user()
        ]);
    });
});