<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    use HasFactory;

    protected $fillable = [
        'facility_name',
        'image_url',
        'description',
        'location',
        'capacity',
        'program_id',
    ];

    protected $appends = ['name', 'image'];

    public function getNameAttribute()
    {
        return $this->attributes['facility_name'] ?? null;
    }

    public function setNameAttribute($value)
    {
        $this->attributes['facility_name'] = $value;
    }

    public function getImageAttribute()
    {
        return $this->attributes['image_url'] ?? null;
    }

    public function setImageAttribute($value)
    {
        $this->attributes['image_url'] = $value;
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }
}
