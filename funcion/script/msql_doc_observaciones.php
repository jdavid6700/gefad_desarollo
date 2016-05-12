<?php
$cod_consul = "SELECT epe_cur_asi_cod,
       				  asi_nombre,
   					  epe_cur_nro,
   					  epe_doc_nro_iden,
					  epe_observa
				 FROM desarrollador1.acevaproest,
				 	  acasperi,
					  acasi
				WHERE epe_ape_ano = ape_ano
				  AND epe_ape_per = ape_per
				  AND ape_estado = 'A'
				  AND epe_cur_asi_cod = asi_cod
				  AND asi_estado = 'A'
				  AND epe_observa IS NOT NULL
				  AND epe_cur_asi_cod =".$_GET['asicod']."
				  AND epe_cur_nro =".$_GET['asigr']."
				  AND epe_doc_nro_iden =".$_SESSION['usuario_login'];
?>