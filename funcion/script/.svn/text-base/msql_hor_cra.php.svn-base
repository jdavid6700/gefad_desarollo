<?php
require_once('msql_ano_per.php');
$cod_consul = "SELECT ACASI.ASI_NOMBRE, 
					  V_ACCURSO_ACHORARIO.ASIGNATURA, 
					  V_ACCURSO_ACHORARIO.GRUPO, 
					  GEDIA.DIA_NOMBRE,
					  GEHORA.HOR_LARGA,
					  ACCRA.CRA_NOMBRE,
					  ACCRA.CRA_COD,
					  ACPEN.PEN_SEM,
					  GESEDE.SED_ABREV,
					  GESALON.SAL_COD
				 FROM V_ACCURSO_ACHORARIO, GEDIA, GEHORA, ACASI, ACCRA, ACPEN, GESALON, GESEDE
				WHERE (v_accurso_achorario.ano = $ano
				  AND v_accurso_achorario.per = $per
				  AND v_accurso_achorario.cra_cod =".$_GET['carrera']."
				  AND acpen.pen_asi_cod = asignatura
				  AND acpen.pen_cra_Cod = v_accurso_achorario.cra_cod
				  AND acpen.pen_cra_cod = accra.cra_cod
				  AND hora = hor_cod
				  AND asi_cod = asignatura
				  AND dia = dia_cod
				  AND salon = gesalon.sal_Cod
				  AND sede = gesalon.sal_sed_cod
				  AND gesalon.sal_sed_cod = gesede.sed_cod)
			 ORDER BY ASI_NOMBRE";
?>