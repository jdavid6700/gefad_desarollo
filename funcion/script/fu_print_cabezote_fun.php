<?PHP
require_once('dir_relativo.cfg');
function fu_print_cabezote_fun($titulo){
	$fec = date("j-M-Y");
	$hor = date("g:i:s A");
	$escudo = dir_img.'Uescudo.gif';
	echo '<div align="center">
		<table border="0" width="90%">
			<tr>
				<td width="14%" rowspan="2" align="center"><IMG SRC=';echo $escudo; echo '></td>
				<td width="86%" align="center"><b><font color="#0000FF" face="Arial">UNIVERSIDAD DISTRITAL FRANCISCO JOS&Eacute; DE CALDAS<br>
				NIT. 899.999.230-7<br>
				DIVISI&Oacute;N DE RECURSOS HUMANOS</font></b></td>
			</tr>
			<tr>
				<td width="86%" align="center"><b><font face="Arial" size="2" color="#FF3300"><br>';echo $titulo; echo '<br>
				</font></b></td>
			</tr>
		</table>

		<table border="0" width="90%" cellpadding="2" height="3">
			<tr>
				<td width="16%" align="center"><div align="right">
    				<input name="button" type="button" onClick="javascript:window.print();" value="Impreso: " title="Imprimir desprendible" style="cursor:pointer">
    				</div></td>
				<td width="14%" align="center"><div align="left"><font face="Tahoma" size="2">'; echo $fec; echo '</font></div></td>
				<td width="14%" align="center"><div align="left"><font face="Tahoma" size="2">'; echo $hor; echo '</font></div></td>
				<td width="17%" align="center">&nbsp;</td>
				<td width="17%" align="center">&nbsp;</td>
				<td width="17%" align="center">&nbsp;</td>
				<td width="17%" align="center">&nbsp;</td>
			</tr>
		</table>
	</div><BR>';
}
?>