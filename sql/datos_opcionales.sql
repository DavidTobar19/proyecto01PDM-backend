-- =============================================================================
-- DATOS OPCIONALES - NO EJECUTAR si ya tienes Usuario, Postulante y Certificacion
--
-- Si ya cargaste usuarios y certificaciones en Aiven, usa en su lugar:
--   1) actualizar_esquema_sin_perder_datos.sql
--   2) catalogos_solo_si_faltan.sql (solo si faltan generos/tipos doc)
-- =============================================================================

USE defaultdb;

INSERT IGNORE INTO `android_metadata` (`locale`) VALUES ('es_US');

INSERT IGNORE INTO `GENERO` (`IDPGENERO`, `DESCRIPCIONGENERO`) VALUES
(1, 'Femenino'),
(2, 'Masculino');

INSERT IGNORE INTO `TIPODOCUMENTO` (`IDTIPODOCUMENTO`, `NOMBREDOCUMENTO`) VALUES
(1, 'DUI'),
(2, 'Pasaporte');

INSERT IGNORE INTO `ESTADOPOSTULACION` (`IDESTADOPOSTULACION`, `DESCRIPCIONESTADOPOSTULACION`) VALUES
(1, 'En revision'),
(2, 'Aceptada'),
(3, 'Rechazada');

INSERT IGNORE INTO `CATEGORIAHABILIDAD` (`IDCATEGORIAHABILIDAD`, `NOMBRECATEGORIAHABILIDAD`) VALUES
(1, 'Tecnologicas'),
(2, 'Blandas');

-- Solo para base de datos NUEVA sin usuarios:
-- INSERT IGNORE INTO `Usuario` ...
-- INSERT IGNORE INTO `POSTULANTE` ...

SELECT 'Usuario' AS tabla, COUNT(*) AS registros FROM Usuario
UNION ALL SELECT 'POSTULANTE', COUNT(*) FROM POSTULANTE
UNION ALL SELECT 'CERTIFICACION', COUNT(*) FROM CERTIFICACION;
