<?php
require_once("funciones.php");
require_once("conexionBD.php");
$link=conectarse();
//****Verificar si el sistema se encuentra activo*****

$query = "select * from general";
$result = $link->query($query);

$leer= $result->fetch_array();
if ($leer['activo']=="S") {

if (!isset($_POST['envia_consulta'])) {
        include_once("ingresa.html");
}
else {

//******VALIDACION DE INGRESO AL SISTEMA******

if ($_POST['documento']!="") {
	$DocEst=$_POST['documento'];
}
else {
	include_once("encabezado.html");
	print "<strong>No ha escrito el n�mero de documento<br />";
	print"<br /><a href='javascript:history.go(-1)'>Volver al formulario</a></strong></div></body></html>";
	exit;
}
//****Se valida la contrase�a del estudiante si el sistema la solicita
if ($leer['clave']=="S") {
	if ($_POST['contra']!="") {
		$ContraEst=md5($_POST['contra']);
	}
	else {
		include_once("encabezado.html");
		print "<strong>No ha escrito la contrase�a de acceso<br />";
		print"<br /><a href='javascript:history.go(-1)'>Volver al formulario</a></strong></div></body></html>";
		exit;
	}
	$resp6=$link->query(sprintf("select id from estudiantes where documento=%s AND clave=%s", comillas($DocEst), comillas($ContraEst)),$link);
	if (!$row6=  $resp6 ->fetch_array()) {
		include_once("encabezado.html");
		print "<strong>La contrase�a de acceso es inv�lida<br />";
		print"<br /><a href='javascript:history.go(-1)'>Volver al formulario</a></strong></div></body></html>";
		exit;
	}
}

//******Funcion para guardar los datos de control ******
function LogControl($faccion2, $idest2) {
	require_once("conexionBD.php");
	$link=conectarse();
	$ffecha=date("Y-m-d");
	$fhora=date("G:i:s");
	$fip = $_SERVER['REMOTE_ADDR'];
	$cons_sql  = sprintf("INSERT INTO control(c_fecha,c_hora,c_ip,c_accion,c_idest) VALUES(%s,%s,%s,%s,%d)", comillas($ffecha), comillas($fhora), comillas($fip), comillas($faccion2),$idest2);
	$link->query($cons_sql);
}
//***** VALIDAMOS QUE EL ESTUDIANTE NO HAYA VOTADO*****
$resp2=$link->query(sprintf("select id_estudiante from voto, estudiantes where documento=%s and id_estudiante=estudiantes.id",comillas($DocEst)));
        if ($row2=  $resp2->fetch_array()) {
		$faccion="Intento-IngresoDuplicado";
		LogControl($faccion,$row2['id_estudiante']);
		include_once("encabezado.html");
		print "<strong>No puede ingresar</strong><br />Su voto ya est� registrado en el sistema.<br />";
		print"<br /><strong><a href='javascript:history.go(-1)'>Volver al formulario</a></strong></div></body></html>";
		exit;
	}

	$resp=$link->query(sprintf("select id,nombres,apellidos,grado from estudiantes where documento=%s",comillas($DocEst)));
	if ($row= $resp->fetch_array()) {
		$IdEncrip=md5($row['id']);
		//**** Creamos la cookie
		setcookie("DataVota", $DocEst, time()+3600);
		echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
		echo '<html>';
		echo '<head>';
		echo '<title>'.$leer['institucion'].' - Tarjet�n de votaci�n</title>';
		echo '<link href="estilo4.css" rel="stylesheet" type="text/css" />';

		echo '</head>';
		echo '<body>';
		echo '<div align="center">';
		$faccion="Ingreso-".$DocEst;
		LogControl($faccion,$row['id']);
		echo '<div class="nombrevota"; font-weight: bold;">ESTUDIANTE: '.$row['nombres'].' '.$row['apellidos'].'</div>';
		echo '<img src="iconos/EscudoColombia.png" style="display:scroll;position:fixed; top:35px;left:150px;" width="110" alt="Escudo de Colombia" />';
		echo '<img src="iconos/EscudoColegio.png" style="display:scroll;position:fixed; top:35px;right:150px;" width="130" alt="EscudoColegio" /><br />';
        //Variable que guarda las categor�as que se muestran en el tarjet�n
        $catarj="";
		echo '<form name="votacion" action="votacion.php" method="post">';
		echo '<h2>'.$leer['institucion'].'<br />';
		echo $leer['descripcion'].'<br /></h2>';
		echo '<table style="font-weight:bold";>';						
		echo '<thead><tr><th>TARJET�N ELECTORAL</th></tr></thead>';		
		echo '<tr>';
		echo '<td>';
        //Leemos la lista de categor�as que aparecer�n en el tarjet�n
		$resp5=$link->query("select * from categorias order by id");
		while($row5 = $resp5->fetch_array()) {
            //Verificamos si existe un grado con el mismo nombre de la categor�a
            //para tener en cuenta para las votaciones de los candidatos por grado.
            $vrep=0;
            $resp9=$link->query("select * from grados");
            while($row9 = $resp9->fetch_array()) {
                $grados[$row9["id"]]=$row9["grado"];
                if (cambia_mayuscula($row5['nombre'])==cambia_mayuscula($row9['grado'])) {
                    $vrep=1;
                }
            }
        //Se muestran los candidatos por grado (pertenecen al mismo grado del estudiante) o de otras categor�as
        if ((cambia_mayuscula($grados[$row['grado']])==cambia_mayuscula($row5['nombre']))  or ($vrep==0)) {
			//*****Contar el total de candidatos por categoria******
			$resp8=$link->query(sprintf("select count(nombres) from candidatos where representante=%d",$row5['id']));			
			$row8 = $resp8->fetch_array();
			if ($row8[0]>0) {
                $catarj = $catarj . $row5['id'] . ",";
                echo '<div align="center">';
    			echo '<table style="font-weight:bold";>';
	    		echo '<thead><tr><th colspan="'.$row8[0].'" class="vto";>'.$row5['descripcion'].'</th></tr></thead>';
		    	echo '<tr>';
    			# MOSTRAR CANDIDATOS
	    		$resp3=$link->query(sprintf("select * from candidatos where representante=%d order by apellidos DESC",$row5['id']));
		    	while($row3 = $resp3->fetch_array()) {
    				echo '<td class="cen cd">';
	    			if ((file_exists ('fotos/'.$row3['id'].'.jpg'))||(file_exists ('fotos/'.$row3['id'].'.png'))||(file_exists ('fotos/'.$row3['id'].'.gif'))) {

		    			if (file_exists ('fotos/'.$row3['id'].'.jpg')){
			    			echo '<img src="fotos/'.$row3['id'].'.jpg" width="100" alt="Candidato" onClick = "document.getElementById (\'candidato'.$row3['id'] .'\').checked = true;" /><br />';
				    	}
					    elseif (file_exists ('fotos/'.$row3['id'].'.png')){
    						echo '<img src="fotos/'.$row3['id'].'.png" width="100" alt="Candidato" onClick = "document.getElementById (\'candidato'.$row3['id'] .'\').checked = true;" /><br />';
	    				}
		    			elseif (file_exists ('fotos/'.$row3['id'].'.gif')){
			    			echo '<img src="fotos/'.$row3['id'].'.gif" width="100" alt="Candidato" onClick = "document.getElementById (\'candidato'.$row3['id'] .'\').checked = true;" /><br />';
				    	}

    				}
	    			else {
		    			echo '<img src="fotos/sinfoto.png" alt="Candidato" onClick = "document.getElementById (\'candidato'.$row3['id'] .'\').checked = true;" /><br />';
			    	}
    				echo '<input type="radio" name="categoria'.$row5['id'].'" id ="candidato'.$row3['id'].'" value="'.$row3['id'].'" />';
	    			echo '<strong>'.$row3['nombres'].' '.$row3['apellidos'].'</strong>';
		    		echo '</td>';
    			}
	    		echo '</tr>';
		    	echo '</table></div><br />';
            }
		}
        }
		//***Si el tarjet�n no tiene candidatos se muestra un mensaje
        if ($catarj=="") {
            echo '<strong>No existen candidatos para votar, por favor comun�quese con el administrador del sistema.</strong>';
            echo '</td>';		
    		echo '</tr>';
	    	echo '</table>';
		    echo '</div><br />';
        }
        else {
            echo '</td>';		
	    	echo '</tr>';
		    echo '</table>';
    		echo '</div><br />';
	    	echo '<div class="cen">';
		    echo '<input type="hidden" name="idvoto" value="'.$row['id'].'">';
    		//Eliminamos la �ltima coma "," de la lista de categor�as
            $catarj=trim($catarj,',');
            echo '<input type="hidden" name="catarj" value="'.$catarj.'">';
    		echo '<input type="submit" name="envia_voto" value="Votar" title="Registrar voto" />';
	    	echo '</div>';
        }
		echo '</form>';
		echo '</body>';
		echo '</html>';
	}
	else {
		setcookie("DataVota", "", time()-3600);
		include_once("encabezado.html");
		$faccion="IngresoFallido-".$DocEst;
		LogControl($faccion,0);
		echo '<table>';
		echo '<tr><td class="cen" colspan="2"><strong>El documento escrito no est� registrado en el sistema<br /><br />';
		print "<strong><a href='javascript:history.go(-1)'>Volver a intentarlo</a></strong></td></tr>";
		echo '</table></div></body></html>';
	}
}
}
else {
    include_once("encabezado.html");
	print "<strong>EL SISTEMA DE VOTACI�N ESTA INACTIVO</strong><br />";
    print "(Comun�quese con el administrador del sistema)</div></body></html>";
}
$link -> close();
?>
