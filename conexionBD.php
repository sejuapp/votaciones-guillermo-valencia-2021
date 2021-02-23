<?php

function conectarse()

{

$db_host="localhost"; // Host BD al que conectarse, habitualmente es localhost

$db_nombre="votaciones-2021"; // Nombre de la Base de Datos que se desea utilizar

$db_user="root"; // Nombre del usuario con permisos para acceder a la BD

$db_pass="superadmin"; // Contrase�a del usuario de la BD


$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_nombre);
$mysqli->set_charset("utf8");



return $mysqli;

}
//$link=conectarse();

?>
