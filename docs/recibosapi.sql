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
  `adjunto_cod_recibo` VARCHAR(80) NOT NULL COMMENT 'YYYYMMDDHHMMSS id de este adjunto cada entrada es un solo adjunto',
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
  `monto_recibo` DECIMAL(40,2) NULL DEFAULT NULL COMMENT 'monto total crudo de todo el recibo debe ser positivo',
  `monto_excento` DECIMAL(40,2) NULL DEFAULT NULL COMMENT 'monto al cual ningun impuesto aplica y admite 0',
  `tipo_recibo` VARCHAR(80) NULL DEFAULT NULL COMMENT 'factura | nota',
  `adjunto_cod_recibo` VARCHAR(80) NOT NULL COMMENT 'id del adjunto escaneado cada entrada es un solo adjunto',
  `sessionflag` VARCHAR(80) NULL DEFAULT NULL COMMENT 'YYYYMMDDhhmmss.entidad.usuario quien altero',
  `sessionficha` VARCHAR(80) NULL DEFAULT NULL COMMENT 'YYYYMMDDhhmmss.entidad.usuario quien lo creo',
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

