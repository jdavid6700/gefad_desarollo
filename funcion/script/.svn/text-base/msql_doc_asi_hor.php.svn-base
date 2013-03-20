<?php
$cod_consul = "SELECT dia_nombre,
		MIN(hor_hora),
		(MAX(hor_hora) + 1),
		hor_sal_cod,
		sed_abrev  
		FROM acasperi, achorario, gedia, gesalon, gesede
		WHERE ape_ano = hor_ape_ano
		AND ape_per = hor_ape_per
		AND ape_estado = 'A'
		AND hor_asi_cod =".$_REQUEST['asicod']."
		AND hor_nro =".$_REQUEST['asigr']."
		AND hor_estado = 'A'
		AND hor_dia_nro  = dia_cod
		AND hor_sal_cod = sal_cod
		AND hor_sed_cod = sed_cod
		GROUP BY dia_cod, dia_nombre, hor_sal_cod, sed_abrev
		ORDER BY dia_cod, MIN(hor_hora) ASC";
?>