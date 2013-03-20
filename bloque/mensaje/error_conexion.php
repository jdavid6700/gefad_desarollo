<table class="bloquelateral" cellpadding="0" cellspacing="0">
	<tbody>
		<tr class="bloquelateralencabezado">
			<td valign="middle" align="right" width="10%"><img
				src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["grafico"]?>/importante.png"
				border="0" /></td>
			<td valign="middle" align="left"
				style="background-color: rgb(221, 221, 221); font-weight: bold; font-family: Helvetica, Arial, sans-serif; color: rgb(0, 0, 0);">
				Imposible Conectar con la base de datos</td>
		</tr>
		<tr>
			<td colspan="2">
				<table border="0" cellpadding="10" cellsapcing="0">
					<tbody>
						<tr class="bloquecentralcuerpo">
							<td>En este momento el sistema de base de datos se encuentra
								congestionado. Por favor intente la acci&oacute;n dentro de
								algunos minutos.<br> Si el problema es recurrente por favor
								comunique el caso al administrador del sistema:<br> <?echo $configuracion["correo"]?><br>
								Agradecemos su colaboraci&oacute;n.
							</td>
						</tr>
					</tbody>
				</table>
			</td>
		</tr>
	</tbody>
</table>
