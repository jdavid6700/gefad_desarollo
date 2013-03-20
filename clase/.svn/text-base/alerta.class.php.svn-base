<?	
class alerta
{

	//Constructor
	function alerta($id_pagina,$configuracion)
	{
		
	
	}
	
	
	static function sin_registro($configuracion,$cadena,$encabezado="")
	{
	
	?><table width="100%" align="center" border="0" cellpadding="10" cellspacing="0" >
		<tr>
			<td >
				<table style="text-align: left;" border="0"  cellpadding="5px" cellspacing="0" class="bloquelateral" width="100%">
					<tr>
						<td >
							<table cellpadding="10" cellspacing="0" align="center">
								<tr class="bloquecentralcuerpo">
									<td valign="middle" align="right" width="10%">
										<img src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["grafico"]?>/importante.png" border="0" />
									</td>
									<td align="left"><?
									if($encabezado!="")
									{
										echo "<h3>".$encabezado."</h3>";
										echo "<hr class='hr_subtitulo'>";
									}
									echo 	"<p>".$cadena."</p>";
									
									?></td>
								</tr>
							</table> 
						</td>
					</tr>  
				</table>
			</td>
		</tr>  
	</table><?	
	}
	
	
	
}	
?>
