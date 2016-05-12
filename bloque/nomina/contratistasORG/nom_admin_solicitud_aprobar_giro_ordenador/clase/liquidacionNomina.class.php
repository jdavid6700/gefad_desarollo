<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/funcionGeneral.class.php");

class liquidacionNomina  extends funcionGeneral{

    public function __construct($configuracion)
	{
            require_once("clase/config.class.php");
            $esta_configuracion=new config();
            $configuracion=$esta_configuracion->variable();
            $this->configuracion=$configuracion;

            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sesion.class.php");

            $this->cripto=new encriptar();
            $this->funcionGeneral=new funcionGeneral();
            $this->sesion=new sesiones($configuracion);

            //Conexion NOMINA 
            $this->acceso_nomina = $this->conectarDB($configuracion,"nominapg");

            //Conexion General
            $this->acceso_db=$this->funcionGeneral->conectarDB($configuracion,"mysqlFrame");
		
            //Datos de sesion

            $this->usuario=$this->funcionGeneral->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");

            $this->identificacion=$this->funcionGeneral->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
            $this->nivel=$this->funcionGeneral->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");

	}
       
        
        /**
         * Funcion para obtener el valor de una liquidacion que se encuentre registrada en las tablas de liquidacion
         * @param int $id_liquidacion
         * @param String $nombre_var_liq
         * @param <array> $parametros
         * @return type 
         */
        function obtenerValorLiquidacion($id_liquidacion,$nombre_var_liq,$parametros){
            $valor_final = '';
            $nivel = (isset($parametros[0]['nivel_arp'])?$parametros[0]['nivel_arp']:'');
            $variables_liq = $this->consultarVariablesLiquidacion($id_liquidacion);
            $var_principal = $this->buscarVariableEnArreglo($nombre_var_liq,$variables_liq);
            $formula_principal = (isset($var_principal['liq_formula'])?$var_principal['liq_formula']:'');
            if($formula_principal ){
                    $formula_principal = $this->reemplazarVarLiqEnFormula($formula_principal,$variables_liq);
                    $formula_principal = $this->reemplazarVarDetEnFormula($formula_principal,$id_liquidacion,$nivel);
                    $formula_principal = $this->reemplazarVarBasEnFormula($formula_principal,$id_liquidacion);
                    
                    $formula_principal = $this->reemplazarParametroEnFormula($formula_principal,$parametros);
                    $formula_final = "(".$formula_principal.")";
                    $valor_final = $this->resolverFormula($formula_final);
            }
            $valor_final = (double)$valor_final;
                    
            return $valor_final;
        }
       
        /**
         * Funcion que consulta variables de la tabla de liquidacion en la BD
         * @param int $id_liquidacion
         * @return <array> 
         */
        
        function consultarVariablesLiquidacion($id_liquidacion){
            $cadena_sql=$this->cadena_sql("variables_liquidacion",$id_liquidacion);
            return $resultado=$this->ejecutarSQL($this->configuracion, $this->acceso_nomina, $cadena_sql, "busqueda");
        }
        
        /**
         * Funcion que recorre un arreglo de variables y retorna los datos de una variable especifica
         * @param string $nombre_var
         * @param <array> $datos_variables
         * @return <array> 
         */
        function buscarVariableEnArreglo($nombre_var, $datos_variables){
            $registro='';
            $nombre_var = "[".$nombre_var."]";
            foreach ($datos_variables as $variable) {
                if( $nombre_var ==$variable['liq_nombre_variable']){
                    $registro = $variable;
                }
            }
            return $registro;
        }
        

        /**
         * Funcion que reemplaza las variables de tipo $L que son de la tabla de liquidacion en la formula
         * @param String $formula
         * @param <arra> $variables_liq
         * @return string 
         */
        function reemplazarVarLiqEnFormula($formula,$variables_liq){
            if(is_array($variables_liq)){
                foreach ($variables_liq as $variable) {
                    $nombre_var = "$"."L".$variable['liq_nombre_variable'];
                    $formula_var = "(".$variable['liq_formula'].")";
                    $formula = str_replace($nombre_var, $formula_var, $formula);

                }
            }else{
                $formula='';
            }
            return $formula;
        }
      
        
        /**
         * Funcion para buscar los valores de base y porcentajes de un descuento en la tabla de detalles para 
         * luego reemplazar el valor en la formula
         * @param String $formula
         * @param int $id_liquidacion
         * @return String
         */
        function reemplazarVarDetEnFormula($formula,$id_liquidacion,$nivel){
            $detalle = $this->consultarDetalleDescuento($id_liquidacion,$nivel);
            if(is_array($detalle)){
                
                    $nombre_valor_base = "$"."D[dtd_valor_base]";
                    $valor_valor_base = $detalle[0]['dtd_valor_base'];
                    $formula = str_replace($nombre_valor_base, $valor_valor_base, $formula);
                    
                    $nombre_porcentaje = "$"."D[dtd_porcentaje_dcto]";
                    $valor_porcentaje = $detalle[0]['dtd_porcentaje_dcto'];
                    $formula = str_replace($nombre_porcentaje, $valor_porcentaje, $formula);
                
            }
            return $formula;
        }
        
        /**
         * Funcion para buscar los valores de base de salarios en la tabla de salario base para 
         * luego reemplazar el valor en la formula
         * @param String $formula
         * @param int $anio
         * @return String 
         */
        function reemplazarVarBasEnFormula($formula,$anio){
            $base = $this->consultarSalarioBase($anio);
            
            if(is_array($base)){
                
                    $nombre_valor_salario = "$"."B[sab_valor_salario_minimo]";
                    $valor_valor_salario = $base[0]['sab_valor_salario_minimo'];
                    $formula = str_replace($nombre_valor_salario, $valor_valor_salario, $formula);
                    
                    $nombre_valor_uvt = "$"."B[sab_valor_uvt]";
                    $valor_valor_uvt = $base[0]['sab_valor_uvt'];
                    $formula = str_replace($nombre_valor_uvt, $valor_valor_uvt, $formula);
            }
            return $formula;
        }

        
        /**
         * 
         */
        function reemplazarParametroEnFormula($formula,$parametros){
            if(is_array($parametros)){
                foreach ($parametros as $key => $parametro) {
                    
                    $nombre_parametro = "$"."P[".$parametro['nombre_parametro']."]";
                    $valor_parametro = $parametro['valor_parametro'];
                    $formula = str_replace($nombre_parametro, $valor_parametro, $formula);
                    
                }
               
            }
            return $formula;
        }

        /**
         * 
         */
        function consultarDetalleDescuento($id_liquidacion,$nivel){
            $datos = array( 'id_liquidacion'=>$id_liquidacion,
                            'nivel'=>$nivel); 
            $cadena_sql=$this->cadena_sql("detalle_descuento",$datos);
            return $resultado=$this->ejecutarSQL($this->configuracion, $this->acceso_nomina, $cadena_sql, "busqueda");
        }
            
        /**
         * 
         */
        function consultarSalarioBase(){
            $cadena_sql=$this->cadena_sql("salario_base",'');
            return $resultado=$this->ejecutarSQL($this->configuracion, $this->acceso_nomina, $cadena_sql, "busqueda");
        }

        /**
         * Funcion para resolver una formula y retornar el valor resultado
         * @param type $resultado
         * @return type 
         */
        function resolverFormula($resultado){
            $pos_parentesis_ab = strrpos($resultado,"(");
            $can_parentesis = substr_count($resultado, '(');
            //$resultado = '((2981400*0.40)*0.125*2)' ;
            //buscamos los parentesis y empezamos a despejarlos uno a uno
            while($can_parentesis >0){
                    $pos_parentesis_ce = strpos($resultado,")",$pos_parentesis_ab);
                    $cant_pos_extraer  = ($pos_parentesis_ce-$pos_parentesis_ab)-1;
                    $cadena = substr($resultado,($pos_parentesis_ab+1),$cant_pos_extraer);
                    $operaciones = $this->buscarOperaciones($cadena);

                    $numeros = $this->buscarNumerosAOperar($operaciones,$cadena);
                    $pos_parentesis_ab = strrpos($resultado,"(");
                    //revisamos operaciones de multiplicacion y division para operar
                    $resultadoDiv = $this->operarDivisionesMultiplicaciones($operaciones,$numeros);
                    $operaciones=$resultadoDiv['operaciones'];
                    $numeros =$resultadoDiv['numeros'];
                    $res = $resultadoDiv['res'];
                    $cadena = "(".$cadena.")";

                    if($res){
                        $resultado = str_replace($cadena, $res, $resultado);
                    }

                    //revisamos operaciones de suma y resta para operar
                    $resultadoSum = $this->operarSumasRestas($operaciones,$numeros);
                    $operaciones=$resultadoSum['operaciones'];
                    $numeros = $resultadoSum['numeros'];
                    $res = $resultadoSum['res'];
                    if($res){
                        $resultado = str_replace($cadena, $res, $resultado);
                    }
                    $pos_parentesis_ab = strrpos($resultado,"(");
                    $can_parentesis = substr_count($resultado, '(');
            }
            return $resultado;
         
        }
        
        /**
         * Funcion para buscar las operaciones que tiene una cadena y las retornamos en un arreglo
         * @param type $cadena
         * @return int 
         */
        function buscarOperaciones($cadena){
                $operaciones='';
                $long_cadena = strlen($cadena);
                $j=0;
                for ($i=0; $i<$long_cadena ; $i++){
                    $caracter = substr($cadena,$i,1);
                    if($caracter==="+" || $caracter==="-" || $caracter==="*" || $caracter==="/" ) {
                        $operaciones[$j][0]=$caracter;
                        $operaciones[$j][1]=$i;
                        $j++;
                    }
                }
                return $operaciones;
        }
        
        /**
         * Funcion para buscar los numeros que posteriormente se van a operar
         * @param <array> $operaciones
         * @param String $cadena
         * @return <array> 
         */
        function buscarNumerosAOperar($operaciones,$cadena){
            
                $j=0;
                $cant_operaciones = count($operaciones);
                $long_cadena = strlen($cadena);
                
                for($i=0; $i<=$cant_operaciones;$i++){//buscamos los numeros que se van a operar
                    if($i==0) {
                        $pos_desde=0;
                        $pos_hasta=(isset($operaciones[$i][1])?$operaciones[$i][1]:0);
                    }
                    else if($i===$cant_operaciones){
                        $x=$cant_operaciones-1;
                        $pos_desde=(isset($operaciones[$x][1])?$operaciones[$x][1]:0)+1;
                        $pos_hasta=$long_cadena;
                    }
                    else{
                        $pos_desde= $operaciones[$i-1][1]+1;
                        $pos_hasta= $operaciones[$i][1];
                    }
                
                    $cant_pos_extraer2 = $pos_hasta-$pos_desde;
                    $numeros[$j] = substr($cadena,$pos_desde,$cant_pos_extraer2);
                   $j++;
                }
                return $numeros;
        }
        
        /**
         * Funcion para buscar las operaciones de multiplicaciones y divisiones y resolverlas
         * @param <array> $operaciones
         * @param <array> $numeros
         * @return <array> 
         */
        function operarDivisionesMultiplicaciones($operaciones,$numeros){
                $cant_operaciones=count($operaciones);
                $res='';
                for($i=0; $i<$cant_operaciones;$i++){
                    $operaciones[$i][0] = (isset($operaciones[$i][0])?$operaciones[$i][0]:'');
                    if($operaciones[$i][0]==="/" || $operaciones[$i][0]==="*"){
                        $a = floatval($numeros[$i]);
                        $b = floatval($numeros[$i+1]);
                        $res = $this->operar($a,$b,$operaciones[$i][0]);
                        $numeros[$i]=$res;
                        $operaciones[$i][0]= (isset($operaciones[$i+1][0])?$operaciones[$i+1][0]:'');
                        $operaciones[$i][1]= (isset($operaciones[$i+1][1])?$operaciones[$i+1][1]:'');
                        for($j=$i+1;$j< $cant_operaciones;$j++){
                            if (!$numeros[$j+1]){
                                unset($numeros[$j]);
                                $numeros = array_values($numeros);

                            }else{
                                $numeros[$j]=$numeros[$j+1];
                            }
                            $operaciones[$j+1][0]=(isset($operaciones[$j+1][0])?$operaciones[$j+1][0]:'');
                            if (!$operaciones[$j+1][0]){
                                unset($operaciones[$j]);
                                $operaciones = array_values($operaciones);

                            }else{
                                $operaciones[$j][0]= $operaciones[$j+1][0];
                                $operaciones[$j][1]= $operaciones[$j+1][1];
                            }
                        }
                         $cant_operaciones =count($operaciones);
                        $i--;

                    }
                }
                $resultado= array(  'operaciones'=>$operaciones,
                                    'numeros'=>$numeros,
                                    'res'=>$res
                    );
                return $resultado;
        }
        
                
        /**
         * Funcion para buscar las operaciones de sumas y las restas y resolverlas
         * @param <array> $operaciones
         * @param <array> $numeros
         * @return <array> 
         */
        function operarSumasRestas($operaciones,$numeros){
                $res='';
                $cant_operaciones = count($operaciones);
                for($i=0; $i<$cant_operaciones;$i++){
                    if($operaciones[$i][0]==="+" || $operaciones[$i][0]==="-"){
                        $a = floatval($numeros[$i]);
                        $b = floatval($numeros[$i+1]);
                        $res = $this->operar($a,$b,$operaciones[$i][0]);
                        $numeros[$i]=$res;
                        $operaciones[$i][0]= (isset($operaciones[$i+1][0])?$operaciones[$i+1][0]:'');
                        $operaciones[$i][1]= (isset($operaciones[$i+1][1])?$operaciones[$i+1][1]:'');
                        for($j=$i+1;$j< $cant_operaciones;$j++){
                            if (!$numeros[$j+1]){
                                unset($numeros[$j]);
                                $numeros = array_values($numeros);

                            }else{
                                $numeros[$j]=$numeros[$j+1];
                            }
                            if (!(isset($operaciones[$j+1][0])?$operaciones[$j+1][0]:'')){
                                unset($operaciones[$j]);
                                $operaciones = array_values($operaciones);

                            }else{
                                $operaciones[$j][0]= $operaciones[$j+1][0];
                                $operaciones[$j][1]= $operaciones[$j+1][1];
                            }
                        }
                        $cant_operaciones =count($operaciones);
                        $i--;
                    }
                }
                $resultado= array(  'operaciones'=>$operaciones,
                                    'numeros'=>$numeros,
                                    'res'=>$res);
                return $resultado;
        }

        /**
         * funcion que realiza una operacion especifica entre 2 numeros 
         * @param double $numero1
         * @param double $numero2
         * @param String $operacion
         * @return double 
         */
        function operar($numero1,$numero2,$operacion){
            switch($operacion)
		{

			case "/":
				if($numero1>$numero2 && $numero2!=0){
                                    $resultado = $numero1/$numero2;
                                }else{
                                    $resultado=0;
                                }
                                break;
			case "*":
				$resultado = $numero1*$numero2;
                                break;
			case "+":
				$resultado = $numero1+$numero2;
                                break;
			case "-":
				$resultado = $numero1-$numero2;
                                break;
                }
            return $resultado;
        }//fin funcion operar

    
       /**
        * Funcion para determinar el rango inicial y el final de un nivel de detalle de descuento
        * @param <array> $rangos_retefuente
        * @return <array>
        */
        function establecerRangosNivel($rangos_retefuente){
           foreach ($rangos_retefuente as $key => $rango) {
               $valores = explode("-", $rango['dtd_nivel']);
               $rangos_retefuente[$key]['valor_inicial']=$valores[0];
               $rangos_retefuente[$key]['valor_final']=(isset($valores[1])?$valores[1]:'');
           }
           return $rangos_retefuente;
       }
    
       function obtenerPorcentajeDescuento($valor, $rangos){
           $descuento['porcentaje']= 0;
           $descuento['rango_inicial']= 0;
           foreach ($rangos as  $rango) {
               if($rango['valor_final']){
                    if ($valor>$rango['valor_inicial'] && $valor<=$rango['valor_final']){
                            $descuento['porcentaje']= $rango['dtd_porcentaje_dcto'];
                            $descuento['rango_inicial']= $rango['valor_inicial'];
                    }
               }else{
                    if ($valor>$rango['valor_inicial']){
                            $descuento['porcentaje']= $rango['dtd_porcentaje_dcto'];
                            $descuento['rango_inicial']= $rango['valor_inicial'];
                    }
               }
           }
           return $descuento;
           
       }
       
    /**
     * Funcion para asignar el valor de retefuente
     * @param <array> $parametros
     * @return string 
     */
    function asignarValorRetefuente($parametros){
        //var_dump($parametros);//
        $unidades = (int)$this->obtenerValorLiquidacion(7,'cantidad_uvts',$parametros);
        $rangos_retefuente = $this->consultarDetalleDescuento(7,"");
        
        $rangos_retefuente = $this->establecerRangosNivel($rangos_retefuente);
        //var_dump($rangos_retefuente);
        $descuento =  $this->obtenerPorcentajeDescuento($unidades, $rangos_retefuente);
        
        $parametros[6]['nombre_parametro']='dtd_porcentaje_dcto';
        $parametros[6]['valor_parametro']=$descuento['porcentaje'];
        $parametros[7]['nombre_parametro']='rango_inicial';
        $parametros[7]['valor_parametro']=$descuento['rango_inicial'];
        if($descuento['porcentaje']>0){
            $valor_retefuente = $this->obtenerValorLiquidacion(7,'liq_retefuente',$parametros);
        }else{
            $valor_retefuente=0;
        }
        return $valor_retefuente;
    }
    
    /**
     * Funcion para asignar el valor de retefuente iman
     * @param <array> $parametros
     * @return string 
     */
    function asignarValorRetefuenteIman($parametros){
        $unidades = (int)$this->obtenerValorLiquidacion(11,'cantidad_uvts_iman',$parametros);
        //exit;
        $rangos_retefuente = $this->consultarDetalleDescuento(11,"");
        $rangos_retefuente = $this->establecerRangosNivel($rangos_retefuente);
        //var_dump($rangos_retefuente);exit;
        $descuento =  $this->obtenerPorcentajeDescuento($unidades, $rangos_retefuente);
        
        if($descuento['porcentaje']>0){
            $parametros[8]['nombre_parametro']='unidades_dcto';
            $parametros[8]['valor_parametro']=$descuento['porcentaje'];
       
            $valor_retefuente = $this->obtenerValorLiquidacion(11,'liq_retefuente_iman',$parametros);
        }
        else{
            $valor_retefuente =0;
        }
        
        return $valor_retefuente;
    }
    
        /**
         * Funcion para generar cadenas de sql
         * @param String $opcion
         * @param String/<array> $variable
         * @return string 
         */
        function cadena_sql( $opcion,$variable="")
	{
		
		switch($opcion)
		{
						
			case "variables_liquidacion":
                                $cadena_sql=" SELECT ";
                                $cadena_sql.=" liq_id,";
                                $cadena_sql.=" liq_des_id,";
                                $cadena_sql.=" liq_nombre_variable,";
                                $cadena_sql.=" liq_formula,";
                                $cadena_sql.=" liq_condiciones,";
                                $cadena_sql.=" liq_descripcion,";
                                $cadena_sql.=" liq_estado";
                                $cadena_sql.=" FROM fn_nom_liquidacion";
                                $cadena_sql.=" WHERE liq_estado='A'";
                                $cadena_sql.=" AND liq_des_id=".$variable;
                                break;
						
			
                        case "detalle_descuento":
                                $cadena_sql=" SELECT ";
                                $cadena_sql.=" dtd_id               AS dtd_id,";
                                $cadena_sql.=" dtd_des_id           AS dtd_des_id,";
                                $cadena_sql.=" dtd_valor_base       AS dtd_valor_base,";
                                $cadena_sql.=" dtd_nivel            AS dtd_nivel,";
                                $cadena_sql.=" dtd_porcentaje_dcto  AS dtd_porcentaje_dcto,";
                                $cadena_sql.=" dtd_fecha_inicial    AS dtd_fecha_inicial,";
                                $cadena_sql.=" dtd_fecha_final      AS dtd_fecha_final,";
                                $cadena_sql.=" dtd_fecha_registro   AS dtd_fecha_registro,";
                                $cadena_sql.=" dtd_estado           AS dtd_estado ";
                                $cadena_sql.=" FROM fn_nom_dtlle_descuento";
                                $cadena_sql.=" WHERE dtd_estado='A'";
                                $cadena_sql.=" AND dtd_des_id=".$variable['id_liquidacion'];
                                if($variable['nivel']){
                                    $cadena_sql.=" AND dtd_nivel='".$variable['nivel']."'";
                                }
                                break;
						
			 case "salario_base":
                                $cadena_sql=" SELECT sab_id,";
                                $cadena_sql.=" sab_anio,";
                                $cadena_sql.=" sab_valor_salario_minimo,";
                                $cadena_sql.=" sab_valor_uvt,";
                                $cadena_sql.=" sab_fecha_reg";
                                $cadena_sql.=" sab_estado";
                                $cadena_sql.=" FROM fn_nom_salario_base";
                                $cadena_sql.=" WHERE sab_estado='A'";
                                break;
						
			
                        
                            default:
				$cadena_sql="";
				break;
		}
		return $cadena_sql;
	}
	

}
?>
