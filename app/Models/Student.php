<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * students: id, nis, name, class_id, status, timestamps
 */
class Student extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'nis', 'name', 'class_id', 'status'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }

    public function isActive(): bool
    {
        return $this->status === 'aktif';
    }

    public function grades()
    {
        return $this->hasMany(StudentGrade::class);
    }

    public function tracerStudy()
    {
        return $this->hasOne(TracerStudy::class);
    }
}
