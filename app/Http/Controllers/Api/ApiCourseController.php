<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiCourseController extends Controller
{
    public function index()
    {
        $courses = Course::with(['category', 'pengajar'])
            ->where('is_published', true)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $courses
        ]);
    }

    public function show($id)
    {
        $course = Course::with(['category', 'pengajar', 'materials'])
            ->where('is_published', true)
            ->find($id);

        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Course not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $course
        ]);
    }

    public function enroll($id)
    {
        $user = Auth::user();
        $course = Course::findOrFail($id);

        // Check if already enrolled
        $existing = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Already enrolled'
            ], 400);
        }

        $enrollment = Enrollment::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'status' => 'active',
            'enrolled_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Enrolled successfully',
            'data' => $enrollment
        ], 201);
    }

    public function myCourses()
    {
        $user = Auth::user();
        
        $enrollments = Enrollment::with('course.category', 'course.pengajar')
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->get();

        $courses = $enrollments->map(function ($enrollment) {
            return $enrollment->course;
        });

        return response()->json([
            'success' => true,
            'data' => $courses
        ]);
    }
}