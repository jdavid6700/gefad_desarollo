<?PHP
$HorCra = "SELECT cur_semestre,
									     asi_cod,
									     asi_nombre,
									     cur_nro,
									     dia_cod,
									     dia_nombre,
									     hor_sal_cod,
									     sed_cod,
									     sed_nombre,
									     MIN(hor_hora)||'-'||(MAX(hor_hora)+1)
									FROM acasperi,acasi,accurso,achorario,gedia,gesede
								   WHERE ape_estado = 'A'
									 AND ape_ano = cur_ape_ano
									 AND ape_per = cur_ape_per
									 AND cur_estado = 'A'
									 AND cur_cra_cod = $carrera
									 AND cur_semestre = $semestre
									 AND asi_cod = cur_asi_cod
									 AND ape_ano = hor_ape_ano
									 AND ape_per = hor_ape_per
									 AND cur_asi_cod = hor_asi_cod
									 AND cur_nro = hor_nro
									 AND hor_estado = 'A'
									 AND hor_dia_nro = dia_cod
									 AND hor_sed_cod = sed_cod
								GROUP BY cur_semestre, asi_cod, asi_nombre, cur_nro, dia_cod, dia_nombre, hor_sal_cod, sed_cod, sed_nombre
								ORDER BY 2,4,5 ASC";
?>
