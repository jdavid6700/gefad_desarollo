<?

/* ----------------------------------------------------------------------------------------
  |				Control Versiones				    	  |
  -----------------------------------------------------------------------------------------
  | fecha      |        Autor            | version     |              Detalle              |
  -----------------------------------------------------------------------------------------
  | 26/11/2012 | Marcela Morales      	| 0.0.0.1     |                                   |
  ----------------------------------------------------------------------------------------- */

/* * ***************************************************************************************
 * NOTA IMPORTANTE: Cada una de las funciones que se creen con el fin de recargar un     *
 * atributo deben tener por nombre el mismo nombre que aparezca en el campo ATR_NOMBRE_BD*
 * de la tabla atributoespacio esto con el fin de no realizar cambios en la funcion      *
 * desplegarFormularioRegistro de la clase Interfaz donde se genera dinámicamente el     *
 * llamado a estas.                                                                      *
 * *************************************************************************************** */

///////////////////////////////////////////////////////////////////////////////////////////////
// Funcion:     EFA8 (Este es el ID del combo para subtipo en el formulario)                 //                                                 //
// Descripción: permite recargar en un combobox los subtipos de espacios según el tipo de    //
//              espacio seleccionado por el usuario.                                         //
// Parametros de entrada:   $tipo= tipo espacio escogido por el usuario                      //
// Valores de salida:       Retorna arreglo con los subtipos relacionados                    //
///////////////////////////////////////////////////////////////////////////////////////////////

//function SAL_COD_SUB($tipo) {
function EFA08($tipo) {
    
    //rescata el valor de la configuracion
    require_once("clase/config.class.php");
    setlocale(LC_MONETARY, 'en_US');
    $esta_configuracion = new config();
    $configuracion = $esta_configuracion->variable();

    //Buscar un registro que coincida con el valor
    include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/funcionGeneral.class.php");
    include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/html.class.php");


    $conexion = new funcionGeneral();
    //$conexionOracle=$conexion->conectarDB($configuracion,"coordinador");
    $conexion_db = $conexion->conectarDB($configuracion, "coordinador");
    
    //echo "<br> conexion db ";var_dump($conexion_db);
    
    include_once($configuracion["raiz_documento"] . $configuracion["bloques"] . "/adminEspacioFisico/sql.class.php");
    $sql = new SqlAdminEspacioFisico();
    if ($tipo != 0) {
        
        $subtipos_sql = $sql->cadena_sql($configuracion, "consultar_subtipos", $tipo, "", "");
        $subtipos = $conexion->ejecutarSQL($configuracion, $conexion_db, $subtipos_sql, "busqueda");
        $total_reg = count($subtipos);
    }
    
    for ($i = 0; $i < $total_reg; $i++) {
        $subtipos_a[$i][0] = $subtipos[$i][0];
        $subtipos_a[$i][1] = $subtipos[$i][1];
        $subtipos_a[$i][1] = reemplazar_caracteres($subtipos[$i][1]);
    }
    
    //echo "<br> subtipos ";var_dump($subtipos_a);

    $html = new html();
    $mi_cuadro = $html->cuadro_lista($subtipos_a, 'EFA08', $configuracion, 0, 2, FALSE, $tab++, 'EFA08', "");
    $configuracion["ajax_function"] = "";
    $configuracion["ajax_control"] = "";

    //se crea el objeto xajax para enviar la respuesta
    $respuesta = new xajaxResponse();
    //Se asignan los valores al objeto y se envia la respuesta
    $respuesta->addAssign("DIV_EFA08", "innerHTML", $mi_cuadro);
    return $respuesta;
}

///////////////////////////////////////////////////////////////////////////////////////////////
// Funcion:     EFA11 (Este es el ID del combo para sede en el formulario)                                                                //
// Descripción: permite recargar en un combo las sedes según la facultad que haya seleccio-  //
//              nado el usuario.                                                             //
// Parametros de entrada:   $facultad= facultad seleccionada por el usuario                  //
// Valores de salida:       Retorna arreglo con las sedes relacionadas                       //
///////////////////////////////////////////////////////////////////////////////////////////////

function EFA02($facultad) {
    
    //rescata el valor de la configuracion
    require_once("clase/config.class.php");
    setlocale(LC_MONETARY, 'en_US');
    $esta_configuracion = new config();
    $configuracion = $esta_configuracion->variable();

    //Buscar un registro que coincida con el valor
    include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/funcionGeneral.class.php");
    include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/html.class.php");


    $conexion = new funcionGeneral();
    //$conexionOracle=$conexion->conectarDB($configuracion,"coordinador");
    $conexion_db = $conexion->conectarDB($configuracion, "coordinador");

    include_once($configuracion["raiz_documento"] . $configuracion["bloques"] . "/adminEspacioFisico/sql.class.php");
    $sql = new SqlAdminEspacioFisico();
        
    if ($facultad != " ") {
        $sedes_sql = $sql->cadena_sql($configuracion, "consultar_sedes", $facultad, "", "");        
        $sedes = $conexion->ejecutarSQL($configuracion, $conexion_db, $sedes_sql, "busqueda");        
        //echo "<br> sedes ";var_dump($sedes);
        $total_reg = count($sedes);
    }

    for ($i = 0; $i < $total_reg; $i++) {
        $sedes_a[$i][0] = $sedes[$i][0];
        $sedes_a[$i][1] = $sedes[$i][1];
        $sedes_a[$i][1] = reemplazar_caracteres($sedes[$i][1]);
    }

    $html = new html();
    $configuracion["ajax_function"] = "xajax_EFA03";
    $configuracion["ajax_control"] = "EFA02";
    $mi_cuadro = $html->cuadro_lista($sedes_a, 'EFA02', $configuracion, -1, 2, FALSE, $tab++, 'EFA02', "");
    $inbox="<input id='EFA06' name='EFA06' type='text' class='field text large' value='".$facultad."' maxlength='255' />";
    
    //se crea el objeto xajax para enviar la respuesta
    $respuesta = new xajaxResponse();    
    $respuesta->addAssign("DIV_EFA06", "innerHTML", $inbox);
    //Se asignan los valores al objeto y se envia la respuesta
    $respuesta->addAssign("DIV_EFA02", "innerHTML", $mi_cuadro);
    
    return $respuesta;
}

///////////////////////////////////////////////////////////////////////////////////////////////
// Funcion:     EFA12 (Este es el ID del combo para edificio en el formulario)                                                                 //
// Descripción: permite recargar en un combo las sedes según la facultad que haya seleccio-  //
//              nado el usuario.                                                             //
// Parametros de entrada:   $facultad= facultad seleccionada por el usuario                  //
// Valores de salida:       Retorna arreglo con las sedes relacionadas                       //
///////////////////////////////////////////////////////////////////////////////////////////////

function EFA03($sede) {
    
    //rescata el valor de la configuracion
    require_once("clase/config.class.php");
    setlocale(LC_MONETARY, 'en_US');
    $esta_configuracion = new config();
    $configuracion = $esta_configuracion->variable();

    //Buscar un registro que coincida con el valor
    include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/funcionGeneral.class.php");
    include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/html.class.php");


    $conexion = new funcionGeneral();
    //$conexionOracle=$conexion->conectarDB($configuracion,"coordinador");
    $conexion_db = $conexion->conectarDB($configuracion, "coordinador");

    include_once($configuracion["raiz_documento"] . $configuracion["bloques"] . "/adminEspacioFisico/sql.class.php");
    $sql = new SqlAdminEspacioFisico();
    if ($sede != "") {
        $edificios_sql = $sql->cadena_sql($configuracion, "consultar_edificios", $sede, "", "");
        $edificios = $conexion->ejecutarSQL($configuracion, $conexion_db, $edificios_sql, "busqueda");
        $total_reg = count($edificios);
    }

    for ($i = 0; $i < $total_reg; $i++) {
        $edificios_a[$i][0] = $edificios[$i][0];
        $edificios_a[$i][1] = $edificios[$i][1];
        $edificios_a[$i][1] = reemplazar_caracteres($edificios[$i][1]);
    }
    
    $configuracion["ajax_function"] = "xajax_EFA04";
    $configuracion["ajax_control"] = "EFA03";
    
    $html = new html();
    $mi_cuadro = $html->cuadro_lista($edificios_a, 'EFA03', $configuracion, -1, 2, FALSE, $tab++, 'EFA03', "");
    $inbox="<input id='EFA06' name='EFA06' type='text' class='field text large' value='".$sede."' maxlength='255' />";
    
    //se crea el objeto xajax para enviar la respuesta
    $respuesta = new xajaxResponse();    
    $respuesta->addAssign("DIV_EFA06", "innerHTML", $inbox);
    //Se asignan los valores al objeto y se envia la respuesta
    $respuesta->addAssign("DIV_EFA03", "innerHTML", $mi_cuadro);
    return $respuesta;
}

////////////////////////////////////////////////////////////////////////////////////////////////
// Funcion:     EFA14 (Este es el ID del combo para el tipo de dependencia encargada)         //
// Descripción: permite recargar en un combo las sedes según la facultad que haya seleccio-   //
//              nado el usuario.                                                              //
// Parametros de entrada:   $dep_encargada= dependencia encargada seleccionada por el usuario //
// Valores de salida:       Retorna arreglo con las sedes relacionadas                        //
////////////////////////////////////////////////////////////////////////////////////////////////

function EFA14($dep_encargada) {

    //rescata el valor de la configuracion
    require_once("clase/config.class.php");
    setlocale(LC_MONETARY, 'en_US');
    $esta_configuracion = new config();
    $configuracion = $esta_configuracion->variable();

    //Buscar un registro que coincida con el valor
    include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/funcionGeneral.class.php");
    include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/html.class.php");


    $conexion = new funcionGeneral();
    //$conexionOracle=$conexion->conectarDB($configuracion,"coordinador");
    $conexion_db = $conexion->conectarDB($configuracion, "coordinador");

    include_once($configuracion["raiz_documento"] . $configuracion["bloques"] . "/adminEspacioFisico/sql.class.php");
    $sql = new SqlAdminEspacioFisico();
    if ($dep_encargada != "") {
        $tipos_sql = $sql->cadena_sql($configuracion, "consultar_tipo_depE", $dep_encargada, "", "");
        $tipos = $conexion->ejecutarSQL($configuracion, $conexion_db, $tipos_sql, "busqueda");
        $total_reg = count($tipos);
    }

    for ($i = 0; $i < $total_reg; $i++) {
        $tipos_a[$i][0] = $tipos[$i][0];
        $tipos_a[$i][1] = $tipos[$i][1];
        $tipos_a[$i][1] = reemplazar_caracteres($tipos[$i][1]);
    }

    //echo "<br> tipos ";var_dump($tipos_a);

    $html = new html();
    $mi_cuadro = $html->cuadro_lista($tipos_a, 'EFA14', $configuracion, -1, 2, FALSE, $tab++, 'EFA14', "");
    $configuracion["ajax_function"] = "";
    $configuracion["ajax_control"] = "";

    //se crea el objeto xajax para enviar la respuesta
    $respuesta = new xajaxResponse();
    //Se asignan los valores al objeto y se envia la respuesta
    $respuesta->addAssign("DIV_EFA14", "innerHTML", $mi_cuadro);
    return $respuesta;
}

function EFA04($edificio){
    
    $inbox="<input id='EFA06' name='EFA06' type='text' class='field text large' value='".$edificio."' maxlength='255' />";
    
    $respuesta = new xajaxResponse();     
    $respuesta->addAssign("DIV_EFA06", "innerHTML", $inbox);
    
     return $respuesta;
}

function buscarEspacioFisicoAcademico($buscarEspacio) {

    //rescata el valor de la configuracion
    require_once("clase/config.class.php");
    setlocale(LC_MONETARY, 'en_US');
    $esta_configuracion = new config();
    $configuracion = $esta_configuracion->variable();

    //Buscar un registro que coincida con el valor
    include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/funcionGeneral.class.php");
    include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/html.class.php");
    include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");

    $conexion = new funcionGeneral();
    $conexion_db = $conexion->conectarDB($configuracion, "coordinador");

    include_once($configuracion["raiz_documento"] . $configuracion["bloques"] . "/adminEspacioFisico/sql.class.php");
    $sql = new SqlAdminEspacioFisico();
    $cripto = new encriptar();

    if (!isset($buscarEspacio) || is_null($buscarEspacio) || ($buscarEspacio) == '') {
        echo "Por favor ingrese el código o nombre del espacio.";
        exit;
    } else {
        $cadenaBusqueda = $sql->cadena_sql($configuracion, "consultarEFA", $buscarEspacio, "", "");
        $espaciosCoinciden = $conexion->ejecutarSQL($configuracion, $conexion_db, $cadenaBusqueda, "busqueda");
        $cant_espacios = count($espaciosCoinciden);
    }

    if ($espaciosCoinciden != FALSE) {
        $htmlInfo = "<table align='center' width='100%' cellpadding='2' cellspacing='2' class='sigma contenidotabla'>";
        $htmlInfo.="<thead class='sigma centrar'><tr class='sigma centrar'>";
        $htmlInfo.="<th align='center' width='33%'>Código Espacio Académico:</th>";
        $htmlInfo.="<th align='center' width='33%'>Nombre Espacio Académico:</th>";
        $htmlInfo.="<th align='center' width='33%'>Ampliar Consulta:</th>";
        $htmlInfo.="</tr></thead>";

        for ($i = 0; $i < $cant_espacios; $i++) {
            if ($i % 2 == 0) {
                $clase = "sigma";
            } else {
                $clase = "";
            }

            $indice = $configuracion["host"] . $configuracion["site"] . "/index.php?";
            $variable = "pagina=adminEspacioFisico";
            $variable.="&opcion=desplegarConsulta";
            $variable.="&espacio=4";
            $variable.="&codEspacio=". $espaciosCoinciden[$i][0];
            $variable = $cripto->codificar_url($variable, $configuracion);

            $htmlInfo.="<tr class=" . $clase . "><td class='centrar' align='center' style='position: relative;'>" . $espaciosCoinciden[$i][0] . "</td>";
            $htmlInfo.="<td class='centrar' align='center' style='position: relative;'>" . $espaciosCoinciden[$i][1] . "</td>";
            $htmlInfo.="<td class='centrar' align='center' style='position: relative;'><a href='" . $indice . $variable . "'>";
            $htmlInfo.="<img src='" . $configuracion['site'] . $configuracion['grafico'] . "/viewmag.png' width='20' height='20' border='0' alt='Consultar'>";
            $htmlInfo.="</a></td></tr>";
        }
    } else {
        $htmlInfo.="<tr><td colspan='3' class='centrar'><b><font color='red'><blink>EL ESPACIO ACAD&Eacute;MICO NO EXISTE</blink></font></b></td></tr>";
    }

    $respuesta = new xajaxResponse();
    $respuesta->addAssign("div_infoEFA", "innerHTML", $htmlInfo);

    return $respuesta;
}


//////////////////////////////////////////////////////////////////////////////////////////////////////
// Funcion:     armarCodigoEspacio (Armar código del espacio Físico según la selección del usuario) //
// Descripción: Permite armar el código de el espacio físico académico según la selección del usua- //
//              rio.                                                                                //
// Parametros de entrada:   $seleccion= Información para el código según la selección del usuario   //
// Valores de salida:       Retorna dato añadido en el inbox de el código del espacio               //
//////////////////////////////////////////////////////////////////////////////////////////////////////

function armarCodigoEspacio($seleccion, $codigo) {
    
    $longitud=strlen($codigo);
    
    if($longitud>11){
        $valor=$codigo;
    }else{
        $valor=$codigo.$seleccion;
    }
    
    $inbox="<input id='EFA06' name='EFA06' type='text' class='field text large' value='".$valor."' maxlength='11' />";
    
    $respuesta = new xajaxResponse();     
    $respuesta->addAssign("DIV_EFA06", "innerHTML", $inbox);
    
     return $respuesta;   
}


//////////////////////////////////////////////////////////////////////////////////////////////////////
// Funcion:     armarCodigoEspacio (Armar código del espacio Físico según la selección del usuario) //
// Descripción: Permite armar el código de el espacio físico académico según la selección del usua- //
//              rio.                                                                                //
// Parametros de entrada:   $seleccion= Información para el código según la selección del usuario   //
// Valores de salida:       Retorna dato añadido en el inbox de el código del espacio               //
//////////////////////////////////////////////////////////////////////////////////////////////////////

function armarCodigoEdificio($seleccion) {
    
    $longitud=strlen($codigo);
    
    if($longitud>11){
        $valor=$codigo;
    }else{
        $valor=$codigo.$seleccion;
    }
    
    $inbox="<input id='E02' name='E02' type='text' class='field text large' value='".$codigo.$seleccion."' maxlength='11' />";
    
    $respuesta = new xajaxResponse();     
    $respuesta->addAssign("DIV_E02", "innerHTML", $inbox);
    
     return $respuesta;   
}


//////////////////////////////////////////////////////////////////////////////////////////////////////
// Funcion:     armarCodigoEspacio (Armar código del espacio Físico según la selección del usuario) //
// Descripción: Permite armar el código de el espacio físico académico según la selección del usua- //
//              rio.                                                                                //
// Parametros de entrada:   $seleccion= Información para el código según la selección del usuario   //
// Valores de salida:       Retorna dato añadido en el inbox de el código del espacio               //
//////////////////////////////////////////////////////////////////////////////////////////////////////

function armarCodigoSede($seleccion) {
    
    $longitud=strlen($codigo);
    
    if($longitud>11){
        $valor=$codigo;
    }else{
        $valor=$codigo.$seleccion;
    }
    
    $inbox="<input id='S02' name='S02' type='text' class='field text large' value='".$codigo.$seleccion."' maxlength='11' />";
    
    $respuesta = new xajaxResponse();     
    $respuesta->addAssign("DIV_S02", "innerHTML", $inbox);
    
     return $respuesta;   
}


///////////////////////////////////////////////////////////////////////////////////////////////
// Funcion:     reemplazar_caracteres                                                        //
// Descripción: funcion que reemplaza los caracteres especiales.                             //
// Parametros de entrada:   cadena  $mensaje                                                 //
// Valores de salida:       Retorna cadena con caracteres reemplazados.                      //
///////////////////////////////////////////////////////////////////////////////////////////////

function reemplazar_caracteres($mensaje) {

    /* $mensaje = str_replace("&aacute;", "á", $mensaje);
      $mensaje = str_replace("&AACUTE;", "Á", $mensaje);
      $mensaje = str_replace("&eacute;", "é", $mensaje);
      $mensaje = str_replace("&EACUTE;", "É", $mensaje);
      $mensaje = str_replace("&iacute;", "í", $mensaje);
      $mensaje = str_replace("&IACUTE;", "Í", $mensaje);
      $mensaje = str_replace("&oacute;", "ó", $mensaje);
      $mensaje = str_replace("&OACUTE;", "Ó", $mensaje);
      $mensaje = str_replace("&uacute;", "ú", $mensaje);
      $mensaje = str_replace("&UACUTE;", "Ú", $mensaje);
      $mensaje = str_replace("&ntilde;", "ñ", $mensaje);
      $mensaje = str_replace("&NTILDE;", "Ñ", $mensaje);
      $mensaje = str_replace("&agrave;", "à", $mensaje);
      $mensaje = str_replace("&AGRAVE;", "À", $mensaje);
      $mensaje = str_replace("&egrave;", "è", $mensaje);
      $mensaje = str_replace("&EGRAVE;", "È", $mensaje);
      $mensaje = str_replace("&igrave;", "ì", $mensaje);
      $mensaje = str_replace("&IGRAVE;", "Ì", $mensaje);
      $mensaje = str_replace("&ograve;", "ò", $mensaje);
      $mensaje = str_replace("&OGRAVE;", "Ò", $mensaje);
      $mensaje = str_replace("&ugrave;", "ù", $mensaje);
      $mensaje = str_replace("&UGRAVE;", "Ù", $mensaje);
      $mensaje = str_replace("&auml;", "ä", $mensaje);
      $mensaje = str_replace("&AUML;", "Ä", $mensaje);
      $mensaje = str_replace("&euml;", "ë", $mensaje);
      $mensaje = str_replace("&EUML;", "Ë", $mensaje);
      $mensaje = str_replace("&iuml;", "ï", $mensaje);
      $mensaje = str_replace("&IUML;", "Ï", $mensaje);
      $mensaje = str_replace("&ouml;", "ö", $mensaje);
      $mensaje = str_replace("&OUML;", "Ö", $mensaje);
      $mensaje = str_replace("&uuml;", "ü", $mensaje);
      $mensaje = str_replace("&UUML;", "Ü", $mensaje);
      $mensaje = str_replace("&acirc;", "â", $mensaje);
      $mensaje = str_replace("&ACIRC;", "Â", $mensaje);
      $mensaje = str_replace("&ecirc;", "ê", $mensaje);
      $mensaje = str_replace("&ECIRC;", "Ê", $mensaje);
      $mensaje = str_replace("&circ;", "î", $mensaje);
      $mensaje = str_replace("&ICIRC;", "Î", $mensaje);
      $mensaje = str_replace("&circ;", "ô", $mensaje);
      $mensaje = str_replace("&OCIRC;", "Ô", $mensaje);
      $mensaje = str_replace("&circ;", "û", $mensaje);
      $mensaje = str_replace("&UCIRC;", "Û", $mensaje);
      $mensaje = str_replace("&Atilde;","N", $mensaje); */
    $mensaje = str_replace("Ñ", "N", $mensaje);
    $mensaje = str_replace("ñ", "N", $mensaje);
    $mensaje = str_replace("á", "a", $mensaje);
    $mensaje = str_replace("é", "e", $mensaje);
    $mensaje = str_replace("í", "i", $mensaje);
    $mensaje = str_replace("ó", "o", $mensaje);
    $mensaje = str_replace("ú", "u", $mensaje);
    $mensaje = str_replace("Á", "A", $mensaje);
    $mensaje = str_replace("É", "E", $mensaje);
    $mensaje = str_replace("Í", "I", $mensaje);
    $mensaje = str_replace("Ó", "O", $mensaje);
    $mensaje = str_replace("Ú", "U", $mensaje);
    $mensaje = str_replace("�", "N", $mensaje);
    $mensaje = str_replace("Ã", "I", $mensaje);

    //echo "reemplazar caracteres   ".$mensaje;;

    return $mensaje;
}

?>
