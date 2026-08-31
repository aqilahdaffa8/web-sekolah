<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Columns: id, user_id, action, table_affected, created_at
 *
 * Note: The migration only defines user_id, action, table_affected, created_at.
 * Keep fillable aligned to what exists in DB.
 */
class ActivityLog extends Model
{
    public $timestamps = false; // Only created_at exists (set by DB default)

    protected $fillable = [
        'user_id',
        'action',
        'table_affected',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
