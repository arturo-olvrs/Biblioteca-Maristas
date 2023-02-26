<?php

  if ($_SERVER["REQUEST_METHOD"] === "POST") {

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

    //Variables de POST
    $CodLlave="";
    $Nombre="";
    $PrimerApellido="";
    $SegundoApellido="";
    $CorreoElectronico="";

    // Limpieza de los datos
    $CodLlave=htmlspecialchars(trim($_POST["CodLlave"]));
    $Nombre=htmlspecialchars(ucwords(trim($_POST["nombre"])));
    $PrimerApellido=htmlspecialchars(ucwords(trim($_POST["PrimerApellido"])));
    $SegundoApellido=htmlspecialchars(ucwords(trim($_POST["SegundoApellido"])));
    $CorreoElectronico=filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    

    //Lo modifico en la tabla estática Usuarios
    $query1 = "UPDATE `Usuarios` SET `Nombre`='".$Nombre."',`PrimerApellido`='".$PrimerApellido."',`SegundoApellido`='".$SegundoApellido."',`CorreoElectronico`='".$CorreoElectronico. "'" .
              " WHERE `CodLlave`='".$CodLlave."'";
    $resultado1 = $db->query($query1);

  } // if ($_SERVER["REQUEST_METHOD"] === "POST")
?>




<html lang="es">
  <head>
    <meta charset="utf-8">
    <title>Biblioteca</title>
    <link rel="icon" href="Imagenes/Maristas_Logo.png">
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
          <a href="modificar_datos.php" style="border-bottom: 2px solid red">Modificar Datos</a>
        </div>
      </div>

      <a href="normativa.html">Normativa</a>
      
      <a class="no-style" href="https://www.maristasgranada.com/" target="_blank"><img src="Imagenes/Maristas_Logo.png" style="margin-left:40px; height:30px"></a>
    </nav>
  </header>  


  <body>

    <div class="contenido">
            
      <?php
        if (!($resultado1)){
          echo "Ha habido un error. ";
          echo "Notificar al encargado. <br>";
          echo "<strong>Error:</strong> " . $db->error . ".<br>";
          exit();
        }

        $db->close();
      ?>

      <!-- No ha habido ningún error -->
      <h1>Datos modificados</h1>

      <p> Los datos actualizados son:</p> 
        
      <!-- //Obtener de MySql -->
      <div class="list">
        <a>Código de Llave:    <?= $CodLlave?> </a> <br>
        <a>Nombre:             <?= $Nombre?> </a> <br>
        <a>Primer apellido:    <?= $PrimerApellido?> </a> <br>
        <a>Segundo apellido:   <?= $SegundoApellido?> </a> <br>
        <a>Correo Electrónico: <?= $CorreoElectronico?> </a> <br>
      </div>

    </div>
    
  </body>
</html>