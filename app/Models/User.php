<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ── RBAC Relations ────────────────────────────────────────────────────────

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    /**
     * Collect all permissions through the user's roles.
     * Cached per-request to avoid N+1 on every middleware check.
     */
    public function allPermissions(): Collection
    {
        return $this->roles
            ->flatMap(fn ($role) => $role->permissions)
            ->pluck('permission_name')
            ->unique();
    }

    public function hasRole(string $roleName): bool
    {
        return $this->roles->contains('role_name', $roleName);
    }

    public function hasPermission(string $permissionName): bool
    {
        return $this->allPermissions()->contains($permissionName);
    }

    // ── Other Relations ───────────────────────────────────────────────────────

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function teacherClassSubjects()
    {
        return $this->hasMany(TeacherClassSubject::class, 'teacher_id');
    }
}
