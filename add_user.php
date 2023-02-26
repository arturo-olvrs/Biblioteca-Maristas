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


    $feedback="";

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
    

    //Importante que on DELETE Cascade

    // DEPENDE DEL SERVIDOR
    //Para que se actualice a las 00.00 a todos fuera, lo de abajo
    //CREATE DEFINER=`root`@`localhost` EVENT `UsuariosFuera` ON SCHEDULE EVERY 1 DAY STARTS '2022-09-09 00:00:00' ON COMPLETION PRESERVE ENABLE DO UPDATE UsuarioEstado SET IdEstado=0

    //Lo añado a la tabla estática Usuarios
    //No se comprueba si CodLlave está repetida porque está como UNIQUE
    $query1 = 'INSERT INTO `Usuarios` (`CodLlave`, `Nombre`, `PrimerApellido`, `SegundoApellido`, `CorreoElectronico`)
        VALUES ("'.$CodLlave.'","'. $Nombre.'","'. $PrimerApellido.'","'. $SegundoApellido.'","'. $CorreoElectronico.'")';
    $resultado1 = $db->query($query1);


    /*
    //Obtengo sus datos
    $query2 = 'SELECT * FROM Usuarios WHERE CodLlave="'.$CodLlave.'"';
    $resultado2 = $db->query($query2);
    $ultimo_usuario = $resultado2->fetch_array(MYSQLI_ASSOC);

    //Lo añado a la tabla dinámica UsuarioEstado, dándole el valor por defecto 0
    $query3='INSERT INTO UsuarioEstado (IdUsuario, IdEstado) VALUES ("'.$ultimo_usuario["IdUsuario"].'", "0")';
    $resultado3 = $db->query($query3);
    */

    if (!($resultado1)){ // && $resultado2 && $resultado3
      $feedback = "Ha habido un error. ";
      $feedback .= "Notificar al encargado. <br>";
      $feedback .= "<strong>Error:</strong> " . $db->error . ".<br>";
      $feedback .= "Vuelva a intentarlo.";
    }
    
    else{
      header("Location: add_success.php", TRUE, 307);
      exit;      
    }

    $db->close();

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
          <a href="modificar_datos.php">Modificar Datos</a>
        </div>
      </div>

      <a href="normativa.html">Normativa</a>
      
      <a class="no-style" href="https://www.maristasgranada.com/" target="_blank"><img src="Imagenes/Maristas_Logo.png" style="margin-left:40px; height:30px"></a>
    </nav>
  </header>   


  <body>

    <div class="contenido">
      <h1>Añadir un nuevo usuario</h1>
      <form action="" method="post" class="formulario">
        
        <label>Código de Llave:</label>
        <input type="text" name="CodLlave" id="CodLlave" required  value="<?=$CodLlave?>"
                pattern="[A-Z0-9]{2}-[A-Z0-9]{2}-[A-Z0-9]{2}-[A-Z0-9]{2}">
        <button onclick='pasteFromClipBoard()'><ion-icon name='clipboard-outline'></ion-icon></button>
        <br><br>

        <label>Nombre:</label>
        <input id="nombre" name="nombre" type="text" required minlength="3" maxlength="50" pattern="[A-za-z][A-za-z ]+" value="<?=$nombre?>">
        <br><br>

        <label>Primer Apellido:</label>
        <input id="PrimerApellido" name="PrimerApellido" type="text" required minlength="3" maxlength="50" pattern="[A-za-z][A-za-z ]+" value="<?=$PrimerApellido?>">
        <br><br>

        <label>Segundo Apellido:</label>
        <input id="SegundoApellido" name="SegundoApellido" type="text" minlength="3" maxlength="50" pattern="[A-za-z][A-za-z ]+" value="<?=$SegundoApellido?>">
        <br><br>

        <label>Correo Electrónico:</label>
        <input type="email" name="email" id="email" required  value="<?=$email?>"
                pattern="^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)+$">
        <br><br>

        <input type="submit" value="Añadir">
      </form>

      <p><?=$feedback?></p>
    </div>

    
    <!--Necesario para el botón del portapapeles-->
    <script>
      function pasteFromClipBoard() {
        navigator.clipboard
          .readText()
          .then(
              cliptext =>
                  (document.getElementById('CodLlave').value = cliptext),
                  err => console.log(err)
          );
      }
    </script>

      <!--Necesario para la imagen del portapapeles-->
    <script type='module' src='https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js'></script>
    <script nomodule src='https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js'></script>
  </body>
</html>