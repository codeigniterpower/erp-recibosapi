-- -----------------------------------------------------
-- Schema elretenciondb
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema elretenciondb : WARNING SQLITE NO SOPORTE COMMENT KEYWORD!
-- -----------------------------------------------------
-- CREATE SCHEMA IF NOT EXISTS `elretenciondb` DEFAULT CHARACTER SET latin1 ;
-- USE `elretenciondb` ;

-- -----------------------------------------------------
-- Table `apirec_recibo_adjunto`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `apirec_recibo_adjunto` ;

CREATE TABLE IF NOT EXISTS `apirec_recibo_adjunto` (
  `adjunto_recibo_id` VARCHAR(80) NOT NULL COMMENT 'YYYYMMDDHHMMSS id de este adjunto',
  `adjunto_recibo` BINARY NULL COMMENT 'adjunto escaneado pero guardado en db',
  `adjunto_recibo_ruta` VARCHAR(80) NULL COMMENT 'ruta absoluta del recibo.. separador de directorios es barra dividir',
  `sessionflag` VARCHAR(80) NULL DEFAULT NULL COMMENT 'YYYYMMDDhhmmss.entidad.usuario quien altero',
  `sessionficha` VARCHAR(80) NULL DEFAULT NULL COMMENT 'YYYYMMDDhhmmss.entidad.usuario quien creo',
  PRIMARY KEY (`adjunto_recibo_id`))
COMMENT = 'adjudicacion del recibo en db o en sistema de ficheros';


-- -----------------------------------------------------
-- Table `apirec_recibo`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `apirec_recibo` ;

CREATE TABLE IF NOT EXISTS `apirec_recibo` (
  `cod_recibo` VARCHAR(80) NOT NULL COMMENT 'YYYYMMDDHHMMSS codigo unico de retencion interno',
  `num_recibo` VARCHAR(80) NULL DEFAULT NULL COMMENT 'YYYYMMDD+NNNNNNNN numero unico desde el seniat',
  `num_control` VARCHAR(80) NULL,
  `fecha_recibo` VARCHAR(80) NULL DEFAULT NULL COMMENT 'YYYYMMDD del recibo en el momento de concretar la compra..  indicado en el documento',
  `fecha_compra` VARCHAR(80) NULL DEFAULT NULL COMMENT 'YYYYMMDD en el momento que se empezo la compra, fecha actual al crear el recibo',
  `monto_imponible` DECIMAL(40,2) NULL DEFAULT NULL COMMENT 'base imponible o monto sin el iva permite 0',
  `monto_excento` DECIMAL(40,2) NULL DEFAULT NULL COMMENT 'monto al cual el iva no se aplica y admite 0',
  `monto_iva` DECIMAL(40,2) NULL DEFAULT '0.00' COMMENT 'monto del IVA o de la retencion si es ISLR',
  `tipo_recibo` VARCHAR(80) NULL DEFAULT NULL COMMENT 'factura | nota',
  `adjunto_recibo_id` VARCHAR(80) NOT NULL COMMENT 'id del adjunto escaneado ',
  `sessionflag` VARCHAR(80) NULL DEFAULT NULL COMMENT 'YYYYMMDDhhmmss.entidad.usuario quien altero',
  `sessionficha` VARCHAR(80) NULL DEFAULT NULL COMMENT 'id del mismo adjunto pero si esta en db tambien',
  PRIMARY KEY (`cod_recibo`))
COMMENT = 'tabla centralizada de registros de recibos';


-- -----------------------------------------------------
-- Table `apirec_usuarios`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `apirec_usuarios` ;

CREATE TABLE IF NOT EXISTS `apirec_usuarios` (
  `username` VARCHAR(80) NOT NULL COMMENT 'login del usuario, id del correo para este sistema especifico',
  `userkey` VARCHAR(80) NULL DEFAULT NULL COMMENT 'sincronia con al calve del usuario',
  `userstatus` VARCHAR(80) NULL DEFAULT NULL COMMENT 'ACTIVO|INACTIVO',
  `sessionflag` VARCHAR(80) NULL DEFAULT NULL COMMENT 'YYYYMMDDhhmmss.entidad.usuario quien altero',
  `sessionficha` VARCHAR(80) NULL DEFAULT NULL COMMENT 'YYYYMMDDhhmmss.entidad.usuario quien creo',
  PRIMARY KEY (`username`))
COMMENT = 'tabla de accesos de usuario, no es tabla de autenticado pero actua como una';


-- -----------------------------------------------------
-- Table `apirec_usuarios_permisos`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `apirec_usuarios_permisos` ;

CREATE TABLE IF NOT EXISTS `apirec_usuarios_permisos` (
  `username` VARCHAR(80) NOT NULL,
  `cod_modulo` VARCHAR(80) NOT NULL DEFAULT 'ALL' COMMENT 'en que ambitos de recibo puede operar este usuario',
  `sessionflag` VARCHAR(80) NULL DEFAULT NULL COMMENT 'YYYYMMDDhhmmss.entidad.usuario quien altero',
  `sessionficha` VARCHAR(80) NULL DEFAULT NULL COMMENT 'YYYYMMDDhhmmss.entidad.usuario quien creo',
  PRIMARY KEY (`username`, `cod_modulo`))
COMMENT = 'permiso granular en que recibos opera el usuario';


-- -----------------------------------------------------
-- Table `apirec_modulos`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `apirec_modulos` ;

CREATE TABLE IF NOT EXISTS `apirec_modulos` (
  `cod_modulo` VARCHAR(80) NOT NULL COMMENT 'ambito de aplicabilidad de permiso',
  `cod_recibo` VARCHAR(80) NOT NULL COMMENT 'a que recibo aplica este modulo',
  PRIMARY KEY (`cod_modulo`, `cod_recibo`))
COMMENT = 'permiso granular en que recibos opera el usuario';

