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


class html_adminSolicitudAprobarGiro {
    
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
     * Funcion que muestra los datos de las solicitudes de pago realizadas
     * @param <array> $configuracion
     * @param <array> $registro
     * @param int $total
     * @param <array> $variable 
     */
/*    function multiplesSolicitudesPago($configuracion,$registro,$datos_documento)
	{        
                //var_dump($registro);exit;
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
		$indice = $configuracion["host"].$configuracion["site"]."/index.php?";
		$cripto = new encriptar();
		
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
                                                                                        <th width="10%">No. Solicitud</th>
											<th width="10%">Fecha de registro</th>
                                                                                        <th width="20%">Dependencia</th>
                                                                                        <th width="10%">Rubro</th>
											<th width="10%">Cordis</th>
                                                                                        <th width="10%">Estado</th>
                                                                                        <th width="5%">Año pago</th>
                                                                                        <th width="5%">Mes pago</th>
                                                                                        <th width="10%">Observaciones</th>
                                                                                        <th width="10%"></th>
                                                                                        
										</tr><?
                                                         if($registro){                       
                                                                foreach ($registro as $key => $value)
                                                                        {                                                                                                         
                                                                                $id = (isset($registro[$key]['sol_id'])?$registro[$key]['sol_id']:'');
                                                                                $rubro = (isset($registro[$key]['sol_rubro'])?$registro[$key]['sol_rubro']:'');
                                                                                $cordis = (isset($registro[$key]['sol_cordis'])?$registro[$key]['sol_cordis']:'');
                                                                                $anio= (isset($registro[$key]['sol_pago_anio'])?$registro[$key]['sol_pago_anio']:'');
                                                                                $mes = (isset($registro[$key]['sol_pago_mes'])?$registro[$key]['sol_pago_mes']:'');
                                                                                $fecha_registro= (isset($registro[$key]['sol_fecha_reg'])?$registro[$key]['sol_fecha_reg']:'');
                                                                                $observaciones= (isset($registro[$key]['sol_observacion'])?$registro[$key]['sol_observacion']:'');
                                                                                $dependencia =  (isset($registro[$key]['sol_cod_dependencia'])?$registro[$key]['sol_cod_dependencia']:'');
                                                                                $nombre_dependencia =  (isset($registro[$key]['nombre_dependencia'])?$registro[$key]['nombre_dependencia']:'');
                                                                                $estado= (isset($registro[$key]['sol_estado'])?$registro[$key]['sol_estado']:'');
                                                                                $parametro = "pagina=nom_adminSolicitudPagoSupervisor";
                                                                                $parametro .= "&hoja=1";
                                                                                $parametro .= "&opcion=consultarDetallesSolicitudPago";
                                                                                $parametro .= "&id_solicitud=".$id;
                                                                                $parametro = $cripto->codificar_url($parametro,$this->configuracion);
                                                                                $enlace_solicitud= $this->generarEnlaceOficioSolicitud($id,$cordis,$datos_documento);
                                                                                echo "	<tr> 
                                                                                                <td class='texto_elegante estilo_td'><a href='".$indice.$parametro."'>".$id."</a></td>
                                                                                                <td class='texto_elegante estilo_td'>".$fecha_registro."</td>    
                                                                                                <td class='texto_elegante estilo_td'>".$nombre_dependencia."</td>    
                                                                                                <td class='texto_elegante estilo_td'>".$rubro."</td>    
                                                                                                <td class='texto_elegante estilo_td'>".$cordis."</td>    
                                                                                                <td class='texto_elegante estilo_td'>".$estado."</td>    
                                                                                                <td class='texto_elegante estilo_td'>".$anio."</td>    
                                                                                                <td class='texto_elegante estilo_td'>".$mes."</td>    
                                                                                                <td class='texto_elegante estilo_td'>".$observaciones."</td>    
                                                                                                <td class='texto_elegante estilo_td'>".$enlace_solicitud."</td>    

                                                                                        </tr>";

                                                                        }//fin for
                                                         }else{
                                                             echo "	<tr> 
                                                                                                <td class='texto_elegante estilo_td'>No hay registros de </td>    

                                                                                        </tr>";

                                                         }
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
        
       function multiplesDetallesSolicitudPago($configuracion,$registro)
	{
           	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
		$indice = $configuracion["host"].$configuracion["site"]."/index.php?";
		$cripto = new encriptar();
                $tab=0;
                $total_registros = count($registro);
		//var_dump($registro);
		?>
                <link rel="stylesheet" href="<? echo $configuracion["host"].$configuracion["site"].$configuracion["plugins"];?>/jPages-master/css/jPages.css">

                        <script type="text/javascript" src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["plugins"];?>/jPages-master/js/jquery-1.8.2.min.js"></script>
                        <script src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["plugins"];?>/jPages-master/js/jPages.js"></script>
                        <!-- Script para paginar el listado -->
                        
                <?
                
                $this->formulario = "nom_adminCumplidoSupervisor"; ?>
                <form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario;?>'>
                           
                        
                <table width="80%" align="center" border="0" cellpadding="10" cellspacing="0" >
			<tbody>
				<tr>
					<td >
						<table class="bordered" width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
							<tr class="texto_subtitulo">
								<th >Solicitudes de Pagos 
								</th>
							</tr>
							<tr>
								<td>
                                                                   
									<table class="bordered"  width="100%" >
										<tr class='cuadro_color'>
                                                                                        <th width="5%">Vigencia</th>
											<th width="5%">N&uacute;mero de Contrato </th>
                                                                                        <th width="5%">Contratista </th>
                                                                                        <th width="5%">Fecha inicio Pago </th>
                                                                                        <th width="5%">Fecha finalizaci&oacute;n Pago </th>
                                                                                        <th width="5%">Cantidad d&iacute;as Pago </th>
                                                                                        <th width="5%">Valor Pago antes de Iva($)</th>
                                                                                        <th width="5%">AFC ($)</th>
                                                                                        <th width="5%">Cooperativas o depósitos ($)</th>
                                                                                        <th width="5%">Salud ($)</th>
                                                                                        <th width="5%">Pensi&oacute;n ($)</th>
                                                                                        <th width="5%">ARP ($)</th>
                                                                                        
										</tr>
                                                                                <tbody id="contratistas">
                                                                                    <?
                                                                                        if(is_array($registro )){
                                                                                            foreach ($registro as $key => $value)
                                                                                                {        
                                                                                                        $id = (isset($registro[$key]['id'])?$registro[$key]['id']:'');
                                                                                                        $vigencia = (isset($registro[$key]['vigencia_contrato'])?$registro[$key]['vigencia_contrato']:'');
                                                                                                        $num_contrato = (isset($registro[$key]['numero_contrato'])?$registro[$key]['numero_contrato']:'');
                                                                                                        $num_id = (isset($registro[$key]['num_id'])?$registro[$key]['num_id']:'');
                                                                                                        $nombre_contratista = (isset($registro[$key]['primer_nombre'])?$registro[$key]['primer_nombre']:'');
                                                                                                        $nombre_contratista .= " ".(isset($registro[$key]['segundo_nombre'])?$registro[$key]['segundo_nombre']:'');
                                                                                                        $nombre_contratista .= " ".(isset($registro[$key]['primer_apellido'])?$registro[$key]['primer_apellido']:'');
                                                                                                        $nombre_contratista .= " ".(isset($registro[$key]['segundo_apellido'])?$registro[$key]['segundo_apellido']:'');
                                                                                                        
                                                                                                        $fecha_inicio_per = (isset($registro[$key]['fecha_inicio_periodo'])?$registro[$key]['fecha_inicio_periodo']:'');
                                                                                                        $fecha_final_per = (isset($registro[$key]['fecha_corte_periodo'])?$registro[$key]['fecha_corte_periodo']:'');
                                                                                                        $num_dias= (isset($registro[$key]['dias_pagados'])?$registro[$key]['dias_pagados']:'');
                                                                                                        $valor_antes_iva = floatval(isset($registro[$key]['valor_liquidacion_antes_iva'])?$registro[$key]['valor_liquidacion_antes_iva']:'');
                                                                                                        $valor_afc= floatval(isset($registro[$key]['valor_afc'])?$registro[$key]['valor_afc']:'');
                                                                                                        $valor_cooperativas_depositos= floatval(isset($registro[$key]['valor_cooperativas_depositos'])?$registro[$key]['valor_cooperativas_depositos']:'');
                                                                                                        $valor_salud =  floatval(isset($registro[$key]['valor_salud'])?$registro[$key]['valor_salud']:'');
                                                                                                        $valor_pension =  floatval(isset($registro[$key]['valor_pension'])?$registro[$key]['valor_pension']:'');
                                                                                                        $valor_arp =  floatval(isset($registro[$key]['valor_arp'])?$registro[$key]['valor_arp']:'');
                                                                                                        
                                                                                                        echo "	<tr> 
                                                                                                                        <td class='texto_elegante estilo_td'>".$vigencia."</td>    
                                                                                                                        <td class='texto_elegante estilo_td'>".$num_contrato."</td>    
                                                                                                                        <td class='texto_elegante estilo_td'>".$num_id." ".$nombre_contratista."</td>    
                                                                                                                        <td class='texto_elegante estilo_td'>".$fecha_inicio_per."</td>    
                                                                                                                        <td class='texto_elegante estilo_td'>".$fecha_final_per."</td>   
                                                                                                                        <td class='texto_elegante estilo_td'>".$num_dias."</td>   
                                                                                                                        <td class='texto_elegante_der estilo_td'>".number_format($valor_antes_iva, 2)."</td>    
                                                                                                                        <td class='texto_elegante_der estilo_td'>".number_format($valor_afc, 2)."</td>   
                                                                                                                        <td class='texto_elegante_der estilo_td'>".number_format($valor_cooperativas_depositos, 2)."</td>   
                                                                                                                        <td class='texto_elegante_der estilo_td'>".number_format($valor_salud, 2)."</td>   
                                                                                                                        <td class='texto_elegante_der estilo_td'>".number_format($valor_pension, 2)."</td>   
                                                                                                                        <td class='texto_elegante_der estilo_td'>".number_format($valor_arp, 2)."</td>   
                                                                                                                         
                                                                                                                </tr>";

                                                                                                }//fin for 
                                                                                        }else{
                                                                                            echo "<tr><td colspan=6>No hay registros de solicitudes de pago</td></tr>";
                                                                                        }
                                                                                    ?>      
                                                                                </tbody>
                                                                                    
									</table>
                                                                    <center><div class="holder"></div></center>
								</td>
							</tr>
                                                        
						</table>
                                            
					</td>
				</tr>
				
			</tbody>
		</table>
		
                </form>
                
                        <?php
                        
                     
		
	}//fin funcion 
        */
       function form_solicitar_giro($configuracion,$registro)
	{
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
		$indice = $configuracion["host"].$configuracion["site"]."/index.php?";
		$cripto = new encriptar();
                $tab=0;
                $total_registros = count($registro);
		$_REQUEST['periodo_pago']=(isset($_REQUEST['periodo_pago'])?$_REQUEST['periodo_pago']:'');
		$this->formulario = "nom_adminSolicitudAprobarGiroOrdenador";
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
               
                <form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario;?>'>
                           
                        
                <table width="80%" align="center" border="0" cellpadding="10" cellspacing="0" >
			<tbody>
				<tr>
					<td >
						<table class="bordered" width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
							<tr class="texto_subtitulo">
								<th >Nominas generadas sin autorizaci&oacute;n de giro
								</th>
							</tr>
                                                        <tr>
								<td>
									<table class="bordered"  width="100%" >
										<tr class='cuadro_color'>
                                                                                        <th width="5%">No. n&oacute;mina</th>
											<th width="30%">Dependencia</th>
											<th width="30%">Rubro</th>
											<th width="5%">Año</th>
                                                                                        <th width="5%">Mes</th>
                                                                                        <th width="10%">Fecha generaci&oacute;n</th>
                                                                                        <th width="5%">Cantidad contratistas</th>
                                                                                        <th><input name="checktodos" type="checkbox" /></th>
                                                                                        
										</tr>
                                                                                <tbody id="contratistas">
                                                                                    <?
                                                                                        if(is_array($registro )){
                                                                                            foreach ($registro as $key => $value)
                                                                                                {                                                                                               
                                                                                                        $id_nomina = (isset($registro[$key]['nom_id'])?$registro[$key]['nom_id']:'');
                                                                                                        $nombre_dependencia = (isset($registro[$key]['nombre_dependencia'])?$registro[$key]['nombre_dependencia']:'');
                                                                                                        $nombre_rubro = (isset($registro[$key]['nombre_rubro'])?$registro[$key]['nombre_rubro']:'');
                                                                                                        $anio = (isset($registro[$key]['nom_anio'])?$registro[$key]['nom_anio']:'');
                                                                                                        $mes = (isset($registro[$key]['nom_mes'])?$registro[$key]['nom_mes']:'');
                                                                                                        $fecha = (isset($registro[$key]['nom_fecha_registro'])?$registro[$key]['nom_fecha_registro']:'');
                                                                                                        $cantidad_contratistas = (isset($registro[$key]['cantidad_registros'])?$registro[$key]['cantidad_registros']:'');
                                                                                                        
                                                                                                        echo "	<tr> 
                                                                                                                        <td class='texto_elegante estilo_td'>".$id_nomina."</td>    
                                                                                                                        <td class='texto_elegante estilo_td'>".$nombre_dependencia."</td>    
                                                                                                                        <td class='texto_elegante estilo_td'>".$nombre_rubro."</td>    
                                                                                                                        <td class='texto_elegante estilo_td'>".$anio."</td>    
                                                                                                                        <td class='texto_elegante estilo_td'>".$mes."</td>    
                                                                                                                        <td class='texto_elegante estilo_td'>".$fecha."</td>    
                                                                                                                        <td class='texto_elegante estilo_td'>".$cantidad_contratistas."</td>    
                                                                                                                        <input type='hidden' name='vigencia_".$key."' value='".$anio."'>
                                                                                                                        <td class='texto_elegante estilo_td'><input value='".$id_nomina."' name='id_nomina_".$key."' type='checkbox' /></td>    
                                                                                                                        
                                                                                                                        
                                                                                                                </tr>";

                                                                                                }//fin for 
                                                                                        }else{
                                                                                            echo "<tr><td colspan=6>No hay registros de nominas generadas sin solicitudes de aprobacion de giro</td></tr>";
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
                                                                        <input type='hidden' name='pagina' value='nom_adminSolicitudAprobarGiroOrdenador'>
                                                                        <input type='hidden' name='opcion' value='solicitar_aprobacion_giro'>
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
/*        
        function form_asignar_cordis($configuracion,$registro)
	{
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
		$indice = $configuracion["host"].$configuracion["site"]."/index.php?";
		$cripto = new encriptar();
                $tab=0;
                $total_registros = count($registro);
		
                $this->formulario = "nom_adminSolicitudPagoSupervisor";
		//var_dump($registro);
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
               
                <form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario;?>'>
                           
                        
                <table width="80%" align="center" border="0" cellpadding="10" cellspacing="0" >
			<tbody>
				<tr>
					<td >
						<table class="bordered" width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
							<tr class="texto_subtitulo">
								<th >Solicitudes sin cordis
								</th>
							</tr>
                                                        
							<tr>
								<td>
									<table class="bordered"  width="100%" >
										<tr class='cuadro_color'>
                                                                                        <th width="20%">No. Solicitud</th>
											<th width="20%">Rubro</th>
											<th width="20%">Fecha</th>
											<th width="20%">No. CORDIS</th>
                                                                                        <th><input name="checktodos" type="checkbox" /></th>
                                                                                        
										</tr>
                                                                                <tbody id="contratistas">
                                                                                    <?
                                                                                        if(is_array($registro )){
                                                                                            foreach ($registro as $key => $value)
                                                                                                {                                                                                               
                                                                                                        $id = (isset($registro[$key]['sol_id'])?$registro[$key]['sol_id']:'');
                                                                                                        $rubro_interno = (isset($registro[$key]['sol_rubro'])?$registro[$key]['sol_rubro']:'');
                                                                                                        $fecha = (isset($registro[$key]['sol_fecha_reg'])?$registro[$key]['sol_fecha_reg']:'');
                                                                                                         
                                                                                                        
                                                                                                        echo "	<tr> 
                                                                                                                        <td class='texto_elegante estilo_td'>".$id."</td>    
                                                                                                                        <td class='texto_elegante estilo_td'>".$rubro_interno."</td>    
                                                                                                                        <td class='texto_elegante estilo_td'>".$fecha."</td>    
                                                                                                                        <td class='texto_elegante estilo_td'><input name='num_cordis_".$key."' type='text' size='8' /></td>    
                                                                                                                        <td class='texto_elegante estilo_td'><input value='".$id."' name='id_solicitud_".$key."' type='checkbox' /></td>    
                                                                                                                        

                                                                                                                </tr>";

                                                                                                }//fin for 
                                                                                        }else{
                                                                                            echo "<tr><td colspan=6>No hay registros de solicitudes sin cordis</td></tr>";
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
                                                                        <input type='hidden' name='pagina' value='nom_adminSolicitudPagoSupervisor'>
                                                                        <input type='hidden' name='opcion' value='guardar_cordis'>
                                                                        <input type='hidden' name='total_registros' value='<? echo $total_registros;?>'>
                                                                        <input value="Guardar" name="aceptar" tabindex="<?= $tab++ ?>" type="button" onclick="document.forms['<? echo $this->formulario?>'].submit()">
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
        
        /**
         * Funcion que genera el enlace para mostrar el archivo de solicitud
         * @param int $id
         * @param String $cedula
         * @param int $tipo_documento
         * @return string 
         */
/*        function generarEnlaceOficioSolicitud($id,$cordis,$tipo_documento){
                $archivo=$this->configuracion["host"] . $this->configuracion["site"] .$tipo_documento[0]['ubicacion'].$tipo_documento[0]['nombre_pdf'].$id."_".$cordis.".pdf";
                $enlace ="<a href='".$archivo."' ><img width='26' heigth='26' src='".$this->configuracion["host"].$this->configuracion["site"].$this->configuracion["grafico"]."/pdf_logo.jpg' alt='Ver oficio' title='Ver oficio' border='0' />&nbsp;<br>Ver oficio solicitud</a>";
       
            return $enlace;
            
        }
   */
      
        
}
?>
