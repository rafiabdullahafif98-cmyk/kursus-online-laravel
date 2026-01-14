<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // ✅ IMPORT INI

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable; // ✅ HAPUS DUPLIKAT

    protected $fillable = [
        'name', 
        'email', 
        'password', 
        'role',
        'address', 
        'bio',
        'last_login_at',
    ];
    
    protected $hidden = [
        'password', 
        'remember_token',
    ];
    
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'last_login_at' => 'datetime',
    ];

    // FILAMENT ACCESS
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->role === 'admin' && $panel->getId() === 'admin';
    }

    // ROLE CHECKERS
    public function isAdmin(): bool 
    { 
        return $this->role === 'admin'; 
    }
    
    public function isPengajar(): bool 
    { 
        return $this->role === 'pengajar'; 
    }
    
    public function isSiswa(): bool 
    { 
        return $this->role === 'siswa'; 
    }

    // RELATIONSHIPS
    public function taughtCourses(): HasMany
    {
        return $this->hasMany(Course::class, 'pengajar_id');
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function enrolledCourses(): BelongsToMany
    {
        return $this->belongsToMany(
            Course::class, 
            'enrollments', 
            'user_id', 
            'course_id'
        )
        ->withPivot('status', 'enrolled_at')
        ->withTimestamps();
    }

    public function progressTrackings(): HasMany
    {
        return $this->hasMany(ProgressTracking::class);
    }
}