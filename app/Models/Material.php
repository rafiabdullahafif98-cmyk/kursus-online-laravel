<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'file_path',
        'file_type',
        'order',
        'course_id'
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function isCompletedByUser($userId)
    {
        if (!$userId) return false;

        return \App\Models\ProgressTracking::where('user_id', $userId)
            ->where('material_id', $this->id)
            ->where('is_completed', true)
            ->exists();
    }
}
