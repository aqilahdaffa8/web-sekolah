<?php

namespace App\Services;

use App\Models\ActivityLog;

class ActivityLogService
{
    /**
     * Record an admin action to the activity_logs table.
     *
     * @param  string  $action  e.g. 'created', 'updated', 'deleted'
     * @param  string  $tableAffected  e.g. 'users', 'posts'
     * @param  int|null  $recordId  Ignored (column not in current schema, reserved for future migration)
     * @param  string|null  $description  Ignored (column not in current schema)
     */
    public function log(
        int $userId,
        string $action,
        string $tableAffected,
        ?int $recordId = null,
        ?string $description = null
    ): void {
        ActivityLog::create([
            'user_id' => $userId,
            'action' => $action,
            'table_affected' => $tableAffected,
        ]);
    }
}
