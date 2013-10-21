<?php
/*--------------------------------------------------------------------------------------------------------------------------
 @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/
/*---------------------------------------------------------------------------------------
 |				Control Versiones				    	|
----------------------------------------------------------------------------------------
| fecha      |        Autor            | version     |              Detalle            |
----------------------------------------------------------------------------------------
| 14/02/2013 | Maritza Callejas C.  	| 0.0.0.1     |                                 |
----------------------------------------------------------------------------------------
*/


if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/navegacion.class.php");
include_once("html.class.php");
class funciones_menuReporteFin extends funcionGeneral
{

	function __construct($configuracion, $sql)
	{
		//[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
		//include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
		include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/log.class.php");

		$this->cripto = new encriptar();
		$this->log_us = new log();
		$this->tema = $tema;
		$this->sql = $sql;

		//Conexion General
		//$this->acceso_db = $this->conectarDB($configuracion,"mysqlFrame");

		//Conexion SICAPITAL
		//$this->acceso_sic = $this->conectarDB($configuracion,"oracleSIC");
		 
		//Datos de sesion

		//$this->usuario = $this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
		//$this->identificacion = $this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");

		$this->configuracion = $configuracion;

		$this->htmlMenuReporte = new html_menuReporteFin($configuracion);

	}


	function nuevoRegistro($configuracion,$reporte)
	{       
           switch ($reporte)
		{
			case 'crearNovedad':
				//Consultar usuario
				//var_dump($_REQUEST);
				break;


			default:
				//Consultar novedad
				$this->htmlMenuReporte->menu();   
				break;

		}//fin switch
            
            
            
                
		

	}

	function editarRegistro($configuracion,$tema,$id,$acceso_db,$formulario)
	{
		
	}

	function corregirRegistro()
	{
	}

	function listaRegistro($configuracion,$id_registro)

	{
	}


	function mostrarRegistro($configuracion,$registro, $totalRegistros, $opcion, $variable)
	{
		switch($opcion)
		{
			case "multiplesNovedades":
				$this->htmlReporte->multiplesNovedades($configuracion,$registro, $totalRegistros, $variable);
				break;

		}

	}


	/*__________________________________________________________________________________________________

	Metodos especificos
	__________________________________________________________________________________________________*/

	/**
	 *Funcion que consulta el listado de unidades presupuestales de las reservas
	 * @return type
	 */
	function consultarUnidades($tipo){
            
            switch($tipo)
                {   case 'reservas':
                        $cadena_sql = $this->sql->cadena_sql($this->configuracion,"","unidades_reservas","");
                    break;
                }
               $unidades = $this->ejecutarSQL($this->configuracion, $this->acceso_sic, $cadena_sql, "busqueda");
               return $unidades;
	}        
        

	/**
	 *Funcion que consulta el listado de vigencias de las reservas
	 * @return type
	 */
	function consultarVigencias($tipo){
            
            switch($tipo)
                {   case 'reservas':
                        $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_sic,"vigencias_reservas","");
                    break;
                }
                $vigencias = $this->ejecutarSQL($this->configuracion, $this->acceso_sic, $cadena_sql, "busqueda");
                return $vigencias;
	}          
        
        
	/**
	 *Funcion que consulta el listado de vigencias de las reservas
	 * @return type
	 */
	function generarReporte($reporte)
            {   
                switch($reporte['nombre'])
                    {   case 'reservas':
                             $reservas['nombre']=$reporte['nombre'].$reporte['unidad'];
                        break;
                    }
                echo  $cadena_sql = $this->sql->cadena_sql($this->configuracion,'','buscarReporte',$reservas);
                $reporteSQL = $this->ejecutarSQL($this->configuracion, $this->acceso_db, $cadena_sql, "busqueda");
                var_dump($reporteSQL);
                
                
            
                exit;
            
                /*
                switch($reporte['unidad'])
                    {   case '01':
                            echo  $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_sic,"reservas_01",$reporte);
                            $reservas = $this->ejecutarSQL($this->configuracion, $this->acceso_sic, $cadena_sql, "busqueda");

                            $titulo='Reservas Presupuestales - Unidad Ejecutora: '.$_REQUEST['unidad'];

                            $this->htmlReporte->mostrarReservas_01($this->configuracion,$reservas,$reporte['nombre'],$titulo);                                
                        break;
                    }*/
            }             
    


	


} // fin de la clase


?>



