<?php
require_once('dir_relativo.cfg');
require_once(dir_script.'msql_ano_per.php');
$cod_consul = "SELECT doc_nro_iden,
					  doc_nombre||' '||doc_apellido,
       				  LOWER(doc_email),
       				  cra_nombre,
       				  dtv_clave
  				 FROM acdocente,acdoctipvin,accra,acasperi
 			  	WHERE doc_nro_iden =".$_POST['cedula']."
				  AND doc_nro_iden = dtv_doc_nro_iden
   				  AND cra_cod      = dtv_cra_cod
   				  AND ape_ano      = dtv_ape_ano
   				  AND ape_per      = dtv_ape_per
				  AND doc_estado   = 'A'
   				  AND ape_estado   = 'A'
   				  AND doc_email LIKE '%@%'
				  AND EXISTS(SELECT car_doc_nro_iden
				  	  		   FROM accarga
				  	  		  WHERE car_doc_nro_iden = doc_nro_iden
							    AND car_ape_ano = $ano
								AND car_ape_per = $per
								AND car_estado = 'A')";
?>