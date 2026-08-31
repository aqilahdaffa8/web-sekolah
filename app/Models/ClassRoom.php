<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassRoom extends Model
{
    use HasFactory;

    protected $table = 'classes';

    protected $fillable = ['class_name', 'program_id'];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'class_id');
    }

    public function teacherClassSubjects()
    {
        return $this->hasMany(TeacherClassSubject::class, 'class_id');
    }
}
