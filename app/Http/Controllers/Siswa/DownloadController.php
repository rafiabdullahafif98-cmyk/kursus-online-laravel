<?php
// app/Http\Controllers\Siswa\DownloadController.php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\ProgressTracking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class DownloadController extends Controller
{
    /**
     * Download file dan otomatis tandai sebagai selesai
     */
    public function downloadWithProgress(Request $request, $courseId, $materialId)
    {
        // 1. Cek login
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        $userId = Auth::id();
        
        // 2. Tandai sebagai SELESAI di database - PAKAI RAW SQL UNTUK PASTI
        try {
            // Method 1: Raw SQL - PASTI BEKERJA
            DB::statement("
                INSERT INTO progress_trackings 
                (user_id, course_id, material_id, is_completed, completed_at, time_spent, created_at, updated_at)
                VALUES (?, ?, ?, 1, NOW(), 0, NOW(), NOW())
                ON DUPLICATE KEY UPDATE 
                is_completed = VALUES(is_completed), 
                completed_at = VALUES(completed_at),
                updated_at = VALUES(updated_at)
            ", [$userId, $courseId, $materialId]);
            
            // Log success
            \Log::info("Progress marked for user {$userId}, course {$courseId}, material {$materialId}");
            
        } catch (\Exception $e) {
            \Log::error("Failed to mark progress: " . $e->getMessage());
            // Lanjutkan download meskipun gagal mark progress
        }

        // 3. Dapatkan file
        $material = \App\Models\Material::find($materialId);
        
        if (!$material || !$material->file_path) {
            return back()->with('error', 'File tidak ditemukan');
        }

        $filePath = 'public/' . $material->file_path;
        
        if (!Storage::exists($filePath)) {
            return back()->with('error', 'File tidak ditemukan di storage');
        }

        // 4. Download file
        $filename = $material->title . '.' . pathinfo($material->file_path, PATHINFO_EXTENSION);
        return Storage::download($filePath, $filename);
    }

    /**
     * API untuk tandai selesai (untuk AJAX)
     */
    public function markComplete(Request $request)
    {
        $request->validate([
            'course_id' => 'required|integer',
            'material_id' => 'required|integer',
        ]);

        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan login terlebih dahulu'
            ], 401);
        }

        $userId = Auth::id();
        $courseId = $request->course_id;
        $materialId = $request->material_id;

        try {
            // PAKAI RAW SQL - 100% WORK
            DB::statement("
                INSERT INTO progress_trackings 
                (user_id, course_id, material_id, is_completed, completed_at, time_spent, created_at, updated_at)
                VALUES (?, ?, ?, 1, NOW(), 0, NOW(), NOW())
                ON DUPLICATE KEY UPDATE 
                is_completed = VALUES(is_completed), 
                completed_at = VALUES(completed_at),
                updated_at = VALUES(updated_at)
            ", [$userId, $courseId, $materialId]);

            // Hitung progress untuk response
            $totalMaterials = \App\Models\Material::where('course_id', $courseId)->count();
            $completed = DB::table('progress_trackings')
                ->where('user_id', $userId)
                ->where('course_id', $courseId)
                ->where('is_completed', true)
                ->count();

            $progress = $totalMaterials > 0 ? round(($completed / $totalMaterials) * 100) : 0;

            return response()->json([
                'success' => true,
                'message' => 'Materi ditandai selesai',
                'progress' => $progress,
                'completed' => $completed,
                'total' => $totalMaterials
            ]);

        } catch (\Exception $e) {
            \Log::error('Mark complete error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}