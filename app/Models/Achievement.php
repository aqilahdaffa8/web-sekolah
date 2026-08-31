<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * achievements: id, title, description, image_url, extracurricular_id, timestamps
 */
class Achievement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image_url',
        'extracurricular_id',
    ];

    public function extracurricular()
    {
        return $this->belongsTo(Extracurricular::class);
    }
}
