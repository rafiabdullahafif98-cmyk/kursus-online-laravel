<?php

namespace App\Http\Controllers\Pengajar;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:pengajar']);
    }

    public function index()
    {
        /** @var User $user */
        $user = auth()->user();
        $totalCourses = $user->courses()->count(); // PERHATIKAN: courses() bukan course()
        $totalStudents = Enrollment::whereIn('course_id', $user->courses()->pluck('id'))->count();
        $recentCourses = $user->courses()->latest()->take(5)->get();

        return view('pengajar.dashboard', compact('totalCourses', 'totalStudents', 'recentCourses'));
    }

    public function myCourses()
    {
        /** @var User $user */
        $user = auth()->user();

        $courses = $user->courses()->with('category', 'enrollments')->paginate(10);
        return view('pengajar.courses', compact('courses'));
    }

    public function profile()
    {
        $user = auth()->user();
        return view('pengajar.profile', compact('user'));
    }
}