<?php
//arreglo que contiene campos de formularios
$form_campos=array('nom_est'=>'Nombres estudiante',
		   'ape_est'=>'Apellidos estudiante',	
		   'institucion'=>'Institucion',	
		   'nacionalidad'=>'Nacionalidad estudiante',	
		   'lugar'=>'Lugar de nacimiento del estudiante',	
		   'peso'=>'Peso del estudiante',	
		   'talla'=>'Talla del estudiante',	
		   'doc_est'=>'Documento de identidad del estudiante',	
		   'doc_est2'=>'Confirmar el documento de identidad del estudiante',	
		   'BlqDoc'=>'N�mero de documento',	
		   'docexp_est'=>'Lugar de expedici�n del documento de identidad del estudiante',	
		   'dir_est'=>'Direcci�n del estudiante',	
		   'barr_est'=>'Barrio',	
                   'tel_est'=>'Tel�fono del estudiante',
                   'nom_pad'=>'Nombres del padre',
                   'ape_pad'=>'Apellidos del padre',
		   'doc_pad'=>'Documento del padre',
		   'prof_pad'=>'Profesi�n del padre',
		   'empr_pad'=>'Empresa del padre',
		   'carg_pad'=>'Cargo del padre',
		   'dir_pad'=>'Direcci�n del padre',
		   'tel_pad'=>'Tel�fono del padre',
		   'cel_pad'=>'Celular del padre',
		   'email_pad'=>'Correo electr�nico del padre',
		   'bco_pad'=>'Banco del padre',
		   'cta_pad'=>'No. de Cuenta bancaria del padre',
                   'nom_mad'=>'Nombres de la madre',
                   'ape_mad'=>'Apellidos de la madre',
		   'doc_mad'=>'Documento de la madre',
		   'prof_mad'=>'Profesi�n de la madre',
		   'empr_mad'=>'Empresa de la madre',
		   'carg_mad'=>'Cargo de la madre',
		   'dir_mad'=>'Direcci�n de la madre',
		   'tel_mad'=>'Tel�fono de la madre',
		   'cel_mad'=>'Celular de la madre',
		   'email_mad'=>'Correo electr�nico de la madre',
		   'bco_mad'=>'Banco de la madre',
		   'cta_mad'=>'No. de Cuenta bancaria de la madre',
		   'nom_acu'=>'Nombres acudiente',
		   'ape_acu'=>'Apellidos acudiente',
		   'par_acu'=>'Parentesco acudiente',
		   'tel_acu'=>'Tel�fono casa del acudiente',
		   'telof_acu'=>'Tel�fono oficina del acudiente',
		   'cel_acu'=>'Celular acudiente',
		   'email_acu'=>'Correo electr�nico acudiente',
		   'hospital'=>'Cl�nica autorizada en caso de emergencia',
		   'clave_actual'=>'Contrase�a actual',
		   'clave1_nueva'=>'Contrase�a nueva',
		   'clave2_nueva'=>'Confirmar contrase�a nueva',
		   'clave_pregunta'=>'Pregunta de seguridad',
		   'clave_respuesta'=>'Respuesta a la pregunta de seguridad',
		   'clave1'=>'Contrase�a',
		   'clave2'=>'Confirmar contrase�a',
		   'respuesta'=>'Respuesta a la pregunta de seguridad');

extract($form_campos);

//Funci�n que valida valores requeridos, email, alfab�ticos, alfanum�ricos, num�ricos y fecha.
function valida($arreglo) {
	global $form_campos;
	extract ($form_campos);
	$mensaje="";
	foreach($arreglo as $tipo=>$campos) {
		if ($tipo=="requerido") {
			$genera=explode(",",$campos);
			foreach($genera as $valor) {
				if(trim($_POST[$valor])=="") $mensaje.=$$valor . "<br />";
			}
			if($mensaje!="") {
				include_once("encabezado.html");
				print "<strong>Ingrese la informaci�n de los siguientes campos requeridos:</strong><br /><br />";
				print $mensaje;
				print"<br /><strong><a href='javascript:history.go(-1)'>Volver al formulario</a></srong>";
				print "</div></body></html>";
				exit;
			}
		}
		if ($tipo=="email") {
                        $genera=explode(",",$campos);
		        foreach($genera as $valor) {
				if (!valida_email($_POST[$valor])&&(!trim($_POST[$valor])=="")) {
					include_once("encabezado.html");
                                        print "<strong>Existe un error en alguna de las direcciones de correo electr�nico<br />";
			                print"<br /><a href='javascript:history.go(-1)'>Volver al formulario</a></strong>";
					print "</div></body></html>";
			                exit;
			        }
			}
		}		
		if ($tipo=="alfa") {
                         $genera=explode(",",$campos);
		         foreach($genera as $valor) {
			 	if(!alfa($_POST[$valor])&&(!trim($_POST[$valor])=="")) $mensaje.=$$valor . "<br />";
			 }
			 if($mensaje!="") {
				 include_once("encabezado.html");
				 print "Los siguientes campos deben ser de tipo alfab�tico:<br /><br />";
			         print $mensaje;
				 print"<br /><a href='javascript:history.go(-1)'>Volver al formulario</a>";
				 print "</div></body></html>";
				 exit;
		         }			 
               	}

	 	if ($tipo=="alfanum") {
                         $genera=explode(",",$campos);
                         foreach($genera as $valor) {
                                if(!alfanum($_POST[$valor])&&(!trim($_POST[$valor])=="")) $mensaje.=$$valor . "<br />";
                         }
			 if($mensaje!="") {
				 include_once("encabezado.html");
                                 print "Los siguientes campos son de tipo alfanum�rico:<br /><br />";
                                 print $mensaje;
                                 print"<br /><a href='javascript:history.go(-1)'>Volver al formulario</a>";
				 print "</div></body></html>";
				 exit;
                         }
                }
                if ($tipo=="num") {
                         $genera=explode(",",$campos);
                         foreach($genera as $valor) {
                                if(!num($_POST[$valor])&&(!trim($_POST[$valor])=="")) $mensaje.=$$valor . "<br />";
                         }
			 if($mensaje!="") {
				 include_once("encabezado.html");
                                 print "<strong>Los siguientes campos son de tipo num�rico (no utilice puntos ni comas):</strong><br /><br />";
                                 print $mensaje;
                                 print"<br /><strong><a href='javascript:history.go(-1)'>Volver al formulario</a></strong>";
				 print "</div></body></html>";
                                 exit;
                         }
                }
                if ($tipo=="fecha") {
                         $genera=explode(",",$campos);
                         foreach($genera as $valor) {
                                if(!valida_fecha($_POST[$valor])&&(!trim($_POST[$valor])=="")) $mensaje.=$$valor . "<br />";
                         }
			 if($mensaje!="") {
				 include_once("encabezado.html");
                                 print "El formato de fecha es incorrecto:<br /><br />";
                                 print $mensaje;
                                 print"<br /><a href='javascript:history.go(-1)'>Volver al formulario</a>";
				 print "</div></body></html>";
                                 exit;
                         }
                }
	}
}

//funci�n que borra espacios de m�s
function borra_espacios($cadena) {
	$patron=array("/^[ ]+/m", "/[ ]+/m", "/[ ]+\$/m");
	$reemplazo=array(""," ","");
	return preg_replace($patron,$reemplazo,$cadena);
}
//Funci�nn convertir a may�sculas
function cambia_mayuscula($cadena) {
	$mayuscula="ABCDEFGHIJKLMN�OPQRSTUVWXYZ������";
	$minuscula="abcdefghijklmn�opqrstuvwxyz������";
	return strtr($cadena,$minuscula,$mayuscula);
}
//Funci�n convertir a min�sculas
function cambia_minuscula($cadena) {
	$mayuscula="ABCDEFGHIJKLMN�OPQRSTUVWXYZ������";
	$minuscula="abcdefghijklmn�opqrstuvwxyz������";
	return strtr($cadena,$mayuscula,$minuscula);
}
//Funci�n que genera nombre de usuario
function nombre_usuario($cadena) {	
	$acento=array("�"=>"a","�"=>"e","�"=>"i","�"=>"o","�"=>"u","�"=>"u");
	$divide=explode(" ", $cadena);
	$cont=count($divide);
	if ($cont>2) 
		$nombre=$divide[0] . substr($divide[$cont-2],0,2) . substr($divide[$cont-1],0,2);
	else
		$nombre=$divide[0] . substr($divide[1],0,4);
	return strtr(cambia_minuscula($nombre), $acento);
}

//funci�n que valida valores alfab�ticos
function alfa($cadena) {
	// Funci�n ereg() no soportada en php 5.3
	//return(ereg("^[a-z������� A-Z�������]+$",$cadena));	
	return(preg_match("/^[a-z������� A-Z�������]+$/",$cadena));	
}

//funci�n que valida valores alfanum�ricos
function alfanum($cadena) {
	//return(ereg("^[a-zA-Z0-9]+$",$cadena));	
	return(preg_match("/^[a-zA-Z0-9]+$/",$cadena));	
}

//funci�n que valida valores num�ricos
function num($cadena) {
	//return(ereg("^[0-9]+$",$cadena));	
	return(preg_match("/^[0-9]+$/",$cadena));
}

//Funci�n que valida direcciones de correo
function valida_email($correo) {
    //return (ereg('^[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+'.
    //              '@'.
    //              '[-!#$%&\'*+\\/0-9=?A-Z^_`a-z{|}~]+\.'.
    //              '[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+$',
    //              $correo));
	return (preg_match('/^[^0-9][a-zA-Z0-9_]+([.-][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/',$correo));

}

//Funci�n que valida fecha con formato AAAA-MM-DD
function valida_fecha($cadena) {
	//$patron="^([[:digit:]]{4})-([[:digit:]]{1,2})-([[:digit:]]{1,2})$";
	//if (ereg($patron,$cadena,$coincide)) {
	$patron="/^([[:digit:]]{4})-([[:digit:]]{1,2})-([[:digit:]]{1,2})$/";
	if (preg_match($patron,$cadena,$coincide)) {
		//comprabaci�n del a�o
		if (($coincide[1]<1900) || ($coincide[1]>3000)) return false;
		//comprobaci�n del mes
		if (($coincide[2]<1) || ($coincide[2]>12)) return false;
		//comprobaci�n del d�a
		if (($coincide[3]<1) || ($coincide[3]>31)) return false;
	return true;
	}
	else {
		return false;
	}
}

//funci�n que agrega comillas a cadenas de caracteres
function comillas($valor) {
// Retirar las barras si es necesario
	if (get_magic_quotes_gpc()) {
		$valor = stripslashes($valor);
	}
// Colocar comillas si no es un entero
	if (!is_int($valor)) {
		$valor = "'" . addslashes($valor) . "'";
	}
	return $valor;
}

// Obtener la fecha actual del sistema en el formato yyyy-mm-dd
function fecha_actual() {
	foreach(getdate() as $nombre=>$valor) $arreglo[$nombre]=$valor;
	$fecha= sprintf("%04d-%02d-%02d", $arreglo['year'], $arreglo['mon'], $arreglo['mday']);
	return $fecha;
}

//Funci�n que controla el acceso autorizado de las p�ginas.
//y retorna el -id- de acuerdo al tipo de usuario.
function restringe($tipo_user) {
	require_once("login/autenticacion.php");
	global $wwwroot,$db;
	$a->start();
	if ($a->checkAuth()) {
		$tipos=explode(",",$tipo_user);
		$permiso=false;
		foreach($tipos as $key=>$val) {
			$sql =& $db->getOne(sprintf("SELECT id FROM usuarios WHERE usuario=%s AND tipo=%s", comillas($a->getUsername()), comillas($val)));
			if (PEAR::isError($sql)) {
			    die($sql->getMessage());
	       		}
			if($sql) {
				$permiso=true;
				break;
			}
		}
	    	if (!$permiso) {
			print "No tiene los privilegios necesarios para ingresar a esta p�gina";
			//*****OJO:Escribir aqu� link para redireccionar****
			die();
		}
		else {
		        print "usuario ->". $a->getUsername();
		        print "::<a title='Salir de la sesi�n' href='$wwwroot/login/logout.php'>Salir</a><br /><br />";
			return $sql;
		}
	}
	else {
	        if($a->getStatus()==AUTH_WRONG_LOGIN) print "<p align='center'><b>Datos inv�lidos</b></p>";
	        if($a->getStatus()==AUTH_EXPIRED) print "<p align='center'><b>Su sesi�n ha expirado</b></p>";
	        if($a->getStatus()==AUTH_IDLED) print "<p align='center'><b>Estuvo inactivo por m�s de 30 minutos, su sesi�n fue cerrada</b></p>";
	        die();
	}
}

//*****Funci�n que retorna los datos del usuario recibiendo como par�metro
//*****el id_usuario

function datos_usuario($id_usuario) {
	global $db;
	//Arreglo que corresponde tipo de usuario con tablas existentes
	$tabla=array('ADM'=>'administrador',
	  	     'DIR'=>'directivo',
		     'PRO'=>'profesor',
		     'PAD'=>'padre',
		     'EST'=>'estudiante');
	$sql =& $db->getOne(sprintf("SELECT tipo FROM usuarios WHERE id=%d", $id_usuario));
	if (PEAR::isError($sql)) {
		    die($sql->getMessage());
	}
	$datos =& $db->getRow(sprintf("SELECT id,nombres,apellidos FROM %s WHERE id_usuario=%d", $tabla[$sql],$id_usuario), array(), DB_FETCHMODE_ASSOC);
	if (PEAR::isError($datos)) {
		    die($datos->getMessage());
	}
	$datos[tipo]=$sql;
	return $datos;
}

//*****Funci�n que retorna la edad recibiendo como par�metro la f_nacimiento,
//*****en formato AAAA-MM-DD
function num_years($fecha) {
	//****Crea la matriz asociativa: [0]=>AAAA,[1]=>MM,[2]=>DD.
        $nacimiento=explode("-",$fecha);
        //****Crea la matriz asociativa de la fecha actual.
        $actual=getdate();
        $fyear=$actual['year']-$nacimiento[0];
        $fmonth=$actual['mon']-$nacimiento[1];
        $fday=$actual['mday']-$nacimiento[2];
        if($fmonth<0) $fyear--;
        if($fmonth==0) {
		if($fday<0) $fyear--;
        }
        return $fyear;
}

// Funci�n para redimensionar im�genes
function escala($url,$base){
	$datos = GetImageSize($url) OR die("Imagen no v�lida");
	$xp = $datos[0]/$base;
	$yp = $datos[1]/$xp;
	echo '<div class="cen"> <img src="'.$url.'" width="'.$base.'" height="'.$yp.'" border="1" alt="foto" / ><br /><br / ></div>';
}

?>
