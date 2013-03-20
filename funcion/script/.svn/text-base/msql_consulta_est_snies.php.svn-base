<?php
//Llamado de est_actualiza_dat.php
$consulta = OCIParse($oci_conecta, "SELECT *
  									  FROM ACESTOTR, ACEST
 								 	   WHERE EOT_COD =".$_SESSION['usuario_login']."
   									   AND EST_ESTADO_EST IN('A','B','H','J','L','T','V')");
OCIExecute($snies) or die(Ora_ErrorCode());
$row = OCIFetch($snies);
?>