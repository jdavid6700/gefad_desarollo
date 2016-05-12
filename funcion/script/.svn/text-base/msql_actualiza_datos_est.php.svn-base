<?php
//Llamado de est_actualiza_dat.php
if($_POST['estc']=='SOLTERO') $_POST['estc']=1;
if($_POST['matdif']=="No") $_POST['matdif']="N";
if($_POST['matdif']=="Si") $_POST['matdif']="S";

$qery = OCIParse($oci_conecta, "UPDATE ACEST
								   SET EST_DIRECCION = :bdir,
									   EST_TELEFONO = :btel,
									   EST_SEXO = :bsex,
									   EST_ZONA_POSTAL = :bzonap,
									   EST_DIFERIDO = :bmatdif
								 WHERE EST_COD =".$_SESSION['usuario_login']);
OCIBindByName($qery, ":bdir", $_POST['dir']);
OCIBindByName($qery, ":btel", $_POST['tel']);
OCIBindByName($qery, ":bsex", $_POST['sex']);
OCIBindByName($qery, ":bzonap", $_POST['zonap']);
OCIBindByName($qery, ":bmatdif", $_POST['matdif']);
OCIExecute($qery, OCI_DEFAULT) or die(ora_errorcode());
OCICommit($oci_conecta);
     
$qry = OCIParse($oci_conecta, "UPDATE ACESTOTR
								  SET EOT_FECHA_NAC = to_date(:bfecnac,'DD-MM-YYYY'),
									  EOT_COD_LUG_NAC = :blugnac,
									  EOT_EMAIL = :bmail,
									  EOT_TIPOSANGRE = :btisa,
									  EOT_RH = :brh,
									  EOT_ESTADO_CIVIL = :bestc
								WHERE EOT_COD =".$_SESSION['usuario_login']);
OCIBindByName($qry, ":bfecnac", $_POST['fecnac']);
OCIBindByName($qry, ":blugnac", $_POST['lugnac']);
OCIBindByName($qry, ":bmail", $_POST['mail']);
OCIBindByName($qry, ":btisa", $_POST['tisa']);
OCIBindByName($qry, ":brh", $_POST['rh']);
OCIBindByName($qry, ":bestc", $_POST['estc']);
OCIExecute($qry, OCI_DEFAULT) or die(ora_errorcode());
OCICommit($oci_conecta);
cierra_bd($qery,$oci_conecta);
cierra_bd($qry,$oci_conecta);
?>