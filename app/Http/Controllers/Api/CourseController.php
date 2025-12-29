<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/courses",
     *     summary="Get all published courses",
     *     tags={"Courses"},
     *     @OA\Response(
     *         response=200,
     *         description="List of published courses",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="title", type="string", example="Belajar Laravel 10"),
     *                     @OA\Property(property="slug", type="string", example="belajar-laravel-10"),
     *                     @OA\Property(property="short_description", type="string", example="Kursus Laravel untuk pemula"),
     *                     @OA\Property(property="price", type="number", format="float", example=0),
     *                     @OA\Property(property="thumbnail", type="string", nullable=true, example="courses/thumbnails/laravel.jpg"),
     *                     @OA\Property(property="category", type="string", example="Programming"),
     *                     @OA\Property(property="pengajar", type="string", example="John Doe"),
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="Courses retrieved successfully")
     *         )
     *     )
     * )
     */
    public function index()
    {
        $courses = Course::where('is_published', true)
            ->with(['category:id,name', 'pengajar:id,name'])
            ->select('id', 'title', 'slug', 'short_description', 'price', 'thumbnail', 'category_id', 'user_id')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $courses,
            'message' => 'Courses retrieved successfully'
        ], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/courses/{slug}",
     *     summary="Get course detail by slug",
     *     tags={"Courses"},
     *     @OA\Parameter(
     *         name="slug",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Course detail",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="Belajar Laravel 10"),
     *                 @OA\Property(property="slug", type="string", example="belajar-laravel-10"),
     *                 @OA\Property(property="description", type="string", example="Full description here..."),
     *                 @OA\Property(property="short_description", type="string", example="Kursus Laravel untuk pemula"),
     *                 @OA\Property(property="price", type="number", format="float", example=0),
     *                 @OA\Property(property="thumbnail", type="string", nullable=true),
     *                 @OA\Property(
     *                     property="category",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Programming"),
     *                 ),
     *                 @OA\Property(
     *                     property="pengajar",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=2),
     *                     @OA\Property(property="name", type="string", example="John Doe"),
     *                     @OA\Property(property="email", type="string", example="pengajar@example.com"),
     *                 ),
     *                 @OA\Property(
     *                     property="materials",
     *                     type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="title", type="string", example="Pengenalan Laravel"),
     *                         @OA\Property(property="file_type", type="string", example="pdf"),
     *                         @OA\Property(property="order", type="integer", example=1),
     *                     )
     *                 ),
     *             ),
     *             @OA\Property(property="message", type="string", example="Course retrieved successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Course not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Course not found")
     *         )
     *     )
     * )
     */
    public function show($slug)
    {
        $course = Course::where('slug', $slug)
            ->where('is_published', true)
            ->with(['category:id,name', 'pengajar:id,name,email', 'materials:id,title,file_type,order,course_id'])
            ->first();

        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Course not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $course,
            'message' => 'Course retrieved successfully'
        ], 200);
    }
}