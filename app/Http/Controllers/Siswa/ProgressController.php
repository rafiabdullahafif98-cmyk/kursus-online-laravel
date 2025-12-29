<?php
// app/Http/Controllers/Siswa/ProgressController.php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\ProgressTracking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class ProgressController extends Controller
{
    public function complete(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'material_id' => 'required|exists:materials,id',
        ]);

        // Cek apakah user login
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda harus login terlebih dahulu'
            ], 401);
        }

        // Cek apakah tabel progress_trackings ada
        if (!Schema::hasTable('progress_trackings')) {
            return response()->json([
                'success' => false,
                'message' => 'Sistem progress tracking belum siap. Silakan hubungi administrator.'
            ], 500);
        }

        try {
            $progress = ProgressTracking::updateOrCreate(
                [
                    'user_id' => Auth::id(),
                    'course_id' => $request->course_id,
                    'material_id' => $request->material_id,
                ],
                [
                    'is_completed' => true,
                    'completed_at' => now(),
                    'time_spent' => 0,
                ]
            );

            // Hitung progress
            $totalMaterials = \App\Models\Material::where('course_id', $request->course_id)->count();
            $completed = ProgressTracking::where('user_id', Auth::id())
                ->where('course_id', $request->course_id)
                ->where('is_completed', true)
                ->count();

            $progressPercent = $totalMaterials > 0 ? round(($completed / $totalMaterials) * 100) : 0;

            return response()->json([
                'success' => true,
                'message' => 'Materi ditandai sebagai selesai',
                'progress' => $progressPercent,
                'completed' => $completed,
                'total' => $totalMaterials,
                'data' => $progress
            ]);

        } catch (\Exception $e) {
            \Log::error('Progress tracking error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }

    public function incomplete(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'material_id' => 'required|exists:materials,id',
        ]);

        // Cek apakah user login
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda harus login terlebih dahulu'
            ], 401);
        }

        // Cek apakah tabel progress_trackings ada
        if (!Schema::hasTable('progress_trackings')) {
            return response()->json([
                'success' => false,
                'message' => 'Sistem progress tracking belum siap. Silakan hubungi administrator.'
            ], 500);
        }

        try {
            $deleted = ProgressTracking::where('user_id', Auth::id())
                ->where('course_id', $request->course_id)
                ->where('material_id', $request->material_id)
                ->delete();

            // Hitung progress setelah delete
            $totalMaterials = \App\Models\Material::where('course_id', $request->course_id)->count();
            $completed = ProgressTracking::where('user_id', Auth::id())
                ->where('course_id', $request->course_id)
                ->where('is_completed', true)
                ->count();

            $progressPercent = $totalMaterials > 0 ? round(($completed / $totalMaterials) * 100) : 0;

            return response()->json([
                'success' => true,
                'message' => 'Materi ditandai sebagai belum selesai',
                'progress' => $progressPercent,
                'completed' => $completed,
                'total' => $totalMaterials,
                'deleted' => $deleted
            ]);

        } catch (\Exception $e) {
            \Log::error('Progress tracking error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }

    // Method untuk mengecek progress user
    public function getUserProgress($courseId)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        if (!Schema::hasTable('progress_trackings')) {
            return response()->json([
                'success' => false,
                'message' => 'Progress tracking not available'
            ], 200);
        }

        $completed = ProgressTracking::where('user_id', Auth::id())
            ->where('course_id', $courseId)
            ->where('is_completed', true)
            ->count();

        $totalMaterials = \App\Models\Material::where('course_id', $courseId)->count();
        $progress = $totalMaterials > 0 ? round(($completed / $totalMaterials) * 100) : 0;

        return response()->json([
            'success' => true,
            'progress' => $progress,
            'completed' => $completed,
            'total' => $totalMaterials
        ]);
    }
}