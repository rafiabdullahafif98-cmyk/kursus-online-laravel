<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Str;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'description', 'short_description',
        'price', 'thumbnail', 'category_id', 'user_id', 'is_published'
    ];

    // Relasi ke pengajar
    public function pengajar(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke kategori
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // Relasi ke materi
    public function materials(): HasMany
    {
        return $this->hasMany(Material::class);
    }

    // Relasi ke enrollment
    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    // Hitung jumlah siswa
    public function getStudentCountAttribute()
    {
        return $this->enrollments()->where('status', 'active')->count();
    }

    // Auto generate slug
    public static function boot()
    {
        parent::boot();
        
        static::creating(function ($course) {
            $course->slug = Str::slug($course->title);
        });
    }
}