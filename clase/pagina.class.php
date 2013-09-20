    <?
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/
/*--------------------------------------------------------------------------------------------------------------------------
* @name          pagina.class.php
* @author        Paulo Cesar Coronado
* @revision      Última revisión 15 de enero de 2009
/*--------------------------------------------------------------------------------------------------------------------------
* @subpackage		
* @package		clase
* @copyright    	Universidad Distrital Francisco Jose de Caldas
* @version      		1.0.0.1
* @author			Paulo Cesar Coronado
* @author			Oficina Asesora de Sistemas
* @link			N/D
* @description  	Clase principal del framework. Gestiona la creacion de paginas
*
/*--------------------------------------------------------------------------------------------------------------------------*/
class pagina
{
	

	function pagina($configuracion)
	{
		$GLOBALS["autorizado"]=TRUE;
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                
		$cripto=new encriptar();
		//ENLACE BASICO
		//echo "<br>enlace=".$_REQUEST[$configuracion["enlace"]];
		if(isset($_REQUEST[$configuracion["enlace"]]))
		{		
			$cripto->decodificar_url($_REQUEST[$configuracion["enlace"]],$configuracion);
			
			if(isset($_REQUEST["pagina"]))
			{
				$this->especificar_pagina($_REQUEST["pagina"]);	
			}
			elseif(isset($_REQUEST["no_pagina"]))
			{
				$this->especificar_pagina($_REQUEST["no_pagina"]);	
			}
			else
			{
				$this->especificar_pagina("");	
			}	
		}
		else
		{
			//SECCION ESPECIAL PARA FORMULARIOS
			
			//UTILIZAR EL FORMULARIO COMO ENLACE (LINK BASADOS EN BOTONES)
			//Si en el formulario llega una variable llamada redireccion entonces lo que sucede es que se llama
			//a la pagina especificada pasandole las variable codificadas en redireccion y las variables que se 
			//llenaron en el formulario.			
			if(isset($_REQUEST["redireccion"]))
			{
				$variable="";		
				reset ($_REQUEST);
				while (list ($clave, $val) = each ($_REQUEST)) 
				{
					if($clave !="redireccion")
					{
						$variable.="&".$clave."=".$val;
					}
				}

                                //elimina la palabra index si la cadena inicia con index
                                if(substr($_REQUEST["redireccion"],0,5)=='index')
                                            { $_REQUEST["redireccion"]=substr($_REQUEST["redireccion"],5,strlen($_REQUEST["redireccion"])); }
                                
				$cripto->decodificar_url($_REQUEST["redireccion"],$configuracion);
				
				while (list ($clave, $val) = each ($_REQUEST)) 
				{
						$variable.="&".$clave."=".$val;
				}
				
				$variable=$cripto->codificar_url($variable,$configuracion);
				$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
				echo "<script>location.replace('".$indice.$variable."')</script>";
				
			}
			else
			{
				//PROCESAR EL FORMULARIO CARGANDO TODO LO DEMAS
				//En los formularios se puede ingresar un variable llamada formulario cuyo valor codificado corresponde a variables
				//que deban ser compartidas entre paginas a traves del metodo REQUEST. Estas variables se decodifican aqui y se
				//anexan a las que trae el formulario				
				if(isset($_REQUEST["formulario"]))
				{
					$variable="";		
					reset ($_REQUEST);
					
					foreach($_REQUEST as $clave => $valor) 
					{
						if($clave !="formulario")
						{
							$formulario[$clave]=$valor;
						}
					} 

                                        //elimina la palabra index si la cadena inicia con index
                                        if(substr($_REQUEST["formulario"],0,5)=='index')
                                                    { $_REQUEST["formulario"]=substr($_REQUEST["formulario"],5,strlen($_REQUEST["formulario"])); }
                                        
					$cripto->decodificar_url($_REQUEST["formulario"],$configuracion);
					
					
					foreach($formulario as $clave => $valor) 
					{
						$_REQUEST[$clave]=$valor;
					}
					
					
				}
				
				if(isset($_REQUEST["pagina"]))
				{
					$this->especificar_pagina($_REQUEST["pagina"]);	
				}
				elseif(isset($_REQUEST["no_pagina"]))
				{
					$this->especificar_pagina($_REQUEST["no_pagina"]);	
				}				
				else
				{
					$pagina_nivel=0;			
					$this->especificar_pagina("index");
				}
			}
		}
		
		//echo "<br>".$this->id_pagina;
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/autenticacion.class.php");
		$nueva_autenticacion=new autenticacion($this->id_pagina,$configuracion);
		//autenticacion::autenticacion($this->id_pagina,$configuracion);
		
            if(!isset($_REQUEST['action']))
		{	//echo "1";
			$this->mostrar_pagina($configuracion);
		}
            else
		{	//echo "2";
			$this->procesar_pagina($configuracion);
		}
	}
	
	
	function especificar_pagina($nombre)
	{
	
		$this->id_pagina=$nombre;
		return 1;
	
	}
	
	
	function mostrar_pagina($configuracion)
	{       
		$this->cadena_sql="SELECT  ";
		$this->cadena_sql.=$configuracion["prefijo"]."bloque_pagina.*,";
		$this->cadena_sql.=$configuracion["prefijo"]."bloque.nombre ,";
                $this->cadena_sql.=$configuracion["prefijo"]."bloque.grupo ,";
		$this->cadena_sql.=$configuracion["prefijo"]."pagina.parametro ";
		$this->cadena_sql.="FROM ";
		$this->cadena_sql.=$configuracion["prefijo"]."pagina, ";
		$this->cadena_sql.=$configuracion["prefijo"]."bloque_pagina, ";
		$this->cadena_sql.=$configuracion["prefijo"]."bloque ";
		$this->cadena_sql.="WHERE ";
		$this->cadena_sql.=$configuracion["prefijo"]."pagina.nombre='".$this->id_pagina."' ";
		$this->cadena_sql.="AND ";
		$this->cadena_sql.=$configuracion["prefijo"]."bloque_pagina.id_bloque=".$configuracion["prefijo"]."bloque.id_bloque ";
		$this->cadena_sql.="AND ";
		$this->cadena_sql.=$configuracion["prefijo"]."bloque_pagina.id_pagina=".$configuracion["prefijo"]."pagina.id_pagina";

		$this->base=new dbms($configuracion);		
		$this->enlace=$this->base->conectar_db();
		if (is_resource($this->enlace))
		{
			$this->base->registro_db($this->cadena_sql,0);
			$this->registro=$this->base->obtener_registro_db();
                        //var_dump($this->registro);
			$this->total=$this->base->obtener_conteo_db();
		
			if($this->total<1)
			{
				echo "<h3>La pagina que esta intentando acceder no esta disponible.</h3><br>";
				unset($this->registro);
				unset($this->total);
				exit;
			}
			else
			{
                            //Verificar parametros por defecto
                            if($this->registro[0][5]!="")
				{	$parametros=explode("&",$this->registro[0][5]);
					foreach($parametros as $valor)
					{	$elParametro=explode("=",$valor);
						$_REQUEST[$elParametro[0]]=(isset($elParametro[1])?$elParametro[1]:'');	
					}
				}
				$nueva_sesion=new sesiones($configuracion);
				$esta_sesion=$nueva_sesion->numero_sesion();
				$this->registro=$nueva_sesion->rescatar_valor_sesion($configuracion,"id_usuario");
				if($this->registro)
				{
					$this->id_usuario=$this->registro[0][0];
                                        $usser=$this->id_usuario;
				}
				else
				{
					$this->id_usuario=0;
                                        $usser=(isset($_REQUEST['usuario'])?$_REQUEST['usuario']:'');
				}
                                /*verifica que la sesion general no haya expirado*/
/*                                if(isset($usser))
                                        {
                                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/valida_pagina.class.php");
                                        $esteBloque = new valida_pag($configuracion,$usser);
                                        $esteBloque->action();
                                        }
*/				
				//echo "usuario=".$this->id_usuario;exit;
				$this->SQL="SELECT  ";
				$this->SQL.="usuario, ";
				$this->SQL.="estilo ";
				$this->SQL.="FROM ";
				$this->SQL.=$configuracion["prefijo"]."estilo ";
				$this->SQL.="WHERE ";
				$this->SQL.="usuario='".$this->id_usuario."'";
				//echo $this->SQL;
				$this->base->registro_db($this->SQL,0);
				$this->registro=$this->base->obtener_registro_db();
				$this->total=$this->base->obtener_conteo_db();
                                if($this->total<1)
                                    {$this->estilo='basico';}
                                else
                                    {$this->estilo=$this->registro[0][1];}
				unset($this->registro);
				unset($this->total);
				//define los tamaños registrados para las paginas				
				$this->tamanno=$configuracion["tamanno_gui"];
				$this->Vtamanno=$configuracion["Vtamanno_gui"];
				$this->VtamannoA=$configuracion["Vtamanno_A"];
				$this->VtamannoE=$configuracion["Vtamanno_E"];
				$this->HtamannoB=$configuracion["Htamanno_B"];
				$this->HtamannoD=$configuracion["Htamanno_D"];
                                
                                $GLOBALS["fila"]=0;
				$GLOBALS["tab"]=1;
				
				if(!isset($_REQUEST["no_pagina"]))
				{
					$this->html_pagina.='<html>';
					$this->html_pagina.="<head>\n";
					$this->html_pagina.="<title>".$configuracion['titulo']."</title>\n";
					$this->html_pagina.="<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />\n";
					$this->html_pagina.="<link rel='shortcut icon' href='".$configuracion["host"].$configuracion["site"]."/"."favicon.ico' />\n";
					$this->html_pagina.="<link rel='stylesheet' type='text/css' href='".$configuracion["host"].$configuracion["site"].$configuracion["estilo"]."/".$this->estilo."/estilo.php' />\n";
					$this->html_pagina.="<script src='".$configuracion["host"].$configuracion["site"].$configuracion["javascript"]."/funciones.js' type='text/javascript' language='javascript'></script>\n";
					$this->html_pagina.="<script src='".$configuracion["host"].$configuracion["site"].$configuracion["javascript"]."/navegador.js' type='text/javascript' language='javascript'></script>\n";
					$this->html_pagina.="<script type='text/javascript' src='".$configuracion["host"].$configuracion["site"].$configuracion["javascript"]."/textarea.js"."'></script>\n";
                                        $this->html_pagina.="<script type='text/javascript' src='".$configuracion["host"].$configuracion["site"].$configuracion["plugins"]."/jquery/js/jquery-1.9.1.js'></script>\n";
                                        $this->html_pagina.="<script type='text/javascript' src='".$configuracion["host"].$configuracion["site"].$configuracion["plugins"]."/jquery/js/jquery-1.9.1.min.js'></script>\n";
					
                                        $this->html_pagina.="<!--[if lt IE 7.]>\n";
					$this->html_pagina.="<script defer type='text/javascript' src='".$configuracion["host"].$configuracion["site"].$configuracion["javascript"]."/pngfix.js'></script>\n";
					$this->html_pagina.="<![endif]-->";
					
					/////////////MENU CONDOR
					$this->html_pagina.="<link rel='stylesheet' type='text/css' href='".$configuracion["host"].$configuracion["site"].$configuracion["estilo"]."/menu_condor/estilo_menu.css' />\n";
					$this->html_pagina.="<script type='text/javascript' src='".$configuracion["host"].$configuracion["site"].$configuracion["javascript"]."/clicder.js"."'></script>\n";
					$this->html_pagina.="<script type='text/javascript' src='".$configuracion["host"].$configuracion["site"].$configuracion["javascript"]."/SlideMenu.js"."'></script>\n";
					$this->html_pagina.="<script type='text/javascript' src='".$configuracion["host"].$configuracion["site"].$configuracion["javascript"]."/ventana.js"."'></script>\n";
					$this->html_pagina.="<script type='text/javascript' src='".$configuracion["host"].$configuracion["site"].$configuracion["javascript"]."/BorraLink.js"."'></script>\n";
				///////////////////////////////////
				//Para las paginas que tienen georeferenciacion
				if(isset($_REQUEST["googlemaps"]))
					{
						$this->html_pagina.="<script type='text/javascript' src='".$configuracion["host"].$configuracion["site"].$configuracion["javascript"]."/googlemaps.js"."'></script>";
						$this->html_pagina.="<script src='http://maps.google.com/maps?file=api&amp;v=2&amp;key=".$configuracion["googlemaps"]."' type='text/javascript'></script>";
					}
					//Para paginas que utilizan ajax
				if(isset($_REQUEST["xajax"]))
					{	require_once($configuracion["raiz_documento"].$configuracion["clases"]."/xajax/xajax.inc.php");
						$GLOBALS["xajax"] = new xajax();
						//$GLOBALS["xajax"]->debugOn();
						//Registrar las funciones especificas de ajax para la pagina
						//Las funciones vienen relacionadas en la variable xajax separadas por el caracter "|"						
						$funciones_ajax=explode('|', $_REQUEST["xajax"]);
						$i=0;
                                            //Incluir el archivo que procesara las peticiones Ajax en PHP
                                            if(!isset($_REQUEST["xajax_file"]))
						{   include_once($configuracion["raiz_documento"].$configuracion["clases"]."/xajax/include/funciones_ajax.class.php");
                                                    while(isset($funciones_ajax[$i]))
							{   $GLOBALS["xajax"]->registerExternalFunction($funciones_ajax[$i],$configuracion["host"].$configuracion["site"].$configuracion["clases"]."/xajax/include/funciones_ajax.class.php",XAJAX_POST);
                                                            $i++;
							}
						}
					    else
						{   include_once($configuracion["raiz_documento"].$configuracion["clases"]."/xajax/include/".$_REQUEST["xajax_file"].".class.php");
                                                    while(isset($funciones_ajax[$i]))
							{	$GLOBALS["xajax"]->registerExternalFunction($funciones_ajax[$i],$configuracion["host"].$configuracion["site"].$configuracion["clases"]."/xajax/include/".$_REQUEST["xajax_file"].".class.php",XAJAX_POST);
								//$GLOBALS["xajax"]->registerFunction($funciones_ajax[$i],$configuracion["host"].$configuracion["site"].$configuracion["clases"]."/xajax/include/".$_REQUEST["xajax_file"].".class.php",XAJAX_POST);
								$i++;
							}
						}
						$GLOBALS["xajax"]->processRequests();
						$GLOBALS["xajax"]->printJavascript($configuracion["host"].$configuracion["site"].$configuracion["clases"]."/xajax/");
					}
                                       //desabilitar el uso de la tecla f5 
                                       $this->html_pagina.=" <script language='javascript'> 
                                                               document.onkeydown = function(e)
                                                                    {   if(e)
                                                                        document.onkeypress = function(){return true;}
                                                                        var evt = e?e:event;
                                                                        if(evt.keyCode==116)
                                                                        {   if(e)
                                                                            document.onkeypress = function(){return false;}
                                                                            else
                                                                            {
                                                                            evt.keyCode = 0;
                                                                            evt.returnValue = false;
                                                                            }
                                                                        }
                                                                    } 
                                                               </script> ";
                                       //desabilita el boton derecho del raton
                                       //$this->html_pagina.=" <script language='javascript'> document.oncontextmenu=new Function('return false'); </script> ";
					$this->html_pagina.="</head>\n";
					$this->html_pagina.="<body leftMargin='0' topMargin='0' class='fondoprincipal'";
					if(isset($_REQUEST["googlemaps"]))
					{
						$this->html_pagina.="onload='load()' onunload='GUnload()'";
					}
					$this->html_pagina.=">\n";
					echo $this->html_pagina;
                                        //define las secciones de la pagina
                                        $secciones=$this->ancho_seccion($this->cadena_sql,$configuracion);
					$this->columnas=$this->numeroColumna($secciones);
                                        //arma las secciones de la pagina    
                                        foreach ($secciones as $key => $value) 
                                            { $this->armar_seccion($key,$this->cadena_sql,$configuracion,$GLOBALS["fila"],$GLOBALS["tab"],$secciones);
                                            }
					$this->html_pagina="<script language='JavaScript' type='text/javascript' src='".$configuracion["host"].$configuracion["site"].$configuracion["javascript"]."/tooltip.js'></script>";
					$this->html_pagina.="</body>\n";
					$this->html_pagina.="</html>\n";
					echo $this->html_pagina;	
				}
				else
				{
					$this->armar_no_pagina('C',$this->cadena_sql,$configuracion);
				}
			}
		}
	}
	
	function numeroColumna($secciones)
	{    $this->columnas=3;
            if(!isset($secciones["C"]))
		{$this->columnas--;}
            if(!isset($secciones["B"]))
		{$this->columnas--;}
            if(!isset($secciones["D"]))
		{$this->columnas--;}
		return $this->columnas;
	}
	
	function procesar_pagina($configuracion)
	{
		$this->cadena_sql="SELECT  ";
		$this->cadena_sql.=$configuracion["prefijo"]."bloque.nombre ,";
		$this->cadena_sql.=$configuracion["prefijo"]."bloque.grupo ";
		$this->cadena_sql.="FROM ";
		$this->cadena_sql.=$configuracion["prefijo"]."bloque ";
		$this->cadena_sql.="WHERE ";
		$this->cadena_sql.=$configuracion["prefijo"]."bloque.nombre='".$_REQUEST['action']."' ";
		$this->base=new dbms($configuracion);
		$this->enlace=$this->base->conectar_db();
		if (is_resource($this->enlace))
		{	$this->base->registro_db($this->cadena_sql,0);
			$this->registro=$this->base->obtener_registro_db();
		}	
		/*si el campo grupo del bloque esta lleno concatena el grupo y el bloque */
		if($this->registro[0][1]=="")
			 {$this->incluir=$this->registro[0][0];}
		else {$this->incluir=$this->registro[0][1]."/".$this->registro[0][0];}
		include_once($configuracion["raiz_documento"].$configuracion["bloques"]."/".$this->incluir."/bloque.php");
	}
	
	function ancho_seccion($cadena,$configuracion)
	{	$secciones=array("A","B","C","D","E");
		$la_seccion=array();
		foreach ($secciones as $key => $value) 
		{
			$this->la_cadena=$cadena." ";
			$this->la_cadena.="AND ";
			$this->la_cadena.=$configuracion["prefijo"]."bloque_pagina.seccion='".$value."' ";
			$this->la_cadena.="LIMIT 1 ";
			//echo $this->la_cadena;
			$this->base->registro_db($this->la_cadena,0);
			$this->armar_registro=$this->base->obtener_registro_db();
			$this->total=$this->base->obtener_conteo_db();
			if($this->total>0)
			{	$la_seccion[$value]=1;
			}
		}
		return $la_seccion;
	}
	
	function armar_seccion($seccion,$cadena,$configuracion,$fila,$tab,$secciones)
	{
		$this->la_cadena=$cadena.' AND '.$configuracion["prefijo"].'bloque_pagina.seccion="'.$seccion.'" ORDER BY '.$configuracion["prefijo"].'bloque_pagina.posicion ASC';
		$this->base->registro_db($this->la_cadena,0);
		$this->armar_registro=$this->base->obtener_registro_db();
		$this->total=$this->base->obtener_conteo_db();
                
               // echo "<br>1:".$this->la_cadena;
                $this->posLeft='3';
                $this->posTop='0';
                 //linea que pinta bordes en el div, pegarlo en el estilo
                 //border-top: none; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black
                
                 if($seccion=='A')
                            { echo '<div id="A" class="seccion_A" style="position: relative; left:'.$this->posLeft.'%; top:'.$this->posTop.'%; width:'.$this->tamanno.'; height:'.$this->VtamannoA.'; "> '; } 
                 if($seccion=='B')
                            { $this->float=(isset($secciones["C"]) || isset($secciones["D"]))?('float:left;'):('');  
                              $ancho=(isset($secciones["C"]) || isset($secciones["D"]))?($this->HtamannoB):($this->tamanno);
                              $alto=(isset($secciones["A"]))?(isset($secciones["E"]))?($this->Vtamanno-$this->VtamannoA-$this->VtamannoE):($this->Vtamanno-$this->VtamannoA):($this->Vtamanno);
                              echo '<div id="B" class="seccion_B" style="position: relative; '.$this->float.' left:'.$this->posLeft.'%; top:'.$this->posTop.'%;  width:'.$ancho.'; height:'.$alto.'%; overflow:auto"> ';
                            }          
                 if($seccion=='C')
                            { $this->float=(isset($secciones["D"]))?('float:left;'):('');  
                              $ancho=(isset($secciones["B"]))?(isset($secciones["D"]))?($this->tamanno-$this->HtamannoB-$this->HtamannoD):($this->tamanno-$this->HtamannoB):($this->tamanno);
                              $alto=(isset($secciones["A"]))?(isset($secciones["E"]))?($this->Vtamanno-$this->VtamannoA-$this->VtamannoE):($this->Vtamanno-$this->VtamannoA):($this->Vtamanno);
                              echo '<div id="C" class="seccion_C" style="position:relative; '.$this->float.' left:'.$this->posLeft.'%; top:'.$this->posTop.'%; width:'.$ancho.'%; height:'.$alto.'%; overflow:auto"> ';
                            } 
                 if($seccion=='D')
                            { $ancho=(isset($secciones["C"]) || isset($secciones["B"]))?($this->HtamannoD):($this->tamanno);
                              $alto=(isset($secciones["A"]))?(isset($secciones["E"]))?($this->Vtamanno-$this->VtamannoA-$this->VtamannoE):($this->Vtamanno-$this->VtamannoA):($this->Vtamanno);
                              echo '<div id="D" class="seccion_D" style="position: relative; left:'.$this->posLeft.'%; top:'.$this->posTop.'%; width:'.$ancho.'; height:'.$alto.'%; overflow:auto"> ';
                            }       
                 if($seccion=='E')
                            { $this->VtamannoE=(isset($secciones["A"]))?(isset($secciones["B"]) || isset($secciones["C"]) || isset($secciones["B"]))?$this->VtamannoE:($this->Vtamanno+$this->VtamannoE.'px'):($this->Vtamanno+$this->VtamannoE+10).'px';
                              echo '<div id="E" class="seccion_E" style="position: relative; left:'.$this->posLeft.'%; top:'.$this->posTop.'%; width:'.$this->tamanno.'; height:'.$this->VtamannoE.'; "> ';
                            }           

                 for($this->contador=0;$this->contador<$this->total;$this->contador++)
                    {     $this->id_bloque=$this->armar_registro[$this->contador][0];
                            //$this->incluir=$this->armar_registro[$this->contador][4];
                            /*si el campo grupo del bloque esta lleno concatena el grupo y el bloque */
                            if($this->armar_registro[$this->contador][5]=="")
                                 {$this->incluir=$this->armar_registro[$this->contador][4];}
                            else {$this->incluir=$this->armar_registro[$this->contador][5]."/".$this->armar_registro[$this->contador][4];}
                            include($configuracion["raiz_documento"].$configuracion["bloques"]."/".$this->incluir."/bloque.php");
                    }
                 echo "</div>\n";		
		$GLOBALS["fila"]=$fila;
		$GLOBALS["tab"]=$tab;
		return TRUE;	
	}
	//Fin del metodo armar_seccion	
	
	function armar_no_pagina($seccion,$cadena,$configuracion)
	{	$this->la_cadena=$cadena.' AND '.$configuracion["prefijo"].'bloque_pagina.seccion="'.$seccion.'" ORDER BY '.$configuracion["prefijo"].'bloque_pagina.posicion ASC';
		$this->base->registro_db($this->la_cadena,0);
		$this->armar_registro=$this->base->obtener_registro_db();
		$this->total=$this->base->obtener_conteo_db();
		if($this->total>0)
		{   for($this->contador=0;$this->contador<$this->total;$this->contador++)
			{	$this->id_bloque=$this->armar_registro[$this->contador][0];
                                /*si el campo grupo del bloque esta lleno concatena el grupo y el bloque */
                                if($this->armar_registro[$this->contador][5]=="")
                                     {$this->incluir=$this->armar_registro[$this->contador][4];}
                                else {$this->incluir=$this->armar_registro[$this->contador][5]."/".$this->armar_registro[$this->contador][4];}
				include($configuracion["raiz_documento"].$configuracion["bloques"]."/".$this->incluir."/bloque.php");
			}
		}
		return TRUE;	
	}
}
?>
