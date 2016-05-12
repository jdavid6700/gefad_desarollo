<?php
//Llamado de coor_actualiza_dat.php, doc_actualiza_dat.php
$qry = "UPDATE ";
$qry.="acdocente ";
$qry.="SET ";
$qry.="DOC_DIRECCION = '".strtolower($_REQUEST['dir'])."', ";
$qry.="DOC_TELEFONO = '".$_REQUEST['tel']."', ";
$qry.="DOC_TELEFONO_ALT = '".$_REQUEST['tela']."', ";
$qry.="DOC_SEXO = '".$_REQUEST['sex']."', ";
$qry.="DOC_ESTADO_CIVIL = '".$_REQUEST['estc']."', ";
$qry.="DOC_TIPO_SANGRE = '".$_REQUEST['tisa']."', ";
$qry.="DOC_CELULAR = '".$_REQUEST['cel']."', ";
$qry.="DOC_EMAIL = '".strtolower($_REQUEST['mail'])."' ";
$qry.="WHERE ";
$qry.="doc_nro_iden ='".$_SESSION['usuario_login']."' ";
//echo $qry;
$resultado=$conexion->ejecutarSQL($configuracion,$accesoOracle,$qry,"busqueda");
?>
