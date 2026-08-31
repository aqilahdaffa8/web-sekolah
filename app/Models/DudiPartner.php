<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * dudi_partners: id, company_name, industry_field, logo_url, mou_document, timestamps
 */
class DudiPartner extends Model
{
    use HasFactory;

    protected $table = 'dudi_partners';

    protected $fillable = [
        'company_name',
        'industry_field',
        'logo_url',
        'mou_document',
    ];

    public function jobVacancies()
    {
        return $this->hasMany(JobVacancy::class);
    }
}
