<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use ZipArchive;

class BackupController extends Controller
{
    public function index()
    {
        $backups = $this->getBackups();
        return view('admin.backups.index', compact('backups'));
    }

    private function getBackups()
    {
        $backupPath = storage_path('app/backups');

        // Check if directory exists
        if (!File::exists($backupPath)) {
            return [];
        }

        $files = File::files($backupPath);
        $backups = [];

        foreach ($files as $file) {
            $ext = $file->getExtension();
            if ($ext === 'zip' || $ext === 'sql') {
                $backups[] = [
                    'path' => $file->getFilename(),
                    'name' => $file->getFilename(),
                    'size' => $file->getSize(),
                    'size_human' => $this->formatBytes($file->getSize()),
                    'date' => $file->getMTime(),
                    'date_human' => date('Y-m-d H:i:s', $file->getMTime()),
                ];
            }
        }

        usort($backups, function($a, $b) {
            return $b['date'] - $a['date'];
        });

        return $backups;
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }

    public function create()
    {
        try {
            set_time_limit(300); // 5 minutes

            $filename = 'backup-' . date('Y-m-d-H-i-s') . '.sql';
            $backupPath = 'backups';

            // Ensure directory exists using File facade
            $fullDir = storage_path('app/' . $backupPath);
            if (!File::exists($fullDir)) {
                File::makeDirectory($fullDir, 0755, true);
            }

            $fullPath = $fullDir . '/' . $filename;

            $this->createPhpBackup($fullPath);

            return back()->with('success', 'Database backup created successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Backup failed: ' . $e->getMessage());
        }
    }

    private function createPhpBackup($filePath)
    {
        $database = config('database.connections.mysql.database');
        $handle = fopen($filePath, 'w');

        if (!$handle) {
            throw new \Exception('Cannot create backup file');
        }

        fwrite($handle, "-- Database Backup\n");
        fwrite($handle, "-- Generated: " . date('Y-m-d H:i:s') . "\n");
        fwrite($handle, "-- Database: {$database}\n\n");
        fwrite($handle, "SET FOREIGN_KEY_CHECKS=0;\n");
        fwrite($handle, "SET SQL_MODE='NO_AUTO_VALUE_ON_ZERO';\n\n");

        $tables = DB::select('SHOW TABLES');

        // Get the correct column name dynamically
        $firstTable = (array)$tables[0];
        $tableKey = array_key_first($firstTable);

        foreach ($tables as $table) {
            $tableName = $table->$tableKey;

            fwrite($handle, "\n-- Table: {$tableName}\n");
            fwrite($handle, "DROP TABLE IF EXISTS `{$tableName}`;\n");

            $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`");
            fwrite($handle, $createTable[0]->{'Create Table'} . ";\n\n");

            $rows = DB::table($tableName)->get();

            if ($rows->count() > 0) {
                foreach ($rows as $row) {
                    $values = [];
                    foreach ((array)$row as $value) {
                        if (is_null($value)) {
                            $values[] = 'NULL';
                        } else {
                            $values[] = "'" . addslashes($value) . "'";
                        }
                    }

                    $columns = array_keys((array)$row);
                    $columnList = '`' . implode('`, `', $columns) . '`';
                    $valueList = implode(', ', $values);

                    fwrite($handle, "INSERT INTO `{$tableName}` ({$columnList}) VALUES ({$valueList});\n");
                }
                fwrite($handle, "\n");
            }
        }

        fwrite($handle, "SET FOREIGN_KEY_CHECKS=1;\n");
        fclose($handle);
    }

    public function createFull()
    {
        try {
            set_time_limit(600); // 10 minutes

            $filename = 'full-backup-' . date('Y-m-d-H-i-s') . '.zip';
            $backupPath = 'backups';

            // Ensure directory exists
            $fullDir = storage_path('app/' . $backupPath);
            if (!File::exists($fullDir)) {
                File::makeDirectory($fullDir, 0777, true);
            }

            $zipPath = $fullDir . '/' . $filename;

            // Create SQL backup first
            $tempDir = storage_path('app/backup-temp');
            if (!File::exists($tempDir)) {
                File::makeDirectory($tempDir, 0777, true);
            }

            $sqlFile = $tempDir . '/database.sql';
            $this->createPhpBackup($sqlFile);

            // Create zip
            $zip = new ZipArchive;
            $result = $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

            if ($result !== true) {
                throw new \Exception('Could not create zip file. Error code: ' . $result);
            }

            // Add database
            $zip->addFile($sqlFile, 'database.sql');

            // Add important files (customize as needed)
            $this->addFilesToZip($zip, base_path('app'), 'app');
            $this->addFilesToZip($zip, base_path('config'), 'config');
            $this->addFilesToZip($zip, base_path('database/migrations'), 'database/migrations');
            $this->addFilesToZip($zip, base_path('public'), 'public');
            $this->addFilesToZip($zip, base_path('resources'), 'resources');
            $this->addFilesToZip($zip, base_path('routes'), 'routes');

            $zip->close();

            // Cleanup
            File::delete($sqlFile);
            File::deleteDirectory($tempDir);

            return back()->with('success', 'Full backup created successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Full backup failed: ' . $e->getMessage());
        }
    }

    private function addFilesToZip($zip, $path, $zipPath)
    {
        if (!File::exists($path)) {
            return;
        }

        $files = File::allFiles($path);
        foreach ($files as $file) {
            $relativePath = $zipPath . '/' . $file->getRelativePathname();
            $zip->addFile($file->getRealPath(), $relativePath);
        }
    }

    public function download($filename)
    {
        $path = storage_path('app/backups/' . $filename);

        if (!File::exists($path)) {
            return back()->with('error', 'Backup file not found.');
        }

        return response()->download($path);
    }

    public function destroy($filename)
    {
        $path = storage_path('app/backups/' . $filename);

        if (!File::exists($path)) {
            return back()->with('error', 'Backup file not found.');
        }

        File::delete($path);
        return back()->with('success', 'Backup deleted successfully!');
    }

    public function restore(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|file|mimes:sql,zip',
        ]);

        try {
            set_time_limit(300);

            $file = $request->file('backup_file');
            $extension = $file->getClientOriginalExtension();

            if ($extension === 'sql') {
                $this->restoreSqlFile($file->getRealPath());
            } elseif ($extension === 'zip') {
                $this->restoreFromZip($file->getRealPath());
            }

            return back()->with('success', 'Database restored successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Restore failed: ' . $e->getMessage());
        }
    }

    private function restoreSqlFile($path)
    {
        $sql = file_get_contents($path);
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::unprepared($sql);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    private function restoreFromZip($zipPath)
    {
        $zip = new ZipArchive;

        if ($zip->open($zipPath) !== true) {
            throw new \Exception('Could not open zip file');
        }

        $tempDir = storage_path('app/restore-temp');
        if (!File::exists($tempDir)) {
            File::makeDirectory($tempDir, 0755, true);
        }

        $zip->extractTo($tempDir);
        $zip->close();

        $sqlFile = null;
        $files = File::allFiles($tempDir);

        foreach ($files as $file) {
            if ($file->getExtension() === 'sql') {
                $sqlFile = $file->getRealPath();
                break;
            }
        }

        if (!$sqlFile) {
            throw new \Exception('No SQL file found in backup');
        }

        $this->restoreSqlFile($sqlFile);
        File::deleteDirectory($tempDir);
    }

    public function clean()
    {
        try {
            $backupPath = 'backups';
            $files = Storage::disk('local')->files($backupPath);

            $backups = [];
            foreach ($files as $file) {
                $backups[] = [
                    'path' => $file,
                    'time' => Storage::disk('local')->lastModified($file)
                ];
            }

            usort($backups, function($a, $b) {
                return $b['time'] - $a['time'];
            });

            // Keep only last 7 backups
            $toDelete = array_slice($backups, 7);

            foreach ($toDelete as $backup) {
                Storage::disk('local')->delete($backup['path']);
            }

            return back()->with('success', 'Old backups cleaned successfully! Kept 7 most recent backups.');
        } catch (\Exception $e) {
            return back()->with('error', 'Cleanup failed: ' . $e->getMessage());
        }
    }
}
