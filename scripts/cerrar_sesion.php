<?php
// Se inicia la sesión
session_start();
// se desasignan todas las variables de sesión
$_SESSION = array();
// se destruye la sesión
session_destroy();
// se redirecciona a la página de inicio
header("location: ../index.html");
//se sale del archivo
exit;

?>