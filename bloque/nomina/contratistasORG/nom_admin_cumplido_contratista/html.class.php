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


class html_adminCumplidoContratista {
    
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
     * Funcion que muestra el formulario de solicitud donde estan los contratos y los periodos para pago
     * @param <array> $contratos
     * @param <array> $meses
     * @param String $tema
     * @param String $estilo 
     */
    function form_solicitud($contratos, $meses, $tema='',$estilo='')
	{
		$indice=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                /*****************************************************************************************************/
		include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/html.class.php");
                $_REQUEST['mes_cumplido']=(isset($_REQUEST['mes_cumplido'])?$_REQUEST['mes_cumplido']:'');
		$_REQUEST['codigo_contrato']=(isset($_REQUEST['codigo_contrato'])?$_REQUEST['codigo_contrato']:'');
		$tab = 1;
		$this->formulario = "nom_admin_cumplido_contratista";
		$this->verificar .= "seleccion_valida(".$this->formulario.",'mes_cumplido')";
                $this->configuracion["ajax_function"]="xajax_periodos_cumplido";
                $this->configuracion["ajax_control"]="codigo_contrato";
                $lista_contratos=$this->html->cuadro_lista($contratos,'codigo_contrato',$this->configuracion,2,$_REQUEST['codigo_contrato'],FALSE,$tab++,'codigo_contrato');
                if($meses){
                    $lista_meses=$this->html->cuadro_lista($meses,'mes_cumplido',$this->configuracion,-1,$_REQUEST['mes_cumplido'],FALSE,$tab++,'mes_cumplido');
                }else{
                    $lista_meses="Faltan fechas del contrato para definir meses de cumplido.";
                }
                //var_dump($_REQUEST);
                ?>
		<script src="<? echo $this->configuracion["host"].$this->configuracion["site"].$this->configuracion["javascript"]  ?>/funciones.js" type="text/javascript" language="javascript"></script>
		<script src="<? echo $this->configuracion['host'].$this->configuracion['site'].$this->configuracion['javascript']  ?>/cargaFormulario.js" type="text/javascript" language="javascript"></script>
                <link rel='stylesheet' type='text/css' media='all' href='<? echo $this->configuracion['host'].$this->configuracion['site'].$this->configuracion['estilo']."/calendario/calendar-blue2.css"?>' title="win2k-cold-1"/>
                <script type='text/javascript' src=<? echo $this->configuracion['host'].$this->configuracion['site'].$this->configuracion['estilo']."/calendario/calendar.js"?>></script> 
                <script type='text/javascript' src=<? echo $this->configuracion['host'].$this->configuracion['site'].$this->configuracion['estilo']."/calendario/calendar-es.js"?>></script>
                <script type='text/javascript' src=<? echo $this->configuracion['host'].$this->configuracion['site'].$this->configuracion['estilo']."/calendario/calendar-setup.js"?>></script>
            	<body topmargin="0" leftmargin="0" >

		<form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario;?>'>

                    <center>
                    <table width='80%' class="bordered" >
                        <?
				echo "<th colspan='2'>Solicitud de cumplido</th>";
			?>
                        <tr>
                                <td width='25%' class="texto_elegante estilo_td"><?
                                        $texto_ayuda = "<b>Contrato.</b><br>Seleccione una opción de la lista. ";
                                        ?><font color="red" >*</font>&nbsp;<span onmouseover="return escape('<? echo $texto_ayuda?>')">No. Contrato:</span>
                                </td>
                                <td class="texto_elegante estilo_td"><div id='DIV_CONTRATOS'>
                                        <?
                                        echo $lista_contratos;
                                        ?>
                                        </div>
                                </td>
                        </tr>
                        <tr>
                                <td width='25%' class="texto_elegante estilo_td"><?
                                        $texto_ayuda = "<b>Mes de cumplido.</b><br>Seleccione una opción de la lista. ";
                                        ?><font color="red" >*</font>&nbsp;<span onmouseover="return escape('<? echo $texto_ayuda?>')">Mes de cumplido:</span>
                                </td>
                                <td class="texto_elegante estilo_td"><div id='DIV_PERIODOS_PAGO'>
                                        <?
                                        echo $lista_meses;
                                        ?>
                                        </div>
                                </td>
                        </tr>
                        
                        <tr >
                                <td colspan="2">
                                    <table width='90%' height="45" >
                                        <tr class="texto_elegante estilo_td">
                                                <td colspan="5"><br>Todos los campos marcados con ( * ) son obligatorios. <br></td>
                                        </tr>
                                    </table>
                                </td>
                        </tr>

                        <tr align='center'>
                                <td colspan='2' rowspan='1'>
                                    <br>
                                        <!--<input type='hidden' name='xajax' value='<? //echo "PERIODOS_CUMPLIDO";?>'>
                                        <input type='hidden' name='xajax_file' value='<? //echo "Cumplido";?>'>-->
                                        <input type='hidden' name='vigencia_contrato' value='<? ?>'>
                                        <input type='hidden' name='pagina' value='nom_adminCumplidoContratista'>
                                        <input type='hidden' name='opcion' value='verificar'>
                                        <input value="Solicitar" name="aceptar" tabindex="<?= $tab++ ?>" type="button" onclick="if(<?= $this->verificar; ?>){document.forms['<? echo $this->formulario?>'].submit()}else{false}">
                                </td>
                        </tr>


		</table>
                </center>

		</form>
                <br>
	<?php
	} // fin function
 
        
    /**
     * Funcion que muestra el formulario para enviar la solicitud
     * @param String $contrato
     * @param String $mes_cumplido
     * @param <array> $cuentas
     * @param String $tema
     * @param String $estilo 
     */
        function form_envio_solicitud($contrato, $solicitud, $cuentas, $tema='',$estilo='')
	{
            //var_dump($solicitud);
		$indice=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                $cod_contrato=(isset($contrato[0]['NUM_CONTRATO'])?$contrato[0]['NUM_CONTRATO']:'');
                $vigencia_contrato=(isset($contrato[0]['VIGENCIA'])?$contrato[0]['VIGENCIA']:'');
		$valor_contrato=(isset($solicitud[0]['valor_contrato'])?$solicitud[0]['valor_contrato']:'');
		$saldo_contrato=(isset($solicitud[0]['saldo_contrato'])?$solicitud[0]['saldo_contrato']:'');
		/*****************************************************************************************************/
		include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/html.class.php");

		$tab = 1;
		$this->formulario = "nom_admin_cumplido_contratista";
                if(is_array($cuentas)){
                    $this->verificar = "seleccion_valida(".$this->formulario.",'cta_id')";
                    $lista_cuentas = $this->html->cuadro_lista($cuentas,'cta_id',$this->configuracion,-1,0,FALSE,$tab++,'cta_id');
                } else{
                    $this->verificar = true;
                    $cod_cta = $cuentas;
                }
                
                $mes_cumplido = (isset($solicitud[0]['mes'])?$solicitud[0]['mes']:'');
                $anio_cumplido = (isset($solicitud[0]['anio'])?$solicitud[0]['anio']:'');
                $finicial_per = (isset($solicitud[0]['finicio_cumplido'])?$solicitud[0]['finicio_cumplido']:'');
                $ffinal_per = (isset($solicitud[0]['ffinal_cumplido'])?$solicitud[0]['ffinal_cumplido']:'');
                
                $cantidad_dias = (isset($solicitud[0]['dias_cumplido'])?$solicitud[0]['dias_cumplido']:'');
                $valor = (isset($solicitud[0]['valor'])?$solicitud[0]['valor']:'');
                $salud = (isset($solicitud[0]['salud'])?$solicitud[0]['salud']:'');
                $pension = (isset($solicitud[0]['pension'])?$solicitud[0]['pension']:'');
                $arp = (isset($solicitud[0]['arp'])?$solicitud[0]['arp']:'');
                $afc = (isset($solicitud[0]['valor_afc'])?$solicitud[0]['valor_afc']:'');
                if($afc){
                    $nov_id_afc = (isset($solicitud[0]['nov_id_afc'])?$solicitud[0]['nov_id_afc']:'');
                }else{
                    $nov_id_afc ='';
                }
                $cooperativas_depositos =(isset($solicitud[0]['valor_cooperativas_depositos'])?$solicitud[0]['valor_cooperativas_depositos']:'');;
                if($cooperativas_depositos){
                    $nov_id_cooperativas = (isset($solicitud[0]['nov_id_cooperativas_depositos'])?$solicitud[0]['nov_id_cooperativas_depositos']:'');
                }else{
                    $nov_id_cooperativas ='';
                }$cod_supervisor=(isset($solicitud[0]['cod_supervisor'])?$solicitud[0]['cod_supervisor']:'');
                
                
                ?>
		<script src="<? echo $this->configuracion["host"].$this->configuracion["site"].$this->configuracion["javascript"]  ?>/funciones.js" type="text/javascript" language="javascript"></script>
		<script src="<? echo $this->configuracion['host'].$this->configuracion['site'].$this->configuracion['javascript']  ?>/cargaFormulario.js" type="text/javascript" language="javascript"></script>
                <link rel='stylesheet' type='text/css' media='all' href='<? echo $this->configuracion['host'].$this->configuracion['site'].$this->configuracion['estilo']."/calendario/calendar-blue2.css"?>' title="win2k-cold-1"/>
                <script type='text/javascript' src=<? echo $this->configuracion['host'].$this->configuracion['site'].$this->configuracion['estilo']."/calendario/calendar.js"?>></script> 
                <script type='text/javascript' src=<? echo $this->configuracion['host'].$this->configuracion['site'].$this->configuracion['estilo']."/calendario/calendar-es.js"?>></script>
                <script type='text/javascript' src=<? echo $this->configuracion['host'].$this->configuracion['site'].$this->configuracion['estilo']."/calendario/calendar-setup.js"?>></script>
            	<body topmargin="0" leftmargin="0" >

		<form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario;?>'>
                    <table width='90%' class="bordered" >
                        <tr><td  align="center"><b>
                            <? echo "Solicitar Cumplido";?>        
                            </b></td>
                        </tr>
                        <tr><td >
                            <? 
                                echo "<br><b>Vigencia contrato:</b> ".$vigencia_contrato;
                                echo "<br><b>Valor contrato:</b> $".number_format($valor_contrato,1);
                                echo "<br><b> Periodo pago (AAAA-MM):</b> ".$anio_cumplido." - ".$mes_cumplido;
                                echo "<br><b> Fecha inicial periodo:</b> ".$finicial_per;
                                echo "<br><b> Fecha final periodo:</b> ".$ffinal_per;
                                echo "<br><b> Cantidad de días:</b> ".$cantidad_dias;
                                echo "<br><b> Valor:</b> $ ".number_format($valor,1);
                                echo "<br><b> Salud:</b> $ ".number_format($salud,1);
                                echo "<br><b> Pensión:</b> $ ".number_format($pension,1);
                                echo "<br><b> ARP:</b> $ ".number_format($arp,1);
                                echo "<br><b> AFC:</b> $ ".number_format($afc,1);
                                echo "<br><b> Cooperativas y depositos:</b> $ ".number_format($cooperativas_depositos,1);
                                ?>        
                            </td>
                        </tr>
                        
                        <tr><td class='texto_elegante estilo_td' ><br>
                        <?
                            if(is_array($cuentas)){
                                echo "  Seleccione la cuenta a la cual le deben consignar el pago, y para elaborar el cumplido.<br><br>";
                                echo $lista_cuentas;
                            }else{
                                echo "<input type='hidden' name='cta_id' value='".$cod_cta."'>";
                                
                            }
                        ?>
                                </td>
                        </tr>
                        <tr align='center'>
                                <td colspan='2' rowspan='1'>
                                    <br>
                                        <input type='hidden' name='cod_contrato' value='<? echo $cod_contrato?>'>
                                        <input type='hidden' name='saldo_contrato' value='<? echo $saldo_contrato;?>'>
                                        <input type='hidden' name='mes_cumplido' value='<? echo $anio_cumplido.$mes_cumplido?>'>
                                        <input type='hidden' name='vigencia_contrato' value='<? echo $vigencia_contrato?>'>
                                        <input type='hidden' name='finicial_cumplido' value='<? echo $finicial_per?>'>
                                        <input type='hidden' name='ffinal_cumplido' value='<? echo $ffinal_per?>'>
                                        <input type='hidden' name='dias_cumplido' value='<? echo $cantidad_dias?>'>
                                        <input type='hidden' name='valor_cumplido' value='<? echo $valor?>'>
                                        <input type='hidden' name='salud' value='<? echo $salud?>'>
                                        <input type='hidden' name='pension' value='<? echo $pension?>'>
                                        <input type='hidden' name='arp' value='<? echo $arp?>'>
                                        <input type='hidden' name='afc' value='<? echo $afc?>'>
                                        <input type='hidden' name='nov_id_afc' value='<? echo $nov_id_afc?>'>
                                        <input type='hidden' name='cooperativas_depositos' value='<? echo $cooperativas_depositos?>'>
                                        <input type='hidden' name='nov_id_cooperativas_depositos' value='<? echo $nov_id_cooperativas?>'>
                                        <input type='hidden' name='cod_supervisor' value='<? echo $cod_supervisor?>'>
                                        <input type='hidden' name='action' value='nom_admin_cumplido_contratista'>
                                        <input type='hidden' name='opcion' value='nuevo'>
                                        <input value="Registrar Solicitud" name="aceptar" tabindex="<?= $tab++ ?>" type="button" onclick="if(<?= $this->verificar; ?>){document.forms['<? echo $this->formulario?>'].submit()}else{false}">
                                </td>
                        </tr>
                        
                    </table>
		</form>
                <br>
	<?php
	} // fin function
    
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
            $duracion .= (isset($datos_contrato[0]['DIAS_CONTRATO'])?' ('.$datos_contrato[0]['DIAS_CONTRATO'].' días)':'');
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
		?><table width="80%" align="center" border="0" cellpadding="10" cellspacing="0" >
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
                                                                                        
										</tr><?
                                                                                
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
         * Funcion que genera el enlace para mostrar el archivo de cumplido
         * @param int $id
         * @param String $cedula
         * @param int $tipo_documento
         * @return string 
         */
        function generarEnlaceCumplido($id,$cedula,$tipo_documento){
                $archivo=$this->configuracion["host"] . $this->configuracion["site"] .$tipo_documento[0]['ubicacion'].$tipo_documento[0]['nombre_pdf'].$cedula."_".$id.".pdf";
                $enlace ="<a href='".$archivo."' ><img width='26' heigth='26' src='".$this->configuracion["host"].$this->configuracion["site"].$this->configuracion["grafico"]."/pdf_logo.jpg' alt='Ver cumplido' title='Ver cumplido' border='0' />&nbsp;<br>Ver cumplido</a>";
       
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
        echo "<p class='fondoImportante'>Por favor verifique los datos y las Novedades que se encuentran registrados en el sistema, tenga en cuenta que estos son los que se van a tener en cuenta para elaborar el cumplido y realizar la solicitud de pago.</p>";
    }
    
}
?>
