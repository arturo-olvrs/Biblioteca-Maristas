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


/*
//Obtener el estado actual. ¿Está dentro o fuera?
$query2 = "SELECT * FROM UsuarioEstado WHERE IdUsuario='".$usuario["IdUsuario"]."'";
$resultado2 = $db->query($query2);
$estado_prev = $resultado2->fetch_array(MYSQLI_ASSOC);



//Según el estado, le deja entrar o no.
if ($estado_prev["IdEstado"] == 1){ //Si el usuario está DENTRO, puede salir.
    $query3 = "UPDATE UsuarioEstado SET IdEstado='0' WHERE IdUsuario='" . $usuario["IdUsuario"] . "';";
    $resultado3 = $db->query($query3);
}
else{ //El usuario está ya FUERA. No puede salir.
    $valor=0;
    echo "valor=" . $valor . ";<br>";
    die ("User outside the library. Cannot go outside.");
}*/


//Cambiar zona horaria a la de Paris
$query4 = "SET time_zone = 'Europe/Paris'";
$resultado4 = $db->query($query4);

//Añadir Registro de Salida.
$query5 = "UPDATE Registros SET HoraSalida=CURTIME() WHERE HoraSalida IS NULL AND Dia=CURDATE() AND IdUsuario='" . $usuario["IdUsuario"] . "'";
$resultado5 = $db->query($query5);

//Responder con Valor=1.
if (!($resultado1 && $resultado4 && $resultado5)){ //$resultado2 && $resultado3 &&
    $valor=0;
    echo "valor=" . $valor . ";<br>";
    echo "Ha habido un error. Notificar al encargado.<br><strong>Error:</strong> " . $db->error . ".<br> Vuelva a intentarlo.";
  }else{
    $valor=1;
    echo "valor=" . $valor . ";<br>";
}


$db->close();

?>