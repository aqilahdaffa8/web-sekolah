<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * student_grades: id, student_id, subject_id, teacher_id, theory_score, practice_score, ukk_score, pkl_score, timestamps
 */
class StudentGrade extends Model
{
    protected $fillable = [
        'student_id',
        'subject_id',
        'teacher_id',
        'theory_score',
        'practice_score',
        'ukk_score',
        'pkl_score',
    ];

    protected $casts = [
        'theory_score' => 'float',
        'practice_score' => 'float',
        'ukk_score' => 'float',
        'pkl_score' => 'float',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}
