<?php

//echo "Este es el valor:".$oci_conecta." ..";
$qry_estcracod = OCIParse($oci_conecta, "SELECT est_cra_cod
										   FROM acest
									      WHERE est_cod = ".$_SESSION['usuario_login']);
//echo $oci_conecta.$_SESSION['usuario_login'];
OCIExecute($qry_estcracod) or die(ora_errorcode());
$row_estcracod = OCIFetch($qry_estcracod);
$EstCraCod = OCIResult($qry_estcracod, 1);			 
OCIFreeCursor($qry_estcracod);
?>