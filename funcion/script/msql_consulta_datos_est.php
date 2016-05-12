<?php
//Llamado de est_actualiza_dat.php
$consulta = OCIParse($oci_conecta, "SELECT EST_NOMBRE,
       									   EST_NRO_IDEN,
       									   EST_DIRECCION,
	   									   EST_TELEFONO,
	   									   EST_ZONA_POSTAL,
										   EST_SEXO,
	   									   EOT_TIPOSANGRE,
	   									   EOT_RH,
										   EOT_EMAIL,
										   EOT_ESTADO_CIVIL,
										   TEC_NOMBRE,
										   TO_CHAR(EOT_FECHA_NAC, 'DD-MM-YYYY'),
										   EOT_COD_LUG_NAC,
										   LUG_NOMBRE,
										   EST_VALOR_MATRICULA,
										   decode(EST_DIFERIDO, 'S', 'Si', 'N', 'No'),
										   CRA_NOMBRE,
										   EOT_EMAIL_INS
  									  FROM ACEST,ACESTOTR,GETIPESCIVIL,GELUGAR,ACCRA
 								 	 WHERE EST_COD = EOT_COD
									   AND EST_CRA_COD = CRA_COD
									   AND EOT_ESTADO_CIVIL = TEC_CODIGO
									   AND EOT_COD_LUG_NAC = LUG_COD
   									   AND EST_COD =".$_SESSION['usuario_login']."
   									   AND EST_ESTADO_EST IN('A','B','H','J','L','T','V')");
OCIExecute($consulta) or die(Ora_ErrorCode());
$row = OCIFetch($consulta);
?>