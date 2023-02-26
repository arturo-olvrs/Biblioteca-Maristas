
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
          <a href="add_user.php">Añadir Usuario</a>
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
      <h1>Modificar datos</h1>

      <!--Formulario para leer llave-->
      <form action="" method="post" class="formulario">
        
        <label>Código de llave a modificar:</label>
        <input type="text" name="CodLlave" id="CodLlave" required  value="<?=$_POST["CodLlave"]?>"
                pattern="[A-Z0-9]{2}-[A-Z0-9]{2}-[A-Z0-9]{2}-[A-Z0-9]{2}">
        <button onclick='pasteFromClipBoard()'><ion-icon name='clipboard-outline'></ion-icon></button>
        <br><br>

        <input type="submit" value="Modificar">
      </form>

      <br><br>

      <?php
        
        $display = "none"; // De normal no se muestra el formulario

        // Tan solo si ya se ha respondido al formulario se dan los datos.
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

          // Ya se ha realizado la conexión
          
          // Obtengo el código de la llave que se desea modificar
          $CodLlave=htmlspecialchars(trim($_POST["CodLlave"]));

          //Obtengo los datos de la persona
          $query1 = 'SELECT * FROM Usuarios WHERE CodLlave="'.$CodLlave.'"';
          $resultado1 = $db->query($query1);
          $usuario = $resultado1->fetch_array(MYSQLI_ASSOC);


          if ($usuario["CodLlave"] != $CodLlave){  // La llave no está registrada
            echo "<p>Esta llave no está registrada en el sistema.</p>";
          }
          else{
            $display = "block"; // El formulario se muestra en este caso
          }


          $db->close();

        } // if ($_SERVER["REQUEST_METHOD"] === "POST")
      ?>

      <!--El usuario registrado es un usuario válido-->
      <form action="modificar_success.php" method="post" class="formulario" style="display:<?=$display?>">

        <label>Código de Llave:</label> <!--Disabled porque no se puede modificar-->
        <input type="text" name="CodLlave" id="CodLlave" required pattern="[A-Z0-9]{2}-[A-Z0-9]{2}-[A-Z0-9]{2}-[A-Z0-9]{2}" value="<?=$usuario["CodLlave"]?>" readonly>
        <br><br>

        <label>Nombre:</label>
        <input id="nombre" name="nombre" type="text" required minlength="3" maxlength="50" pattern="[A-za-z][A-za-z ]+" value="<?=$usuario["Nombre"]?>">
        <br><br>

        <label>Primer Apellido:</label>
        <input id="PrimerApellido" name="PrimerApellido" type="text" required minlength="3" maxlength="50" pattern="[A-za-z][A-za-z ]+" value="<?=$usuario["PrimerApellido"]?>">
        <br><br>

        <label>Segundo Apellido:</label>
        <input id="SegundoApellido" name="SegundoApellido" type="text" minlength="3" maxlength="50" pattern="[A-za-z][A-za-z ]+" value="<?=$usuario["SegundoApellido"]?>">
        <br><br>

        <label>Correo Electrónico:</label>
        <input type="email" name="email" id="email" required  value="<?=$usuario["CorreoElectronico"]?>"
                pattern="^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)+$">
        <br><br>

        <input type="submit" value="Modificar">
      </form>
      

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