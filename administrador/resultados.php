<?php
require_once("../funciones.php");	
require_once("../conexionBD.php");
$link=conectarse(); 
//***Leer variables del sistema******
$estado=$link->query("select * from general");
$leer= $estado->fetch_array();
//****** Verificamos si existe la cookie *****/
if(isset($_COOKIE['VotaDatAdmin'])) {
	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
	echo '<html>';
	echo '<head>';
	echo '<title>'.$leer['institucion'].' - Resultados votaciones</title>';
	echo '<link href="../estilo4.css" rel="stylesheet" type="text/css" />';
	echo '</head>';
	echo '<body>';
	echo '<h1>'.$leer['institucion'].'</h1>';
	echo '<h2>RESULTADOS DE LAS VOTACIONES</h2>';

	$resp5=$link->query("select * from categorias");
	while($row5 = $resp5->fetch_array()) {
	
		echo '<div align="center">';
		echo '<p class="txtinicial">RESULTADOS '.cambia_mayuscula($row5['descripcion']).'</p>';
		echo '<table>';
		echo '<thead><tr><th>GRADO</th>';		
		$resp4=$link->query(sprintf("select nombres,apellidos from candidatos where representante=%d order by apellidos DESC",$row5['id']));		
		$w=0;
		while($row4 = $resp4->fetch_array()) {
			echo '<th>';
			echo $row4['nombres'].' '.$row4['apellidos'];
			echo '</th>';
			$ttl_colum[$w]=0;
			$w=$w+1;
		}
		echo '<th>TOTAL</th>';
		echo '</tr></thead>';

		$resp=$link->query("select * from grados");
		$ttl_acum=0;
		while($row = $resp->fetch_array()) {			
			$resp2=$link->query(sprintf("select id from candidatos where representante=%d order by apellidos DESC",$row5['id']));
			echo '<tr>';
			$ContCol=0;
			$ContRow=0;
			echo '<td class="cen">'.$row['grado'].'</td>';
			while($row2 = $resp2->fetch_array()) {
				$resp3=$link->query(sprintf("select count(id_estudiante) from voto,estudiantes where grado=%d and candidato=%d and estudiantes.id=id_estudiante",$row['id'],$row2['id']));
				$row3 = $resp3->fetch_array();
				echo '<td class="cen">'.$row3[0].'</td>';
				$ttl_colum[$ContCol]=$ttl_colum[$ContCol]+$row3[0];
				$ContCol=$ContCol+1;
				$ContRow=$ContRow+$row3[0];
				$ttl_acum=$ttl_acum+$row3[0];
			}
			echo '<td class="cen"><strong>'.$ContRow.'</strong></td>';
			echo '</tr>';

		}
			echo '<tr>';
			echo '<td><strong>TOTAL...</strong></td>';
			for ($i = 0; $i < $ContCol; $i++) {
				echo '<td class="cen"><strong>'.$ttl_colum[$i].'</strong></td>';
			}
			echo '<td class="cen"><strong>'.$ttl_acum.'</strong></td>';
			echo '</tr>';
			
			echo '</table>';
			echo '</div>';
	}			
	echo '</body>';
	echo '</html>';
}
else {
        include_once("encabezado.html");
        echo '<table>';
        echo '<tr><td class="cen"><strong>Su sesi�n ha finalizado, por favor vuelva a ingresar al sistema</strong></td></tr>';
        echo '</table></div></body></html>';
}
$link -> close();
?>
