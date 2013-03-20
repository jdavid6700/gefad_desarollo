<?php
$cod_consul = "SELECT DOC_NRO_IDEN,
   					  LTRIM(doc_nombre||'  '||doc_apellido) nombre,
   					  dep_cod, 
   					  dep_nombre,
   					  car_cra_cod,
   					  cra_nombre,
   					  tvi_cod,
   					  tvi_nombre,
					  CAR_CUR_ASI_COD,
   					  asi_nombre,
   					  CAR_CUR_NRO,
   					  cur_nro_ins
				   FROM accarga,acdocente,actipvin,acasi,accra,gedep,accurso,acdoctipvin,acasperi
				  WHERE dep_cod = cra_dep_cod
   					AND dtv_tvi_cod=tvi_cod
   					AND asi_cod = car_cur_asi_cod 
   					AND car_ape_ano = ape_ano
   					AND car_ape_per = ape_per
   					AND ape_estado = '$estado'
   					AND car_ape_ano = cur_ape_ano
   					AND car_ape_per = cur_ape_per
   					AND car_cur_asi_cod = cur_asi_cod
   					AND car_cur_nro = cur_nro
   					AND doc_nro_iden = $cedula
   					AND cra_cod = cur_cra_cod
   					AND doc_estado = 'A'
   					AND cra_estado = 'A'
   					AND cur_asi_cod = car_cur_asi_cod
   					AND cur_nro = car_cur_nro
   					AND cur_ape_ano = car_ape_ano
   					AND cur_ape_per = car_ape_per
   					AND cur_cra_cod = car_cra_cod
   					AND car_doc_nro_iden = doc_nro_iden
   					AND cur_ape_ano = dtv_ape_ano
   					AND cur_ape_per = dtv_ape_per
   					AND car_doc_nro_iden = dtv_doc_nro_iden
   					AND car_cra_cod = dtv_cra_cod
   					AND cur_estado = 'A'
   					AND car_estado = 'A'
			   ORDER BY dep_cod, car_cra_cod, doc_nro_iden, tvi_cod, car_cur_asi_cod, car_cur_nro ASC";
?>