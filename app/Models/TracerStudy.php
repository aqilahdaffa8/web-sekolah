<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * tracer_studies: id, student_id, graduation_year, current_status, company_or_campus_name, timestamps
 */
class TracerStudy extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'graduation_year',
        'current_status',         // Kerja, Kuliah, Wirausaha
        'company_or_campus_name',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
