/**
 * Este script PHP se utiliza para subir un directorio completo a un servidor remoto utilizando SFTP.
 * Utiliza la biblioteca phpseclib para manejar la conexión SFTP y la transferencia de archivos.
 * 
 * Requisitos:
 * - Instalar la biblioteca phpseclib3 mediante Composer.
 * - Configurar las credenciales de acceso SFTP (host, puerto, usuario y contraseña).
 * 
 * Funciones principales:
 * - Conexión al servidor SFTP.
 * - Autenticación del usuario.
 * - Subida recursiva de un directorio local a un directorio remoto.
 * 
 * Variables:
 * - $host: Dirección del servidor SFTP.
 * - $port: Puerto del servidor SFTP.
 * - $username: Nombre de usuario para la autenticación SFTP.
 * - $password: Contraseña para la autenticación SFTP.
 * - $localDirectory: Directorio local que se desea subir.
 * - $remoteDirectory: Directorio remoto donde se subirán los archivos.
 * 
 * Funciones:
 * - uploadDirectory($sftp, $localDir, $remoteDir): Función recursiva que sube un directorio local al servidor remoto.
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

function uploadDirectory($sftp, $localDir, $remoteDir) {
    $dir = opendir($localDir);
    if (!$sftp->is_dir($remoteDir)) {
        $sftp->mkdir($remoteDir);
    }

    while (($file = readdir($dir)) !== false) {
        if ($file != '.' && $file != '..') {
            $localPath = "$localDir/$file";
            $remotePath = "$remoteDir/$file";

            if (is_dir($localPath)) {
                uploadDirectory($sftp, $localPath, $remotePath);
            } else {
                if ($sftp->put($remotePath, $localPath, SFTP::SOURCE_LOCAL_FILE)) {
                    echo "Archivo subido: $remotePath\n";
                } else {
                    echo "Falló la subida: $remotePath\n";
                }
            }
        }
    }
    closedir($dir);
}

// Define el directorio local y remoto
$localDirectory = 'D:/Proyecto/VersionDylon/SAAFCECEYT'; // Directorio local del proyecto
$remoteDirectory = '/Prueba1'; // Directorio remoto donde se subirá el proyecto

// Sube el directorio completo
uploadDirectory($sftp, $localDirectory, $remoteDirectory);

echo "Subida del proyecto completada.\n";

