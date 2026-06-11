<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../conexion.php';

$idCert = (int) ($_REQUEST['idCertificacion'] ?? 0);
$nom = $_REQUEST['nombre'] ?? '';
$tip = $_REQUEST['tipo'] ?? '';
$cod = $_REQUEST['codigo'] ?? '';
$inst = $_REQUEST['institucion'] ?? '';
$fec = $_REQUEST['fecha'] ?? '';

if ($idCert <= 0 || $nom === '' || $tip === '' || $cod === '' || $inst === '' || $fec === '') {
    http_response_code(400);
    echo json_encode(['resultado' => '0', 'mensaje' => 'Faltan parametros obligatorios']);
    exit;
}

$sql = 'UPDATE CERTIFICACION SET
        NOMBRECERTIFICACION = ?,
        TIPOCERTIFICACION = ?,
        CODIGOCERTIFICACION = ?,
        INSTITUCIONCERTIFICACION = ?,
        FECHACERTIFICACION = ?
        WHERE IDCERTIFICACION = ?';

$stmt = mysqli_prepare($conexion, $sql);

if (!$stmt) {
    http_response_code(500);
    echo json_encode(['resultado' => '0', 'mensaje' => mysqli_error($conexion)]);
    exit;
}

mysqli_stmt_bind_param($stmt, 'sssssi', $nom, $tip, $cod, $inst, $fec, $idCert);

if (mysqli_stmt_execute($stmt)) {
    if (mysqli_stmt_affected_rows($stmt) > 0) {
        echo json_encode(['resultado' => '1', 'mensaje' => 'Actualizado en la nube']);
    } else {
        $check = mysqli_prepare($conexion, 'SELECT IDCERTIFICACION FROM CERTIFICACION WHERE IDCERTIFICACION = ?');
        mysqli_stmt_bind_param($check, 'i', $idCert);
        mysqli_stmt_execute($check);
        $exists = mysqli_stmt_get_result($check)->num_rows > 0;
        mysqli_stmt_close($check);

        if ($exists) {
            echo json_encode(['resultado' => '1', 'mensaje' => 'Sin cambios en la nube']);
        } else {
            echo json_encode(['resultado' => '0', 'mensaje' => 'No se encontro la certificacion']);
        }
    }
} else {
    http_response_code(500);
    echo json_encode(['resultado' => '0', 'mensaje' => mysqli_error($conexion)]);
}

mysqli_stmt_close($stmt);
mysqli_close($conexion);
