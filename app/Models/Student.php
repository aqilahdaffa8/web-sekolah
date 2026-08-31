<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * students: id, nis, name, class_id, status, timestamps
 */
class Student extends Model
{
    use HasFactory;

    protected $fillable = ['nis', 'name', 'class_id', 'status'];

    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
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
