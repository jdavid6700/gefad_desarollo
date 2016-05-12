<?php
$docnroiden = $_SESSION['usuario_login'];
$cod_consulta = "SELECT doc_nro_iden eca_nro_iden, 
					  (doc_nombre||' '||doc_apellido) eca_nombre, 
					  cur_ape_ano, 
					  cur_ape_per,
   					  cur_asi_cod, 
					  asi_nombre, 
					  cur_nro, 
					  ins_est_cod, 
					  est_nombre,
   					  est_estado_est,
   					  ins_nota_par1, 
					  cur_par1, 
   					  ins_nota_par2, 
					  cur_par2, 
   					  ins_nota_par3, 
					  cur_par3, 
   					  ins_nota_par4, 
					  cur_par4,
   					  ins_nota_par5, 
					  cur_par5,
   					  ins_nota_exa, 
					  cur_exa, 
   					  ins_nota_lab, 
					  cur_hab, 
					  ins_nota_hab, 
					  cur_lab, 
					  ins_nota, 
					  ins_obs,
   					  cur_hab, 
					  ins_nota_acu,
					  cur_nro_ins,
					  cur_cra_cod
				 FROM acins, accurso, acasperi, acasi, acest, accarga, acdocente, acdoctipvin
				WHERE doc_nro_iden = $docnroiden
   				  AND asi_cod =".$_SESSION["A"]."
   				  AND cur_nro =".$_SESSION["G"]."
   				  AND cur_ape_ano = ins_ano
   				  AND cur_ape_per = ins_per
   				  AND cur_asi_cod = ins_asi_cod
   				  AND cur_asi_cod = asi_cod
   				  AND cur_nro = ins_gr
   				  AND cur_ape_ano = ape_ano
   				  AND cur_ape_per = ape_per
   				  AND ape_estado = '$estado'
   				  AND ins_ano = ape_ano
   				  AND ins_per = ape_per
   				  AND ins_est_cod = est_cod
   				  AND ins_estado = 'A'
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
			ORDER BY cur_asi_cod, cur_nro, ins_est_cod";
?>