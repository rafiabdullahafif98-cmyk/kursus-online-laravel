<?php
// app/Http/Controllers/Api/ApiMaterialController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\ProgressTracking;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiMaterialController extends Controller
{
    /**
     * Get materials for a course
     */
    public function index($courseId)
    {
        $user = Auth::user();
        
        // Check if user is enrolled
        $isEnrolled = Enrollment::where('user_id', $user->id)
            ->where('course_id', $courseId)
            ->exists();

        if (!$isEnrolled) {
            return response()->json([
                'success' => false,
                'message' => 'You are not enrolled in this course'
            ], 403);
        }

        $materials = Material::where('course_id', $courseId)
            ->orderBy('order')
            ->get()
            ->map(function ($material) use ($user) {
                $material->is_completed = ProgressTracking::where('user_id', $user->id)
                    ->where('material_id', $material->id)
                    ->where('is_completed', true)
                    ->exists();
                return $material;
            });

        return response()->json([
            'success' => true,
            'data' => $materials
        ]);
    }

    /**
     * Mark material as completed
     */
    public function markComplete($id)
    {
        $user = Auth::user();
        $material = Material::findOrFail($id);

        // Check enrollment
        $isEnrolled = Enrollment::where('user_id', $user->id)
            ->where('course_id', $material->course_id)
            ->exists();

        if (!$isEnrolled) {
            return response()->json([
                'success' => false,
                'message' => 'You are not enrolled in this course'
            ], 403);
        }

        $progress = ProgressTracking::updateOrCreate(
            [
                'user_id' => $user->id,
                'course_id' => $material->course_id,
                'material_id' => $material->id,
            ],
            [
                'is_completed' => true,
                'completed_at' => now(),
                'time_spent' => 0,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Material marked as completed',
            'data' => $progress
        ]);
    }

    /**
     * Mark material as incomplete
     */
    public function markIncomplete($id)
    {
        $user = Auth::user();
        $material = Material::findOrFail($id);

        ProgressTracking::where('user_id', $user->id)
            ->where('material_id', $material->id)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Material marked as incomplete'
        ]);
    }
}