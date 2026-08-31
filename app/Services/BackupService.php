<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;

class BackupService
{
    /**
     * Run mysqldump and store the file in storage/app/backups/.
     *
     * @return string Absolute path to the .sql.gz file
     *
     * @throws \RuntimeException on failure
     */
    public function create(): string
    {
        $db = config('database.connections.mysql');
        $filename = 'backup_'.now()->format('Y-m-d_His').'.sql.gz';
        $path = storage_path('app/backups/'.$filename);

        // Ensure directory exists
        if (! is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        $command = sprintf(
            'mysqldump --host=%s --port=%s --user=%s %s | gzip > %s',
            escapeshellarg($db['host']),
            escapeshellarg($db['port']),
            escapeshellarg($db['username']),
            escapeshellarg($db['database']),
            escapeshellarg($path)
        );

        $process = Process::fromShellCommandline($command);
        $process->setEnv(['MYSQL_PWD' => $db['password']]);
        $process->setTimeout(300);
        $process->run();

        if (! $process->isSuccessful()) {
            throw new \RuntimeException(
                'mysqldump failed: '.$process->getErrorOutput()
            );
        }

        return $path;
    }

    /**
     * List all available backup files (newest first).
     */
    public function list(): array
    {
        $dir = storage_path('app/backups/');
        $files = glob($dir.'*.sql.gz') ?: [];
        usort($files, fn ($a, $b) => filemtime($b) - filemtime($a));

        return array_map(fn ($f) => [
            'filename' => basename($f),
            'size' => filesize($f),
            'created' => date('Y-m-d H:i:s', filemtime($f)),
        ], $files);
    }
}
