<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model; // Pastikan ini ada

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

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
        return $this->role === 'admin';
    }

    // ROLE CHECKERS (SIMPLE VERSION)
    public function isAdmin(): bool { return $this->role === 'admin'; }
    public function isPengajar(): bool { return $this->role === 'pengajar'; }
    public function isSiswa(): bool { return $this->role === 'siswa'; }

    // RELATIONSHIPS (SIMPLE VERSION)
    public function taughtCourses(): HasMany
    {
        return $this->hasMany(\App\Models\Course::class, 'user_id');
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(\App\Models\Enrollment::class);
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

    // FIX: Tambahkan method save jika tidak ada (seharusnya ada dari Model)
    public function save(array $options = [])
    {
        return parent::save($options);
    }
}