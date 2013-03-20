<?php
//Llamado de coor_actualiza_dat.php, doc_actualiza_dat.php
$consulta="SELECT ";
$consulta.="LTRIM(DOC_NOMBRE||'  '||DOC_APELLIDO), ";
$consulta.="DOC_DIRECCION, ";
$consulta.="DOC_TELEFONO, ";
$consulta.="DOC_TELEFONO_ALT, ";
$consulta.="DOC_CELULAR, ";
$consulta.="DOC_SEXO, ";
$consulta.="DOC_ESTADO_CIVIL, ";
$consulta.="TEC_NOMBRE, ";
$consulta.="DOC_TIPO_SANGRE, ";
$consulta.="DOC_EMAIL, ";
$consulta.="DOC_EMAIL_INS ";
$consulta.="FROM ";
$consulta.="ACDOCENTE, ";
$consulta.="GETIPESCIVIL ";
$consulta.="WHERE ";
$consulta.="DOC_NRO_IDEN ='".$_SESSION['usuario_login']."' ";
$consulta.="AND ";
$consulta.="DOC_ESTADO_CIVIL = TEC_CODIGO ";
$consulta.="AND ";
$consulta.="DOC_ESTADO = 'A'";
?>