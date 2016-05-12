<?php
$QryHor = "SELECT hor_cod codigo,
				hor_rango rango,
				NVL((SELECT (ins_asi_cod||' - '||ins_gr||' / '||hor_sal_cod||' - '||sed_abrev)
						FROM gehora, acasperi, gesede, accurso, achorario, acins
						WHERE ape_estado = 'A'
						AND x.hor_cod = hor_cod
						AND hor_dia_nro = 1
						AND ape_ano = hor_ape_ano
						AND ape_per = hor_ape_per
						AND hor_cod = hor_hora
						AND sed_cod = hor_sed_cod
						AND achorario.hor_estado = 'A'
						AND ape_ano = cur_ape_ano
						AND ape_per = cur_ape_per
						AND cur_estado = 'A'
						AND cur_asi_cod = hor_asi_cod
						AND cur_nro = hor_nro
						AND ape_ano = ins_ano
						AND ape_per = ins_per
						AND cur_asi_cod = ins_asi_cod
						AND cur_nro = ins_gr
						AND ins_est_cod = ".$_SESSION['usuario_login']."),' ') LUNES,
				NVL((SELECT (ins_asi_cod||' - '||ins_gr||' / '||hor_sal_cod||' - '||sed_abrev)
						FROM gehora, acasperi, gesede, accurso, achorario, acins
						WHERE ape_estado = 'A'
						AND x.hor_cod = hor_cod
						AND hor_dia_nro = 2
						AND ape_ano = hor_ape_ano
						AND ape_per = hor_ape_per
						AND hor_cod = hor_hora
						AND sed_cod = hor_sed_cod
						AND achorario.hor_estado = 'A'
						AND ape_ano = cur_ape_ano
						AND ape_per = cur_ape_per
						AND cur_estado = 'A'
						AND cur_asi_cod = hor_asi_cod
						AND cur_nro = hor_nro
						AND ape_ano = ins_ano
						AND ape_per = ins_per
						AND cur_asi_cod = ins_asi_cod
						AND cur_nro = ins_gr
						AND ins_est_cod = ".$_SESSION['usuario_login']."),' ') MARTES,
				NVL((SELECT (ins_asi_cod||' - '||ins_gr||' / '||hor_sal_cod||' - '||sed_abrev)
						FROM gehora, acasperi, gesede, accurso, achorario, acins
						WHERE ape_estado = 'A'
						AND x.hor_cod = hor_cod
						AND hor_dia_nro = 3
						AND ape_ano = hor_ape_ano
						AND ape_per = hor_ape_per
						AND hor_cod = hor_hora
						AND sed_cod = hor_sed_cod
						AND achorario.hor_estado = 'A'
						AND ape_ano = cur_ape_ano
						AND ape_per = cur_ape_per
						AND cur_estado = 'A'
						AND cur_asi_cod = hor_asi_cod
						AND cur_nro = hor_nro
						AND ape_ano = ins_ano
						AND ape_per = ins_per
						AND cur_asi_cod = ins_asi_cod
						AND cur_nro = ins_gr
						AND ins_est_cod = ".$_SESSION['usuario_login']."),' ') MIERCOLES,
				NVL((SELECT (ins_asi_cod||' - '||ins_gr||' / '||hor_sal_cod||' - '||sed_abrev)
						FROM gehora, acasperi, gesede, accurso, achorario, acins
						WHERE ape_estado = 'A'
						AND x.hor_cod = hor_cod
						AND hor_dia_nro = 4
						AND ape_ano = hor_ape_ano
						AND ape_per = hor_ape_per
						AND hor_cod = hor_hora
						AND sed_cod = hor_sed_cod
						AND achorario.hor_estado = 'A'
						AND ape_ano = cur_ape_ano
						AND ape_per = cur_ape_per
						AND cur_estado = 'A'
						AND cur_asi_cod = hor_asi_cod
						AND cur_nro = hor_nro
						AND ape_ano = ins_ano
						AND ape_per = ins_per
						AND cur_asi_cod = ins_asi_cod
						AND cur_nro = ins_gr
						AND ins_est_cod = ".$_SESSION['usuario_login']."),' ') JUEVES,
				NVL((SELECT (ins_asi_cod||' - '||ins_gr||' / '||hor_sal_cod||' - '||sed_abrev)
						FROM gehora, acasperi, gesede, accurso, achorario, acins
						WHERE ape_estado = 'A'
						AND x.hor_cod = hor_cod
						AND hor_dia_nro = 5
						AND ape_ano = hor_ape_ano
						AND ape_per = hor_ape_per
						AND hor_cod = hor_hora
						AND sed_cod = hor_sed_cod
						AND achorario.hor_estado = 'A'
						AND ape_ano = cur_ape_ano
						AND ape_per = cur_ape_per
						AND cur_estado = 'A'
						AND cur_asi_cod = hor_asi_cod
						AND cur_nro = hor_nro
						AND ape_ano = ins_ano
						AND ape_per = ins_per
						AND cur_asi_cod = ins_asi_cod
						AND cur_nro = ins_gr
						AND ins_est_cod = ".$_SESSION['usuario_login']."),' ') VIERNES,
				NVL((SELECT (ins_asi_cod||' - '||ins_gr||' / '||hor_sal_cod||' - '||sed_abrev)
						FROM gehora, acasperi, gesede, accurso, achorario, acins
						WHERE ape_estado = 'A'
						AND x.hor_cod = hor_cod
						AND hor_dia_nro = 6
						AND ape_ano = hor_ape_ano
						AND ape_per = hor_ape_per
						AND hor_cod = hor_hora
						AND sed_cod = hor_sed_cod
						AND achorario.hor_estado = 'A'
						AND ape_ano = cur_ape_ano
						AND ape_per = cur_ape_per
						AND cur_estado = 'A'
						AND cur_asi_cod = hor_asi_cod
						AND cur_nro = hor_nro
						AND ape_ano = ins_ano
						AND ape_per = ins_per
						AND cur_asi_cod = ins_asi_cod
						AND cur_nro = ins_gr
						AND ins_est_cod = ".$_SESSION['usuario_login']."),' ') SABADO,
				NVL((SELECT (ins_asi_cod||' - '||ins_gr||' / '||hor_sal_cod||' - '||sed_abrev)
						FROM gehora, acasperi, gesede, accurso, achorario, acins
						WHERE ape_estado = 'A'
						AND x.hor_cod = hor_cod
						AND hor_dia_nro = 7
						AND ape_ano = hor_ape_ano
						AND ape_per = hor_ape_per
						AND hor_cod = hor_hora
						AND sed_cod = hor_sed_cod
						AND achorario.hor_estado = 'A'
						AND ape_ano = cur_ape_ano
						AND ape_per = cur_ape_per
						AND cur_estado = 'A'
						AND cur_asi_cod = hor_asi_cod
						AND cur_nro = hor_nro
						AND ape_ano = ins_ano
						AND ape_per = ins_per
						AND cur_asi_cod = ins_asi_cod
						AND cur_nro = ins_gr
						AND ins_est_cod = ".$_SESSION['usuario_login']."),' ') DOMINGO												
				FROM gehora x
				WHERE hor_estado = 'A'
				ORDER BY hor_cod ASC";

?>