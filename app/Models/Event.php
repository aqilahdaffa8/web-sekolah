<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image',
        'location',
        'event_date',
        'start_date',
        'end_date',
        'status',
        'created_by',
    ];

    protected $casts = [
        'event_date' => 'date',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];
}
