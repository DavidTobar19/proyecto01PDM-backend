-- =============================================================================
-- SOLO CATALOGOS VACIOS (opcional)
--
-- Usar SOLO si faltan datos de catalogo (generos, tipos documento, estados).
-- NO toca Usuario, POSTULANTE ni CERTIFICACION.
-- INSERT IGNORE = si el id ya existe, lo deja como esta.
-- =============================================================================

USE defaultdb;

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

INSERT IGNORE INTO `android_metadata` (`locale`) VALUES ('es_US');

SELECT 'GENERO' AS tabla, COUNT(*) AS registros FROM GENERO
UNION ALL SELECT 'TIPODOCUMENTO', COUNT(*) FROM TIPODOCUMENTO
UNION ALL SELECT 'ESTADOPOSTULACION', COUNT(*) FROM ESTADOPOSTULACION;
