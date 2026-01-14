<?php
// app/Http\Controllers\Api/ProgressController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProgressTracking;
use App\Models\Material;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ApiProgressController extends Controller
{
    /**
     * Get progress for specific course
     */
    public function getProgress($courseId)
    {
        $user = Auth::user();
        
        $totalMaterials = Material::where('course_id', $courseId)->count();
        $completedMaterials = ProgressTracking::where('user_id', $user->id)
            ->where('course_id', $courseId)
            ->where('is_completed', true)
            ->count();

        $progress = $totalMaterials > 0 
            ? round(($completedMaterials / $totalMaterials) * 100) 
            : 0;

        $materials = Material::where('course_id', $courseId)
            ->orderBy('order')
            ->get()
            ->map(function ($material) use ($user) {
                $progress = ProgressTracking::where('user_id', $user->id)
                    ->where('material_id', $material->id)
                    ->first();

                return [
                    'id' => $material->id,
                    'title' => $material->title,
                    'description' => $material->description,
                    'file_type' => $material->file_type,
                    'order' => $material->order,
                    'is_completed' => $progress ? $progress->is_completed : false,
                    'completed_at' => $progress ? $progress->completed_at : null,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => [
                'course_id' => $courseId,
                'progress_percentage' => $progress,
                'completed_materials' => $completedMaterials,
                'total_materials' => $totalMaterials,
                'materials' => $materials,
            ]
        ]);
    }

    /**
     * Get overall progress across all courses
     */
    public function overallProgress(Request $request)
    {
        $user = Auth::user();
        
        // Get all enrolled courses with progress
        $enrolledCourses = Enrollment::with('course')
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->get();

        $progressData = [];
        $totalProgress = 0;
        $courseCount = 0;

        foreach ($enrolledCourses as $enrollment) {
            $course = $enrollment->course;
            $totalMaterials = Material::where('course_id', $course->id)->count();
            $completedMaterials = ProgressTracking::where('user_id', $user->id)
                ->where('course_id', $course->id)
                ->where('is_completed', true)
                ->count();

            $courseProgress = $totalMaterials > 0 
                ? round(($completedMaterials / $totalMaterials) * 100) 
                : 0;

            $progressData[] = [
                'course_id' => $course->id,
                'course_title' => $course->title,
                'course_slug' => $course->slug,
                'progress_percentage' => $courseProgress,
                'completed_materials' => $completedMaterials,
                'total_materials' => $totalMaterials,
            ];

            $totalProgress += $courseProgress;
            $courseCount++;
        }

        $averageProgress = $courseCount > 0 
            ? round($totalProgress / $courseCount) 
            : 0;

        return response()->json([
            'success' => true,
            'data' => [
                'average_progress' => $averageProgress,
                'total_courses' => $courseCount,
                'courses' => $progressData,
            ]
        ]);
    }
}