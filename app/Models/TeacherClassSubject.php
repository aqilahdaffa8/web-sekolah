<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherClassSubject extends Model
{
    protected $table = 'teacher_class_subject';

    protected $fillable = ['teacher_id', 'class_id', 'subject_id'];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
