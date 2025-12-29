<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CourseController;

Route::get('/courses', [CourseController::class, 'index']);
Route::get('/courses/{slug}', [CourseController::class, 'show']);

// Protected API routes (will add later)
Route::middleware('auth:sanctum')->group(function () {
    // Route::post('/enroll', [EnrollmentController::class, 'store']);
    // Route::get('/my-courses', [DashboardController::class, 'myCourses']);

});
