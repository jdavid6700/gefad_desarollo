<?
/*
############################################################################
#    UNIVERSIDAD DISTRITAL Francisco Jose de Caldas                        #
#    paulo_cesar@etb.net.co                                                #
#    Copyright: Vea el archivo EULA.txt que viene con la distribucion      #
############################################################################
*/
/***************************************************************************
  
index.php 

Oficina Asesora de Sistemas
Copyright (C) 2008

Última revisión 15 de julio de 2008

*****************************************************************************
* @subpackage   
* @package	bloques
* @copyright    
* @version      0.2
* @author      	
* @link		N/D
* @description  Formulario para el registro de un archivo de bloques
* @usage        
*******************************************************************************/ 
if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;		
}



class datosSolicitud
{
	function __construct()
	{
			
	}
	
	function rescatarDatoSolicitud($configuracion, $tipo, $variable, $acceso_db)
	{
		$cadena_sql=$this->cadenaSQL_datosSolicitud($configuracion, $tipo, $variable);	
		
		$registro=$this->acceso_db_datosSolicitud($cadena_sql,$acceso_db,"busqueda");
		if(is_array($registro))
		{
			return $registro;
		}
		else
		{
			return false;
		
		}
		
	
	}
	
	
	
	function registroPagoBruto($unaSolicitud, $unEstudiante, $datosBasicos)
	{
		//Total sin exenciones
		$valorMatriculaBruto=0;
		switch($unaSolicitud[9])
		{
			case 1:
				$valorMatriculaBruto=$unEstudiante[6];
			break;
			
			case 2://Postgrados por creditos
				//BORRAR
				
				switch($unEstudiante[10])
				{
					case 33://Ingenieria
						if($datosBasicos["nivelCarrera"]=='POSGRADO' || $datosBasicos["nivelCarrera"]=='ESPECIALIZACION')
						{
							$valorMatriculaBruto=0.5*$datosBasicos["salarioMinimo"]*$unaSolicitud[10];
						}
						elseif($datosBasicos["nivelCarrera"]=='MAESTRIA')
						{
							$valorMatriculaBruto=0.55*$datosBasicos["salarioMinimo"]*$unaSolicitud[10];
							echo $$valorMatriculaBruto;
						}
						break;
					
					case 23:
					case 24:
					case 32:
					case 101:
					
						if($datosBasicos["nivelCarrera"]=='POSGRADO' || $datosBasicos["nivelCarrera"]=='ESPECIALIZACION')
						{
							$valorMatriculaBruto=0.35*$datosBasicos["salarioMinimo"]*$unaSolicitud[10];
						}
						elseif($datosBasicos["nivelCarrera"]=='MAESTRIA')
						{
							$valorMatriculaBruto=0.5*$datosBasicos["salarioMinimo"]*$unaSolicitud[10];
						}
						break;
				}
				
			break;
			
			case 3:
				$valorMatriculaBruto=$datosBasicos["salarioMinimo"]*$unaSolicitud[10];
			break;
			
		}
		return $valorMatriculaBruto;
	}
	
	function observacionSolicitud($registroExencion)
	{
		$observacion="PAGO EN EFECTIVO ";
		/*foreach ($registroExencion[0] as $key => $value) 
		{
		
			echo $key."=>".$value."<br>";
		
		}
		*/
		$j=0;
		while (isset($registroExencion[$j][4]) && $j<2) 
		{
			$observacion.=$registroExencion[$j][4]." ";
						
			if($registroExencion[$j][1])
			{
				$porcentajeCertificado=$registroExencion[$j][3];
			}
			else
			{
				$porcentaje+=$registroExencion[$j][3];
			}
			$j++;
		}
		
		return $observacion;
	
	}
	
	
	//Para aplicar las exenciones	
	function matriculaPagoNeto($valorMatriculaBruto, $registroExencion)
	{
		$porcentajeCertificado=0;
		$porcentaje=0;
		$j=0;
		while (isset($registroExencion[$j][1]) && $j<2) 
		{
			//echo "El Porcentaje=".$registroExencion[$j][1]."<br>";
			if($registroExencion[$j][1]==1)
			{
				$porcentajeCertificado=$registroExencion[$j][3];
			}
			else
			{
				$porcentaje+=$registroExencion[$j][3];
			}
			$j++;
		}
		
		return round($valorMatriculaBruto-(($valorMatriculaBruto*$porcentajeCertificado)/100) - (($valorMatriculaBruto*$porcentaje)/100));
		
	}
	
	
	
	
	function listaConcepto($registroConcepto)
	{
		foreach ($registroConcepto[0] as $key => $value) 
		{
		
			echo $key."=>".$value."<br>";
		
		}
		
		$lista="";
		$j=0;
		while (isset($registroConcepto[$j][1])) 
		{
			switch($registroConcepto[$j][1])
			{
				case 1:
					
					$lista.=" + SEGURO ";
					break;
				
				case 2:
					$lista.=" + CARNET ";
					break;
				case 3:
					$lista.=" + SISTEMATIZACION ";
					break;
			}
			
			$j++;
		}
		
		return $lista;
	}
	
	function valorConcepto($registroConcepto,$tipo=0)
	{
		$j=0;
		while (isset($registroConcepto[$j][1])) 
		{
			
			if($registroConcepto[$j][1]==$tipo)
			{
				//To Do mostrar los valores de acuerdo a los valores de la tabla
				switch($registroConcepto[$j][1])
				{
					case 1:
						
						$lista=5300;
						break;
					
					case 42:
						$lista=7700;
						break;
					case 43:
						$lista=37000;
						break;
				}
				
				return $lista;
				
			}
			$j++;
		}
		
		return 0;
	}
	
	function pagoNeto($matriculaPagoNeto, $registroConcepto)
	{
		//matriculaPagoNeta + Otros conceptos
		$j=0;
		while (isset($registroConcepto[$j][1])) 
		{
			switch($registroConcepto[$j][1])
			{
				case 1:
					
					$seguro=true;
					break;
				
				case 42:
					$carnet=true;
					break;
				case 43:
					$sistematizacion=true;
					break;
			}
			
			$j++;
		}
		
		$elPagoNeto=$matriculaPagoNeto+$seguro+$carnet+$sistematizacion;
		return $elPagoNeto;
		
	/*
	
		elseif($codigoPlantilla==2)
		{SELECT rpa_numero_cuotas
		INTO nro_cuotas
		FROM ACRANGOSPAGO
		WHERE valor_matricula BETWEEN rpa_limite_inferior AND rpa_limite_superior;
		
		
		}
		elseif($codigoPlantilla==3)
		{
		
		
		}		
					
	*/
	}
	
	
	
	function cadenaSQL_datosSolicitud($configuracion, $tipo, $variable)
	{
		$cadena_sql="";
		switch($tipo)
		{
			case "solicitud":
				
				$cadena_sql="SELECT ";
				$cadena_sql.="`id_solicitud_recibo`, ";
				$cadena_sql.="`id_usuario`, ";
				$cadena_sql.="`codigo_est`, ";
				$cadena_sql.="`id_carrera`, ";
				$cadena_sql.="`cuota`, ";
				$cadena_sql.="`estado`, ";
				$cadena_sql.="`fecha`, ";
				$cadena_sql.="`anno`, ";
				$cadena_sql.="`periodo`, ";
				$cadena_sql.="`tipoPlantilla`, ";
				$cadena_sql.="`unidad` ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$configuracion["prefijo"]."solicitudRecibo ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="estado=0 ";
				$cadena_sql.="AND ";
				$cadena_sql.="id_carrera=".$_REQUEST["registro"];  
				echo $cadena_sql;
				break;
				
			case "conceptoSolicitud":
				$cadena_sql="SELECT ";
				$cadena_sql.="`id_solicitud`, ";
				$cadena_sql.="`id_concepto` ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$configuracion["prefijo"]."solicitudConcepto ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="id_solicitud=".$variable;
				
				break;
				
			case "fechaPago":
				$cadena_sql="SELECT ";
				$cadena_sql.="`cuota`, ";
				$cadena_sql.="`ordinaria`, ";
				$cadena_sql.="`extraordinaria` ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$configuracion["prefijo"]."fechasPago ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="cuota=".$variable;
				//echo $cadena_sql;
				
				
				break;
				
			case "exencionSolicitud":
				$cadena_sql="SELECT ";
				$cadena_sql.=$configuracion["prefijo"]."solicitudExencion.id_solicitud, ";
				$cadena_sql.=$configuracion["prefijo"]."exencion.id_exencion, ";
				$cadena_sql.=$configuracion["prefijo"]."exencion.`nombre`, ";
				$cadena_sql.=$configuracion["prefijo"]."exencion.`porcentaje`, ";
				$cadena_sql.=$configuracion["prefijo"]."exencion.`etiqueta`, ";
				$cadena_sql.=$configuracion["prefijo"]."exencion.`tipo`, ";
				$cadena_sql.=$configuracion["prefijo"]."exencion.`soporte` ";	
				$cadena_sql.="FROM ";
				$cadena_sql.=$configuracion["prefijo"]."solicitudExencion, ";
				$cadena_sql.=$configuracion["prefijo"]."exencion ";			
				$cadena_sql.="WHERE ";
				$cadena_sql.=$configuracion["prefijo"]."solicitudExencion.id_solicitud=".$variable." ";
				$cadena_sql.="AND ";
				$cadena_sql.=$configuracion["prefijo"]."exencion.id_exencion=".$configuracion["prefijo"]."solicitudExencion.id_exencion";
				//echo $cadena_sql;
				break;
				
		
			
			case "datosEstudiante":
				//En ORACLE
				$cadena_sql="SELECT ";
				$cadena_sql.="est_cod, ";
				$cadena_sql.="est_nro_iden, ";
				$cadena_sql.="est_nombre, ";
				$cadena_sql.="est_cra_cod, ";
				$cadena_sql.="est_diferido, ";
				$cadena_sql.="est_estado_est, ";
				$cadena_sql.="emb_valor_matricula vr_mat, ";
				$cadena_sql.="cra_abrev, ";
				$cadena_sql.="est_exento, ";
				$cadena_sql.="est_motivo_exento, ";
				$cadena_sql.="cra_dep_cod ";						
				$cadena_sql.="FROM ";
				$cadena_sql.="acest, ";
				$cadena_sql.="V_ACESTMATBRUTO, ";
				$cadena_sql.="ACCRA ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="est_cod =".$variable." ";
				$cadena_sql.="AND ";
				$cadena_sql.="emb_est_cod = est_cod ";
				$cadena_sql.="AND ";
				$cadena_sql.="cra_cod = est_cra_cod";
				
				break;
			
			case "certificadoElectoral":
				//En ORACLE
				$cadena_sql="SELECT ";
				$cadena_sql.="cer_est_cod, ";
				$cadena_sql.="cer_fecha ";
				$cadena_sql.="FROM ";
				$cadena_sql.="ACCERELECTORAL ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="cer_est_cod =".$valor." ";
				$cadena_sql.="AND ";
				$cadena_sql.="cer_estado= 'A' ";
				break;
			
			case "diferidoPregrado":
				//En Oracle
				$cadena_sql="SELECT ";
				$cadena_sql.="EST_DIFERIDO ";
				$cadena_sql.="FROM ";
				$cadena_sql.="ACEST ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="EST_COD =".$valor;
				break;
			
			case "exencionActual":
				$cadena_sql="SELECT ";
				$cadena_sql.="`id_exencion`, ";
				$cadena_sql.="`nombre`, ";
				$cadena_sql.="`porcentaje`, ";
				$cadena_sql.="`etiqueta`, ";
				$cadena_sql.="`tipo`, ";
				$cadena_sql.="`soporte` ";		
				$cadena_sql.="FROM ";
				$cadena_sql.=$configuracion["prefijo"]."exencion ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="tipo=".$valor." ";
				$cadena_sql.="OR ";
				$cadena_sql.="tipo=3 ";
				$cadena_sql.="ORDER BY ";
				$cadena_sql.="id_exencion ";
				break;
				
			case "exencionAnterior":
				$cadena_sql="SELECT ";
				$cadena_sql.="est_motivo_exento ";
				$cadena_sql.="FROM ";
				$cadena_sql.="ACEST ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="est_exento='S' ";
				$cadena_sql.="AND ";
				$cadena_sql.="est_cod=".$valor;
				break;
				
			case "exencion":
				$cadena_sql="SELECT ";
				$cadena_sql.="id_exencion ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$configuracion["prefijo"]."exencion ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="EXE_COD=".$valor;
				break;
				
			case "numeroCuotas":
				$cadena_sql="SELECT ";
				$cadena_sql.="rpa_numero_cuotas ";
				$cadena_sql.="FROM ";
				$cadena_sql.="ACRANGOSPAGO ";
				$cadena_sql.="WHERE ";
				$cadena_sql.=$valor." BETWEEN rpa_limite_inferior AND rpa_limite_superior";
				break;
				
			case "cuotasSolicitud":
				$cadena_sql="SELECT ";
				$cadena_sql.="`id_solicitud`, ";
				$cadena_sql.="`cuota`, ";
				$cadena_sql.="`porcentaje`, ";
				$cadena_sql.="`fecha_ordinaria`, ";
				$cadena_sql.="`fecha_extra` ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$configuracion["prefijo"]."solicitudCuota "; 
				$cadena_sql.="WHERE ";
				$cadena_sql.="id_solicitud=".$variable;
				
				break;
			
		
		}
		
		return $cadena_sql;
	
	
	
	}
	
	
	//Funcion para el acceso a las bases de datos
	
	function acceso_db_datosSolicitud($cadena_sql,$acceso_db,$tipo)
	{
		//echo $cadena_sql;
		if($tipo=="busqueda")
		{
			$acceso_db->registro_db($cadena_sql,0);
			$registro=$acceso_db->obtener_registro_db();
			return $registro;
		}
		else
		{
			$resultado=$acceso_db->ejecutar_acceso_db($cadena_sql);
			return $resultado;
		}
	}
}
		
?>
