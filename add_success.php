<?php
  //Variables para DataBase
  $username="uizmimvv2wcd3";
  $password="9xhqongskab4";
  $host="danipartal.net";
  $database="dbq5x9hjq8yxq3";

  //Configuración de la BD
  $db = new mysqli($host, $username, $password, $database);
  if ($db->connect_errno != null) {
    echo "Error número $db->connect_errno conectando a la base de datos.";
    echo "<br>";
    echo "Mensaje: $db->connect_error.";
    exit();
  }
  //Configurar el juego de caracteres
  $db->set_charset('utf8');

  //Para obtener el último usuario, elige el último IdUsuario

  $sql = 'SELECT * FROM Usuarios ORDER BY IdUsuario DESC LIMIT 1;';
  $resultado = $db->query($sql);
  $ultimo_usuario = $resultado->fetch_array(MYSQLI_ASSOC);

  
  $db->close();
?>



<html lang="es">
  <head>
    <meta charset="utf-8">
    <title>Biblioteca</title>
    <link rel="icon" href="Imágenes/Maristas_Logo.png">
    <link rel="stylesheet" href="Estilo/style.css" type='text/css'>
  </head>

    
  <header>
    <nav>
      <a href="./">Inicio</a>
      <a href="#" target="_blank">Leer Llave</a>

      <!--Menú de Usuarios-->
      <div class="dropdown">
        <a class="dropbtn" style="border-bottom: 2px solid red">Usuarios</a>
        <div class="dropdown-content">
          <a href="add_user.php" style="border-bottom: 2px solid red">Añadir Usuario</a>
          <a href="eliminar_user.php">Eliminar Usuario</a>
          <a href="consultar_datos.php">Consultar Datos</a>
          <a href="modificar_datos.php">Modificar Datos</a>
        </div>
      </div>

      <a href="normativa.html">Normativa</a>
      
      <a class="no-style" href="https://www.maristasgranada.com/" target="_blank"><img src="Imagenes/Maristas_Logo.png" style="margin-left:40px; height:30px"></a>
    </nav>
  </header>   


  <body>
    <div class="contenido">
      <h1>Usuario registrado</h1>
      <p>Se ha registrado un usuario con los siguientes datos:</p> 
      
      <!-- //Obtener de MySql -->
      <div class="list">
        <a>- <strong>ID:</strong>                 <?= $ultimo_usuario["IdUsuario"]?> </a><br>
        <a>- <strong>Código de Llave:</strong>    <?= $ultimo_usuario["CodLlave"]?> </a><br>
        <a>- <strong>Nombre:</strong>             <?= $ultimo_usuario["Nombre"]?> </a><br>
        <a>- <strong>Primer apellido:</strong>    <?= $ultimo_usuario["PrimerApellido"]?> </a><br>
        <a>- <strong>Segundo apellido:</strong>   <?= $ultimo_usuario["SegundoApellido"]?> </a><br>
        <a>- <strong>Correo Electrónico:</strong> <?= $ultimo_usuario["CorreoElectronico"]?> </a><br>
      </div>

      <br>
      <p>En caso de error, notificar al encargado.</p>

    </div>
  </body>
</html>