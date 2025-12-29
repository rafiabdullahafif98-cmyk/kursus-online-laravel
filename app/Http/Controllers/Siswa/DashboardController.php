<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use App\Models\Category;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:siswa']);
    }

    public function index()
    {
        $user = auth()->user();

        $totalEnrolled = Enrollment::where('user_id', $user->id)->count();
        $activeCourses = Enrollment::where('user_id', $user->id)
            ->where('status', 'active')
            ->count();
        $completedCourses = Enrollment::where('user_id', $user->id)
            ->where('status', 'completed')
            ->count();

        $enrolledCourses = Course::whereIn('id', function ($query) use ($user) {
            $query->select('course_id')
                ->from('enrollments')
                ->where('user_id', $user->id)
                ->where('status', 'active');
        })
            ->with('category:id,name', 'pengajar:id,name')
            ->take(4)
            ->get();

        $userCourseIds = Enrollment::where('user_id', $user->id)
            ->pluck('course_id')
            ->toArray();

        $recommendedCourses = Course::where('is_published', true)
            ->with('category:id,name', 'pengajar:id,name')
            ->whereNotIn('id', $userCourseIds)
            ->inRandomOrder()
            ->take(3)
            ->get();

        return view('siswa.dashboard', compact(
            'totalEnrolled',
            'activeCourses',
            'completedCourses',
            'enrolledCourses',
            'recommendedCourses'
        ));
    }

    public function courses()
    {
        $user = auth()->user();

        $query = Enrollment::where('user_id', $user->id);

        if (request()->has('status') && in_array(request('status'), ['active', 'completed', 'cancelled'])) {
            $query->where('status', request('status'));
        }

        $enrollments = $query->with([
            'course:id,title,slug,short_description,description,thumbnail,category_id,user_id',
            'course.category:id,name',
            'course.pengajar:id,name'
        ])
            ->orderBy('enrolled_at', 'desc')
            ->paginate(12);

        // Transform paginator items safely (use collection on paginator)
        $enrollments->setCollection(
            $enrollments->getCollection()->transform(function ($enrollment) {
                if ($enrollment->enrolled_at && is_string($enrollment->enrolled_at)) {
                    $enrollment->enrolled_at = \Carbon\Carbon::parse($enrollment->enrolled_at);
                }
                return $enrollment;
            })
        );

        return view('siswa.courses', compact('enrollments'));
    }

    public function allCourses(Request $request)
    {
        $user = auth()->user();

        $enrolledCourseIds = Enrollment::where('user_id', $user->id)
            ->pluck('course_id')
            ->toArray();

        $query = Course::where('is_published', true)
            ->with([
                'category:id,name',
                'pengajar:id,name'
            ])
            ->withCount(['enrollments', 'materials']);

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('short_description', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }

        // Sort
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'popular':
                $query->orderBy('enrollments_count', 'desc');
                break;
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            default: // newest
                $query->latest();
        }

        $courses = $query->paginate(12);
        $categories = Category::withCount('courses')->get();

        return view('siswa.all-courses', compact('courses', 'categories', 'enrolledCourseIds'));
    }

    public function enroll($courseId)
    {
        $user = auth()->user();
        $course = Course::findOrFail($courseId);

        // Cek apakah kursus published
        if (!$course->is_published) {
            return back()->with('error', 'Kursus ini belum tersedia.');
        }

        // Cek apakah sudah enrolled
        $alreadyEnrolled = Enrollment::where('user_id', $user->id)
            ->where('course_id', $courseId)
            ->exists();

        if ($alreadyEnrolled) {
            return back()->with('error', 'Anda sudah terdaftar di kursus ini.');
        }

        // Enroll siswa
        Enrollment::create([
            'user_id' => $user->id,
            'course_id' => $courseId,
            'status' => 'active',
            'enrolled_at' => now(),
        ]);

        return redirect()->route('siswa.courses')
            ->with('success', 'Berhasil mendaftar ke kursus: ' . $course->title);
    }

    // COURSE DETAIL
    public function showCourse($slug)
    {
        $course = Course::where('slug', $slug)
            ->with(['category', 'pengajar', 'materials'])
            ->withCount(['enrollments', 'materials'])
            ->firstOrFail();

        $user = auth()->user();
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        $materials = $course->materials()->orderBy('order')->get();

        // Get other courses by same teacher
        $otherCourses = Course::where('user_id', $course->user_id)
            ->where('id', '!=', $course->id)
            ->where('is_published', true)
            ->withCount('enrollments')
            ->latest()
            ->limit(5)
            ->get();

        // Calculate ACTUAL progress
        $progress = 0;
        if ($enrollment && $materials->count() > 0) {
            $completedMaterials = \App\Models\ProgressTracking::where('user_id', $user->id)
                ->where('course_id', $course->id)
                ->where('is_completed', true)
                ->count();

            $progress = round(($completedMaterials / $materials->count()) * 100);
        }

        return view('siswa.course-detail', compact(
            'course',
            'enrollment',
            'materials',
            'otherCourses',
            'progress'
        ));
    }

    // PROFILE - METHOD 1 (tampilkan form)
    public function profile()
    {
        $user = auth()->user();

        $stats = [
            'total_courses' => Enrollment::where('user_id', $user->id)->count(),
            'active_courses' => Enrollment::where('user_id', $user->id)
                ->where('status', 'active')->count(),
            'completed_courses' => Enrollment::where('user_id', $user->id)
                ->where('status', 'completed')->count(),
            'total_hours' => 120,
        ];

        $lastLogin = $user->last_login_at ?? $user->created_at;

        return view('siswa.profile', compact('user', 'stats', 'lastLogin'));
    }

    // PROFILE - METHOD 2 (update profile & password)
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        // Deteksi tipe update
        if ($request->filled('current_password')) {
            // Update password
            return $this->updatePassword($request, $user);
        } else {
            // Update profile info
            return $this->updateProfileInfo($request, $user);
        }
    }

    private function updateProfileInfo(Request $request, $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'bio' => 'nullable|string|max:1000',
            'address' => 'nullable|string|max:500',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->bio = $request->bio ?? $user->bio;
        $user->address = $request->address ?? $user->address;

        $user->save();

        return redirect()->route('siswa.profile')
            ->with('success', 'Profil berhasil diperbarui');
    }

    private function updatePassword(Request $request, $user)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini salah']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('siswa.profile')
            ->with('success', 'Password berhasil diubah');
    }
}
