<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = ['subject_name'];

    public function grades()
    {
        return $this->hasMany(StudentGrade::class);
    }

    public function teacherClassSubjects()
    {
        return $this->hasMany(TeacherClassSubject::class);
    }

    public function learningModules()
    {
        return $this->hasMany(LearningModule::class);
    }
}
