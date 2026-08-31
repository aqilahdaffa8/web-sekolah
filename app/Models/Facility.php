<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    use HasFactory;

    protected $fillable = ['facility_name', 'image_url', 'program_id'];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }
}
