/**
 * Este script verifica la conexión a un servidor MySQL utilizando las credenciales proporcionadas.
 * 
 * Variables:
 * - $host_name: Nombre del host del servidor MySQL.
 * - $database: Nombre de la base de datos a la que se desea conectar.
 * - $user_name: Nombre de usuario para autenticarse en el servidor MySQL.
 * - $password: Contraseña para autenticarse en el servidor MySQL.
 * 
 * Procedimiento:
 * 1. Se crea una nueva instancia de la clase mysqli con las credenciales proporcionadas.
 * 2. Se verifica si la conexión fue exitosa.
 * 3. Si la conexión falla, se muestra un mensaje de error.
 * 4. Si la conexión es exitosa, se muestra un mensaje de éxito.
 * 
 * Nota: Asegúrese de cambiar las credenciales por las correctas antes de ejecutar el script.
 */
<?php
  $host_name = 'db5017013256.hosting-data.io';
  $database = 'dbs13703007'; // Cambiar por el nombre de la base de datos
  $user_name = 'dbu2862995'; // Cambiar por el nombre de usuario del servidor MySQL
  $password = 'Alexander134431'; // Cambiar por la contraseña del servidor MySQL

  $link = new mysqli($host_name, $user_name, $password, $database);

  if ($link->connect_error) {
    die('<p>Error al conectar con servidor MySQL: '. $link->connect_error .'</p>');
  } else {
    echo '<p>Se ha establecido la conexión al servidor MySQL con éxito.</p>';
  }
?>
