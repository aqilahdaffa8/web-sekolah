<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * extracurriculars: id, name, description, schedule, coach_id, timestamps
 */
class Extracurricular extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'schedule', 'coach_id'];

    public function coach()
    {
        return $this->belongsTo(User::class, 'coach_id');
    }

    public function registrations()
    {
        return $this->hasMany(ExtracurricularRegistration::class);
    }
}
