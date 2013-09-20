<?php 
/*
############################################################################
#    UNIVERSIDAD DISTRITAL Francisco Jose de Caldas                        #
#    Copyright: Vea el archivo EULA.txt que viene con la distribucion      #
############################################################################
*/

if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;
}

include_once("funcion.class.php");
//include_once("sql.class.php");

class html_adminNominaOrdenador extends funciones_adminNominaOrdenador {
    
    public $configuracion;
    public $cripto;
    public $indice;
    		
    function __construct($configuracion) {
        
        $this->configuracion = $configuracion;   
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
        
        $indice=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
        $this->cripto=new encriptar();
        $this->indice=$configuracion["host"].$configuracion["site"]."/index.php?";
        $this->html = new html();
                              
    }    

    
    
    /**
     * Funcion que muestra los datos de las solicitudes de cumplido realizadas
     * @param <array> $configuracion
     * @param <array> $registro
     * @param int $total
     * @param <array> $variable 
     */
    function multiplesNominas($configuracion,$registro,$sql,$accesoSIC)
	{
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
		$indice = $configuracion["host"].$configuracion["site"]."/index.php?";
		$cripto = new encriptar();
		if((isset($registro[0]['VIGENCIA'])?$registro[0]['VIGENCIA']:0)>0){
                    $vigencia = "Vigencia ".$registro[0]['VIGENCIA'];
                }else{
                    $vigencia = "";
                }
		?><table width="80%" align="center" border="0" cellpadding="10" cellspacing="0" >
			<tbody>
				<tr>
					<td >
						<table class="bordered" width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
							<tr class="texto_subtitulo">
								<th>Nomina
								</th>
							</tr>
							<tr>
								<td>
									<table class="bordered"  width="100%" >
										<tr class='cuadro_color'>
                                                                                        <th width="5%">Código</th>
											<th width="10%">Rubro</th>
											<th width="10%">Año</th>
                                                                                        <th width="10%">Mes</th>
                                                                                        <th width="15%">Fecha de registro</th>
                                                                                        <th width="20%">Supervisor</th>
                                                                                        <th width="20%">Ordenador del gasto</th>
                                                                                        <th width="10%">Estado</th>
                                                                                        
										</tr><?
                                                                                
                                                         foreach ($registro as $key => $value)
                                                                {       //busca datos del rubro
                                                                        $rubro=array('interno'=>$registro[$key]['nom_rubro_interno'],
                                                                                     'vigencia'=>$registro[$key]['nom_anio']   );
                                                                        $rubro_sql = $sql->cadena_sql($this->configuracion,"","datos_rubro",$rubro);
                                                                        $resultadoRB = $this->ejecutarSQL($this->configuracion, $accesoSIC, $rubro_sql, "busqueda");
                                                                        //busca datos del ordenador del gasto
                                                                        $ordenador_sql = $sql->cadena_sql($this->configuracion,"","datos_ordenador",$registro[$key]['nom_num_id_ordenador']);
                                                                        $resultadoORD = $this->ejecutarSQL($this->configuracion, $accesoSIC, $ordenador_sql, "busqueda");
                                                                        //busca datos de supervisor del contrato
                                                                        $dependencia_sql = $sql->cadena_sql($this->configuracion,"","nombre_dependencia",$registro[$key]['nom_cod_dep_supervisor']);
                                                                        $resultadoDEP = $this->ejecutarSQL($this->configuracion, $accesoSIC, $dependencia_sql, "busqueda");
                                                                        
                                                        		$id = (isset($registro[$key]['nom_id'])?$registro[$key]['nom_id']:'');
                                                                        $rubro = (isset($resultadoRB[0]['NOM_RUBRO'])? $resultadoRB[0]['NOM_RUBRO']:'');
									$anio= (isset($registro[$key]['nom_anio'])?$registro[$key]['nom_anio']:'');
                                                                        $mes = (isset($registro[$key]['nom_mes'])?$this->nombreMes($registro[$key]['nom_mes']):'');
                                                                        $fecha_registro= (isset($registro[$key]['nom_fecha_registro'])?$registro[$key]['nom_fecha_registro']:'');
                                                                        $supervisor= (isset($resultadoDEP[0]['NOMBRE_DEPENDENCIA'])?$resultadoDEP[0]['NOMBRE_DEPENDENCIA']:'');
                                                                        $ordenador= (isset($resultadoORD[0]['NUMERO_DOCUMENTO'])?$resultadoORD[0]['NUMERO_DOCUMENTO']." - ".$resultadoORD[0]['NOMBRES_JEFE']." ".$resultadoORD[0]['PRIMER_APELLIDO']." ".$resultadoORD[0]['SEGUNDO_APELLIDO']:'');
                                                                        $estado= (isset($registro[$key]['nom_estado'])?$registro[$key]['nom_estado']:'');
                                                                        //var_dump($registro);exit;
                                                                        if($estado=='APROBADO'){
                                                                            $enlace_cumplido= $this->generarEnlaceCumplido($id,$cedula,$datos_documento);
                                                                            $periodo_pago=$finicio_per." a ".$ffinal_per;
                                                                        }else{
                                                                            $enlace_cumplido= "";
                                                                            $periodo_pago=$anio." - ".$mes;
                                                                        }
									$parametro = "pagina=nom_adminNominaOrdenador";
									$parametro .= "&hoja=1";
									$parametro .= "&opcion=consultarDetalles";
									$parametro .= "&accion=consultar";
									$parametro .= "&id_nomina=".$id;
									$parametro = $cripto->codificar_url($parametro,$this->configuracion);
                                                                        
									
									echo "	<tr> 
										 	<td class='texto_elegante estilo_td'><a href='".$indice.$parametro."'>".$id."</a></td>
                                                                                        <td class='texto_elegante estilo_td'>".$rubro."</td>    
											<td class='texto_elegante estilo_td'>".$anio."</td>    
                                                                                        <td class='texto_elegante estilo_td'>".$mes."</td>    
                                                                                        <td class='texto_elegante estilo_td'>".$fecha_registro."</td>    
                                                                                        <td class='texto_elegante estilo_td'>".$supervisor."</td>    
                                                                                        <td class='texto_elegante estilo_td'>".$ordenador."</td>    
                                                                                        <td class='texto_elegante estilo_td'>".$estado."</td>    
                                                                                        
										</tr>";
					
								}//fin for 
								?>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				
			</tbody>
		</table>
		<?
	}//fin funcion multiples cumplidos
        
    
        /**
         * funcion que muestra la información del reporte
         */
        
        function mostrarReportes($configuracion,$registro,$nombre,$titulo)
        {   
            include_once($this->configuracion["raiz_documento"] . $this->configuracion["bloques"]."/nomina/contratistas/nom_admin_nomina_ordenador". $this->configuracion["clases"] . "/reporteadorHtml.class.php");
            $reporte = new reporteador();
            $reporte->mostrarReporte($configuracion,$registro,$nombre,$titulo);
        }
        

        /**
         * funcion que muestra la información del reporte
         */
        
        function sinDatos($configuracion,$titulo)
        {   include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php"); 
            $cadena=".::".$titulo."::."; 
            $cadena=htmlentities($cadena, ENT_COMPAT, "UTF-8");
            alerta::sin_registro($configuracion,$cadena);
        }        
        
            /**
     * Funcion que muestra el formulario para revisar las solicitudes de pago de nomina
     * @param <array> $configuracion
     * @param <array> $registro
     * @param int $total
     * @param <array> $variable 
     */
    function form_revisar_solicitud($configuracion,$registro)
	{        
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
		$indice = $configuracion["host"].$configuracion["site"]."/index.php?";
		$cripto = new encriptar();
                $tab=0;
                $total_registros = count($registro);
		?>
                <link rel="stylesheet" href="<? echo $configuracion["host"].$configuracion["site"].$configuracion["plugins"];?>/jPages-master/css/jPages.css">

                        <script type="text/javascript" src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["plugins"];?>/jPages-master/js/jquery-1.8.2.min.js"></script>
                        <script src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["plugins"];?>/jPages-master/js/jPages.js"></script>
                        <!-- Script para paginar el listado -->
                        <script>
                            
                        $(function (){
                            $("div.holder").jPages({
                            containerID : "contratistas",
                            previous : "←",
                            next : "→",
                            perPage : <? echo $configuracion["registro"]?>,
                            delay : 20
                            });
                        });
                        </script>
                        <!-- Script para seleccionar todos los ckechbox-->
                        <script type="text/javascript">
                            $(document).ready(function(){

                                    //Checkbox
                                    $("input[name=checktodos]").change(function(){
                                            $('input[type=checkbox]').each( function() {			
                                                    if($("input[name=checktodos]:checked").length == 1){
                                                            this.checked = true;
                                                    } else {
                                                            this.checked = false;
                                                    }
                                            });
                                    });

                            });
                            </script>
                <?
                
                $this->formulario = "nom_adminCumplidoSupervisor"; ?>
                <form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario;?>'>
                           
                        
                <table width="80%" align="center" border="0" cellpadding="10" cellspacing="0" >
			<tbody>
				<tr>
					<td >
						<table class="bordered" width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
							<tr class="texto_subtitulo">
								<th >Pagos de nomina solicitados 
								</th>
							</tr>
							<tr>
								<td>
                                                                   
									<table class="bordered"  width="100%" >
										<tr class='cuadro_color'>
                                                                                        <th width="5%">Dependencia</th>
											<th width="5%">Vigencia</th>
											<th width="5%">N&uacute;mero de Contrato </th>
                                                                                        <th width="5%">Contratista </th>
                                                                                        <th width="5%">Saldo contrato antes de pago </th>
                                                                                        <th width="5%">Fecha inicio Pago </th>
                                                                                        <th width="5%">Fecha finalizaci&oacute;n Pago </th>
                                                                                        <th width="5%">Cantidad d&iacute;as Pago </th>
                                                                                        <th width="5%">Valor Pago antes de Iva($)</th>
                                                                                        <th width="5%">AFC ($)</th>
                                                                                        <th width="5%">Cooperativas o depósitos ($)</th>
                                                                                        <th width="5%">Salud ($)</th>
                                                                                        <th width="5%">Pensi&oacute;n ($)</th>
                                                                                        <th width="5%">ARP ($)</th>
                                                                                        <th width="5%">Base Retefuente  ($)</th>
                                                                                        <th width="5%">Retefuente 099/13 ($)</th>
                                                                                        <th width="5%">Retefuente IMAN ($)</th>
                                                                                        <th width="5%">Retefuente  ($)</th>
                                                                                        <th width="5%">IVA ($)</th>
                                                                                        <th width="5%">RETEIVA ($)</th>
                                                                                        <th width="5%">Base ICA y estampillas($)</th>
                                                                                        <th width="5%">ICA ($)</th>
                                                                                        <th width="5%">Estampilla UD ($)</th>
                                                                                        <th width="5%">Estampilla Procultura ($)</th>
                                                                                        <th width="5%">Estampilla Pro-adultomayor($)</th>
                                                                                        <th><input name="checktodos" type="checkbox" /></th>
                                                                                        
										</tr>
                                                                                <tbody id="contratistas">
                                                                                    <?
                                                                                        if(is_array($registro )){
                                                                                            foreach ($registro as $key => $value)
                                                                                                {        
                                                                                                        $id = (isset($registro[$key]['id'])?$registro[$key]['id']:'');
                                                                                                        $detalle_id=(isset($registro[$key]['detalle_id'])?$registro[$key]['detalle_id']:'');
                                                                                                        $nombre_dependencia = (isset($registro[$key]['nombre_dependencia'])?$registro[$key]['nombre_dependencia']:'');
                                                                                                        $vigencia = (isset($registro[$key]['vigencia'])?$registro[$key]['vigencia']:'');
                                                                                                        $num_contrato = (isset($registro[$key]['num_contrato'])?$registro[$key]['num_contrato']:'');
                                                                                                        $tipo_id = (isset($registro[$key]['tipo_id'])?$registro[$key]['tipo_id']:'');
                                                                                                        $num_id = (isset($registro[$key]['num_id'])?$registro[$key]['num_id']:'');
                                                                                                        $fecha_inicio_per = (isset($registro[$key]['fecha_inicio_per'])?$registro[$key]['fecha_inicio_per']:'');
                                                                                                        $fecha_final_per = (isset($registro[$key]['fecha_final_per'])?$registro[$key]['fecha_final_per']:'');
                                                                                                        $num_dias= (isset($registro[$key]['num_dias'])?$registro[$key]['num_dias']:'');
                                                                                                        $valor_antes_iva = floatval(isset($registro[$key]['valor_antes_iva'])?$registro[$key]['valor_antes_iva']:'');
                                                                                                        $valor_afc= floatval(isset($registro[$key]['afc'])?$registro[$key]['afc']:'');
                                                                                                        $valor_coop_depositos= floatval(isset($registro[$key]['coop_depositos'])?$registro[$key]['coop_depositos']:'');
                                                                                                        $valor_salud =  floatval(isset($registro[$key]['salud'])?$registro[$key]['salud']:'');
                                                                                                        $valor_pension =  floatval(isset($registro[$key]['pension'])?$registro[$key]['pension']:'');
                                                                                                        $valor_arp =  floatval(isset($registro[$key]['arp'])?$registro[$key]['arp']:'');
                                                                                                        $valor_base_retefuente =  floatval(isset($registro[$key]['base_retefuente'])?$registro[$key]['base_retefuente']:'');
                                                                                                        $valor_retefuente_099 =  floatval(isset($registro[$key]['retefuente_099'])?$registro[$key]['retefuente_099']:'');
                                                                                                        $valor_retefuente_iman =  floatval(isset($registro[$key]['retefuente_iman'])?$registro[$key]['retefuente_iman']:'');
                                                                                                        $valor_retefuente =  floatval(isset($registro[$key]['retefuente'])?$registro[$key]['retefuente']:'');
                                                                                                        $valor_iva =  floatval(isset($registro[$key]['iva'])?$registro[$key]['iva']:'');
                                                                                                        $valor_reteiva =  floatval(isset($registro[$key]['reteiva'])?$registro[$key]['reteiva']:'');
                                                                                                        $valor_base_ica_estampillas =  floatval(isset($registro[$key]['base_ica_estampillas'])?$registro[$key]['base_ica_estampillas']:'');
                                                                                                        $valor_ica =  floatval(isset($registro[$key]['ica'])?$registro[$key]['ica']:'');
                                                                                                        $valor_estampilla_ud =  floatval(isset($registro[$key]['estampilla_ud'])?$registro[$key]['estampilla_ud']:'');
                                                                                                        $valor_estampilla_procultura =  floatval(isset($registro[$key]['estampilla_procultura'])?$registro[$key]['estampilla_procultura']:'');
                                                                                                        $valor_estampilla_proadultomayor =  floatval(isset($registro[$key]['estampilla_proadultomayor'])?$registro[$key]['estampilla_proadultomayor']:'');
                                                                                                        $valor_saldo_antes_pago=  floatval(isset($registro[$key]['saldo_antes_pago'])?$registro[$key]['saldo_antes_pago']:'');
                                                                                                        
                                                                                                        echo "	<tr> 
                                                                                                                        <td class='texto_elegante estilo_td'>".$nombre_dependencia."</td>    
                                                                                                                        <td class='texto_elegante estilo_td'>".$vigencia."</td>    
                                                                                                                        <td class='texto_elegante estilo_td'>".$num_contrato."</td>    
                                                                                                                        <td class='texto_elegante estilo_td'>".$num_id."</td>   
                                                                                                                        <td class='texto_elegante estilo_td'>".number_format($valor_saldo_antes_pago, 2)."</td>   
                                                                                                                        <td class='texto_elegante estilo_td'>".$fecha_inicio_per."</td>    
                                                                                                                        <td class='texto_elegante estilo_td'>".$fecha_final_per."</td>   
                                                                                                                        <td class='texto_elegante estilo_td'>".$num_dias."</td>   
                                                                                                                        <td class='texto_elegante_der estilo_td'>".number_format($valor_antes_iva, 2)."</td>    
                                                                                                                        <td class='texto_elegante_der estilo_td'>".number_format($valor_afc, 2)."</td>   
                                                                                                                        <td class='texto_elegante_der estilo_td'>".number_format($valor_coop_depositos, 2)."</td>   
                                                                                                                        <td class='texto_elegante_der estilo_td'>".number_format($valor_salud, 2)."</td>   
                                                                                                                        <td class='texto_elegante_der estilo_td'>".number_format($valor_pension, 2)."</td>   
                                                                                                                        <td class='texto_elegante_der estilo_td'>".number_format($valor_arp, 2)."</td>   
                                                                                                                        <td class='texto_elegante_der estilo_td'>".number_format($valor_base_retefuente, 2)."</td>   
                                                                                                                        <td class='texto_elegante_der estilo_td'>".number_format($valor_retefuente_099, 2)."</td>   
                                                                                                                        <td class='texto_elegante_der estilo_td'>".number_format($valor_retefuente_iman, 2)."</td>   
                                                                                                                        <td class='texto_elegante_der estilo_td'>".number_format($valor_retefuente, 2)."</td>   
                                                                                                                        <td class='texto_elegante_der estilo_td'>".number_format($valor_iva, 2)."</td>   
                                                                                                                        <td class='texto_elegante_der estilo_td'>".number_format($valor_reteiva, 2)."</td>   
                                                                                                                        <td class='texto_elegante_der estilo_td'>".number_format($valor_base_ica_estampillas, 2)."</td>   
                                                                                                                        <td class='texto_elegante_der estilo_td'>".number_format($valor_ica, 2)."</td>   
                                                                                                                        <td class='texto_elegante_der estilo_td'>".number_format($valor_estampilla_ud, 2)."</td>   
                                                                                                                        <td class='texto_elegante_der estilo_td'>".number_format($valor_estampilla_procultura, 2)."</td>   
                                                                                                                        <td class='texto_elegante_der estilo_td'>".number_format($valor_estampilla_proadultomayor, 2)."</td>   
                                                                                                                        <td class='texto_elegante_centrado estilo_td'><input value='".$detalle_id."' name='id_solicitud_".$key."' type='checkbox' /></td>    
                                                                                                                        <input type='hidden' name='vigencia_".$key."' value='".$vigencia."'>
                                                                                                                        <input type='hidden' name='valor_base_retefuente_".$key."' value='".$valor_base_retefuente."'>
                                                                                                                        <input type='hidden' name='valor_retefuente_".$key."' value='".$valor_retefuente."'>
                                                                                                                        <input type='hidden' name='valor_retefuente_099_".$key."' value='".$valor_retefuente_099."'>
                                                                                                                        <input type='hidden' name='valor_retefuente_iman_".$key."' value='".$valor_retefuente_iman."'>
                                                                                                                        <input type='hidden' name='valor_iva_".$key."' value='".$valor_iva."'>
                                                                                                                        <input type='hidden' name='valor_reteiva_".$key."' value='".$valor_reteiva."'>
                                                                                                                        <input type='hidden' name='valor_base_ica_estampillas_".$key."' value='".$valor_base_ica_estampillas."'>
                                                                                                                        <input type='hidden' name='valor_ica_".$key."' value='".$valor_ica."'>
                                                                                                                        <input type='hidden' name='valor_estampilla_ud_".$key."' value='".$valor_estampilla_ud."'>
                                                                                                                        <input type='hidden' name='valor_estampilla_procultura_".$key."' value='".$valor_estampilla_procultura."'>
                                                                                                                        <input type='hidden' name='valor_estampilla_proadultomayor_".$key."' value='".$valor_estampilla_proadultomayor."'>
                                                                                                                        
                                                                                                                </tr>";

                                                                                                }//fin for 
                                                                                        }else{
                                                                                            echo "<tr><td colspan=6>No hay registros de solicitudes por aprobar</td></tr>";
                                                                                        }
                                                                                    ?>      
                                                                                </tbody>
                                                                                    
									</table>
                                                                    <center><div class="holder"></div></center>
								</td>
							</tr>
                                                        <?
                                                            if(is_array($registro )){
                                                        ?>
                                                        <tr align='center'>
                                                                <td colspan='2' rowspan='1'>
                                                                    <br>
                                                                        <input type='hidden' name='pagina' value='nom_adminNominaOrdenador'>
                                                                        <input type='hidden' name='opcion' value='aprobar_nomina'>
                                                                        <input type='hidden' name='total_registros' value='<? echo $total_registros;?>'>
                                                                        <input value="Aprobar" name="aceptar" tabindex="<?= $tab++ ?>" type="button" onclick="document.forms['<? echo $this->formulario?>'].submit()">
                                                                </td>
                                                        </tr>
                                                        <?
                                                            }
                                                        ?>
						</table>
                                            
					</td>
				</tr>
				
			</tbody>
		</table>
		
                </form>
                
                        <?php
                        
                     
		
	}//fin funcion 

    /**************///////
    
        /**
         * Funcion que muestra los dayos del contrato
         * @param <array> $datos_contrato
         * @param String $tipo_contrato
         * @param date $fecha_contrato 
         */
        function mostrarDatosContrato($datos_contrato,$tipo_contrato,$fecha_contrato){
            $unidad_ejecutora=(isset($datos_contrato[0]['CODIGO_UNIDAD_EJECUTORA'])?$datos_contrato[0]['CODIGO_UNIDAD_EJECUTORA']:'');
//            $tipo_identificacion=(isset($datos_contrato[0]['TIPO_IDENTIFICACION'])?$datos_contrato[0]['TIPO_IDENTIFICACION']:'');
//            $identificacion=(isset($datos_contrato[0]['NUM_IDENTIFICACION'])?$datos_contrato[0]['NUM_IDENTIFICACION']:'');
//            $nombre=(isset($datos_contrato[0]['RAZON_SOCIAL'])?$datos_contrato[0]['RAZON_SOCIAL']:'');
            $num_contrato=(isset($datos_contrato[0]['NUM_CONTRATO'])?$datos_contrato[0]['NUM_CONTRATO']:'');
            $fecha_inicio=(isset($datos_contrato[0]['FECHA_INICIO'])?$datos_contrato[0]['FECHA_INICIO']:'');
            $fecha_fin=(isset($datos_contrato[0]['FECHA_FINAL'])?$datos_contrato[0]['FECHA_FINAL']:'');
            $valor_contrato=(isset($datos_contrato[0]['CUANTIA'])?$datos_contrato[0]['CUANTIA']:'');
            $duracion = (isset($datos_contrato[0]['PLAZO_EJECUCION'])?$datos_contrato[0]['PLAZO_EJECUCION']:'');
            $objeto= (isset($datos_contrato[0]['OBJETO'])?$datos_contrato[0]['OBJETO']:'');
            $tipo_contrato= (isset($tipo_contrato)?$tipo_contrato:'');
            $estilo_finicio = "";
            $estilo_ffinal = "";
            $estilo_num_contrato = "";
             
            if(!$fecha_inicio){
                $estilo_finicio = "_importante";
            }
            if(!$fecha_fin){
                $estilo_ffinal = "_importante";
            }
            if(!$num_contrato){
                $estilo_num_contrato = "_importante";
            }
            
        ?>
    
            <table class='bordered'>
                    <tr>
                        <th colspan="6" class="estilo_th">DATOS CONTRATO</th>
                    </tr>
                    <tr>
                            <td class='texto_elegante<? echo $estilo_num_contrato;?> estilo_td' >N&uacute;mero del contrato:</td>
                            <td class='texto_elegante estilo_td'><? echo  $num_contrato?></td>
                            <td class='texto_elegante estilo_td' >Tipo de contrato:</td>
                            <td class='texto_elegante estilo_td' colspan="3"><? echo  $tipo_contrato?></td>
                    </tr>
                    <tr>
                            <td class='texto_elegante estilo_td' >Fecha contrato:</td>
                            <td class='texto_elegante estilo_td' ><? echo  $fecha_contrato?></td>
                            <td class='texto_elegante<? echo $estilo_finicio;?> estilo_td' >Fecha inicio del contrato:</td>
                            <td class='texto_elegante estilo_td' ><? echo  $fecha_inicio?></td>
                            <td class='texto_elegante<? echo $estilo_ffinal;?> estilo_td' >Fecha final del contrato :</td>
                            <td class='texto_elegante estilo_td' ><? echo  $fecha_fin?></td>
                    </tr>			
                    <tr>
                            <td class='texto_elegante estilo_td' >Valor del Contrato:</td>
                            <td class='texto_elegante estilo_td'><? echo  "$ ".number_format($valor_contrato)?></td>
                            <td class='texto_elegante estilo_td' >Plazo de ejecuci&oacute;n:</td>
                            <td class='texto_elegante estilo_td'><? echo  $duracion?></td>
                            <td class='texto_elegante estilo_td' >Unidad Ejecutora:</td>
                            <td class='texto_elegante estilo_td'><? echo  $unidad_ejecutora?></td>
                            
                    </tr>
                    <tr>
                            <td class='texto_elegante estilo_td' >Objeto:</td>
                            <td class='texto_elegante estilo_td' colspan="5"><? echo  $objeto?></td>
                            
                    </tr>	
                    
            </table>
    <?
    }

    /**
     * Funcion que muestra los datos de las cuentas bancarias relacionadas al contratista
     * @param <array> $datos_cuenta 
     */
    function mostrarDatosCuentaBanco($datos_cuenta){
                
                echo "<table class='bordered' width='100%'>";
                echo "    <tr>";
                echo "        <th colspan='3' class='estilo_th'>DATOS CUENTA(S) BANCARIA(S)</th>";
                echo "    </tr>";
                echo "    <tr>";
                echo "        <td class='texto_elegante estilo_td' >Nombre Banco</td>";
                echo "        <td class='texto_elegante estilo_td' >No. cuenta</td>";
                echo "        <td class='texto_elegante estilo_td' >Tipo Cuenta</td>";
                echo "    </tr>";
                
                if(is_array($datos_cuenta)){
                        foreach ($datos_cuenta as $key => $value)
                            {     
                                    $banco=(isset($datos_cuenta[$key]['BANCO'])?$datos_cuenta[$key]['BANCO']:'');
                                    $tipo_cta=(isset($datos_cuenta[$key]['TIPO_CTA'])?$datos_cuenta[$key]['TIPO_CTA']:'');
                                    $num_cta=(isset($datos_cuenta[$key]['NRO_CTA'])?$datos_cuenta[$key]['NRO_CTA']:'');

                                    echo "<tr>";
                                    echo "    <td class='texto_elegante estilo_td'>".$banco."</td>";
                                    echo "    <td class='texto_elegante estilo_td'>".$num_cta."</td>";
                                    echo "    <td class='texto_elegante estilo_td'>".$tipo_cta."</td>";
                                    echo "</tr>";
                        }
                    }else{
                            echo "<tr>";
                            echo "  <td class='texto_elegante estilo_td' colspan='3'> NO EXISTEN DATOS DE CUENTAS RELACIONADAS</td>";
                            echo "</tr>";
                    }
                   echo "</table>";
        
    }

    /**
     * Funcion que muestra los dat os de la disponibilidad relacionada a un contrato
     * @param type $datos_disponibilidad 
     */    
    function mostrarDatosDisponibilidad($datos_disponibilidad){
            $nro_cdp=(isset($datos_disponibilidad[0]['NUMERO_DISPONIBILIDAD'])?$datos_disponibilidad[0]['NUMERO_DISPONIBILIDAD']:'');
            $fecha_cdp=(isset($datos_disponibilidad[0]['FECHA_DISPONIBILIDAD'])?$datos_disponibilidad[0]['FECHA_DISPONIBILIDAD']:'');
            $valor_cdp=(isset($datos_disponibilidad[0]['VALOR'])?$datos_disponibilidad[0]['VALOR']:'');
            		
        ?>
    
            <table class='bordered' width='100%'>
                    <tr>
                        <th colspan="6" class="estilo_th">CERTIFICADO DE DISPONIBILIDAD PRESUPUESTAL</th>
                    </tr>
                    <tr>
                            <td class='texto_elegante estilo_td' >Certificado de Disponibilidad Presupuestal No:</td>
                            <td class='texto_elegante estilo_td' ><? echo  $nro_cdp?></td>
                            <td class='texto_elegante estilo_td' >Fecha:</td>
                            <td class='texto_elegante estilo_td' ><? echo  $fecha_cdp?></td>
                            <td class='texto_elegante estilo_td' >Valor:</td>
                            <td class='texto_elegante estilo_td' ><? echo "$ ".number_format($valor_cdp)?></td>
                    </tr>
                   
            </table>
    <?
    }

    /**
     * Funcion que muestra los datos de los certificados presupuestales
     * @param <array> $datos_registro 
     */
    function mostrarDatosRegistroPresupuestal($datos_registro){
            $nro_crp=(isset($datos_registro[0]['NUMERO_REGISTRO'])?$datos_registro[0]['NUMERO_REGISTRO']:'');
            $fecha_crp=(isset($datos_registro[0]['FECHA_REGISTRO'])?$datos_registro[0]['FECHA_REGISTRO']:'');
            $valor_crp=(isset($datos_registro[0]['VALOR'])?$datos_registro[0]['VALOR']:'');
            		
        ?>
    
            <table class='bordered' width='100%'>
                    <tr>
                        <th colspan="6" class="estilo_th">CERTIFICADO DE REGISTRO PRESUPUESTAL</th>
                    </tr>
                    <tr>
                            <td class='texto_elegante estilo_td' >Certificado de Registro Presupuestal No:</td>
                            <td class='texto_elegante estilo_td' ><? echo  $nro_crp?></td>
                            <td class='texto_elegante estilo_td' >Fecha:</td>
                            <td class='texto_elegante estilo_td' ><? echo  $fecha_crp?></td>
                            <td class='texto_elegante estilo_td' >Valor:</td>
                            <td class='texto_elegante estilo_td' ><? echo "$ ".number_format($valor_crp)?></td>
                    </tr>
                   
            </table>
    <?
    }
    
    /**
     * Funcion para mostrar datos del contratista
     * @param <array> $datos_contratista 
     */   
    function mostrarDatosContratista($datos_contratista){
            $tipo_identificacion=(isset($datos_contratista[0]['TIPO_DOCUMENTO'])?$datos_contratista[0]['TIPO_DOCUMENTO']:'');
            $identificacion=(isset($datos_contratista[0]['NUMERO_DOCUMENTO'])?$datos_contratista[0]['NUMERO_DOCUMENTO']:'');
            $primer_nombre=(isset($datos_contratista[0]['PRIMER_NOMBRE'])?$datos_contratista[0]['PRIMER_NOMBRE']:'');
            $segundo_nombre=(isset($datos_contratista[0]['SEGUNDO_NOMBRE'])?$datos_contratista[0]['SEGUNDO_NOMBRE']:'');
            $primer_apellido=(isset($datos_contratista[0]['PRIMER_APELLIDO'])?$datos_contratista[0]['PRIMER_APELLIDO']:'');
            $segundo_apellido=(isset($datos_contratista[0]['SEGUNDO_APELLIDO'])?$datos_contratista[0]['SEGUNDO_APELLIDO']:'');
            $direccion=(isset($datos_contratista[0]['DIRECCION'])?$datos_contratista[0]['DIRECCION']:'');
            $telefono=(isset($datos_contratista[0]['TELEFONO'])?$datos_contratista[0]['TELEFONO']:'');
            $estilo_num_identificacion = "";
            $estilo_nombre = "";
            $estilo_apellido = "";
           if(!$identificacion || !$tipo_identificacion){
                $estilo_num_identificacion = "_importante";
            }
           
            if(!$primer_nombre){
                $estilo_nombre = "_importante";
            }
            if(!$primer_apellido){
                $estilo_apellido = "_importante";
            }
        ?>
    
            <table class='bordered' width='100%'>
                    
                    <tr>
                        <th colspan="4" class="estilo_th">CONTRATISTA</th>
                    </tr>
                    <tr>
                            <td class='texto_elegante<? echo $estilo_num_identificacion;?> estilo_td' >Identificaci&oacute;n:</td>
                            <td class='texto_elegante estilo_td' colspan="3"><? echo  $tipo_identificacion." No. ".$identificacion?></td>
                            
                    </tr>	
                    <tr>
                            <td class='texto_elegante<? echo $estilo_nombre;?> estilo_td' >Primer Nombre:</td>
                            <td class='texto_elegante estilo_td' ><? echo  $primer_nombre?></td>
                            <td class='texto_elegante estilo_td' >Segundo Nombre:</td>
                            <td class='texto_elegante estilo_td'  ><? echo  $segundo_nombre?></td>
                    </tr>
                    <tr>
                            <td class='texto_elegante<? echo $estilo_apellido;?> estilo_td' >Primer Apellido:</td>
                            <td class='texto_elegante estilo_td' ><? echo  $primer_apellido?></td>
                            <td class='texto_elegante estilo_td' >Segundo Apellido:</td>
                            <td class='texto_elegante estilo_td' ><? echo  $segundo_apellido?></td>
                    </tr>
                    <tr>
                            <td class='texto_elegante estilo_td' >Direcci&oacute;n:</td>
                            <td class='texto_elegante estilo_td'><? echo  $direccion?></td>
                            <td class='texto_elegante estilo_td' >Tel&eacute;fono:</td>
                            <td class='texto_elegante estilo_td'><? echo  $telefono?></td>
                            
                    </tr>			
                    
            </table>
    <?
    }

    /**
     * Funcion que muestra los datos de ordenes de pago
     */   
    function mostrarDatosOrdenPago($datos_orden){
                
                echo "<table class='bordered' width='100%'>";
                echo "    <tr>";
                echo "          <th colspan='3' class='estilo_th'>ORDEN(ES) DE PAGO</th>";
                echo "    </tr>";

                echo "    <tr>";
                echo "        <td class='texto_elegante estilo_td' >No.</td>";
                echo "        <td class='texto_elegante estilo_td' >Fecha</td>";
                echo "        <td class='texto_elegante estilo_td' >Valor</td>";
                echo "    </tr>";
                
                if(is_array($datos_orden)){
                        foreach ($datos_orden as $key => $value)
                            {     
                                    $num_orden=(isset($datos_orden[$key]['NUMERO_ORDEN'])?$datos_orden[$key]['NUMERO_ORDEN']:'');
                                    $fecha=(isset($datos_orden[$key]['FECHA_ORDEN'])?$datos_orden[$key]['FECHA_ORDEN']:'');
                                    $valor=(isset($datos_orden[$key]['VALOR_OP'])?$datos_orden[$key]['VALOR_OP']:'');

                                    echo "<tr>";
                                    echo "    <td class='texto_elegante estilo_td'>".$num_orden."</td>";
                                    echo "    <td class='texto_elegante estilo_td'>".$fecha."</td>";
                                    echo "    <td class='texto_elegante estilo_td'>"."$ ".number_format($valor,2)."</td>";
                                    echo "</tr>";
                        }
                    }else{
                            echo "<tr>";
                            echo "  <td class='texto_elegante estilo_td' colspan='3'> NO EXISTEN DATOS DE ORDENES DE PAGO RELACIONADAS</td>";
                            echo "</tr>";
                    }
                   echo "</table>";
        
    }

       
    /**
     * Funcion que muestra los datos de las novedades
     * @param <array> $datos_novedad 
     */
    function mostrarNovedades($datos_novedad){
           //var_dump($datos_novedad);
                echo "<table class='bordered' width='100%'>";
                echo "    <tr>";
                echo "          <th colspan='7' class='estilo_th'>NOVEDADES</th>";
                echo "    </tr>";
                echo "    <tr>";
                echo "        <td class='texto_elegante estilo_td' width='5%'>No.</td>";
                echo "        <td class='texto_elegante estilo_td' width='15%'>Tipo</td>";
                echo "        <td class='texto_elegante estilo_td' width='12%'>Fecha</td>";
                echo "        <td class='texto_elegante estilo_td' width='12%'>Fecha Inicial</td>";
                echo "        <td class='texto_elegante estilo_td' width='12%'>Fecha Final</td>";
                echo "        <td class='texto_elegante estilo_td' width='39%'>Descripcion</td>";
                echo "        <td class='texto_elegante estilo_td' width='5%'>Estado</td>";
                echo "    </tr>";
                
                if(is_array($datos_novedad)){
                        foreach ($datos_novedad as $key => $value)
                            {     
                                    $num_nov=(isset($datos_novedad[$key]['id_nov'])?$datos_novedad[$key]['id_nov']:'');
                                    $tipo=(isset($datos_novedad[$key]['tipo_nov'])?$datos_novedad[$key]['tipo_nov']:'');
                                    $fecha_nov=(isset($datos_novedad[$key]['fecha_nov'])?$datos_novedad[$key]['fecha_nov']:'');
                                    $fecha_ini=(isset($datos_novedad[$key]['fecha_inicio'])?$datos_novedad[$key]['fecha_inicio']:'');
                                    $fecha_fin=(isset($datos_novedad[$key]['fecha_fin'])?$datos_novedad[$key]['fecha_fin']:'');
                                    $estado=(isset($datos_novedad[$key]['estado_nov'])?$datos_novedad[$key]['estado_nov']:'');
                                    $valor = (isset($datos_novedad[$key]['valor_nov'])?$datos_novedad[$key]['valor_nov']:'');
                                    switch ($datos_novedad[$key]['cod_tipo_nov']){
                                        case 1:
                                            $descripcion=(isset($datos_novedad[$key]['descripcion_nov'])?$datos_novedad[$key]['descripcion_nov']:'');
                                            $descripcion.="<br>BANCO: ".(isset($datos_novedad[$key]['banco'])?$datos_novedad[$key]['banco']:'')."<br>";
                                            $descripcion.="<br>No. CUENTA: ".(isset($datos_novedad[$key]['num_cta'])?$datos_novedad[$key]['num_cta']:'')."<br>";
                                            $descripcion.="<br>TIPO CUENTA: ".(isset($datos_novedad[$key]['tipo_cta'])?$datos_novedad[$key]['tipo_cta']:'')."<br>";
                                            break;
                                        default:
                                            $descripcion=(isset($datos_novedad[$key]['descripcion_nov'])?$datos_novedad[$key]['descripcion_nov']:'');
                                            if($valor){
                                                $descripcion.="<br>VALOR: $".number_format($valor,2);
                                            }
                                            break;
                                    }
                                    echo "<tr>";
                                    echo "    <td class='texto_elegante estilo_td'>".$num_nov."</td>";
                                    echo "    <td class='texto_elegante estilo_td'>".$tipo."</td>";
                                    echo "    <td class='texto_elegante estilo_td'>".$fecha_nov."</td>";
                                    echo "    <td class='texto_elegante estilo_td'>".$fecha_ini."</td>";
                                    echo "    <td class='texto_elegante estilo_td'>".$fecha_fin."</td>";
                                    echo "    <td class='texto_elegante estilo_td'>".$descripcion."</td>";
                                    echo "    <td class='texto_elegante estilo_td'>".$estado."</td>";
                                    echo "</tr>";
                        }
                    }else{
                            echo "<tr>";
                            echo "  <td class='texto_elegante estilo_td' colspan='6'> NO EXISTEN DATOS DE NOVEDADES RELACIONADAS</td>";
                            echo "</tr>";
                    }
                   echo "</table>";
        
    }


    function generarEnlaceCumplido($id,$cedula,$tipo_documento)
        {  $archivo=$this->configuracion["host"] . $this->configuracion["site"] .$tipo_documento[0]['ubicacion'].$tipo_documento[0]['nombre_pdf'].$cedula."_".$id.".pdf";
           $enlace ="<a href='".$archivo."' ><img width='26' heigth='26' src='".$this->configuracion["host"].$this->configuracion["site"].$this->configuracion["grafico"]."/pdf_logo.jpg' alt='Editar este registro' title='Ver cumplido' border='0' />&nbsp;<br>Ver cumplido</a>";
            return $enlace;
        }
   
        /**
         *Funcion que muestra un mensaje de alerta
         * @param type $cadena 
         */
        function mensajeAlerta($cadena){
                include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/alerta.class.php");
		alerta::sin_registro($this->configuracion,$cadena);
                   
        }
 
        /**
     * Funcion que muestra un mensaje de verificacion de datos
     */
    function mostrarMensajeVerificacion(){
        echo "<p class='fondoImportante'>Por favor verifique los datos que se encuentran registrados en el sistema, tenga en cuenta que estos son los que se van a tener en cuenta para elaborar el cumplido y realizar la solicitud de pago.</p>";
    }

                /**
     * Funcion que muestra el formulario para revisar las solicitudes de pago de nomina
     * @param <array> $configuracion
     * @param <array> $registro
     * @param int $total
     * @param <array> $variable 
     */
    function form_revisar_nomina($configuracion,$periodo_pago,$registro)
	{
        //echo "<br>form revisar";
        //var_dump($registro);//exit;
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
		$indice = $configuracion["host"].$configuracion["site"]."/index.php?";
		$cripto = new encriptar();
                $tab=0;
                if(is_array($registro)){
                    $total_registros = count($registro);
                }else{
                    $total_registros = 0;
                }
                $_REQUEST['periodo_pago']=(isset($_REQUEST['periodo_pago'])?$_REQUEST['periodo_pago']:'');
		$lista_meses=$this->html->cuadro_lista($periodo_pago,'periodo_pago',$this->configuracion,-1,$_REQUEST['periodo_pago'],FALSE,$tab++,'periodo_pago');
		?>
                <link rel="stylesheet" href="<? echo $configuracion["host"].$configuracion["site"].$configuracion["plugins"];?>/jPages-master/css/jPages.css">

                        <script type="text/javascript" src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["plugins"];?>/jPages-master/js/jquery-1.8.2.min.js"></script>
                        <script src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["plugins"];?>/jPages-master/js/jPages.js"></script>
                        <!-- Script para paginar el listado -->
                        <script>
                            
                        $(function (){
                            $("div.holder").jPages({
                            containerID : "contratistas",
                            previous : "←",
                            next : "→",
                            perPage : <? echo $configuracion["registro"]?>,
                            delay : 20
                            });
                        });
                        </script>
                        <!-- Script para seleccionar todos los ckechbox-->
                        <script type="text/javascript">
                            $(document).ready(function(){

                                    //Checkbox
                                    $("input[name=checktodos]").change(function(){
                                            $('input[type=checkbox]').each( function() {			
                                                    if($("input[name=checktodos]:checked").length == 1){
                                                            this.checked = true;
                                                    } else {
                                                            this.checked = false;
                                                    }
                                            });
                                    });

                            });
                            </script>
                <?
                
                $this->formulario = "nom_adminCumplidoSupervisor"; ?>
                <form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario;?>'>
                           
                    
                <table width="80%" align="center" border="0" cellpadding="10" cellspacing="0" >
			<tr>
				
                        <?
				echo "<th colspan='2'>Generar nómina</th>";
			?>
                               
                        <tr>
                                <td width='25%' class="texto_elegante estilo_td"><?
                                        $texto_ayuda = "<b>Mes de nomina.</b><br>Seleccione una opción de la lista. ";
                                        ?><font color="red" >*</font>&nbsp;<span onmouseover="return escape('<? echo $texto_ayuda?>')">Mes de nomina:</span>
                                </td>
                                <td class="texto_elegante estilo_td"><div id='DIV_PERIODOS_PAGO'>
                                        <?
                                        echo $lista_meses;
                                        ?>
                                        </div>
                                </td>
                        </tr>
                        
		</table>
						<table class="bordered" width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
							<tr class="texto_subtitulo">
								<th >Pagos de nomina solicitados aprobados
								</th>
							</tr>
							<tr>
								<td>
                                                                   
									<table class="bordered"  width="100%" >
										<tr class='cuadro_color'>
                                                                                        <th width="5%">Dependencia</th>
											<th width="5%">Vigencia</th>
											<th width="5%">N&uacute;mero de Contrato </th>
                                                                                        <th width="5%">C. C.</th>
                                                                                        <th width="5%">Primer Apellido </th>
                                                                                        <th width="5%">Segundo Apellido </th>
                                                                                        <th width="5%">Primer Nombre</th>
                                                                                        <th width="5%">Segundo Nombre</th>
                                                                                        <th width="5%">CDP No.</th>
                                                                                        <th width="5%">RP No.</th>
                                                                                        <th width="5%">Cod. banco</th>
                                                                                        <th width="5%">Nombre Banco</th>
                                                                                        <th width="5%">Tipo Cuenta</th>
                                                                                        <th width="5%">N&uacute;mero Cuenta</th>
                                                                                        <th width="5%">% Retefuente </th>
                                                                                        <th width="5%">Neto a abonar a la cuenta</th>
                                                                                        <th width="5%">Neto a aplicar en SICAPITAL</th>
                                                                                        <th width="5%">Valor Contrato ($)</th>
                                                                                        <th width="5%">Fecha inicio Contrato </th>
                                                                                        <th width="5%">Fecha final Contrato</th>
                                                                                        <th width="5%">Saldo antes de pago</th>
                                                                                        <th width="5%">Fecha inicio Periodo </th>
                                                                                        <th width="5%">Fecha corte Periodo </th>
                                                                                        <th width="5%">Cantidad d&iacute;as Pago </th>
                                                                                        <th width="5%">R&eacute;gimen Com&uacute;n</th>
                                                                                        <th width="5%">Valor Pago antes de Iva($)</th>
                                                                                        <th width="5%">IVA ($)</th>
                                                                                        <th width="5%">TOTAL ($)</th>
                                                                                        <th width="5%">Base retefuente renta ($)</th>
                                                                                        <th width="5%">Retefuente Renta ($)</th>
                                                                                        <th width="5%">RETEIVA ($)</th>
                                                                                        <th width="5%">Base ICA y estampillas ($)</th>
                                                                                        <th width="5%">ICA ($)</th>
                                                                                        <th width="5%">Estampilla UD ($)</th>
                                                                                        <th width="5%">Estampilla Procultura ($)</th>
                                                                                        <th width="5%">Estampilla Pro-adultomayor($)</th>
                                                                                        <th width="5%">ARP ($)</th>
                                                                                        <th width="5%">Cooperativas o depósitos ($)</th>
                                                                                        <th width="5%">AFC ($)</th>
                                                                                        <th width="5%">Total descuentos sin retenciones ($)</th>
                                                                                        <th width="5%">Neto a pagar sin aplicar retenciones ($)</th>
                                                                                        <th width="5%">Saldo del contrato al corte ($)</th>
                                                                                        <th width="5%">Salud ($)</th>
                                                                                        <th width="5%">Pensi&oacute;n ($)</th>
                                                                                        <th width="5%">Pensionado</th>
                                                                                        <th width="5%">Caso especial Pago saldos menores</th>
                                                                                        <th width="5%">Pasante</th>

                                                                                        <th><input name="checktodos" type="checkbox" /></th>
                                                                                        
										</tr>
                                                                                <tbody id="contratistas">
                                                                                    <?
                                                                                        if(is_array($registro)){
                                                                                            foreach ($registro as $key => $value)
                                                                                                {        
                                                                                                        $detalle_id=(isset($registro[$key]['detalle_id'])?$registro[$key]['detalle_id']:'');
                                                                                                        $cumplido_id=(isset($registro[$key]['cumplido_id'])?$registro[$key]['cumplido_id']:'');
                                                                                                        $num_solicitud_pago=(isset($registro[$key]['num_solicitud_pago'])?$registro[$key]['num_solicitud_pago']:'');
                                                                                                        $cod_dependencia = (isset($registro[$key]['cod_dependencia'])?$registro[$key]['cod_dependencia']:'');
                                                                                                        $nombre_dependencia = (isset($registro[$key]['nombre_dependencia'])?$registro[$key]['nombre_dependencia']:'');
                                                                                                        
                                                                                                        $vigencia = (isset($registro[$key]['vigencia'])?$registro[$key]['vigencia']:'');
                                                                                                        $num_contrato = (isset($registro[$key]['numero_contrato'])?$registro[$key]['numero_contrato']:'');
                                                                                                        $tipo_id = (isset($registro[$key]['tipo_id'])?$registro[$key]['tipo_id']:'');
                                                                                                        $num_id = (isset($registro[$key]['identificacion'])?$registro[$key]['identificacion']:'');
                                                                                                        $fecha_inicio_per = (isset($registro[$key]['fecha_inicio_periodo'])?$registro[$key]['fecha_inicio_periodo']:'');
                                                                                                        $fecha_final_per = (isset($registro[$key]['fecha_final_periodo'])?$registro[$key]['fecha_final_periodo']:'');
                                                                                                        $num_dias= (isset($registro[$key]['dias_pagados'])?$registro[$key]['dias_pagados']:'');
                                                                                                        
                                                                                                        $valor_saldo_antes_de_pago = floatval(isset($registro[$key]['valor_saldo_antes_de_pago'])?$registro[$key]['valor_saldo_antes_de_pago']:'');
                                                                                                        $valor_antes_iva = floatval(isset($registro[$key]['valor_liquidacion_antes_iva'])?$registro[$key]['valor_liquidacion_antes_iva']:'');
                                                                                                        $valor_afc= floatval(isset($registro[$key]['valor_afc'])?$registro[$key]['valor_afc']:'');
                                                                                                        $valor_coop_depositos= floatval(isset($registro[$key]['coop_depositos'])?$registro[$key]['coop_depositos']:'');
                                                                                                        $valor_salud =  floatval(isset($registro[$key]['valor_salud'])?$registro[$key]['valor_salud']:'');
                                                                                                        $valor_pension =  floatval(isset($registro[$key]['valor_pension'])?$registro[$key]['valor_pension']:'');
                                                                                                        $valor_arp =  floatval(isset($registro[$key]['valor_arp'])?$registro[$key]['valor_arp']:'');
                                                                                                        $valor_retefuente =  floatval(isset($registro[$key]['valor_retefuente_renta'])?$registro[$key]['valor_retefuente_renta']:'');
                                                                                                        $valor_iva =  floatval(isset($registro[$key]['valor_iva'])?$registro[$key]['valor_iva']:'');
                                                                                                        $valor_reteiva =  floatval(isset($registro[$key]['valor_reteiva'])?$registro[$key]['valor_reteiva']:'');
                                                                                                        $valor_ica =  floatval(isset($registro[$key]['valor_ica'])?$registro[$key]['valor_ica']:'');
                                                                                                        $valor_estampilla_ud =  floatval(isset($registro[$key]['valor_estampilla_ud'])?$registro[$key]['valor_estampilla_ud']:'');
                                                                                                        $valor_estampilla_procultura =  floatval(isset($registro[$key]['valor_estampilla_procultura'])?$registro[$key]['valor_estampilla_procultura']:'');
                                                                                                        $valor_estampilla_proadultomayor =  floatval(isset($registro[$key]['valor_estampilla_proadultomayor'])?$registro[$key]['valor_estampilla_proadultomayor']:'');
                                                                                                        
                                                                                                        $primer_apellido= (isset($registro[$key]['primer_apellido'])?$registro[$key]['primer_apellido']:'');
                                                                                                        $segundo_apellido= (isset($registro[$key]['segundo_apellido'])?$registro[$key]['segundo_apellido']:'');
                                                                                                        $primer_nombre= (isset($registro[$key]['primer_nombre'])?$registro[$key]['primer_nombre']:'');
                                                                                                        $segundo_nombre= (isset($registro[$key]['segundo_nombre'])?$registro[$key]['segundo_nombre']:'');
                                                                                                        $cdp =  (isset($registro[$key]['cdp'])?$registro[$key]['cdp']:'');
                                                                                                        $interno_rubro =  (isset($registro[$key]['interno_rubro'])?$registro[$key]['interno_rubro']:'');
                                                                                                        $rp =  (isset($registro[$key]['rp'])?$registro[$key]['rp']:'');
                                                                                                        $codigo_banco =  (isset($registro[$key]['codigo_banco'])?$registro[$key]['codigo_banco']:'');
                                                                                                        $nombre_banco =  (isset($registro[$key]['nombre_banco'])?$registro[$key]['nombre_banco']:'');
                                                                                                        $tipo_cuenta =  (isset($registro[$key]['tipo_cuenta'])?$registro[$key]['tipo_cuenta']:'');
                                                                                                        $cuenta_No =  (isset($registro[$key]['cuenta_No'])?$registro[$key]['cuenta_No']:'');
                                                                                                        
                                                                                                        $fecha_inicio_contrato= (isset($registro[$key]['fecha_inicio'])?$registro[$key]['fecha_inicio']:'');
                                                                                                        $fecha_final_contrato = (isset($registro[$key]['fecha_final'])?$registro[$key]['fecha_final']:'');
                                                                                                        $valor_base_retefuente =  floatval(isset($registro[$key]['valor_base_retefuente_renta'])?$registro[$key]['valor_base_retefuente_renta']:'');
                                                                                                        $valor_base_ica_estampillas =  floatval(isset($registro[$key]['valor_base_ica_estampillas'])?$registro[$key]['valor_base_ica_estampillas']:'');
                                                                                                        $valor_contrato =  floatval(isset($registro[$key]['valor_contrato'])?$registro[$key]['valor_contrato']:'');
                                                                                                        $valor_total =  floatval(isset($registro[$key]['valor_total'])?$registro[$key]['valor_total']:'');
                                                                                                        $valor_neto_a_abonar_a_la_cuenta_bancaria =  floatval(isset($registro[$key]['valor_neto_a_abonar_a_la_cuenta_bancaria'])?$registro[$key]['valor_neto_a_abonar_a_la_cuenta_bancaria']:'');
                                                                                                        $valor_neto_a_aplicar_en_sicapital =  floatval(isset($registro[$key]['valor_neto_a_aplicar_en_sicapital'])?$registro[$key]['valor_neto_a_aplicar_en_sicapital']:'');
                                                                                                        $valor_total_descuento_sin_retenciones =  floatval(isset($registro[$key]['valor_total_descuento_sin_retenciones'])?$registro[$key]['valor_total_descuento_sin_retenciones']:'');
                                                                                                        $valor_neto_a_pagar_sin_aplicar_retenciones =  floatval(isset($registro[$key]['valor_neto_a_pagar_sin_aplicar_retenciones'])?$registro[$key]['valor_neto_a_pagar_sin_aplicar_retenciones']:'');
                                                                                                        $valor_saldo_corte_pago =  $valor_saldo_antes_de_pago-$valor_antes_iva;
                                                                                                        
                                                                                                        echo "	<tr> 
                                                                                                                        <td class='texto_elegante estilo_td'>".$nombre_dependencia."</td>    
                                                                                                                        <td class='texto_elegante estilo_td'>".$vigencia."</td>    
                                                                                                                        <td class='texto_elegante estilo_td'>".$num_contrato."</td>    
                                                                                                                        <td class='texto_elegante estilo_td'>".$num_id."</td>    
                                                                                                                        <td class='texto_elegante estilo_td'>".$primer_apellido."</td>    
                                                                                                                        <td class='texto_elegante estilo_td'>".$segundo_apellido."</td>    
                                                                                                                        <td class='texto_elegante estilo_td'>".$primer_nombre."</td>    
                                                                                                                        <td class='texto_elegante estilo_td'>".$segundo_nombre."</td>    
                                                                                                                        <td class='texto_elegante estilo_td'>".$cdp."</td>    
                                                                                                                        <td class='texto_elegante estilo_td'>".$rp."</td>    
                                                                                                                        <td class='texto_elegante estilo_td'>".$codigo_banco."</td>    
                                                                                                                        <td class='texto_elegante estilo_td'>".$nombre_banco."</td>    
                                                                                                                        <td class='texto_elegante estilo_td'>".$tipo_cuenta."</td>    
                                                                                                                        <td class='texto_elegante estilo_td'>".$cuenta_No."</td>    
                                                                                                                        <td class='texto_elegante estilo_td'>"."</td>    
                                                                                                                        <td class='texto_elegante estilo_td'>".$valor_neto_a_abonar_a_la_cuenta_bancaria."</td>    
                                                                                                                        <td class='texto_elegante estilo_td'>".$valor_neto_a_aplicar_en_sicapital."</td>    
                                                                                                                        <td class='texto_elegante estilo_td'>".$valor_contrato."</td>    
                                                                                                                        <td class='texto_elegante estilo_td'>".$fecha_inicio_contrato."</td>    
                                                                                                                        <td class='texto_elegante estilo_td'>".$fecha_final_contrato."</td>    
                                                                                                                        <td class='texto_elegante estilo_td'>".$valor_saldo_antes_de_pago."</td>    
                                                                                                                        <td class='texto_elegante estilo_td'>".$fecha_inicio_per."</td>    
                                                                                                                        <td class='texto_elegante estilo_td'>".$fecha_final_per."</td>   
                                                                                                                        <td class='texto_elegante estilo_td'>".$num_dias."</td>   
                                                                                                                        <td class='texto_elegante_der estilo_td'>"."</td>    
                                                                                                                        <td class='texto_elegante_der estilo_td'>".number_format($valor_antes_iva, 2)."</td>   
                                                                                                                        <td class='texto_elegante_der estilo_td'>".number_format($valor_iva, 2)."</td>   
                                                                                                                        <td class='texto_elegante_der estilo_td'>".number_format($valor_total, 2)."</td>   
                                                                                                                        <td class='texto_elegante_der estilo_td'>".number_format($valor_base_retefuente, 2)."</td>   
                                                                                                                        <td class='texto_elegante_der estilo_td'>".number_format($valor_retefuente, 2)."</td>   
                                                                                                                        <td class='texto_elegante_der estilo_td'>".number_format($valor_reteiva, 2)."</td>   
                                                                                                                        <td class='texto_elegante_der estilo_td'>".number_format($valor_base_ica_estampillas, 2)."</td>   
                                                                                                                        <td class='texto_elegante_der estilo_td'>".number_format($valor_ica, 2)."</td>   
                                                                                                                        <td class='texto_elegante_der estilo_td'>".number_format($valor_estampilla_ud, 2)."</td>   
                                                                                                                        <td class='texto_elegante_der estilo_td'>".number_format($valor_estampilla_procultura, 2)."</td>   
                                                                                                                        <td class='texto_elegante_der estilo_td'>".number_format($valor_estampilla_proadultomayor, 2)."</td>   
                                                                                                                        <td class='texto_elegante_der estilo_td'>".number_format($valor_arp, 2)."</td>   
                                                                                                                        <td class='texto_elegante_der estilo_td'>".number_format($valor_coop_depositos, 2)."</td>   
                                                                                                                        <td class='texto_elegante_der estilo_td'>".number_format($valor_afc, 2)."</td>   
                                                                                                                        <td class='texto_elegante_der estilo_td'>".number_format($valor_total_descuento_sin_retenciones, 2)."</td>   
                                                                                                                        <td class='texto_elegante_der estilo_td'>".number_format($valor_neto_a_pagar_sin_aplicar_retenciones, 2)."</td>   
                                                                                                                        <td class='texto_elegante_der estilo_td'>".number_format($valor_saldo_corte_pago, 2)."</td>   
                                                                                                                        <td class='texto_elegante_der estilo_td'>".number_format($valor_salud, 2)."</td>   
                                                                                                                        <td class='texto_elegante_der estilo_td'>".number_format($valor_pension, 2)."</td>   
                                                                                                                        <td class='texto_elegante_der estilo_td'>"."</td>   
                                                                                                                        <td class='texto_elegante_der estilo_td'>"."</td>   
                                                                                                                        <td class='texto_elegante_der estilo_td'>"."</td>   
                                                                                                                        <td class='texto_elegante_centrado estilo_td'><input value='".$detalle_id."' name='id_detalle_".$key."' type='checkbox' /></td>    
                                                                                                                        <input type='hidden' name='cumplido_id_".$key."' value='".$cumplido_id."'>
                                                                                                                        <input type='hidden' name='vigencia_".$key."' value='".$vigencia."'>
                                                                                                                        <input type='hidden' name='num_solicitud_pago_".$key."' value='".$num_solicitud_pago."'>
                                                                                                                        <input type='hidden' name='interno_rubro_".$key."' value='".$interno_rubro."'>
                                                                                                                        <input type='hidden' name='valor_total_".$key."' value='".$valor_total."'>
                                                                                                                        <input type='hidden' name='valor_neto_a_pagar_sin_aplicar_retenciones_".$key."' value='".$valor_neto_a_pagar_sin_aplicar_retenciones."'>
                                                                                                                        <input type='hidden' name='valor_neto_a_abonar_a_la_cuenta_bancaria_".$key."' value='".$valor_neto_a_abonar_a_la_cuenta_bancaria."'>
                                                                                                                        <input type='hidden' name='valor_neto_a_aplicar_en_sicapital_".$key."' value='".$valor_neto_a_aplicar_en_sicapital."'>
                                                                                                                        <input type='hidden' name='valor_total_descuento_sin_retenciones_".$key."' value='".$valor_total_descuento_sin_retenciones."'>
                                                                                                                        <input type='hidden' name='valor_saldo_corte_pago_".$key."' value='".$valor_saldo_corte_pago."'>
                                                                                                                        <input type='hidden' name='cod_dependencia_".$key."' value='".$cod_dependencia."'>
                                                                                                                            
                                                                                                                        
                                                                                                                </tr>";

                                                                                                }//fin for 
                                                                                        }else{
                                                                                            echo "<tr><td colspan=6>No hay registros de solicitudes pendientes</td></tr>";
                                                                                        }
                                                                                    ?>      
                                                                                </tbody>
                                                                                    
									</table>
                                                                    <center><div class="holder"></div></center>
								</td>
							</tr>
                                                        <?
                                                            if(is_array($registro )){
                                                        ?>
                                                        <tr align='center'>
                                                                <td colspan='2' rowspan='1'>
                                                                    <br>
                                                                        <input type='hidden' name='pagina' value='nom_adminNominaOrdenador'>
                                                                        <input type='hidden' name='opcion' value='generar_nomina'>
                                                                        <input type='hidden' name='total_registros' value='<? echo $total_registros;?>'>
                                                                        <input value="Aprobar" name="aceptar" tabindex="<?= $tab++ ?>" type="button" onclick="document.forms['<? echo $this->formulario?>'].submit()">
                                                                </td>
                                                        </tr>
                                                        <?
                                                            }
                                                        ?>
						</table>
                                            
					</td>
				</tr>
				
			</tbody>
		</table>
		
                </form>
                
                        <?php
                        
                     
		
	}//fin funcion 

}
?>
