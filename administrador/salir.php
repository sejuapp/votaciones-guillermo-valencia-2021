<?php
//**** Eliminar cookie de sesi�n *****
setcookie("VotaDatAdmin", "", time()-3600);

//**** Redireccionar p�gina web *****
header ("Location: index.php"); 
exit();
?>
