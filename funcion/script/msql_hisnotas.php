<?php
$cod_consul = "SELECT est_cod,
  					  est_nombre,
  					  est_nro_iden,
  					  cra_cod,
  					  cra_nombre,
  					  trunc(fa_promedio_nota(est_cod),2),
  					  v_acnot.NOT_ASI_COD,
  					  v_acnot.ASI_NOMBRE,
  					  NVL(acnot.not_gr,0),  
  					  v_acnot.not_sem, 
  					  DECODE(LENGTH(v_acnot.NOT_ASI_COD),2,SUBSTR(ultima,3,4),3,SUBSTR(ultima,4,4),4,SUBSTR(ultima,5,4),5,SUBSTR(ultima,6,4),
  					  6,SUBSTR(ultima,7,4),7,SUBSTR(ultima,8,4),SUBSTR(ultima,9,4)) ano,
  					  DECODE(LENGTH(v_acnot.NOT_ASI_COD),2,SUBSTR(ultima,7,1),3,SUBSTR(ultima,8,1),4,SUBSTR(ultima,9,1),5,SUBSTR(ultima,10,1),
  					  6,SUBSTR(ultima,11,1),7,SUBSTR(ultima,12,1),SUBSTR(ultima,13,1)) per,
  					  not_nota,
  					  DECODE(nob_cod,0,' ',nob_nombre) nob_nombre,
  					  v_acnot.cursada
				 FROM acest, mntac.ACMONTO, ACNOT, mntac.v_acnot, mntac.acnotobs,accra
				WHERE est_cod = v_acnot.not_est_cod
  				  AND est_cra_cod = cra_cod
  				  AND (acnot.NOT_NOTA = ACMONTO.MON_COD)
  				  AND v_acnot.not_asi_cod = acnot.not_asi_cod
  				  AND v_acnot.not_est_cod = acnot.not_est_cod
  				  AND v_acnot.not_cra_cod = acnot.not_cra_cod
  				  AND acnot.not_ano = TO_NUMBER(DECODE(LENGTH(v_acnot.NOT_ASI_COD),2,SUBSTR(ultima,3,4),3,SUBSTR(ultima,4,4),4,SUBSTR(ultima,5,4),5,SUBSTR(ultima,6,4),
  					  6,SUBSTR(ultima,7,4),7,SUBSTR(ultima,8,4),SUBSTR(ultima,9,4)))
  				  AND acnot.not_per  =   TO_NUMBER(DECODE(LENGTH(v_acnot.NOT_ASI_COD),2,SUBSTR(ultima,7,1),3,SUBSTR(ultima,8,1),4,SUBSTR(ultima,9,1),5,SUBSTR(ultima,10,1),
  					  6,SUBSTR(ultima,11,1),7,SUBSTR(ultima,12,1),SUBSTR(ultima,13,1)))
  				  AND acnot.not_obs = acnotobs.nob_cod
  				  AND acnot.not_obs NOT IN(2,16,7,12)
  				  AND not_est_reg != 'I'
  				  AND v_acnot.not_est_cod = $estcod
			 ORDER BY v_acnot.NOT_SEM, v_acnot.not_asi_cod";

?>
