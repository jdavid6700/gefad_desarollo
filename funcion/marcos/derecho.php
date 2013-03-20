<?

	include_once("../clase/dbms.class.php");
	require_once('../conexion/valida_pag.php');
	require_once("../conexion/fu_tipo_user.php");
			

fu_tipo_user($_SESSION['usuario_nivel']);


	switch($_SESSION['usuario_nivel']){
		case 51:
			$home="onclick=parent.principal.location.href='../estudiantes/est_pag_principal.php'";
			$help="onclick=parent.principal.location.href='http://oasdes.udistrital.edu.co/development/desarrolloweb/manual/estudiante.pdf'";
		break;
		
	
	
	}

?>


<html>
	<head>
		<script language="JavaScript" src="../script/ventana.js"></script>
		<link href="apariencia.css" rel="stylesheet" type="text/css">
	</head>
	<body class="derecho">
<center>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<table>		


	<tr><td style='border: 1px solid ;'><div  style="cursor:pointer" <? echo $home ?> ><img src='../grafico/esquema/tabs/home.png'></div></tr></td>
	<tr><td style='border: 1px solid ;'><div  style="cursor:pointer" <? echo $help ?> ><img src='../grafico/esquema/tabs/kblackbox.png'></div></tr></td>	
	<tr><td style='border: 1px solid ;'><div  style="cursor:pointer" onClick="javascript:popUpWindow( '../generales/frm_contacto.php?pemail=computo@udistrital.edu.co','yes', 90, 40, 500, 620)"><img src='../grafico/esquema/tabs/kopeteavailable.png'></div></tr></td>		
	<!--tr><td style='border: 1px solid ;'><div  style="cursor:pointer" <? echo $home ?> ><img src='../grafico/esquema/tabs/user 1.png'></div></td></tr-->	
	<tr><td style='border: 1px solid ;'><div  style="cursor:pointer" onclick="window.open('http://udistrital.edu.co/portal/dependencias/administrativas/tipica.php?id=10')"><img src='../grafico/esquema/tabs/OAS.png'></div></tr></td>			
	</tr>					
</table>
</center>
	</body>
  
</html>


