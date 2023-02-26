<?php
  //Variables para DataBase
  $username="root";
  $password="root";
  $host="localhost";
  $database="Pruebas";

  //Configuración de la BD
  $db = new mysqli($host, $username, $password, $database);
  if ($db->connect_errno != null) {
    echo "Error número $db->connect_errno conectando a la base de datos.<br>Mensaje: $db->connect_error.";
    exit(); 
  }
  //Configurar el juego de caracteres
  $db->set_charset('utf8');

  $sql = 'SELECT * FROM Usuarios ORDER BY IdUsuario DESC LIMIT 1;';
  $resultado = $db->query($sql);
  $ultimo_usuario = $resultado->fetch_array(MYSQLI_ASSOC);
?>



<html lang="es">
  <head><title>Usuario Registrado</title></head>
  <body>
    <h1>Usuario registrado</h1>
    <p>Se ha registrado un usuario con los siguientes datos:</p> 
    <? //Obtener de MySql?>
      <ul>
        <li>ID: <?= $ultimo_usuario["IdUsuario"]?> </li>
        <li>Código de Llave: <?= $ultimo_usuario["CodLlave"]?> </li>
        <li>Nombre: <?= $ultimo_usuario["Nombre"]?> </li>
        <li>Primer apellido: <?= $ultimo_usuario["PrimerApellido"]?> </li>
        <li>Segundo apellido: <?= $ultimo_usuario["SegundoApellido"]?> </li>
        <li>Correo Electrónico: <?= $ultimo_usuario["CorreoElectronico"]?> </li>
      </ul>

    <br>
    <p>En caso de error, notificar al encargado.</p>
  </body>
</html>