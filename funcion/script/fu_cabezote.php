<?PHP ?>
<HEAD>
<script language="JavaScript">
var popUpWin=0;
function popUpWindow(URLStr, yes, left, top, width, height){
  if(popUpWin){
     if(!popUpWin.closed)
	    popUpWin.close();
  }
  popUpWin = open(URLStr, 'popUpWin', 'resizable=no,scrollbars='+yes+',menubar=no,toolbar=no,status=no,location=no,titlebar=no,directories=no", width='+width+',height='+height+',left='+left+', top='+top+',screenX='+left+',screenY='+top+'');
}
</script>
<!-- <link href="estilo.css" rel="stylesheet" type="text/css"> -->
</HEAD>
<?PHP
$EstiloTab='style="border-style:solid;border-width:0.9" bordercolor="#E6E6DE"';
function fu_cabezote($titulo){
	//$cal = "javascript:popUpWindow('../script/calendario.php', 'no', 800, 260, 150, 150)";
	$cal = "javascript:popUpWindow('../generales/cont_cal.php', 'no', 730, 150, 252, 216)";
		
	if($titulo == "CAPTURA DE NOTAS PARCIALES")
	   $ayuda = "javascript:popUpWindow('ay_obs_notaspar.php', 'yes', 100, 100, 710, 310)";
	
	elseif($titulo == "NOTAS PARCIALES")
			$ayuda = "javascript:popUpWindow('ay_notaspar.php', 'yes', 100, 100, 600, 300)";
			
	elseif($titulo == "CARGA ACADÉMICA")
			$ayuda = "javascript:popUpWindow('ay_carga.php', 'yes', 100, 100, 600, 300)";
	
	elseif($titulo == "CURSOS")
			$ayuda = "javascript:popUpWindow('ay_cursos.php', 'yes', 100, 100, 600, 300)";
	
	elseif($titulo == "HORARIO")
			$ayuda = "javascript:popUpWindow('ay_horario.php', 'yes', 100, 100, 600, 300)";
	
	elseif($titulo == "CAMBIAR MI CLAVE")
			$ayuda = "javascript:popUpWindow('ay_clave.php', 'yes', 100, 100, 600, 300)";
			
	elseif($titulo == "ACTUALIZACIÓN DE DATOS")
			$ayuda = "javascript:popUpWindow('ay_update_dat.php', 'yes', 100, 100, 600, 300)";
	
	elseif($titulo == "REGISTRO DE ASIGNATURAS")
			$ayuda = "javascript:popUpWindow('ay_asi_ins.php', 'yes', 100, 100, 600, 300)";
	
	elseif($titulo == "PLAN DE ESTUDIO")
			$ayuda = "javascript:popUpWindow('ay_semaforo.php', 'yes', 100, 100, 600, 300)";
	
	elseif($titulo == "HISTÓRICO DE NOTAS")
			$ayuda = "javascript:popUpWindow('ay_notas.php', 'yes', 100, 100, 600, 300)";
			
	elseif($titulo == "OBSERVACIONES DE LA EVALUACIÓN DOCENTE")
			$ayuda = "javascript:popUpWindow('ay_observaciones.php', 'yes', 100, 100, 600, 300)";
	
	elseif($titulo == "LISTAS DE CLASE Y CAPTURA DE NOTAS")
			$ayuda = "javascript:popUpWindow('ay_lis_clase.php', 'yes', 100, 100, 600, 300)";
			
	elseif($titulo == "CONTÁCTENOS")
			$ayuda = "javascript:popUpWindow('ay_contacto.php', 'yes', 100, 100, 600, 300)";
			
	elseif($titulo == "CONTACTAR AL DOCENTE")
			$ayuda = "javascript:popUpWindow('ay_email_doc.php', 'yes', 100, 100, 600, 300)";
	
	elseif($titulo == "INSCRIPCIÓN DE ASIGNATURAS")
			$ayuda = "javascript:popUpWindow('ay_inscripcion.php', 'yes', 100, 100, 600, 300)";
			
	elseif($titulo == "ADICIONAR Y CANCELAR ASIGNATURAS")
			$ayuda = "javascript:popUpWindow('ay_add_can.php', 'yes', 100, 100, 600, 300)";
			
	elseif($titulo == "ADICIÓN DE ASIGNATURAS")
			$ayuda = "javascript:popUpWindow('ay_adicion.php', 'yes', 100, 100, 600, 300)";
	
	elseif($titulo == "LISTA DE CLASE")
			$ayuda = "javascript:popUpWindow('ay_lisclase.php', 'yes', 100, 100, 600, 300)";
			
	elseif($titulo == "PLAN DE TRABAJO")
			$ayuda = "javascript:popUpWindow('ay_ges_actividad.php', 'yes', 100, 100, 600, 300)";
			
	elseif($titulo == "DIGITAR ACTIVIDAD")
			$ayuda = "javascript:popUpWindow('ay_dig_actividad.php', 'yes', 100, 100, 600, 300)";
			
	elseif($titulo == "CONTROL CIERRE DE SEMESTRE")
			$ayuda = "javascript:popUpWindow('ay_control_cierre.php', 'yes', 100, 100, 600, 300)";
	
	elseif($titulo == "CONSULTA DE HORARIOS")
			$ayuda = "javascript:popUpWindow('ay_conhor.php', 'yes', 100, 100, 600, 300)";

	elseif($titulo == "ADMINISTRACIÓN DE NOTICIAS")
			$ayuda = "javascript:popUpWindow('ay_con_msg.php', 'yes', 100, 100, 600, 300)";
			
	elseif($titulo == "ENVIO DE CORREOS EN GRUPO")
			$ayuda = "javascript:popUpWindow('ay_correogr.php', 'yes', 100, 100, 600, 300)";
			
	elseif($titulo == "ENVIO DE CORREOS A DOCENTES")
			$ayuda = "javascript:popUpWindow('ay_correogr.php', 'yes', 100, 100, 600, 300)";
			
	elseif($titulo == "CONSULTA DESPRENDIBLES DE PAGO")
			$ayuda = "javascript:popUpWindow('ay_desprendible.php', 'yes', 100, 100, 600, 300)";
	
	elseif($titulo == "GESTIÓN DE USUARIOS")
			$ayuda = "javascript:popUpWindow('ay_gestion.php', 'yes', 100, 100, 600, 300)";
			
	elseif($titulo == "CREACIÓN DE USUARIOS")
			$ayuda = "javascript:popUpWindow('ay_creausu.php', 'yes', 100, 100, 600, 300)";
	
	elseif($titulo == "CAMBIO DE CLAVES")
			$ayuda = "javascript:popUpWindow('ay_cambioclave.php', 'yes', 100, 100, 600, 300)";
	
	elseif($titulo == "ADMINISTRACIÓN DE DECANOS")
			$ayuda = "javascript:popUpWindow('ay_admdec.php', 'yes', 100, 100, 600, 300)";
			
	elseif($titulo == "FACULTADES")
			$ayuda = "javascript:popUpWindow('ay_admcoor.php', 'yes', 100, 100, 600, 300)";
	
	elseif($titulo == "ADMINISTRACIÓN DE DOCENTES")
			$ayuda = "javascript:popUpWindow('ay_admdoc.php', 'yes', 100, 100, 600, 300)";
			
	elseif($titulo == "CONSULTA DE DOCENTES")
			$ayuda = "javascript:popUpWindow('ay_condoc.php', 'yes', 100, 100, 600, 300)";
			
	elseif($titulo == "CONSULTA DE ESTUDIANTES")
			$ayuda = "javascript:popUpWindow('ay_conest.php', 'yes', 100, 100, 600, 300)";
			
	elseif($titulo == "-PROYECTOS CURRICULARES-")
			$ayuda = "javascript:popUpWindow('ay_procu.php', 'yes', 100, 100, 600, 300)";
			
	elseif($titulo == "USUARIO")
			$ayuda = "javascript:popUpWindow('ay_usuario.php', 'yes', 100, 100, 600, 300)";
	
	elseif($titulo == "FECHAS DE NOTAS PARCIALES")
			$ayuda = "javascript:popUpWindow('ay_fec_notaspar.php', 'yes', 100, 100, 600, 300)";
			
	elseif($titulo == "HORARIOS")
			$ayuda = "javascript:popUpWindow('ay_horarios.php', 'yes', 100, 100, 600, 300)";
			
	elseif($titulo == "CONTACTAR DOCENTES")
			$ayuda = "javascript:popUpWindow('ay_correo_doc.php', 'yes', 100, 100, 600, 300)";
	
	elseif($titulo == "CORRECCIÓN DE DOCUMENTO DE IDENTIDAD")
			$ayuda = "javascript:popUpWindow('ay_cornom.php', 'yes', 100, 100, 600, 300)";
	
	elseif($titulo == "CONTROL DE DIGITACIÓN DE NOTAS")
			$ayuda = "javascript:popUpWindow('ay_dignot.php', 'yes', 100, 100, 600, 300)";
			
	elseif($titulo == "ASPIRANTES POR AÑO Y PERÍODO")
			$ayuda = "javascript:popUpWindow('ay_asp_anoper.php', 'yes', 100, 100, 600, 300)";
			
	elseif($titulo == "ADMITIDOS POR AÑO Y PERÍODO")
			$ayuda = "javascript:popUpWindow('ay_adm_anoper.php', 'yes', 100, 100, 600, 300)";
			
	elseif($titulo == "CODIFICADOS POR AÑO Y PERÍODO")
			$ayuda = "javascript:popUpWindow('ay_codif_anoper.php', 'yes', 100, 100, 600, 300)";
			
	elseif($titulo == "LISTADO DE CURSOS PROGRAMADOS")
			$ayuda = "javascript:popUpWindow('ay_lis_asi.php', 'yes', 100, 100, 600, 300)";
			
	elseif($titulo == "ESTUDIANTES ACTIVOS")
			$ayuda = "javascript:popUpWindow('ay_est_act.php', 'yes', 100, 100, 600, 300)";
			
	elseif($titulo == "PERFILES DE USUARIO")
			$ayuda = "javascript:popUpWindow('ay_perfil_usuario.php', 'yes', 100, 100, 600, 300)";
			
	elseif($titulo == "CONTROL DIGITACIÓN PLAN DE TRABAJO")
			$ayuda = "javascript:popUpWindow('ay_ctrl_pt.php', 'yes', 100, 100, 600, 300)";
			
	elseif($titulo == "PENSUM")
			$ayuda = "javascript:popUpWindow('ay_pensum.php', 'yes', 100, 100, 600, 300)";
			
	elseif($titulo == "CARGAR REPORTE DE PAGOS")
			$ayuda = "javascript:popUpWindow('ay_rep_pagos.php', 'yes', 100, 100, 600, 300)";
	
	elseif($titulo == "CARGAR TABLA DE PAGOS")
			$ayuda = "javascript:popUpWindow('ay_tab_pagos.php', 'yes', 100, 100, 600, 300)";
			
	elseif($titulo == "ASÍ VA EL PROCESO DE ADMISIONES")
			$ayuda = "javascript:popUpWindow('ay_admisiones.php', 'yes', 100, 100, 600, 300)";
			
	elseif($titulo == "CONSULTA Y MODIFICACIÓN DE SNP")
			$ayuda = "javascript:popUpWindow('ay_upd_snp.php', 'yes', 100, 100, 600, 300)";
			
	elseif($titulo == "POBLACIÓN DE ASPIRANTES POR ESTRATO")
			$ayuda = "javascript:popUpWindow('ay_asestrato.php', 'yes', 100, 100, 600, 300)";
			
	elseif($titulo == "POBLACIÓN DE ASPIRANTES POR SEXO")
			$ayuda = "javascript:popUpWindow('ay_assexo.php', 'yes', 100, 100, 600, 300)";
			
	elseif($titulo == "POBLACIÓN DE ASPIRANTES POR LOCALIDAD")
			$ayuda = "javascript:popUpWindow('ay_aslocalidad.php', 'yes', 100, 100, 600, 300)";
			
	elseif($titulo == "POBLACIÓN DE ADMITIDOS POR ESTRATO")
			$ayuda = "javascript:popUpWindow('ay_adestrato.php', 'yes', 100, 100, 600, 300)";
			
	elseif($titulo == "POBLACIÓN DE ADMITIDOS POR SEXO")
			$ayuda = "javascript:popUpWindow('ay_adsexo.php', 'yes', 100, 100, 600, 300)";
			
	elseif($titulo == "POBLACIÓN DE ADMITIDOS POR LOCALIDAD")
			$ayuda = "javascript:popUpWindow('ay_adlocalidad.php', 'yes', 100, 100, 600, 300)";
	
	elseif($titulo == "POBLACIÓN DE ESTUDIANTES ACTIVOS POR ESTRATO")
			$ayuda = "javascript:popUpWindow('ay_acestrato.php', 'yes', 100, 100, 600, 300)";
			
	elseif($titulo == "POBLACIÓN DE ESTUDIANTES ACTIVOS POR SEXO")
			$ayuda = "javascript:popUpWindow('ay_acsexo.php', 'yes', 100, 100, 600, 300)";

	else
		$ayuda = "javascript:popUpWindow('ayuda.php', 'yes', 100, 100, 600, 300)";
		
echo'<table align="center" border="0" width="100%">
<tr style="background-image:url(../img/td.gif)">
	<td width="15%" align="right"><input type="button" value="   Ayuda   " onClick="'.$ayuda.'" class="button"></td>
    <td width="70%" align="center" class="estilo6">'.$titulo.'</td>
    <td width="15%" align="left"><input type="button" value="Calendario" onClick="'.$cal.'" class="button"></td>
</tr></table>';
}
?>