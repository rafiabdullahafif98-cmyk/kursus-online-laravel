<?php

namespace App\Http\Controllers\Pengajar;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:pengajar']);
    }

    public function index()
    {
        $user = auth()->user();
    
    // Statistik (PAKAI QUERY LANGSUNG, bukan method dari User)
    $totalCourses = $user->taughtCourses()->count();
    $publishedCourses = $user->taughtCourses()->where('is_published', true)->count();
    $draftCourses = $user->taughtCourses()->where('is_published', false)->count();
    
    // Total siswa (query langsung)
    $totalStudents = \App\Models\Enrollment::whereIn('course_id', 
        $user->taughtCourses()->pluck('id')
    )->distinct('user_id')->count('user_id');

    // Kursus terbaru
    $recentCourses = $user->taughtCourses()
        ->with('category:id,name')
        ->latest()
        ->take(5)
        ->get();

    // Siswa baru (query langsung)
    $recentStudents = \App\Models\Enrollment::whereIn('course_id', 
        $user->taughtCourses()->pluck('id')
    )
    ->where('created_at', '>=', now()->subDays(30))
    ->with('user:id,name,email')
    ->distinct('user_id')
    ->latest()
    ->take(5)
    ->get();

    return view('pengajar.dashboard', compact(
        'totalCourses',
        'publishedCourses',
        'draftCourses',
        'totalStudents',
        'recentCourses',
        'recentStudents'
    ));
    }

    public function courses()
    {
        $user = Auth::user();
        $courses = $user->taughtCourses()
            ->with(['category:id,name'])
            ->withCount('enrollments')
            ->latest()
            ->paginate(10);

        return view('pengajar.courses', compact('courses'));
    }

    public function createCourse()
    {
        $categories = \App\Models\Category::all();
        return view('pengajar.course-create', compact('categories'));
    }

    public function storeCourse(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'category_id' => 'required|exists:categories,id',
            'price' => 'nullable|numeric|min:0',
            'thumbnail' => 'nullable|image|max:2048',
        ]);

        $courseData = [
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'description' => $request->description,
            'short_description' => $request->short_description,
            'price' => $request->price ?? 0,
            'category_id' => $request->category_id,
            'user_id' => Auth::id(),
            'is_published' => $request->has('is_published'),
        ];

        // Buat course
        $course = Course::create($courseData);

        // Upload thumbnail jika ada
        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('courses/thumbnails', 'public');
            $course->thumbnail = $path;
            $course->save(); // Method save() dari Eloquent Model
        }

        return redirect()->route('pengajar.courses')
            ->with('success', 'Kursus berhasil dibuat!');
    }

    public function editCourse($id)
    {
        $course = Course::where('user_id', Auth::id())->findOrFail($id);
        $categories = \App\Models\Category::all();
        
        return view('pengajar.course-edit', compact('course', 'categories'));
    }

    public function updateCourse(Request $request, $id)
    {
        $course = Course::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'category_id' => 'required|exists:categories,id',
            'price' => 'nullable|numeric|min:0',
            'thumbnail' => 'nullable|image|max:2048',
        ]);

        $updateData = [
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'description' => $request->description,
            'short_description' => $request->short_description,
            'price' => $request->price ?? 0,
            'category_id' => $request->category_id,
            'is_published' => $request->has('is_published'),
        ];

        // Update course (method update() dari Eloquent Model)
        $course->update($updateData);

        // Upload thumbnail jika ada
        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('courses/thumbnails', 'public');
            $course->thumbnail = $path;
            $course->save();
        }

        return redirect()->route('pengajar.courses')
            ->with('success', 'Kursus berhasil diperbarui!');
    }

    public function deleteCourse($id)
    {
        $course = Course::where('user_id', Auth::id())->findOrFail($id);
        $course->delete();

        return redirect()->route('pengajar.courses')
            ->with('success', 'Kursus berhasil dihapus!');
    }

    public function students()
    {
        $user = Auth::user();
        
        // Ambil semua siswa yang enroll ke kursus pengajar ini
        $students = Enrollment::whereIn('course_id', 
            $user->taughtCourses()->pluck('id')
        )
        ->with(['user:id,name,email', 'course:id,title'])
        ->select('user_id', 'course_id', 'status', 'enrolled_at')
        ->distinct('user_id', 'course_id')
        ->latest()
        ->paginate(15);

        return view('pengajar.students', compact('students'));
    }

    public function profile()
    {
        $user = Auth::user();
        return view('pengajar.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();
        
        // Method update() dari Eloquent Model
        $user->update([
            'name' => $request->name,
            // Tambahkan bio jika ada di database
            // 'bio' => $request->bio,
        ]);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}