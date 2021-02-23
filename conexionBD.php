<?php

function conectarse()

{

$db_host="bxgpkrzi7babzgz8skdo-mysql.services.clever-cloud.com"; // Host BD al que conectarse, habitualmente es localhost

$db_nombre="bxgpkrzi7babzgz8skdo"; // Nombre de la Base de Datos que se desea utilizar

$db_user="uoyb6yzzi1qaizoy"; // Nombre del usuario con permisos para acceder a la BD

$db_pass="MOLwhh8HdJP7so9p9o6t"; // Contraseña del usuario de la BD


$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_nombre);
$mysqli->set_charset("utf8");



return $mysqli;

}
//$link=conectarse();

?>
