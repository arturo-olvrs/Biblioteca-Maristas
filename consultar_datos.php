
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
          <a href="consultar_datos.php" style="border-bottom: 2px solid red">Consultar Datos</a>
          <a href="modificar_datos.php">Modificar Datos</a>
        </div>
      </div>

      <a href="normativa.html">Normativa</a>
      
      <a class="no-style" href="https://www.maristasgranada.com/" target="_blank"><img src="Imagenes/Maristas_Logo.png" style="margin-left:40px; height:30px"></a>
    </nav>
  </header>   


  <body>

    <div class="contenido">
      <h1>Consultar datos</h1>

      <!--Formulario para leer llave-->
      <form action="" method="post" class="formulario">
        
        <label>Código de llave a consultar:</label>
        <input type="text" name="CodLlave" id="CodLlave" required  value="<?=$_POST["CodLlave"]?>"
                pattern="[A-Z0-9]{2}-[A-Z0-9]{2}-[A-Z0-9]{2}-[A-Z0-9]{2}">
        <button onclick='pasteFromClipBoard()'><ion-icon name='clipboard-outline'></ion-icon></button>
        <br><br>

        <input type="submit" value="Consultar">
      </form>

      <br><br>

      <?php
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
          
          // Obtengo el código de la llave que se desea consultar
          $CodLlave=htmlspecialchars(trim($_POST["CodLlave"]));

          //Obtengo los datos de la persona
          $query1 = 'SELECT * FROM Usuarios WHERE CodLlave="'.$CodLlave.'"';
          $resultado1 = $db->query($query1);
          $usuario = $resultado1->fetch_array(MYSQLI_ASSOC);


          if ($usuario["CodLlave"] === $CodLlave){  // La llave está registrada
            echo "<div class='list'>";
            echo    "<a>- <strong>ID:</strong> "                  . $usuario["IdUsuario"]          . "</a><br>";
            echo    "<a>- <strong>Código de Llave:</strong> "     . $usuario["CodLlave"]           . "</a><br>";
            echo    "<a>- <strong>Nombre:</strong> "              . $usuario["Nombre"]             . "</a><br>";
            echo    "<a>- <strong>Primer apellido:</strong> "     . $usuario["PrimerApellido"]     . "</a><br>";
            echo    "<a>- <strong>Segundo apellido:</strong> "    . $usuario["SegundoApellido"]    . "</a><br>";
            echo    "<a>- <strong>Correo Electrónico:</strong> "  . $usuario["CorreoElectronico"]  . "</a><br>";
            echo "</div>";
          }

          else{ // Esa llave no está registrada
            echo "<p>Esta llave no está registrada en el sistema.</p>";
          }

          $db->close();

        } // if ($_SERVER["REQUEST_METHOD"] === "POST")
      ?>

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