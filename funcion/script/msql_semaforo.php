<?php
$cod_consul = "SELECT est_cod,
		est_nombre,
		est_nro_iden,
		est_cra_cod,
		cra_nombre,
		trunc(fa_promedio_nota(est_cod),2),
		est_pen_nro,
		pen_asi_cod,
		asi_nombre,
		pen_sem,
		fua_sem_not(est_cod,est_cra_cod,pen_asi_cod),
		fua_sem_obs(est_cod,est_cra_cod,pen_asi_cod)
		FROM ACEST, ACCRA, ACPENNUE, ACASI
		WHERE est_cod = $estcod
		AND est_cra_cod = cra_cod
		AND est_cra_cod = pen_cra_cod
		AND est_pen_nro = pen_nro
		AND pen_asi_cod = asi_cod
		AND pen_ESTADO ='A'
		ORDER BY PEN_SEM";
?>
