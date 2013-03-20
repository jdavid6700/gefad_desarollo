-- phpMyAdmin SQL Dump
-- version 2.10.0.2
-- http://www.phpmyadmin.net
-- 
-- Servidor: localhost
-- Tiempo de generación: 02-07-2007 a las 12:47:09
-- Versión del servidor: 4.1.20
-- Versión de PHP: 5.0.4

-- 
-- Base de datos: `sitem`
-- 

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `/*prefijo_db*/bloque`
-- 

CREATE TABLE `/*prefijo_db*/bloque` (
  `id_bloque` int(5) NOT NULL auto_increment,
  `nombre` varchar(50) NOT NULL default '',
  `descripcion` text,
  `grupo` varchar(20) NOT NULL default '',
  PRIMARY KEY  (`id_bloque`),
  KEY `id_bloque` (`id_bloque`)
) TYPE=MyISAM PACK_KEYS=0 COMMENT='Bloques disponibles';

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `/*prefijo_db*/bloque_pagina`
-- 

CREATE TABLE `/*prefijo_db*/bloque_pagina` (
  `id_pagina` int(5) NOT NULL default '0',
  `id_bloque` int(5) NOT NULL default '0',
  `seccion` char(1) NOT NULL default '',
  `posicion` int(2) NOT NULL default '0'
) TYPE=MyISAM COMMENT='Estructura de bloques de las paginas en el aplicativo';

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `/*prefijo_db*/configuracion`
-- 

CREATE TABLE `/*prefijo_db*/configuracion` (
  `id_parametro` int(3) NOT NULL auto_increment,
  `parametro` char(255) NOT NULL default '',
  `valor` char(255) NOT NULL default '',
  PRIMARY KEY  (`id_parametro`),
  KEY `parametro` (`parametro`)
) TYPE=InnoDB COMMENT='Variables de configuracion';

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `/*prefijo_db*/entidad`
-- 

CREATE TABLE `/*prefijo_db*/entidad` (
  `id_entidad` int(9) NOT NULL auto_increment,
  `id_usuario` int(9) NOT NULL default '0',
  `fecha` varchar(50) NOT NULL default '',
  `nombre` text NOT NULL,
  `etiqueta` varchar(255) NOT NULL default '',
  `logosimbolo` text NOT NULL,
  `nit` varchar(255) NOT NULL default '',
  `fundacion` varchar(255) NOT NULL default '',
  `direccion` varchar(255) NOT NULL default '',
  `telefono` varchar(50) NOT NULL default '',
  `web` varchar(255) NOT NULL default '',
  `correo` varchar(255) NOT NULL default '',
  `mision` text NOT NULL,
  `vision` text NOT NULL,
  `descripcion` text NOT NULL,
  `comentario` text NOT NULL,
  PRIMARY KEY  (`id_entidad`)
) TYPE=MyISAM PACK_KEYS=0 COMMENT='Entidades de Salud';

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `/*prefijo_db*/especialidad`
-- 

CREATE TABLE `/*prefijo_db*/especialidad` (
  `id_especialidad` int(9) NOT NULL auto_increment,
  `id_usuario` int(9) NOT NULL default '0',
  `codigo` varchar(255) NOT NULL default '',
  `denominacion` text NOT NULL,
  `etimologia` text NOT NULL,
  `definicion` text NOT NULL,
  `descripcion` text NOT NULL,
  `especialista` text NOT NULL,
  `perfil` text NOT NULL,
  `fecha` varchar(100) NOT NULL default '',
  `imagen` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id_especialidad`)
) TYPE=MyISAM PACK_KEYS=0;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `/*prefijo_db*/estilo`
-- 

CREATE TABLE `/*prefijo_db*/estilo` (
  `usuario` char(50) NOT NULL default '0',
  `estilo` char(50) NOT NULL default '',
  PRIMARY KEY  (`usuario`,`estilo`)
) TYPE=MyISAM COMMENT='Estilo de pagina en el sitio';

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `/*prefijo_db*/logger`
-- 

CREATE TABLE `/*prefijo_db*/logger` (
  `id_usuario` varchar(5) NOT NULL default '',
  `evento` varchar(255) NOT NULL default '',
  `fecha` varchar(50) NOT NULL default ''
) TYPE=MyISAM COMMENT='Registro de acceso de los usuarios';

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `/*prefijo_db*/pagina`
-- 

CREATE TABLE `/*prefijo_db*/pagina` (
  `id_pagina` int(5) NOT NULL auto_increment,
  `nombre` char(50) NOT NULL default '',
  `descripcion` char(250) NOT NULL default '',
  `modulo` char(50) NOT NULL default '',
  `nivel` int(2) NOT NULL default '0',
  PRIMARY KEY  (`id_pagina`),
  UNIQUE KEY `id_pagina` (`id_pagina`)
) TYPE=MyISAM PACK_KEYS=0 COMMENT='Relacion de paginas en el aplicativo';

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `/*prefijo_db*/procedimiento`
-- 


-- 
-- Estructura de tabla para la tabla `/*prefijo_db*/procedimiento`
-- 

CREATE TABLE `/*prefijo_db*/procedimiento` (
  `id_procedimiento` int(11) NOT NULL default '0',
  `denominacion` text NOT NULL,
  `descripcion` text NOT NULL,
  `cups` varchar(50) NOT NULL default '',
  `imagen` varchar(255) NOT NULL default '',
  `id_usuario` int(9) NOT NULL default '0',
  `fecha` varchar(50) NOT NULL default ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Procedimientos medicos';

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `/*prefijo_db*/registrado`
-- 

CREATE TABLE `/*prefijo_db*/registrado` (
  `id_usuario` int(4) NOT NULL auto_increment,
  `nombre` char(250) NOT NULL default '',
  `apellido` char(250) NOT NULL default '',
  `correo` char(100) NOT NULL default '',
  `telefono` char(50) NOT NULL default '',
  `usuario` char(50) NOT NULL default '',
  `clave` char(50) NOT NULL default '',
  PRIMARY KEY  (`id_usuario`),
  KEY `id_usuario` (`id_usuario`)
) TYPE=MyISAM PACK_KEYS=0;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `/*prefijo_db*/registrado_borrador`
-- 

CREATE TABLE `/*prefijo_db*/registrado_borrador` (
  `nombre` char(250) NOT NULL default '',
  `apellido` char(250) NOT NULL default '',
  `correo` char(100) NOT NULL default '',
  `telefono` char(50) NOT NULL default '',
  `usuario` char(50) NOT NULL default '',
  `identificador` char(50) NOT NULL default ''
) TYPE=MyISAM PACK_KEYS=0;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `/*prefijo_db*/registrado_subsistema`
-- 

CREATE TABLE `/*prefijo_db*/registrado_subsistema` (
  `id_usuario` int(6) NOT NULL default '0',
  `id_subsistema` int(6) NOT NULL default '0',
  `estado` int(2) NOT NULL default '0'
) TYPE=MyISAM COMMENT='Relacion de usuarios que tienen acceso a modulos especiales';

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `/*prefijo_db*/subsistema`
-- 

CREATE TABLE `/*prefijo_db*/subsistema` (
  `id_subsistema` int(7) NOT NULL auto_increment,
  `nombre` varchar(250) NOT NULL default '',
  `etiqueta` varchar(100) NOT NULL default '',
  `id_pagina` int(7) NOT NULL default '0',
  `observacion` text,
  PRIMARY KEY  (`id_subsistema`)
) TYPE=MyISAM PACK_KEYS=0 COMMENT='Subsistemas que componen el aplicativo';

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `/*prefijo_db*/valor_sesion`
-- 

CREATE TABLE `/*prefijo_db*/valor_sesion` (
  `id_sesion` varchar(32) NOT NULL default '',
  `variable` varchar(20) NOT NULL default '',
  `valor` text NOT NULL,
  PRIMARY KEY  (`id_sesion`,`variable`)
) TYPE=MyISAM COMMENT='Valores de sesion';
