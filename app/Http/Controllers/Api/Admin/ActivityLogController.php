<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $logs = ActivityLog::with('user')
            ->when($request->user_id, fn ($q) => $q->where('user_id', $request->user_id))
            ->when($request->table, fn ($q) => $q->where('table_affected', $request->table))
            ->when($request->action, fn ($q) => $q->where('action', $request->action))
            ->latest()
            ->paginate(20);

        return response()->json($logs);
    }
}
