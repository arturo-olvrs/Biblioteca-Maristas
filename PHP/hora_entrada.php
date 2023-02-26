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


//Variable de GET
$CodLlave=$_GET['CodLlave'];

//Obtengo los datos de la persona
$query1 = 'SELECT * FROM Usuarios WHERE CodLlave="'.$CodLlave.'"';
$resultado1 = $db->query($query1);
$usuario = $resultado1->fetch_array(MYSQLI_ASSOC);

//Compruebo si está registrada
if ($usuario["CodLlave"] != $CodLlave){
    $valor=0;
    echo "valor=" . $valor . ";<br>";
    die ("Key not found");
}


//Obtener el estado actual. ¿Está dentro o fuera?
$query2 = "SELECT * FROM UsuarioEstado WHERE IdUsuario='".$usuario["IdUsuario"]."'";
$resultado2 = $db->query($query2);
$estado_prev = $resultado2->fetch_array(MYSQLI_ASSOC);



//Según el estado, le deja entrar o no.
if ($estado_prev["IdEstado"] == 0){ //Si el usuario está FUERA, puede entrar.
    $query3 = "UPDATE UsuarioEstado SET IdEstado='1' WHERE IdUsuario='" . $usuario["IdUsuario"] . "';";
    $resultado3 = $db->query($query3);
}
else{ //El usuario está ya DENTRO. No puede entrar.
    $valor=0;
    echo "valor=" . $valor . ";<br>";
    die ("User inside the library. Cannot enter.");
}


//Añadir Registro de Entrada.
$query4 = "INSERT INTO Registros(IdUsuario, Dia, HoraEntrada) VALUES ('" . $usuario["IdUsuario"] . "', CURDATE(), CURTIME())";
$resultado4 = $db->query($query4);

//Responder con Valor=1.
if (!($resultado1 && $resultado2 && $resultado3 && $resultado4)){
    $valor=0;
    echo "valor=" . $valor . ";<br>";
    echo "Ha habido un error. Notificar al encargado.<br><strong>Error:</strong> " . $db->error . ".<br> Vuelva a intentarlo.";
  }else{
    $valor=1;
    echo "valor=" . $valor . ";<br>";
}


$db->close();

?>