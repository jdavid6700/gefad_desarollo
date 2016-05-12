<?
$indice=$configuracion["host"].$configuracion["sitePrincipal"];
echo "<br>indice".$indice;
?>
<link
	rel='stylesheet' type='text/css'
	href='<? echo $configuracion["host"].$configuracion["site"].$configuracion["estilo"]."/basico/estilo.php" ?>' />
<table class="bloquelateral" cellpadding="0" cellspacing="0"
	align="center" width="50%">
	<tbody>
		<tr class="bloquelateralencabezado">
			<td valign="middle" align="right" width="10%"><img
				src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["grafico"]?>/importante.png"
				border="0" />
			</td>
			<td valign="middle" align="left">Fallo General de
				Autenticaci&oacute;n</td>
		</tr>
		<tr>
			<td colspan="2">
				<table border="0" cellpadding="10" cellsapcing="0">
					<tbody>
						<tr class="bloquecentralcuerpo">
							<td><b>No ha sido posible iniciar una sesi&oacute;n de trabajo.</b><br>
								Probablemente la sesi&oacute;n iniciada ya haya expirado. Por
								favor regrese a la p&aacute;gina principal e ingrese su nombre
								de usuario y clave.<br> <br> Si el problema persiste por favor
								contacte al administrador del sistema en:<br> <a
								href="mailto:<? echo $configuracion["correo"]?>"><? echo $configuracion["correo"]?>
							</a><br>
							</td>
						</tr>
						<tr class="bloquecentralcuerpo">
							<td style="font-size: 130%" align="center"><a
								href="<? echo $indice;?>" target='_top'><b>Aceptar</b> </a>
							</td>
						</tr>
					</tbody>
				</table>
			</td>
		</tr>
	</tbody>
</table>
