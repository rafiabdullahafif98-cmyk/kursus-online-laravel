<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // TAMBAHKAN
use Illuminate\Database\Eloquent\Relations\BelongsToMany; // TAMBAHKAN
use Illuminate\Database\Eloquent\Relations\HasMany; // TAMBAHKAN
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->role === 'admin';
    }

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

    // Relasi: User sebagai pengajar memiliki banyak courses
    public function courses(): HasMany
    {
        return $this->hasMany(Course::class, 'user_id');
    }

    // Relasi: User memiliki banyak enrollments
    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    // Relasi: User (siswa) memiliki banyak courses melalui enrollments
    public function enrolledCourses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'enrollments', 'user_id', 'course_id')
            ->withPivot('status', 'enrolled_at')
            ->withTimestamps();
    }
}