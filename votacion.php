<?php
require_once("funciones.php");
require_once("conexionBD.php");
$link=conectarse();

//****** Verificamos si existe la cookie *****/
if(isset($_COOKIE['DataVota'])) {
	if (isset($_POST['envia_voto'])) {
		$VotoID = $_POST['idvoto'];
        //Lista de grados
        $resp5=$link->query("select * from categorias");
        while($row5 = $resp5->fetch_array()) {
            $categorias[$row5["id"]]=$row5["descripcion"];
        }

		//Verificar que se haya seleccionado el(los) candidato(s) para registrar la votaci�n		
        $veri_cat = explode(',', $_POST['catarj']);
		foreach($veri_cat as $valor) { 
			if (!isset($_POST['categoria'.$valor])) {
				include_once("encabezado.html");
				print "<strong>No ha seleccionado su voto para ".$categorias[$valor]."<br />";
				print "<br /><a href='javascript:history.go(-1)'>Volver al formulario</a></strong></div></body></html>";
				exit;
			}
		}
		

		//***** VALIDAMOS QUE EL ESTUDIANTE NO HAYA VOTADO*****
		$resp=$link->query(sprintf("select id from voto where id_estudiante=%d",$VotoID));
	        if ($row= $resp->fetch_array()) {
               		//******Guardamos los datos de control ******
	                $ffecha=date("Y-m-d");
        	        $fhora=date("G:i:s");
	                $fip = $_SERVER['REMOTE_ADDR'];
        	        $faccion="Intento-DuplicarVoto";
	                $cons_sql5  = sprintf("INSERT INTO control(c_fecha,c_hora,c_ip,c_accion,c_idest) VALUES(%s,%s,%s,%s,%d)", comillas($ffecha), comillas($fhora), comillas($fip), comillas($faccion),$VotoID);
			$link->query($cons_sql5);
		        include_once("encabezado.html");
		        print "<strong>Su voto ya se encuentra registrado en el sistema.</strong><br />";
		        print"<br /><strong><a href='salir.php'>Finalizar</a></strong></div></body></html>";
		        exit;
		}
				//****Registrar votaci�n****
		        foreach($veri_cat as $valor) { 
					$cons_sql = sprintf("INSERT INTO voto(id_estudiante,candidato) VALUES(%d,%d)",$VotoID, $_POST['categoria'.$valor]);
					$link->query($cons_sql);
				}		

               //******Guardamos los datos de control ******
                $ffecha=date("Y-m-d");
                $fhora=date("G:i:s");
                $fip = $_SERVER['REMOTE_ADDR'];
                $faccion="Voto-Registrado";
                $cons_sql5  = sprintf("INSERT INTO control(c_fecha,c_hora,c_ip,c_accion,c_idest) VALUES(%s,%s,%s,%s,%d)", comillas($ffecha), comillas($fhora), comillas($fip), comillas($faccion),$VotoID);
		$link->query($cons_sql5);
	        include_once("encabezado2.html");
	        print "<strong>Muchas gracias por registrar su voto.</strong><br />";
	        print"<br /><strong><a href='salir.php'>Finalizar</a></strong></div></body></html>";
	}
}
$link -> close();
?>

