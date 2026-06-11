-- ============================================================
-- EJECUTAR EN DBeaver: Ctrl+A -> Ctrl+Alt+X (Execute SQL Script)
-- Conexion activa: mysql-...aivencloud.com / defaultdb
-- ============================================================

SELECT DATABASE() AS base_actual;
SELECT COUNT(*) AS tablas_antes
FROM information_schema.tables
WHERE table_schema = 'defaultdb';

CREATE TABLE IF NOT EXISTS `defaultdb`.`GENERO` (
  `IDPGENERO` int NOT NULL AUTO_INCREMENT,
  `DESCRIPCIONGENERO` text NOT NULL,
  PRIMARY KEY (`IDPGENERO`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `defaultdb`.`TIPODOCUMENTO` (
  `IDTIPODOCUMENTO` int NOT NULL AUTO_INCREMENT,
  `NOMBREDOCUMENTO` text NOT NULL,
  PRIMARY KEY (`IDTIPODOCUMENTO`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `defaultdb`.`POSTULANTE` (
  `IDPOSTULANTE` int NOT NULL AUTO_INCREMENT,
  `IDGENERO` int NOT NULL,
  `IDTIPODOCUMENTO` int NOT NULL,
  `IDUSUARIO2` int NOT NULL,
  `NOMBREPOSTULANTE` varchar(255) NOT NULL,
  `APELLIDOPOSTULANTE` varchar(255) NOT NULL,
  `FECHANAC` varchar(50) NOT NULL,
  `DUI` varchar(50) NOT NULL,
  `NIT` varchar(50) NOT NULL,
  `DIRECCION` text NOT NULL,
  `CORREO` varchar(255) NOT NULL,
  PRIMARY KEY (`IDPOSTULANTE`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `defaultdb`.`CERTIFICACION` (
  `IDCERTIFICACION` int NOT NULL AUTO_INCREMENT,
  `IDPOSTULANTE` int NOT NULL,
  `NOMBRECERTIFICACION` text NOT NULL,
  `TIPOCERTIFICACION` text NOT NULL,
  `CODIGOCERTIFICACION` text NOT NULL,
  `INSTITUCIONCERTIFICACION` text NOT NULL,
  `FECHACERTIFICACION` varchar(50) NOT NULL,
  PRIMARY KEY (`IDCERTIFICACION`),
  KEY `index_CERTIFICACION_IDPOSTULANTE` (`IDPOSTULANTE`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `defaultdb`.`Usuario` (
  `idUsuario` int NOT NULL AUTO_INCREMENT,
  `idEmpresa` int DEFAULT NULL,
  `idPostulante` int DEFAULT NULL,
  `nomUsuario` varchar(255) NOT NULL,
  `clave` varchar(255) NOT NULL,
  `rol` varchar(50) NOT NULL,
  PRIMARY KEY (`idUsuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO `defaultdb`.`GENERO` (`IDPGENERO`, `DESCRIPCIONGENERO`) VALUES
(1, 'Femenino'),
(2, 'Masculino');

INSERT IGNORE INTO `defaultdb`.`TIPODOCUMENTO` (`IDTIPODOCUMENTO`, `NOMBREDOCUMENTO`) VALUES
(1, 'DUI'),
(2, 'Pasaporte');

INSERT IGNORE INTO `defaultdb`.`POSTULANTE` (
  `IDPOSTULANTE`, `IDGENERO`, `IDTIPODOCUMENTO`, `IDUSUARIO2`,
  `NOMBREPOSTULANTE`, `APELLIDOPOSTULANTE`, `FECHANAC`, `DUI`, `NIT`, `DIRECCION`, `CORREO`
) VALUES (
  1, 2, 1, 1,
  'David', 'Lemus', '2004-01-01', '12345678-9', '0614-123456-101-1', 'Apopa', 'david@gmail.com'
);

INSERT IGNORE INTO `defaultdb`.`Usuario` (
  `idUsuario`, `idEmpresa`, `idPostulante`, `nomUsuario`, `clave`, `rol`
) VALUES (
  1, NULL, 1, 'david', '1', 'Postulante'
);

-- Verificacion final (debe mostrar 5 tablas y 1 postulante)
SELECT table_name AS tabla
FROM information_schema.tables
WHERE table_schema = 'defaultdb'
ORDER BY table_name;

SELECT * FROM `defaultdb`.`POSTULANTE`;
SELECT * FROM `defaultdb`.`CERTIFICACION`;
