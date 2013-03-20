<?php
/*
WQI55POzSFHRzd2bVg
WgJTnvOzSFGBum9pQyPCfxI
XAI9efOzSFF9WuGWB2Ba65EzkQ
XQKM1fOzSFHIe3ub8Mf-1Fse0w
XwLSGvOzSFFeqsQIVUE_zkZzXsYrFr4
YAJArPOzSFFrfEAfIS1EcQ
*/

// Listado de posibles fuentes para la dirección IP, en orden de prioridad
        	$fuentes_ip = array(
            	"HTTP_X_FORWARDED_FOR",
            	"HTTP_X_FORWARDED",
            	"HTTP_FORWARDED_FOR",
            	"HTTP_FORWARDED",
            	"HTTP_X_COMING_FROM",
            	"HTTP_COMING_FROM",
            	"REMOTE_ADDR",
        	);

        	foreach ($fuentes_ip as $fuentes_ip) {
            		// Si la fuente existe captura la IP
            		if (isset($_SERVER[$fuentes_ip])) {
            		    	$proxy_ip = $_SERVER[$fuentes_ip];
            		    	break;
            		}
        	}

        	$proxy_ip = (isset($proxy_ip)) ? $proxy_ip : @getenv("REMOTE_ADDR");
        	// Regresa la IP
        
?><html>
<head>
	<title>Acceso no autorizado.</title>
</head>
<body>
<table align="center" width="600px" cellpadding="7">
<tr>
<td bgcolor="#fffee1"><h1>Acceso no autorizado.</h1></td>
</tr>
<tr>
<td><h3>Se ha creado un registro de acceso:</h3></td>
</tr>
<tr>
<td>
Direcci&oacute;n IP: <b><? echo $proxy_ip ?></b><br>
Hora de acceso ilegal:<b> <? echo date("d-m-Y h:m:s",time())?></b><br>
Navegador y sistema operativo utilizado:<b><?echo $_SERVER["HTTP_USER_AGENT"]?></b><br>
</td>
</tr>
<tr>
<td style="font-size:12;">
<hr>
Nota: Otras variables se han capturado y almacenado en nuestras bases de datos.<br>
</td>
</tr>
</table>
</body>
<html>
