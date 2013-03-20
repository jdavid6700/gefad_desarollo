<?php
$cod_consul = "SELECT cur_asi_cod,
	TRIM(asi_nombre),
        cur_nro,
        DECODE(CUR_SEMESTRE,0,'Electiva',CUR_SEMESTRE) CUR_SEMESTRE,
	cur_cra_cod,
	cra_nombre,
	cur_nro_cupo,
	cur_nro_ins,
	ape_ano,
	ape_per,
	ins_cra_cod,
	ins_est_cod,
	est_nombre,
	est_estado_est
	FROM accurso,accra,acasi,acasperi,acins,acest
	WHERE cur_asi_cod =".$_REQUEST['as']."
	AND cur_nro =".$_REQUEST['gr']."
	AND cur_asi_cod = asi_cod
	AND cur_cra_cod = cra_cod
	AND cur_ape_ano = ape_ano
	AND cur_ape_per = ape_per
	AND ape_estado = '$estado'
	AND CUR_ASI_COD = INS_ASI_COD
	AND CUR_NRO     = INS_GR
	AND CUR_APE_ANO = INS_ANO
	AND CUR_APE_PER = INS_PER
	AND ins_est_cod = est_cod
	ORDER BY ins_est_cod";
?>