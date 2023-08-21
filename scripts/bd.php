<?php
$server="localhost";
$user="root";
$psw="170522Aa#89";
$bd="gymbas";
$conex=mysqli_connect($server, $user, $psw, $bd);
if(!$conex) //si no existe la conección 
{
    Die ("No hay conexión:" .mysqli_connect_error());
}
?>