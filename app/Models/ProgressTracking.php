<?php
// app/Models/ProgressTracking.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgressTracking extends Model
{
    use HasFactory;

    // TIDAK ADA protected $table
    // Biarkan Laravel otomatis cari 'progress_trackings'
    
    protected $fillable = [
        'user_id',
        'course_id', 
        'material_id',
        'is_completed',
        'completed_at',
        'time_spent'
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'completed_at' => 'datetime'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}