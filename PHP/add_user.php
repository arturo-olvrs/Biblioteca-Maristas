<?php
  //Variables para DataBase
  $username="uizmimvv2wcd3";
  $password="9xhqongskab4";
  $host="danipartal.net";
  $database="dbq5x9hjq8yxq3";

  //Configuración de la BD
  $db = new mysqli($host, $username, $password, $database);
  if ($db->connect_errno != null) {
    echo "Error número $db->connect_errno conectando a la base de datos.<br>Mensaje: $db->connect_error.";
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


  if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $CodLlave=htmlspecialchars(trim($_POST["CodLlave"]));
    $Nombre=htmlspecialchars(ucwords(trim($_POST["nombre"])));
    $PrimerApellido=htmlspecialchars(ucwords(trim($_POST["PrimerApellido"])));
    $SegundoApellido=htmlspecialchars(ucwords(trim($_POST["SegundoApellido"])));
    $CorreoElectronico=filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    

    //No compruebo si CodLlave está repetida porque está como UNIQUE
    //Importante que on DELETE Cascade
    //Para que se actualice a las 00.00 a todos fuera, lo de abajo
    //CREATE DEFINER=`root`@`localhost` EVENT `UsuariosFuera` ON SCHEDULE EVERY 1 DAY STARTS '2022-09-09 00:00:00' ON COMPLETION PRESERVE ENABLE DO UPDATE UsuarioEstado SET IdEstado=0
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////    //IMPORTANTE COMPROBAAAAAAAR        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    //Lo añado a la tabla estática Usuarios
    $query1 = 'INSERT INTO `Usuarios` (`CodLlave`, `Nombre`, `PrimerApellido`, `SegundoApellido`, `CorreoElectronico`) VALUES ("'.$CodLlave.'","'. $Nombre.'","'. $PrimerApellido.'","'. $SegundoApellido.'","'. $CorreoElectronico.'")';
    $resultado1 = $db->query($query1);


    //Obtengo sus datos
    $query2 = 'SELECT * FROM Usuarios WHERE CodLlave="'.$CodLlave.'"';
    $resultado2 = $db->query($query2);
    $ultimo_usuario = $resultado2->fetch_array(MYSQLI_ASSOC);

    //Lo añado a la tabla dinámica UsuarioEstado, dándole el valor por defecto 0
    $query3="INSERT INTO UsuarioEstado (IdUsuario) VALUES ('".$ultimo_usuario["IdUsuario"]."')";
    $resultado3 = $db->query($query3);

    if (!($resultado1 && $resultado2 && $resultado3)){
      $feedback = "Ha habido un error. Notificar al encargado.<br><strong>Error:</strong> " . $db->error . ".<br> Vuelva a intentarlo.";
    }else{
      header("Location: add_success.php", TRUE, 307);
      exit;      
    }
 }

 $db->close();
?>

<html lang="es">
  <head><title>Añadir Usuario</title></head>
  <body>
    <h1>Añadir un nuevo usuario</h1>

    <form action="" method="post">
      
      <label>Código de Llave:</label>
      <input type="text" name="CodLlave" id="CodLlave" required pattern="[A-Z0-9]{2}-[A-Z0-9]{2}-[A-Z0-9]{2}-[A-Z0-9]{2}" value="<?=$CodLlave?>">
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
      <input type="email" name="email" id="email" required pattern="^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)+$" value="<?=$email?>">
      <br><br>

      <input type="submit" value="Submit">
    </form>

    <p><?=$feedback?></p>

    
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


    <script type='module' src='https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js'></script>
    <script nomodule src='https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js'></script>
  </body>
</html>