<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    protected $fillable = ['program_name', 'description', 'image'];

    protected $appends = ['name'];

    public function getNameAttribute(): string
    {
        return $this->program_name ?? '';
    }

    public function classes()
    {
        return $this->hasMany(ClassRoom::class);
    }

    public function facilities()
    {
        return $this->hasMany(Facility::class);
    }
}
