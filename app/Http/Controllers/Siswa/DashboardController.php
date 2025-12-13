<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:siswa']);
    }

    public function index()
    {
        /** @var User $user */
        $user = auth()->user();
        $enrolledCourses = $user->enrolledCourses()->with('category')->take(5)->get();
        $recommendedCourses = Course::where('is_published', true)
            ->with('category', 'pengajar')
            ->whereNotIn('id', $user->enrolledCourses()->pluck('id'))
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('siswa.dashboard', compact('enrolledCourses', 'recommendedCourses'));
    }

    public function courses()
    {
        /** @var User $user */
        $user = auth()->user();

        $courses = $user->enrolledCourses()->with('category', 'pengajar')->paginate(10);
        return view('siswa.courses', compact('courses'));
    }

    public function profile()
    {
        $user = auth()->user();
        return view('siswa.profile', compact('user'));
    }
}