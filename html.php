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
/* * *************************************************************************

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
?>
<style>
    body{ text-align: center; font-family: Tahoma, Arial, Geneva, sans-serif; color:#797979;}
    #login { width: 300px; float: left}
    #login { font-size: 10px; color: #6682D4; text-decoration: none;}
    #login { margin:0; padding-left:80px; }
    #login input, textarea {   
        padding: 5px;  
        border: solid 1px #E5E5E5;  
        outline: 0;  
        font: normal 10px/100% Verdana, Tahoma, sans-serif;  
        width: 150px;  
        background: #FFFFFF;  
    }  

    #menu{ width: 300px; float: left; text-transform: uppercase}
    div#menu ul li{border-left: 3px solid #002EB8; margin-top: 6px; padding: 3px 0 2px 4px; list-style:none; }
    #menu ul li a{ font-size: 13px; color: #6682D4; text-decoration: none;}
    #menu ul li a:hover{ color: #002EB8 }
    #menu ul{ margin:0; padding-left:80px; }
    #menu h3 {color:#002EB8; font-size: 18px; font-weight: normal; padding-left: 80px;}

    input{text-decoration:none;
          font-weight: bold;
          color: #5C5CFF;
          background-color: #99CCFF;
       }


</style>




<script
    src="<? echo $configuracion["host"] . $configuracion["site"] . $configuracion["javascript"] ?>/md5.js"
type="text/javascript" language="javascript"></script>
<form method="post" action="index.php" name="<? echo $formulario ?>">
 
            <div id="login">
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
            <input name="aceptar" value="Ingresar" type="button"
                   onclick="if (<? echo $formulario ?>.clave.value != '')
                {<? echo $formulario ?>.clave.value = hex_md5(<? echo $formulario ?>.clave.value)};
                                   return(<? echo $validar; ?>) ? document.forms['<? echo $formulario ?>'].submit() : false"
                                                   tabindex='<? echo $tab++ ?>'><br>
            </div>
</form>
</div>





