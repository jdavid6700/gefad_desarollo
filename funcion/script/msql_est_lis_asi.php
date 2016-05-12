<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'conexion.php');
require_once(dir_conect.'cierra_bd.php');

$lisasi = OCIParse($oci_conecta,"SELECT CUR_ASI_COD,
       			  						ASI_NOMBRE,
       			  						CUR_NRO,
       			  						CUR_SEMESTRE,
       			  						CUR_NRO_CUPO,
       			  						CUR_NRO_INS,
       			  						CUR_NRO_CUPO-CUR_NRO_INS Disp
			 						FROM ACCURSO,ACASI
								   WHERE CUR_ASI_COD=ASI_COD
  			  						 AND CUR_CRA_COD=20
  			  						 AND cur_ape_ano =(select ape_ano from acasperi where ape_estado = 'A')
  			  						 AND cur_ape_per =(select ape_per from acasperi where ape_estado = 'A')
  			  						 AND cur_asi_cod in(select pen_asi_cod from acpen where pen_cra_cod = 20)
  			  						 AND CUR_NRO_CUPO >= CUR_NRO_INS  
		 						ORDER BY CUR_SEMESTRE,CUR_ASI_COD,CUR_NRO");

OCIExecute($lisasi);
$row = OCIFetch($lisasi);

echo'<table border="1" cellspacing="0" cellpadding="2" style="border-collapse: collapse" bordercolor="#111111" width="530" id="AutoNumber1">
     <tr><td width="60" align="center"><font face="Tahoma" size="2"><b>Código</b></font></td>
    	 <td width="363" align="center"><font face="Tahoma" size="2"><b>Nombre</b></font></td>
    	 <td width="22" align="center"><font face="Tahoma" size="2"><b>Gr.</b></font></td>
    	 <td width="30" align="center"><font face="Tahoma" size="2"><b>Sem</b></font></td>
    	 <td width="28" align="center"><font face="Tahoma" size="2"><b>Cup</b></font></td>
    	 <td width="22" align="center"><font face="Tahoma" size="2"><b>Ins</b></font></td>
    	 <td width="27" align="center"><font face="Tahoma" size="2"><b>DIS</b></td></font></tr>';

do{
echo'<tr><td width="60" align="right"><font face="Tahoma" size="2">'.OCIResult($lisasi, 1).'</font></td>
    	 <td width="363"><font face="Tahoma" size="2">'.OCIResult($lisasi, 2).'</font></td>
    	 <td width="22" align="center"><font face="Tahoma" size="2">'.OCIResult($lisasi, 3).'</font></td>
    	 <td width="30" align="center"><font face="Tahoma" size="2">'.OCIResult($lisasi, 4).'</font></td>
    	 <td width="28" align="center"><font face="Tahoma" size="2">'.OCIResult($lisasi, 5).'</font></td>
    	 <td width="22" align="center"><font face="Tahoma" size="2">'.OCIResult($lisasi, 6).'</font></td>
    	 <td width="27" align="center"><font face="Tahoma" size="2">'.OCIResult($lisasi, 7).'</font></td></tr>';
}while(OCIFetch($lisasi));
echo'</table>';
cierra_bd($lisasi,$oci_conecta);
?>