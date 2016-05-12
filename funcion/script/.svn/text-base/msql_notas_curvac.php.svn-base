<?php
$cod_consul = "SELECT est_cod,
  					  est_nombre,
  					  est_nro_iden,
  					  cra_cod,
  					  cra_nombre,
  					  trunc(fa_promedio_nota(est_cod),2),
	   				  ins_asi_cod,
	   				  asi_nombre,
	   				  ins_gr,
	   				  INS_NOTA,
	   				  ins_obs
  				 FROM acest, accra, accurso, acins, acasi, acasperi
				WHERE ins_est_cod = $estcod
				  AND ins_asi_cod = cur_asi_cod
				  AND ins_gr = cur_nro
				  AND ins_ano = cur_ape_ano
				  AND ins_per = cur_ape_per
				  AND ins_est_cod = est_cod
				  AND ins_cra_cod = cra_cod
   				  AND ins_asi_cod = asi_cod
   				  AND ins_ano = ape_ano 
   				  AND ins_per = ape_per
   				  AND ape_estado = 'V'
			  ORDER BY ins_asi_cod,ins_gr";
?>