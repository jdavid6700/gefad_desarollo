<?PHP
$cod_consul = "SELECT est_cod,
		est_nombre,
		est_nro_iden,
		cra_cod,
		cra_nombre,
		TRUNC(Fa_Promedio_Nota(est_cod),2),
		asi_cod,
		asi_nombre,
		ins_gr,
		doc_nro_iden,
		(LTRIM(RTRIM(doc_nombre))||' '||LTRIM(RTRIM(doc_apellido))),
		doc_email,
		fua_mostrar_horario(ape_ano,ape_per,asi_cod,ins_gr)
		FROM ACCRA, ACEST,ACINS, ACASI,ACASPERI,ACCARGA,ACDOCENTE
		WHERE cra_cod = est_cra_cod
		AND est_cod     = ins_est_cod
		AND est_cra_cod = ins_cra_cod
		AND est_estado_est IN($estados)
		AND asi_cod     = ins_asi_cod
		AND ape_ano     = ins_ano
		AND ape_per     = ins_per
		AND ape_estado  = 'A'
		AND ins_estado  = 'A'
		AND est_cod = ".$_SESSION['usuario_login']."
		AND ins_ano     = car_ape_ano(+)
		AND ins_per     = car_ape_per(+)
		AND ins_cra_cod = car_cra_cod(+)
		AND ins_asi_cod = car_cur_asi_cod(+)
		AND ins_gr      = car_cur_nro(+)
		AND car_doc_nro_iden = doc_nro_iden(+)
		order by ins_asi_cod";
?>