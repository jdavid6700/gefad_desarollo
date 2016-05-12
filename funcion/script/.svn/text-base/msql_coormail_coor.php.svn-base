<?php
$cod_consul = "SELECT doc_nro_iden,
					  (doc_nombre||' '||doc_apellido),
					  cra_cod,
					  cra_nombre,
					  LOWER(doc_email)
				 FROM accra x,peemp,acdocente
				WHERE cra_emp_cod = emp_cod
				  AND doc_nro_iden = emp_nro_iden
				  AND emp_estado_e != 'R'
				  AND doc_email like  '%@%'
				  AND cra_estado = 'A'
				  AND doc_estado = 'A'
				  AND exists(SELECT *
 							   FROM accurso,acasperi
 							  WHERE ape_ano = cur_ape_ano
 								AND ape_per = cur_ape_per
								AND x.cra_cod = cur_cra_cod)
			 ORDER BY cra_cod";
?>