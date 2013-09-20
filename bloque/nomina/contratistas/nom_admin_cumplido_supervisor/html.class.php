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


class html_adminCumplidoSupervisor {
    
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

    
	
    /**
     * Funcion que muestra los datos de las solicitudes de cumplido realizadas
     * @param <array> $configuracion
     * @param <array> $registro
     * @param int $total
     * @param <array> $variable 
     */
    function multiplesCumplidos($configuracion,$registro, $total, $variable,$datos_documento)
	{
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
		$indice = $configuracion["host"].$configuracion["site"]."/index.php?";
		$cripto = new encriptar();
		if((isset($registro[0]['VIGENCIA'])?$registro[0]['VIGENCIA']:0)>0){
                    $vigencia = "Vigencia ".$registro[0]['VIGENCIA'];
                }else{
                    $vigencia = "";
                }
		?>
                <link rel="stylesheet" href="<? echo $configuracion["host"].$configuracion["site"].$configuracion["plugins"];?>/jPages-master/css/jPages.css">

                        <script type="text/javascript" src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["plugins"];?>/jPages-master/js/jquery-1.8.2.min.js"></script>
                        <script src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["plugins"];?>/jPages-master/js/jPages.js"></script>

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
                        <table width="80%" align="center" border="0" cellpadding="10" cellspacing="0" >
			<tbody>
				<tr>
					<td >
						<table class="bordered" width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
							<tr class="texto_subtitulo">
								<th >Cumplido(s)
								</th>
							</tr>
							<tr>
								<td>
									<table class="bordered"  width="100%" >
										<tr class='cuadro_color'>
                                                                                        <th width="5%">Id.</th>
											<th width="10%">Vigencia</th>
											<th width="10%">N&uacute;mero de Contrato </th>
                                                                                        <th width="25%">Contratista</th>
                                                                                        <th width="20%">Periodo pago</th>
                                                                                        <th width="20%">Estado</th>
                                                                                        <th width="10%">Opciones</th>
                                                                                        
										</tr>
                                                                                <tbody id="contratistas">
                                                                                    <?
                                                                                
                                                         foreach ($registro as $key => $value)
                                                                {                                                                                                         
                                                        		$id = (isset($registro[$key]['id'])?$registro[$key]['id']:'');
                                                                        $vigencia = (isset($registro[$key]['vigencia'])?$registro[$key]['vigencia']:'');
									$num_contrato = (isset($registro[$key]['num_contrato'])?$registro[$key]['num_contrato']:'');
                                                                        $mes = (isset($registro[$key]['mes'])?$registro[$key]['mes']:'');
                                                                        $anio= (isset($registro[$key]['anio'])?$registro[$key]['anio']:'');
                                                                        $finicio_per= (isset($registro[$key]['finicio_per_pago'])?$registro[$key]['finicio_per_pago']:'');
                                                                        $ffinal_per= (isset($registro[$key]['ffinal_per_pago'])?$registro[$key]['ffinal_per_pago']:'');
                                                                        $cedula= (isset($registro[$key]['num_id_contratista'])?$registro[$key]['num_id_contratista']:'');
                                                                        $nombre= (isset($registro[$key]['nombre_contratista'])?$registro[$key]['nombre_contratista']:'');
                                                                        
                                                                        
                                                                        $estado= (isset($registro[$key]['estado'])?$registro[$key]['estado']:'');
                                                                        //var_dump($registro);exit;
                                                                        if($estado=='APROBADO' || $estado=='PROCESADO_SUP' || $estado=='PROCESADO_ORD'){
                                                                            $enlace_cumplido= $this->generarEnlaceCumplido($id,$cedula,$datos_documento);
                                                                            $periodo_pago=$finicio_per." a ".$ffinal_per;
                                                                        }else{
                                                                            $enlace_cumplido= "";
                                                                            $periodo_pago=$anio." - ".$mes;
                                                                        }
									
									
									echo "	<tr> 
										 	<td class='texto_elegante estilo_td'>".$id."</td>
                                                                                        <td class='texto_elegante estilo_td'>".$vigencia."</td>    
											<td class='texto_elegante estilo_td'><CENTER>".$num_contrato."</CENTER></td>    
                                                                                        <td class='texto_elegante estilo_td'>".$nombre."</td>    
                                                                                        <td class='texto_elegante estilo_td'>".$periodo_pago."</td>    
                                                                                        <td class='texto_elegante estilo_td'>".$estado."</td>    
                                                                                        <td class='texto_elegante estilo_td'>".$enlace_cumplido."</td>    
                                                                                        
										</tr>";
					
								}//fin for 
								?></tbody>
									</table>
                                                                    <center><div class="holder"></div></center>
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
     * Funcion que muestra el formulario para revisar las solicitudes de cumplido
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
		if((isset($registro[0]['VIGENCIA'])?$registro[0]['VIGENCIA']:0)>0){
                    $vigencia = "Vigencia ".$registro[0]['VIGENCIA'];
                }else{
                    $vigencia = "";
                }
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
								<th >Cumplidos Solicitados
								</th>
							</tr>
							<tr>
								<td>
									<table class="bordered"  width="100%" >
										<tr class='cuadro_color'>
                                                                                        <th width="5%">Vigencia</th>
											<th width="5%">N&uacute;mero de Contrato </th>
                                                                                        <th width="5%">Fecha inicio Contrato </th>
                                                                                        <th width="5%">Fecha finalizaci&oacute;n Contrato </th>
                                                                                        <th width="35%">Contratista </th>
                                                                                        <th width="10%">Saldo Contrato ($)</th>
                                                                                        <th width="5%">Fecha inicio Pago </th>
                                                                                        <th width="5%">Fecha finalizaci&oacute;n Pago </th>
                                                                                        <th width="5%">Cantidad d&iacute;as Pago </th>
                                                                                        <th width="10%">Valor Pago ($)</th>
                                                                                        <th width="10%">AFC ($)</th>
                                                                                        <th width="10%">SALUD ($)</th>
                                                                                        <th width="10%">PENSION ($)</th>
                                                                                        <th width="10%">ARP ($)</th>
                                                                                        <th width="10%">Cooperativas y Depositos ($)</th>
                                                                                        <th><input name="checktodos" type="checkbox" /></th>
                                                                                        
										</tr>
                                                                                <tbody id="contratistas">
                                                                                    <?
                                                                                        if(is_array($registro )){
                                                                                            foreach ($registro as $key => $value)
                                                                                                {                                                                                               
                                                                                                        $id = (isset($registro[$key]['id'])?$registro[$key]['id']:'');
                                                                                                        $vigencia = (isset($registro[$key]['vigencia'])?$registro[$key]['vigencia']:'');
                                                                                                        $num_contrato = (isset($registro[$key]['num_contrato'])?$registro[$key]['num_contrato']:'');
                                                                                                        $saldo_contrato = (isset($registro[$key]['saldo_antes_pago'])?$registro[$key]['saldo_antes_pago']:'');
                                                                                                        $mes = (isset($registro[$key]['mes'])?$registro[$key]['mes']:'');
                                                                                                        $anio= (isset($registro[$key]['anio'])?$registro[$key]['anio']:'');
                                                                                                        $fecha = (isset($registro[$key]['fecha'])?$registro[$key]['fecha']:'');
                                                                                                        $estado= (isset($registro[$key]['estado'])?$registro[$key]['estado']:'');
                                                                                                        $num_impresiones= (isset($registro[$key]['num_impresiones'])?$registro[$key]['num_impresiones']:'');
                                                                                                        $valor_temporal= (isset($registro[$key]['valor'])?$registro[$key]['valor']:'');
                                                                                                        $contratista =  (isset($registro[$key]['nombre'])?$registro[$key]['nombre']:'');
                                                                                                        $dias_contrato =  (isset($registro[$key]['dias_contrato'])?$registro[$key]['dias_contrato']:'');
                                                                                                        $fecha_contrato = (isset($registro[$key]['fecha_contrato'])?$registro[$key]['fecha_contrato']:'');
                                                                                                        $finicio_contrato =  (isset($registro[$key]['finicio_contrato'])?$registro[$key]['finicio_contrato']:'');
                                                                                                        $ffinal_contrato =  (isset($registro[$key]['ffinal_contrato'])?$registro[$key]['ffinal_contrato']:'');
                                                                                                        $finicio_cumplido =  (isset($registro[$key]['finicio_cumplido'])?$registro[$key]['finicio_cumplido']:'');
                                                                                                        $ffinal_cumplido =  (isset($registro[$key]['ffinal_cumplido'])?$registro[$key]['ffinal_cumplido']:'');
                                                                                                        $dias_cumplido =  (isset($registro[$key]['dias_cumplido'])?$registro[$key]['dias_cumplido']:'');
                                                                                                        $valor_afc =  (isset($registro[$key]['afc'])?$registro[$key]['afc']:'');
                                                                                                        $nov_id =  (isset($registro[$key]['nov_id'])?$registro[$key]['nov_id']:'');
                                                                                                        $valor_salud =  (isset($registro[$key]['salud'])?$registro[$key]['salud']:'');
                                                                                                        $valor_pension =  (isset($registro[$key]['pension'])?$registro[$key]['pension']:'');
                                                                                                        $valor_arp =  (isset($registro[$key]['arp'])?$registro[$key]['arp']:'');
                                                                                                        $valor_cooperativas_depositos =  (isset($registro[$key]['cooperativas_depositos'])?$registro[$key]['cooperativas_depositos']:'');
                                                                                                        $acumulado_antes_pago =  (isset($registro[$key]['acumulado_antes_pago'])?$registro[$key]['acumulado_antes_pago']:'');
                                                                                                        
                                                                                                        echo "	<tr> 
                                                                                                                        <td class='texto_elegante estilo_td'>".$vigencia."</td>    
                                                                                                                        <td class='texto_elegante estilo_td'>".$num_contrato."</td>    
                                                                                                                        <td class='texto_elegante estilo_td'>".$finicio_contrato."</td>    
                                                                                                                        <td class='texto_elegante estilo_td'>".$ffinal_contrato."</td>    
                                                                                                                        <td class='texto_elegante estilo_td'>".$contratista."</td>    
                                                                                                                        <td class='texto_elegante estilo_td'>".$saldo_contrato."</td>    
                                                                                                                        <td class='texto_elegante estilo_td'>".$finicio_cumplido."</td>    
                                                                                                                        <td class='texto_elegante estilo_td'>".$ffinal_cumplido."</td>   
                                                                                                                        <td class='texto_elegante estilo_td'>".$dias_cumplido."</td>   
                                                                                                                        <input type='hidden' name='nov_id_".$key."' value='".$nov_id."'>
                                                                                                                        <input type='hidden' name='vigencia_".$key."' value='".$vigencia."'>
                                                                                                                        <input type='hidden' name='dias_cumplido_".$key."' value='".$dias_cumplido."'>
                                                                                                                        <input type='hidden' name='finicio_cumplido_".$key."' value='".$finicio_cumplido."'>
                                                                                                                        <input type='hidden' name='ffinal_cumplido_".$key."' value='".$ffinal_cumplido."'>
                                                                                                                        <input type='hidden' name='acumulado_antes_pago_".$key."' value='".$acumulado_antes_pago."'>
                                                                                                                            
                                                                                                                        <td class='texto_elegante estilo_td'><input name='valor_cumplido_".$key."' type='text' size='8' maxlength='9'  onKeyPress='return solo_numero_sin_slash(event)' value='".$valor_temporal."' readonly/></td>    
                                                                                                                        <td class='texto_elegante estilo_td'>".$valor_afc."</td>   
                                                                                                                        <td class='texto_elegante estilo_td'>".$valor_salud."</td>   
                                                                                                                        <td class='texto_elegante estilo_td'>".$valor_pension."</td>   
                                                                                                                        <td class='texto_elegante estilo_td'>".$valor_arp."</td>
                                                                                                                        <td class='texto_elegante estilo_td'>".$valor_cooperativas_depositos."</td>
                                                                                                                        <td class='texto_elegante estilo_td'><input value='".$id."' name='id_solicitud_".$key."' type='checkbox' /></td>    
                                                                                                                        

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
                                                                        <input type='hidden' name='pagina' value='nom_adminCumplidoSupervisor'>
                                                                        <input type='hidden' name='opcion' value='aprobar'>
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

        function generarEnlaceCumplido($id,$cedula,$tipo_documento){
                $archivo=$this->configuracion["host"] . $this->configuracion["site"] .$tipo_documento[0]['ubicacion'].$tipo_documento[0]['nombre_pdf'].$cedula."_".$id.".pdf";
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
    
}
?>
