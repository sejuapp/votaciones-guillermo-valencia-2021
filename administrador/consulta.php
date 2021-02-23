<?php
require_once("../funciones.php");	
require_once("../conexionBD.php");
$link=conectarse(); 
//***Leer variables del sistema******
$estado=$link->query("select * from general");
$leer= $estado->fetch_array();
//****** Verificamos si existe la cookie *****/
if(isset($_COOKIE['VotaDatAdmin'])) {
if(isset($_GET['id'])) {	

//Se valida que sea superadministrador para que pueda agregar o modificar estudiantes
if ($_COOKIE['VotaDatAdmin']==1) {
	//****Agregar nuevo estudiante*******
		if (isset($_POST['envia_estudiante'])) {
			if ((borra_espacios($_POST['nom_est'])!="")and(borra_espacios($_POST['ape_est'])!="")and(borra_espacios($_POST['doc_est'])!="")) {
				$fgrado=$_GET['id'];
				$fnom_est=cambia_mayuscula(borra_espacios($_POST['nom_est']));
				$fape_est=cambia_mayuscula(borra_espacios($_POST['ape_est']));
				$fdoc_est=borra_espacios($_POST['doc_est']);			
				$fclave_est=md5($_POST['clave_est']);
			}
			else {
				include_once("encabezado.html");
				print "<strong>Debe llenar todos los campos<br />";
				print"<br /><a href='javascript:history.go(-1)'>Volver al formulario</a></strong></div></body></html>";
				exit;
			}
			
			//*****Validamos que no exista un documento duplicado**** 
			$duplica=0;
			$resp3=$link->query("select documento from estudiantes");
			while($row3 = $resp3->fetch_array()) {
					if (borra_espacios($fdoc_est)==borra_espacios($row3['documento'])){
						   $duplica=1;
					}
			}
			if ($duplica==1) {
					include_once("encabezado.html");
					print "<strong>Ya existe un estudiante con este n�mero de documento<br />";
					print"<br /><a href='javascript:history.go(-1)'>Volver al formulario</a></strong></div></body></html>";
					exit;
			}
			
			//******Guardamos los datos en la BD ******
			$cons_sql  = sprintf("INSERT INTO estudiantes(grado,nombres,apellidos,documento,clave) VALUES(%d,%s,%s,%s,%s)", $fgrado, comillas($fnom_est),comillas($fape_est),comillas($fdoc_est),comillas($fclave_est));
			$link->query($cons_sql);

			//****obtener el id del estudiante guardado
			$id_est=mysql_insert_id($link);

			//******Guardamos los datos de control ******
					$ffecha=date("Y-m-d");
					$fhora=date("G:i:s");
					$fip = $_SERVER['REMOTE_ADDR'];
					$faccion="Admin_Crea_Estudiante (id:".$id_est.")";
					$cons_sql5  = sprintf("INSERT INTO control(c_fecha,c_hora,c_ip,c_accion,c_idest) VALUES(%s,%s,%s,%s,%d)", comillas($ffecha), comillas($fhora), comillas($fip), comillas($faccion),$_COOKIE['VotaDatAdmin']);
	$link->query($cons_sql5);

		}
		//****Actualizar informaci�n de estudiante*******
		if (isset($_POST['edita_est'])) {
			if ((borra_espacios($_POST['nom_est'])!="")and(borra_espacios($_POST['ape_est'])!="")and(borra_espacios($_POST['doc_est'])!="")) {
				$fgrado=$_GET['id'];
				$fnom_est=cambia_mayuscula(borra_espacios($_POST['nom_est']));
				$fape_est=cambia_mayuscula(borra_espacios($_POST['ape_est']));
				$fdoc_est=borra_espacios($_POST['doc_est']);			
				$fclave_est=md5($_POST['clave_est']);
			}
			else {
				include_once("encabezado.html");
				print "<strong>Debe llenar todos los campos<br />";
				print"<br /><a href='javascript:history.go(-1)'>Volver al formulario</a></strong></div></body></html>";
				exit;
			}		
			
			//****Actualizar en la BD*******
			$cons_sql3  = sprintf("UPDATE estudiantes SET grado=%d, nombres=%s, apellidos=%s, documento=%s, clave=%s WHERE id=%d", $fgrado, comillas($fnom_est), comillas($fape_est), comillas($fdoc_est), comillas($fclave_est), $_POST['identificador']);
			$link->query($cons_sql3);
		
			//******Guardamos los datos de control ******
					$ffecha=date("Y-m-d");
					$fhora=date("G:i:s");
					$fip = $_SERVER['REMOTE_ADDR'];
					$faccion="Admin_Actualiza_Estudiante (id:".$_POST['identificador'].")";
					$cons_sql5  = sprintf("INSERT INTO control(c_fecha,c_hora,c_ip,c_accion,c_idest) VALUES(%s,%s,%s,%s,%d)", comillas($ffecha), comillas($fhora), comillas($fip), comillas($faccion),$_COOKIE['VotaDatAdmin']);
	$link->query($cons_sql5);
		
		}	
		
		echo '<div align="center">';	
		//*****Formulario para agregar un estudiante *******
		if((isset($_GET['agrega']))and($_GET['agrega']=="ok")) { 
			echo '<form name="addest" action="consulta.php?id='.$_GET['id'].'" method="post">';
				echo '<table>';
				echo '<tr>';
				echo '<td style="text-align:right;"><label for="nom_est">';
				echo '<strong>Nombres:</strong>';
				echo '</label></td>';
				echo '<td><input type="text" name="nom_est" size="30" maxlength="50" title="Escriba los nombres del estudiante" />';
				echo '</td></tr>';
				echo '<tr>';
				echo '<td style="text-align:right;"><label for="ape_est">';
				echo '<strong>Apellidos:</strong>';
				echo '</label></td>';
				echo '<td><input type="text" name="ape_est" size="30" maxlength="50" title="Escriba los apellidos del estudiante" />';
				echo '</td></tr>';
				echo '<tr>';
				echo '<td style="text-align:right;"><label for="doc_est">';
				echo '<strong>Documento:</strong>';
				echo '</label></td>';
				echo '<td><input type="text" name="doc_est" size="30" maxlength="50" title="Escriba el n�mero de documento del estudiante" />';
				echo '</td></tr>';
				echo '<tr>';
				echo '<td style="text-align:right;"><label for="clave_est">';
				echo '<strong>Contrase�a:</strong>';
				echo '</label></td>';
				echo '<td><input type="password" name="clave_est" size="30" maxlength="50" title="Escriba la contrase�a de acceso del estudiante" />';
				echo '</td></tr>';

				echo '<tr><td class="cen" colspan="2"><input type="submit" name="envia_estudiante" value="Guardar" title="Agregar estudiante" />&nbsp&nbsp&nbsp&nbsp';
			echo '<input type="button" name="Cancel" value="Cancelar" onclick="window.location =\'consulta.php?id='.$_GET['id'].'\'"/></td></tr>';
			echo '</form></table>';
		}
		else {
			echo '<div class=cen>';
			echo '<strong><a href="consulta.php?id='.$_GET['id'].'&agrega=ok" title="Agregar estudiante">Agregar estudiante</a></strong>';
			echo '</div>';
		}
		
		//*****Formulario para editar estudiante *******
		if((isset($_GET['id'])) and (isset($_GET['est'])) and (isset($_GET['editar'])) and ($_GET['editar']=="ok")) { 
			$resp4=$link->query(sprintf("select * from estudiantes where md5(id)=%s",comillas($_GET['est'])));
				if ($row4 = $resp4->fetch_array()) {	

				echo '<br /><form name="editaest" action="consulta.php?id='.$_GET['id'].'" method="post">';
					echo '<table>';
					echo '<tr>';
					echo '<td style="text-align:right;"><label for="nom_est">';
					echo '<strong>Nombres:</strong>';
					echo '</label></td>';
					echo '<td><input type="text" name="nom_est" value="'.$row4['nombres'].'" size="30" maxlength="50" title="Escriba los nombres del estudiante" />';
					echo '</td></tr>';
					echo '<tr>';
					echo '<td style="text-align:right;"><label for="ape_est">';
					echo '<strong>Apellidos:</strong>';
					echo '</label></td>';
					echo '<td><input type="text" name="ape_est" value="'.$row4['apellidos'].'" size="30" maxlength="50" title="Escriba los apellidos del estudiante" />';
					echo '</td></tr>';
					echo '<tr>';
					echo '<td style="text-align:right;"><label for="doc_est">';
					echo '<strong>Documento:</strong>';
					echo '</label></td>';
					echo '<td><input type="text" name="doc_est" value="'.$row4['documento'].'" size="30" maxlength="50" title="Escriba el documento del estudiante" />';
					echo '</td></tr>';
					echo '<tr>';
					echo '<td style="text-align:right;"><label for="clave_est">';
					echo '<strong>Contrase�a:</strong>';
					echo '</label></td>';
					echo '<td><input type="password" name="clave_est" size="30" maxlength="50" title="Escriba la contrase�a de acceso del estudiante" />';
					echo '</td></tr>';
				echo '<input type="hidden" name="identificador" value="'.$row4['id'].'" />';

					echo '<tr><td class="cen" colspan="2"><input type="submit" name="edita_est" value="Guardar" title="Agregar estudiante" />&nbsp&nbsp&nbsp&nbsp';			
				echo '<input type="button" name="Cancel" value="Cancelar" onclick="window.location =\'consulta.php?id='.$_GET['id'].'\'"/></td></tr>';
				echo '</form></table>';
			}
			else {
					echo '<table>';
					echo '<tr><td class="cen"><strong>No hay datos para el estudiante</strong></td></tr>';
					echo '</table>';
			}	
		}
		//******Mostrar mensaje para borrar estudiante*******
		if((isset($_GET['id']))and(isset($_GET['est']))and(isset($_GET['elimina']))and($_GET['elimina']=="0")) {
			
			$resp5=$link->query(sprintf("select * from estudiantes where md5(id)=%s",comillas($_GET['est'])));
				if ($row5 = $resp5->fetch_array()) {
                    //Verificar que no existan votos para borrar el estudiante
                    $resp9=$link->query(sprintf("select id from voto where id_estudiante=%d",$row5['id']));
                    if (!$row9 = $resp9->fetch_array()) {
        				echo '<br /><div class="cen"><strong>';
	        			echo '�Desea borrar el estudiante '.$row5['apellidos'].' '.$row5['nombres'].' del sistema? ';
		        		echo '<a href="consulta.php?id='.$_GET['id'].'&est='.$_GET['est'].'&elimina=1" title="Borrar estudiante del sistema">Si</a>&nbsp&nbsp&nbsp&nbsp';
			        	echo '<a href="consulta.php?id='.$_GET['id'].'" title="Cancelar la eliminaci�n del estudiante">No</a>';
				        echo '</strong></div>';
                    }
                    else {
                        echo '<br /><strong>Advertencia: No puede borrar al estudiante '.$row5['nombres'].' '.$row5['apellidos'].', ya que tiene su voto registrado.</strong>';
                    }
			}
			else {
				echo '<table>';
					echo '<tr><td class="cen"><strong>No hay datos para el estudiante</strong></td></tr>';
					echo '</table>';
			}
		}
		
		//*****Eliminar estudiante******
		if((isset($_GET['id']))and(isset($_GET['est']))and(isset($_GET['elimina']))and($_GET['elimina']=="1")) {
			$resp6=$link->query(sprintf("select * from estudiantes where md5(id)=%s",comillas($_GET['est'])));
				$row6 = $resp6->fetch_array();
				$resp2=$link->query(sprintf("delete from estudiantes where md5(id)=%s",comillas($_GET['est'])));

			//******Guardamos los datos de control ******
					$ffecha=date("Y-m-d");
					$fhora=date("G:i:s");
					$fip = $_SERVER['REMOTE_ADDR'];
					$faccion="Admin_Borra_Estudiante (Grado:".$_GET['id']."-".$row6['apellidos']." ".$row6['nombres'].")";
					$cons_sql5  = sprintf("INSERT INTO control(c_fecha,c_hora,c_ip,c_accion,c_idest) VALUES(%s,%s,%s,%s,%d)", comillas($ffecha), comillas($fhora), comillas($fip), comillas($faccion),$_COOKIE['VotaDatAdmin']);
	$link->query($cons_sql5);

		}	
}

	//******MUESTRA LA LISTA DE ESTUDIANTES POR GRADO
	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
	echo '<html>';
	echo '<head>';
	echo '<title>'.$leer['institucion'].' - Consulta por grados</title>';
	echo '<link href="../estilo4.css" rel="stylesheet" type="text/css" />';
	echo '</head>';
	echo '<body>';
	$resp3=$link->query(sprintf("select grado from grados where id=%d",$_GET['id']));
	if($row3 = $resp3->fetch_array()) {
		echo '<h1>'.$leer['institucion'].'</h1>';
		echo '<h2>GRADO '.$row3['grado'].'</h2>';
		echo '<div align="center">';
		echo '<div class="enlace">';
		echo '<a href="consulta.php?id='.$_GET['id'].'&votos=si" title="Consultar lista de estudiantes que han votado">Ver estudiantes con voto>></a><br />';
		echo '<a href="consulta.php?id='.$_GET['id'].'&votos=no" title="Consultar lista de estudiantes que no han votado">Ver estudiantes sin voto>></a>';
		echo '</div>';
		if (!isset($_GET['votos'])) {
			echo '<table>';
			if ($_COOKIE['VotaDatAdmin']==1) {
				echo '<thead><tr><th>ESTUDIANTE</th><th>DOCUMENTO</th><th colspan="2">OPCIONES</th></tr></thead>';
			}
			else {
				echo '<thead><tr><th>ESTUDIANTE</th><th>DOCUMENTO</th></tr></thead>';
			}
			$ContEst=0;
			$VerAccion="Registro";
			$resp=$link->query(sprintf("select * from estudiantes where grado=%d order by apellidos",$_GET['id']));
			while($row = $resp->fetch_array()) {
				echo '<tr>';
				echo '<td>'.$row['apellidos'].' '.$row['nombres'].'</td>';
		                echo '<td>'.$row['documento'].'</td>';
				if ($_COOKIE['VotaDatAdmin']==1) {
					echo '<td class="cen"><a href="consulta.php?id='.$_GET['id'].'&est='.md5($row['id']).'&editar=ok" title="Editar estudiante"><img src="../iconos/lapiz.png" border="0" width="20px" border="0" alt="Editar" /></a></td>';
        		                echo '<td class="cen"><a href="consulta.php?id='.$_GET['id'].'&est='.md5($row['id']).'&elimina=0" title="Borrar estudiante"><img src="../iconos/delete.png" border="0" alt="Borrar" /></a></td>';
				}
			
				echo '</tr>';
				$ContEst=$ContEst+1;
			}
			echo '<tr>';
			if ($_COOKIE['VotaDatAdmin']==1) {
				echo '<td style="text-align:right;" colspan="4"><strong>Total estudiantes... ';
			}
			else {
				echo '<td style="text-align:right;" colspan="2"><strong>Total estudiantes... ';

			}
			echo $ContEst.'</strong></td></tr>';
			echo '</table></div><br />';
		}
		else {
			echo '<table>';
			$ContEst=0;
			$VerAccion="Registro";
			if($_GET['votos']=="si"){
				$resp=$link->query(sprintf("select distinct estudiantes.id, nombres,apellidos,documento from estudiantes,voto where estudiantes.grado=%d and estudiantes.id=id_estudiante order by apellidos",$_GET['id']));
				echo '<thead><tr><th colspan="2">ESTUDIANTES CON VOTO</th></tr></thead>';
			}
			if($_GET['votos']=="no"){
				$resp=$link->query(sprintf("select nombres,apellidos,documento from estudiantes where estudiantes.grado=%d and estudiantes.id not in (select id_estudiante from voto) order by apellidos",$_GET['id']));
				echo '<thead><tr><th colspan="2">ESTUDIANTES SIN VOTO</th></tr></thead>';
			}
			if(isset($resp)) {
				while($row = $resp->fetch_array()) {
					echo '<tr>';
					echo '<td>'.$row['apellidos'].' '.$row['nombres'].'</td>';
					echo '<td>'.$row['documento'].'</td>';
					echo '</tr>';
					$ContEst=$ContEst+1;
				}
			}
			echo '<tr>';
			echo '<td colspan="2" style="text-align:right;"><strong>Total estudiantes... ';
			echo $ContEst.'</strong></td></tr>';
			echo '</table></div>';

		}
		echo '<div class="cen">';
		echo '<a href="general.php" title="consulta por grados"><strong>< Volver a consulta por grados</strong></a>';
		echo '</div><br />';
	}
	else {
		echo 'Error: Registro no existe';
	}
	echo '</body>';
	echo '</html>';
	
}
}
else {
        include_once("encabezado.html");
        echo '<table>';
        echo '<tr><td class="cen"><strong>Su sesi�n ha finalizado, por favor vuelva a ingresar al sistema</strong></td></tr>';
        echo '</table></div></body></html>';
}
$link -> close();
?>
