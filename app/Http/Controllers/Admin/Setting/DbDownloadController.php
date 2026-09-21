<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class DbDownloadController extends Controller
{
    public function __invoke(Request $request)
    {
        // Outside the try: an unauthorised user must get a 403, not a friendly redirect.
        Gate::authorize('admin.settings.download-db');

        try {
            $connection = config('database.default');
            $config = config("database.connections.{$connection}");

            $filename = $config['database'].'_backup_'.now()->format('Y-m-d_H-i-s').'.sql';
            $filePath = storage_path('app/'.$filename);

            $mysqldump = $this->findMysqldump();

            if (! $mysqldump) {
                return $this->fail($request, 'mysqldump not found on server. Please contact your hosting provider.');
            }

            // Arguments are passed as an array (no shell quoting problems on Windows), the
            // password travels in MYSQL_PWD instead of the process list, and --result-file
            // keeps mysqldump's warnings out of the .sql file.
            $process = new Process([
                $mysqldump,
                '--user='.$config['username'],
                '--host='.$config['host'],
                '--port='.($config['port'] ?? 3306),
                '--single-transaction',
                '--routines',
                '--triggers',
                '--result-file='.$filePath,
                $config['database'],
            ], null, ['MYSQL_PWD' => (string) ($config['password'] ?? '')], null, 300);

            $process->run();

            if (! $process->isSuccessful() || ! file_exists($filePath) || filesize($filePath) === 0) {
                if (file_exists($filePath)) {
                    @unlink($filePath);
                }

                Log::error('Database backup failed.', ['output' => $process->getErrorOutput()]);

                return $this->fail($request, 'Backup failed. Check logs for details.');
            }

            $firstBytes = file_get_contents($filePath, false, null, 0, 200);
            if (stripos($firstBytes, '<html') !== false || stripos($firstBytes, '<!DOCTYPE') !== false) {
                unlink($filePath);

                return $this->fail($request, 'Backup failed: received HTML instead of SQL.');
            }

            $fileSize = filesize($filePath);

            // Safety net: make sure the full database dump never lingers in
            // storage if the client aborts the download before the stream
            // closure finishes its own unlink().
            register_shutdown_function(function () use ($filePath) {
                if (is_file($filePath)) {
                    @unlink($filePath);
                }
            });

            return response()->streamDownload(function () use ($filePath) {
                $handle = fopen($filePath, 'rb');

                if (! $handle) {
                    return;
                }

                while (! feof($handle)) {
                    echo fread($handle, 1024 * 1024);
                    if (ob_get_level() > 0) {
                        ob_flush();
                    }
                    flush();
                }

                fclose($handle);
                unlink($filePath);

            }, $filename, [
                'Content-Type' => 'application/octet-stream',
                'Content-Disposition' => 'attachment; filename="'.$filename.'"',
                'Content-Length' => $fileSize,
                'Cache-Control' => 'no-store, no-cache, must-revalidate',
                'Pragma' => 'no-cache',
            ]);

        } catch (\Throwable $e) {
            report($e);

            return $this->fail($request, 'Something went wrong while creating the backup. Check logs for details.');
        }
    }

    /**
     * The settings page downloads the backup with fetch(), so errors must come back
     * as JSON (a redirect would be followed and saved as the "backup" file).
     */
    private function fail(Request $request, string $message)
    {
        return $request->expectsJson() || $request->ajax()
            ? response()->json(['message' => $message], 500)
            : back()->with('error', $message);
    }

    private function findMysqldump(): ?string
    {
        $isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';

        if ($isWindows) {
            $paths = [
                'D:\\xampp\\mysql\\bin\\mysqldump.exe',
                'C:\\xampp\\mysql\\bin\\mysqldump.exe',
                'C:\\laragon\\bin\\mysql\\mysql-8.0\\bin\\mysqldump.exe',
                'C:\\Program Files\\MySQL\\MySQL Server 8.0\\bin\\mysqldump.exe',
            ];

            foreach ($paths as $path) {
                if (file_exists($path)) {
                    return $path;
                }
            }

            $output = shell_exec('where mysqldump 2>NUL');
            if ($output) {
                return trim(explode(PHP_EOL, $output)[0]);
            }
        } else {
            $paths = [
                '/usr/bin/mysqldump',
                '/usr/local/bin/mysqldump',
                '/usr/local/mysql/bin/mysqldump',
                '/opt/cpanel/ea-mysql80/root/usr/bin/mysqldump',
                '/opt/cpanel/ea-mysql57/root/usr/bin/mysqldump',
                '/usr/local/cpanel/3rdparty/bin/mysqldump',
            ];

            foreach ($paths as $path) {
                if (file_exists($path)) {
                    return $path;
                }
            }

            $output = shell_exec('which mysqldump 2>/dev/null');
            if ($output) {
                return trim($output);
            }
        }

        return null;
    }
}
