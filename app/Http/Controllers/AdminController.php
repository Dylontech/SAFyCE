<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Spatie\Dropbox\Client as DropboxClient;

class AdminController extends Controller
{
    protected $dropboxClient;

    public function __construct()
    {
        $this->dropboxClient = new DropboxClient(env('DROPBOX_AUTHORIZATION_TOKEN'));
    }

    public function index()
    {
        $tables = DB::select('SHOW TABLES');
        $tables = array_map('current', $tables);

        return view('admin.index', compact('tables'));
    }

    public function downloadBackupDatabase(Request $request)
    {
        $backupFileName = 'database_backup_' . date('Y_m_d_H_i_s') . '.sql';
        $passwordPart = env('DB_PASSWORD') ? '-p' . escapeshellarg(env('DB_PASSWORD')) : '';
        $mysqldumpPath = 'D:\Aplicaciones\xampp\mysql\bin\mysqldump';

        // Comando mysqldump sin la opción -p si no hay contraseña
        $command = sprintf(
            '%s -u%s %s %s',
            escapeshellarg($mysqldumpPath),
            escapeshellarg(env('DB_USERNAME')),
            $passwordPart,
            escapeshellarg(env('DB_DATABASE'))
        );

        $response = new StreamedResponse(function() use ($command) {
            $process = popen($command, 'r');
            fpassthru($process);
            pclose($process);
        });

        $response->headers->set('Content-Type', 'application/octet-stream');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $backupFileName . '"');

        return $response;
    }

    public function downloadBackupTable(Request $request)
    {
        $table = $request->input('table');
        $backupFileName = 'table_backup_' . $table . '_' . date('Y_m_d_H_i_s') . '.sql';
        $passwordPart = env('DB_PASSWORD') ? '-p' . escapeshellarg(env('DB_PASSWORD')) : '';
        $mysqldumpPath = 'D:\Aplicaciones\xampp\mysql\bin\mysqldump';

        // Comando mysqldump sin la opción -p si no hay contraseña
        $command = sprintf(
            '%s -u%s %s %s %s',
            escapeshellarg($mysqldumpPath),
            escapeshellarg(env('DB_USERNAME')),
            $passwordPart,
            escapeshellarg(env('DB_DATABASE')),
            escapeshellarg($table)
        );

        $response = new StreamedResponse(function() use ($command) {
            $process = popen($command, 'r');
            fpassthru($process);
            pclose($process);
        });

        $response->headers->set('Content-Type', 'application/octet-stream');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $backupFileName . '"');

        return $response;
    }
}
