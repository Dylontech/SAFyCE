/**
 * Este script PHP se conecta a un servidor SFTP y sube archivos actualizados desde un directorio local a un directorio remoto.
 * 
 * Funciones principales:
 * 
 * - Conexión SFTP: Se conecta a un servidor SFTP utilizando las credenciales proporcionadas.
 * - uploadUpdates: Función que recorre recursivamente un directorio local y sube solo los archivos que han sido modificados
 *   más recientemente que sus contrapartes en el servidor remoto.
 * 
 * Parámetros:
 * - $host: Dirección del servidor SFTP.
 * - $port: Puerto del servidor SFTP (por defecto 22).
 * - $username: Nombre de usuario para la autenticación SFTP.
 * - $password: Contraseña para la autenticación SFTP.
 * - $localDirectory: Directorio local que contiene los archivos a subir.
 * - $remoteDirectory: Directorio remoto donde se subirán los archivos actualizados.
 * 
 * Proceso:
 * 1. Se establece una conexión SFTP y se autentica con las credenciales proporcionadas.
 * 2. La función uploadUpdates recorre el directorio local y compara las fechas de modificación de los archivos locales
 *    con los archivos en el servidor remoto.
 * 3. Si un archivo local ha sido modificado más recientemente que su contraparte remota, se sube al servidor.
 * 4. Se crean los directorios remotos necesarios si no existen.
 * 5. Se muestra un mensaje indicando el estado de la subida de cada archivo.
 */
<?php

require 'vendor/autoload.php';

use phpseclib3\Net\SFTP;

$host = 'access-5017003729.webspace-host.com'; // Dirección del servidor SFTP
$port = 22; // Puerto por defecto para SFTP
$username = 'a1409794'; // Reemplaza con tu nombre de usuario
$password = 'Alexander134431'; // Reemplaza con la contraseña que estableciste

$sftp = new SFTP($host, $port);

if (!$sftp->login($username, $password)) {
    exit('Falló la autenticación');
}

function uploadUpdates($sftp, $localDir, $remoteDir) {
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($localDir));

    foreach ($iterator as $file) {
        if ($file->isFile()) {
            $localPath = $file->getPathname();
            $remotePath = $remoteDir . str_replace($localDir, '', $localPath);

            $localModTime = filemtime($localPath);
            $remoteModTime = $sftp->file_exists($remotePath) ? $sftp->mtime($remotePath) : 0;

            if ($localModTime > $remoteModTime) {
                $remoteDirPath = dirname($remotePath);
                if (!$sftp->is_dir($remoteDirPath)) {
                    $sftp->mkdir($remoteDirPath, -1, true);
                }

                // Eliminar el archivo antiguo en el servidor antes de subir el nuevo
                if ($sftp->file_exists($remotePath)) {
                    $sftp->delete($remotePath);
                }

                if ($sftp->put($remotePath, $localPath, SFTP::SOURCE_LOCAL_FILE)) {
                    echo "Archivo actualizado: $remotePath\n";
                } else {
                    echo "Falló la subida: $remotePath\n";
                }
            }
        }
    }
}

// Define el directorio local y remoto
$localDirectory = 'D:/Proyecto/VersionDylon/SAAFCECEYT'; // Directorio local del proyecto
$remoteDirectory = '/SAAFCECEYT'; // Directorio remoto donde se subirán los archivos actualizados

// Sube solo los archivos actualizados
uploadUpdates($sftp, $localDirectory, $remoteDirectory);

echo "Subida de archivos actualizados completada.\n";

