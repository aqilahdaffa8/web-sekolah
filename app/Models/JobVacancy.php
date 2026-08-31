<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * job_vacancies: id, job_title, description, dudi_id, status, timestamps
 */
class JobVacancy extends Model
{
    use HasFactory;

    protected $fillable = [
        'dudi_id',
        'job_title',
        'description',
        'status',
    ];

    public function dudiPartner()
    {
        return $this->belongsTo(DudiPartner::class, 'dudi_id');
    }
}
