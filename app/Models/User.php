<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
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
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = [
        'active',
        'role_name',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function getActiveAttribute(): bool
    {
        return (bool) ($this->is_active ?? true);
    }

    public function getRoleNameAttribute(): ?string
    {
        return $this->roles->first()?->role_name ?? $this->roles->first()?->name ?? null;
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

    public function student(): HasOne
    {
        return $this->hasOne(Student::class);
    }

    /**
     * Payload used by login /me for the SPA session.
     *
     * @return array<string, mixed>
     */
    public function toSessionArray(): array
    {
        $this->loadMissing(['roles.permissions', 'student']);

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'roles' => $this->roles->pluck('role_name'),
            'permissions' => $this->allPermissions()->values(),
            'student' => $this->student ? [
                'id' => $this->student->id,
                'nis' => $this->student->nis,
                'name' => $this->student->name,
                'status' => $this->student->status,
            ] : null,
        ];
    }
}
