<!DOCTYPE html>
<html>

    <head>
        <title>Sistema Gesti&oacute;n Financiera y Administrativa</title>

        <?
        /*
          ############################################################################
          #    UNIVERSIDAD DISTRITAL Francisco Jose de Caldas                        #
          #    Desarrollo Por:                                                       #
          #    Paulo Cesar Coronado 2004 - 2005                                      #
          #    paulo_cesar@etb.net.co                                                #
          #    Copyright: Vea el archivo EULA.txt que viene con la distribucion      #
          ############################################################################
         */
        ?><?
        /*         * *************************************************************************

          html.php

          Paulo Cesar Coronado
          Copyright (C) 2001-2005

          Última revisión 6 de Marzo de 2006

         * ***************************************************************************
         * @subpackage
         * @package	formulario
         * @copyright
         * @version      0.2
         * @author      	Paulo Cesar Coronado
         * @link		http://acreditacion.udistrital.edu.co
         *
         *
         * Codigo HTML del formulario de autenticacion de usuarios
         *
         * *************************************************************************** */




        $formulario = "autenticacion";
        $validar = "control_vacio(" . $formulario . ",'usuario')";
        $validar.="&&control_vacio(" . $formulario . ",'clave')";


        if (!isset($GLOBALS["autorizado"])) {
            include("../index.php");
            exit;
        }
        ?>





        <link	href="<? echo $configuracion["host"] . $configuracion["site"] . $configuracion["bloques"] ?>/login/estilo_index.css"	rel="stylesheet" type="text/css" />
    </head>
    <body onLoad="inicio();">
        <div id="C">
            <div id="contenedor">
                <div id="encabezado_pagina">
                </div>
                <br>

                <div id="login">
                    <script
                        src="<? echo $configuracion["host"] . $configuracion["site"] . $configuracion["javascript"] ?>/md5.js"
                    type="text/javascript" language="javascript"></script>
                    <form method="post" action="index.php" name="<? echo $formulario ?>">
                        <h3>Ingreso al sistema</h3>
                        <p class="name">  
                            <input type="text" name="usuario" id="usuario" tabindex="<? echo $tab++ ?>" />  
                            <label for="usuario">Usuario</label>  
                        </p>  
                        <p class="pass">  
                            <input maxlength="50" size="12" tabindex="<? echo $tab++ ?>"
                                   name="clave" type="password" id="pass"
                                   onkeypress="if (valida_enter(event) == false)
                                           {
                                               if (<? echo $formulario ?>.clave.value != '') {<? echo $formulario ?>.clave.value = hex_md5(<? echo $formulario ?>.clave.value)
                                               }
                                               ;
                                               return(<? echo $validar; ?>) ? document.forms['<? echo $formulario ?>'].submit() : false
                                           }" /> 
                            <label for="email">Contrase&ntilde;a</label>  
                        </p>  
                        <input type="hidden" name="action" value="login">
                        <!input name="submit" type="submit" value="Entrar" class="Estilo6" onClick="enviarDatos();" style="height:22; width:90; cursor:pointer" >
                        <input name="aceptar" 
                               value="Ingresar" 
                               type="submit"
                               onclick="if (<? echo $formulario ?>.clave.value != '')
                                           {<? echo $formulario ?>.clave.value = hex_md5(<? echo $formulario ?>.clave.value)
                                           }
                                           ;
                                           return(<? echo $validar; ?>) ? document.forms['<? echo $formulario ?>'].submit() : false"
                               ><br>
                    </form>
                    <br> <br> <br> 
                </div>
                <div id="contenido">
                    <h2>BIENVENIDOS</h2>
                    <p>El objetivo de el Sistema de Gesti&oacuten Financiera y Administrativa es integrar los servicios de las diferentes &aacute;reas de las dependencias financieras para el mejoramiento de las actividades administrativas.</p>
                </div>
                <div id="sabio"> 
                </div>
                <div id="menu">
                    <h3>Portales UD</h3>
                    <ul>
                        <li><a href="http://www.udistrital.edu.co/"target="_blank">Universidad Distrital FJC</a></li>
                        <li><a href="https://condor.udistrital.edu.co/appserv/"target="_blank">C&oacute;ndor</a></li>
                    </ul>
                </div>
                <div id="escudo"></div>
                <div id="pie">
                    Universidad Distrital Francisco Jos&eacute; de Caldas <br>
                    Oficina Asesora de Sistemas 2013. Todos los derechos reservados.<br>
                    Carrera 8 N. 40-78 Piso 1 / Teléfonos 3238400 - 3239300 Ext. 1112 -1113.<br>
                    <a href="mailto:computo@udistrital.edu.co" class="enlace">computo@udistrital.edu.co</a>
                </div>
            </div>
    </body>
     <script LANGUAGE="JavaScript">
                function inicio()
                    { document.forms['<? echo $formulario ?>'].elements['usuario'].focus();
                    }								
        </script>

    
</html>


