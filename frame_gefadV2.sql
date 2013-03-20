/* SCRIPT DE CREACION DE LA BASE DE DATOS PARA EL FRAME DE GEFAD
*  MARZO 19 DE 2013
*  MARITZA CALLEJAS - JAIRO lAVADO
*/


CREATE DATABASE IF NOT EXISTS frame_gefad DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci ;

GRANT ALL PRIVILEGES ON frame_gefad.*
TO 'frame_gefad'@'localhost'
IDENTIFIED BY 'admin_gefad2013'
WITH GRANT OPTION;

--
-- Base de datos: `frame_gefad`
--
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `frame_gefad`.`gestion_bloque`
--

DROP TABLE IF EXISTS `frame_gefad`.`gestion_bloque`;
CREATE TABLE IF NOT EXISTS `frame_gefad`.`gestion_bloque` (
  `id_bloque` int(5) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `descripcion` text CHARACTER SET latin1,
  `grupo` varchar(100) CHARACTER SET latin1 NOT NULL DEFAULT '',
  PRIMARY KEY (`id_bloque`),
  KEY `id_bloque` (`id_bloque`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci PACK_KEYS=0 ROW_FORMAT=DYNAMIC COMMENT='Bloques disponibles' AUTO_INCREMENT=17 ;

--
-- Volcado de datos para la tabla `frame_gefad`.`gestion_bloque`
--

INSERT INTO `frame_gefad`.`gestion_bloque` (`id_bloque`, `nombre`, `descripcion`, `grupo`) VALUES
(1, 'mensaje', 'Bloque para gestionar el contenido estatico de una pagina', ''),
(2, 'registro_usuario', 'Bloque con el formulario de registro de usuarios', 'administracion/usuarios'),
(3, 'pie', 'Pie de pagina', ''),
(4, 'banner', 'Banner para las paginas de contenido', ''),
(5, 'login', 'Formulario principal para el ingreso de nombre de usuario y clave', ''),
(6, 'logout', 'Bloque para gestionar la terminacion de sesiones en el sistema', ''),
(7, 'menu_administrador', 'menu principal para el submodulo de administrador', 'administracion'),
(8, 'borrar_registro', 'Bloque principal para borrar registros', ''),
(9, 'admin_usuario', 'bloque para administracion de usuarios', 'administracion/usuarios'),
(10, 'menu_usuario', 'menu para la administracion de ususarios', 'administracion/usuarios'),
(11, 'menu_general', 'menu general', 'administracion'),
(12, 'admin_general', 'bloque para administracion de los daos basicos', 'administracion'),
(13, 'menu_supervisor', 'menu principal para el submodulo de supervisor', 'nomina/contratistas'),
(14, 'nom_admin_novedad', 'bloque para administracion de novedades', 'nomina/contratistas'),
(15, 'nom_menu_novedad', 'Contiene opciones para las novedades', 'nomina/contratistas'),
(16, 'nom_admin_cumplido', NULL, 'nomina/contratistas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `frame_gefad`.`gestion_bloque_pagina`
--

DROP TABLE IF EXISTS `frame_gefad`.`gestion_bloque_pagina`;
CREATE TABLE IF NOT EXISTS `frame_gefad`.`gestion_bloque_pagina` (
  `id_pagina` int(5) NOT NULL DEFAULT '0',
  `id_bloque` int(5) NOT NULL DEFAULT '0',
  `seccion` char(1) COLLATE utf8_spanish_ci NOT NULL DEFAULT '',
  `posicion` int(2) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Estructura de bloques de las paginas en el aplicativo';

--
-- Volcado de datos para la tabla `frame_gefad`.`gestion_bloque_pagina`
--

INSERT INTO `frame_gefad`.`gestion_bloque_pagina` (`id_pagina`, `id_bloque`, `seccion`, `posicion`) VALUES
(1, 1, 'C', 1),
(1, 3, 'E', 1),
(1, 4, 'A', 1),
(1, 5, 'B', 2),
(2, 2, 'C', 1),
(3, 1, 'C', 1),
(3, 3, 'E', 1),
(3, 4, 'A', 1),
(3, 7, 'B', 2),
(3, 11, 'B', 1),
(3, 6, 'B', 3),
(4, 6, 'C', 1),
(5, 8, 'C', 1),
(5, 4, 'A', 1),
(5, 3, 'E', 1),
(6, 4, 'A', 1),
(6, 3, 'E', 1),
(6, 6, 'B', 3),
(6, 11, 'B', 1),
(6, 9, 'C', 1),
(6, 10, 'D', 1),
(6, 7, 'B', 2),
(7, 4, 'A', 1),
(7, 3, 'E', 1),
(7, 6, 'B', 2),
(7, 11, 'B', 1),
(7, 12, 'C', 1),
(8, 1, 'C', 1),
(8, 3, 'E', 1),
(8, 4, 'A', 1),
(8, 13, 'B', 2),
(8, 11, 'B', 1),
(8, 6, 'B', 3),
(9, 14, 'C', 1),
(9, 4, 'A', 1),
(9, 3, 'E', 1),
(9, 6, 'B', 3),
(9, 11, 'B', 1),
(9, 13, 'B', 2),
(10, 4, 'A', 1),
(10, 16, 'C', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `frame_gefad`.`gestion_configuracion`
--

DROP TABLE IF EXISTS `frame_gefad`.`gestion_configuracion`;
CREATE TABLE IF NOT EXISTS `frame_gefad`.`gestion_configuracion` (
  `id_parametro` int(3) NOT NULL AUTO_INCREMENT,
  `parametro` char(255) COLLATE utf8_spanish_ci NOT NULL DEFAULT '',
  `valor` char(255) COLLATE utf8_spanish_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id_parametro`),
  KEY `parametro` (`parametro`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Variables de configuracion' AUTO_INCREMENT=24 ;

--
-- Volcado de datos para la tabla `frame_gefad`.`gestion_configuracion`
--

INSERT INTO `frame_gefad`.`gestion_configuracion` (`id_parametro`, `parametro`, `valor`) VALUES
(1, 'titulo', 'Sistema de Gestión Financiera y Administrativa - Universidad Distrital Francisco José de Caldas'),
(2, 'raiz_documento', '/usr/local/apache/htdocs/gefad'),
(3, 'host', 'http://10.20.0.52'),
(4, 'site', '/gefad'),
(5, 'clave', 'gestion'),
(6, 'correo', 'computo@udistrital.edu.co'),
(7, 'prefijo', 'gestion_'),
(8, 'registro', '10'),
(9, 'expiracion', '1440'),
(10, 'wikipedia', 'http://es.wikipedia.org/wiki/'),
(11, 'enlace', 'gefad'),
(12, 'tamanno_gui', '90%'),
(13, 'grafico', '/grafico'),
(14, 'bloques', '/bloque'),
(15, 'javascript', '/funcion'),
(16, 'documento', '/documento'),
(17, 'estilo', '/estilo'),
(18, 'clases', '/clase'),
(19, 'configuracion', '/configuracion'),
(20, 'plugins', '/plugins');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `frame_gefad`.`gestion_dbms`
--

DROP TABLE IF EXISTS `frame_gefad`.`gestion_dbms`;
CREATE TABLE IF NOT EXISTS `frame_gefad`.`gestion_dbms` (
  `nombre` char(50) COLLATE utf8_spanish_ci NOT NULL,
  `dbms` char(20) COLLATE utf8_spanish_ci NOT NULL,
  `servidor` char(50) COLLATE utf8_spanish_ci NOT NULL,
  `puerto` int(6) NOT NULL,
  `ssl` char(50) COLLATE utf8_spanish_ci NOT NULL,
  `db` char(100) COLLATE utf8_spanish_ci NOT NULL,
  `usuario` char(100) COLLATE utf8_spanish_ci NOT NULL,
  `password` char(100) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`nombre`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `frame_gefad`.`gestion_dbms`
--

INSERT INTO `frame_gefad`.`gestion_dbms` (`nombre`, `dbms`, `servidor`, `puerto`, `ssl`, `db`, `usuario`, `password`) VALUES
('localxe', 'oci8', 'localhost', 1521, '', 'XE', 'system', 'system'),
('mysqlFrame', 'mysql', 'localhost', 3306, '0', 'frame_gefad', 'pAGFfiy7SFE8jqhcYMbM247gnA', 'pgH-aiy7SFFDEhI-AlYmuwpC8VudPFI'),
('oracleSIC', 'oci8', '10.20.0.7', 1521, '', 'CONSULTA_PROD', 'FwO2VwLLSFFj3Ucw0EM', 'GQN1QwLLSFEzRg3wMZQ'),
('nominapg', 'pgsql', '10.20.0.52', 5432, '0', 'gestionUD', 'DwM6SQ3SSFGDhZQpbTg', 'EANHDA3SSFHh7viOJ60');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `frame_gefad`.`gestion_estilo`
--

DROP TABLE IF EXISTS `frame_gefad`.`gestion_estilo`;
CREATE TABLE IF NOT EXISTS `frame_gefad`.`gestion_estilo` (
  `usuario` char(50) COLLATE utf8_spanish_ci NOT NULL DEFAULT '0',
  `estilo` char(50) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`usuario`,`estilo`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Estilo de pagina en el sitio';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `frame_gefad`.`gestion_logger`
--

DROP TABLE IF EXISTS `frame_gefad`.`gestion_logger`;
CREATE TABLE IF NOT EXISTS `frame_gefad`.`gestion_logger` (
  `id_usuario` varchar(5) COLLATE utf8_spanish_ci NOT NULL DEFAULT '',
  `evento` varchar(255) COLLATE utf8_spanish_ci NOT NULL DEFAULT '',
  `fecha` varchar(50) COLLATE utf8_spanish_ci NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Registro de acceso de los usuarios';

--
-- Volcado de datos para la tabla `frame_gefad`.`gestion_logger`
--

INSERT INTO `frame_gefad`.`gestion_logger` (`id_usuario`, `evento`, `fecha`) VALUES
('1', 'Salida del sistema.', '1360793328'),
('1', 'Salida del sistema.', '1360793682'),
('1', 'Salida del sistema.', '1360871358'),
('1', 'Salida del sistema.', '1360872391'),
('2', 'Salida del sistema.', '1361825079'),
('2', 'Salida del sistema.', '1361825606'),
('2', 'Salida del sistema.', '1362667274'),
('2', 'Salida del sistema.', '1363123673'),
('4', 'Salida del sistema.', '1363124563'),
('4', 'Salida del sistema.', '1363124768'),
('4', 'Salida del sistema.', '1363190679'),
('4', 'Salida del sistema.', '1363190721'),
('4', 'Salida del sistema.', '1363190794'),
('4', 'Salida del sistema.', '1363205115'),
('2', 'Salida del sistema.', '1363205153'),
('4', 'Salida del sistema.', '1363205998'),
('2', 'Salida del sistema.', '1363206715'),
('4', 'Salida del sistema.', '1363274060'),
('4', 'Salida del sistema.', '1363282748'),
('4', 'Salida del sistema.', '1363724732'),
('4', 'Salida del sistema.', '1363727481'),
('1', 'Salida del sistema.', '1363728773');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `frame_gefad`.`gestion_log_usuario`
--

DROP TABLE IF EXISTS `frame_gefad`.`gestion_log_usuario`;
CREATE TABLE IF NOT EXISTS `frame_gefad`.`gestion_log_usuario` (
  `id_usuario` int(4) NOT NULL,
  `accion` char(100) COLLATE utf8_spanish_ci NOT NULL,
  `id_registro` int(11) NOT NULL,
  `tipo_registro` char(100) COLLATE utf8_spanish_ci NOT NULL,
  `nombre_registro` char(255) COLLATE utf8_spanish_ci NOT NULL,
  `fecha_log` char(100) COLLATE utf8_spanish_ci NOT NULL,
  `descripcion` char(255) COLLATE utf8_spanish_ci NOT NULL,
  KEY `id_usuario` (`id_usuario`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `frame_gefad`.`gestion_pagina`
--

DROP TABLE IF EXISTS `frame_gefad`.`gestion_pagina`;
CREATE TABLE IF NOT EXISTS `frame_gefad`.`gestion_pagina` (
  `id_pagina` int(7) NOT NULL AUTO_INCREMENT,
  `nombre` char(50) COLLATE utf8_spanish_ci NOT NULL DEFAULT '',
  `descripcion` char(250) COLLATE utf8_spanish_ci NOT NULL DEFAULT '',
  `modulo` char(50) COLLATE utf8_spanish_ci NOT NULL DEFAULT '',
  `nivel` int(2) NOT NULL DEFAULT '0',
  `parametro` char(255) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id_pagina`),
  UNIQUE KEY `id_pagina` (`id_pagina`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci PACK_KEYS=0 ROW_FORMAT=FIXED COMMENT='Relacion de paginas en el aplicativo' AUTO_INCREMENT=11 ;

--
-- Volcado de datos para la tabla `frame_gefad`.`gestion_pagina`
--

INSERT INTO `frame_gefad`.`gestion_pagina` (`id_pagina`, `nombre`, `descripcion`, `modulo`, `nivel`, `parametro`) VALUES
(1, 'index', 'Pagina Principal del SINOC', 'GENERAL', 0, ''),
(2, 'registroUsuario', 'Pagina Principal con formulario para registro de usuario', 'GENERAL', 0, ''),
(3, 'administrador', 'Pagina principal del subsistema de administracion', 'ADMINISTRADOR', 1, ''),
(4, 'logout', 'Pagina intermedia para la finalizacion de seseiones', 'GENERAL', 0, ''),
(5, 'borrar_registro', 'Pagina general para borrar registros en el sistema', 'GENERAL', 0, ''),
(6, 'adminUsuario', 'Pagina para la administracion de los usuarios', 'ADMINISTRADOR', 1, '&xajax=AREA&xajax_file=Usuarios'),
(7, 'adminGeneral', 'Pagina para la administracion de DATOS BASICOS', 'GENERAL', 1, ''),
(8, 'supervisor', 'Pagina principal del subsistema de supervisor', 'GENERAL', 1, ''),
(9, 'nom_adminNovedad', '', 'NOVEDADES', 1, ''),
(10, 'nom_adminCumplido', 'Gestiona los cumplidos', 'CUMPLIDO', 0, '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `frame_gefad`.`gestion_registrado`
--

DROP TABLE IF EXISTS `frame_gefad`.`gestion_registrado`;
CREATE TABLE IF NOT EXISTS `frame_gefad`.`gestion_registrado` (
  `id_usuario` int(7) NOT NULL AUTO_INCREMENT,
  `identificacion` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `nombre` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `apellido` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `correo` char(250) COLLATE utf8_spanish_ci NOT NULL,
  `telefono1` int(7) NOT NULL,
  `extensiones1` varchar(50) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Guarda las extensiones separadas por guion(-)',
  `usuario` char(50) COLLATE utf8_spanish_ci NOT NULL,
  `clave` char(50) COLLATE utf8_spanish_ci NOT NULL,
  `celular` varchar(15) COLLATE utf8_spanish_ci DEFAULT NULL,
  `fecha_registro` date NOT NULL,
  `estado` varchar(1) COLLATE utf8_spanish_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_usuario`),
  KEY `id_usuario` (`id_usuario`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci PACK_KEYS=0 ROW_FORMAT=FIXED AUTO_INCREMENT=5 ;

--
-- Volcado de datos para la tabla `frame_gefad`.`gestion_registrado`
--

INSERT INTO `frame_gefad`.`gestion_registrado` (`id_usuario`, `identificacion`, `nombre`, `apellido`, `correo`, `telefono1`, `extensiones1`, `usuario`, `clave`, `celular`, `fecha_registro`, `estado`) VALUES
(1, '1', 'ADMINISTRADOR', 'ADMINISTRADOR', '', 0, '0', 'administrador', '21232f297a57a5a743894a0e4a801fc3', NULL, '2013-02-13', '1'),
(3, '0', 'SIN', 'ASIGNAR', 'NA', 0, 'NA', 'NA', 'NA', NULL, '2011-08-01', '0'),
(2, '53091267', 'maritza', 'callejas', 'fmcallejasc@correo.udistrital.edu.co', 0, '', '53091267', 'e10adc3949ba59abbe56e057f20f883e', NULL, '2013-02-13', '1'),
(4, '80723875', 'IVAN', 'CRISTANCHO', 'fmcallejasc@correo.udistrital.edu.co', 0, '', '80723875', 'e10adc3949ba59abbe56e057f20f883e', NULL, '2013-02-13', '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `frame_gefad`.`gestion_registrado_borrador`
--

DROP TABLE IF EXISTS `frame_gefad`.`gestion_registrado_borrador`;
CREATE TABLE IF NOT EXISTS `frame_gefad`.`gestion_registrado_borrador` (
  `nombre` char(250) NOT NULL DEFAULT '',
  `apellido` char(250) NOT NULL DEFAULT '',
  `correo` char(100) NOT NULL DEFAULT '',
  `telefono` char(50) NOT NULL DEFAULT '',
  `usuario` char(50) NOT NULL DEFAULT '',
  `identificador` char(50) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1 PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `frame_gefad`.`gestion_registrado_subsistema`
--

DROP TABLE IF EXISTS `frame_gefad`.`gestion_registrado_subsistema`;
CREATE TABLE IF NOT EXISTS `frame_gefad`.`gestion_registrado_subsistema` (
  `id_usuario` int(7) NOT NULL DEFAULT '0',
  `id_subsistema` int(7) NOT NULL DEFAULT '0',
  `estado` int(2) NOT NULL DEFAULT '0',
  `id_dependencia` int(7) NOT NULL COMMENT 'CAMPO QUE INDICA EL CODIGO DE LA DEPENDENCIA A LA QUE PERTENECE EL USUARIO',
  `fecha_registro` date NOT NULL COMMENT 'CAMPO EN EL QUE SE ALMACENA LA FECHA DE REGISTRO DEL USUARIO PARA EL AREA Y PERFIL',
  `fecha_fin` date DEFAULT NULL COMMENT 'CAMPO QUE INDICA LA FECHA EN QUE SE DESACTIVA EL USUARIO'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Relacion de usuarios que tienen acceso a modulos especiales';

--
-- Volcado de datos para la tabla `frame_gefad`.`gestion_registrado_subsistema`
--

INSERT INTO `frame_gefad`.`gestion_registrado_subsistema` (`id_usuario`, `id_subsistema`, `estado`, `id_dependencia`, `fecha_registro`, `fecha_fin`) VALUES
(1, 1, 1, 0, '2013-02-13', '2020-12-31'),
(2, 3, 1, 0, '2013-02-13', '2017-05-11'),
(4, 3, 1, 0, '2013-02-13', '2017-05-11');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `frame_gefad`.`gestion_subsistema`
--

DROP TABLE IF EXISTS `frame_gefad`.`gestion_subsistema`;
CREATE TABLE IF NOT EXISTS `frame_gefad`.`gestion_subsistema` (
  `id_subsistema` int(7) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(250) COLLATE utf8_spanish_ci NOT NULL DEFAULT '',
  `etiqueta` varchar(100) COLLATE utf8_spanish_ci NOT NULL DEFAULT '',
  `id_pagina` int(7) NOT NULL DEFAULT '0',
  `observacion` text COLLATE utf8_spanish_ci,
  PRIMARY KEY (`id_subsistema`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci PACK_KEYS=0 COMMENT='Subsistemas que componen el aplicativo' AUTO_INCREMENT=6 ;

--
-- Volcado de datos para la tabla `frame_gefad`.`gestion_subsistema`
--

INSERT INTO `frame_gefad`.`gestion_subsistema` (`id_subsistema`, `nombre`, `etiqueta`, `id_pagina`, `observacion`) VALUES
(1, 'administrador', 'Administrador', 3, 'Subsistema para la administracion del Sistema'),
(2, 'ordenador', 'Ordenador del Gasto', 8, 'Subsistema para el ordenador del gasto'),
(3, 'supervisor', 'Radicaci&oacute;n', 9, 'Subsistema para la supervisor'),
(4, 'contratista', 'Tramite', 10, 'Subsistema para el contratista');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `frame_gefad`.`gestion_valor_sesion`
--

DROP TABLE IF EXISTS `frame_gefad`.`gestion_valor_sesion`;
CREATE TABLE IF NOT EXISTS `frame_gefad`.`gestion_valor_sesion` (
  `id_sesion` varchar(32) COLLATE utf8_spanish_ci NOT NULL DEFAULT '',
  `variable` varchar(20) COLLATE utf8_spanish_ci NOT NULL DEFAULT '',
  `valor` text COLLATE utf8_spanish_ci NOT NULL,
  `expiracion` varchar(20) COLLATE utf8_spanish_ci DEFAULT '',
  `id_usuario` varchar(20) COLLATE utf8_spanish_ci DEFAULT '',
  PRIMARY KEY (`id_sesion`,`variable`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Valores de sesion';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `frame_gefad`.`gestion_variable`
--

DROP TABLE IF EXISTS `frame_gefad`.`gestion_variable`;
CREATE TABLE IF NOT EXISTS `frame_gefad`.`gestion_variable` (
  `id_tipo` int(4) NOT NULL AUTO_INCREMENT,
  `valor` char(100) COLLATE utf8_spanish_ci NOT NULL,
  `descripcion` char(255) COLLATE utf8_spanish_ci NOT NULL,
  `tipo` char(100) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id_tipo`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci ROW_FORMAT=FIXED AUTO_INCREMENT=5 ;

--
-- Volcado de datos para la tabla `frame_gefad`.`gestion_variable`
--

INSERT INTO `frame_gefad`.`gestion_variable` (`id_tipo`, `valor`, `descripcion`, `tipo`) VALUES
(1, 'CEDULA CIUDADANIA', '', 'DOCUMENTO'),
(2, 'CEDULA DE EXTRANJERIA', '', 'DOCUMENTO'),
(3, 'GENERAL', 'Noticias Generales', 'NOTICIA'),
(4, 'DEVOLUCIONES', 'Noticias de devoluciones de tramite', 'NOTICIA');

