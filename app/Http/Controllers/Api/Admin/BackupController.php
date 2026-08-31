<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Services\ActivityLogService;
use App\Services\BackupService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class BackupController extends Controller
{
    public function __construct(
        private BackupService $backupService,
        private ActivityLogService $logger
    ) {}

    /**
     * POST /api/admin/backup — trigger a new backup
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $path = $this->backupService->create();
            $this->logger->log($request->user()->id, 'backup_created', 'database', null, basename($path));

            return response()->json([
                'message'  => 'Backup created successfully.',
                'filename' => basename($path),
            ]);
        } catch (\RuntimeException $e) {
            return response()->json(['message' => 'Backup failed: ' . $e->getMessage(), 'code' => 500], 500);
        }
    }

    /**
     * GET /api/admin/backup — list all backups
     */
    public function index(): JsonResponse
    {
        return response()->json(['backups' => $this->backupService->list()]);
    }

    /**
     * GET /api/admin/backup/{filename} — download a backup file
     */
    public function download(string $filename): BinaryFileResponse|\Illuminate\Http\JsonResponse
    {
        // Prevent directory traversal
        $filename = basename($filename);
        $path     = storage_path('app/backups/' . $filename);

        if (! file_exists($path)) {
            return response()->json(['message' => 'File not found.', 'code' => 404], 404);
        }

        return response()->download($path);
    }
}
