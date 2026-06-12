<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../conexion.php';

$idTipoDocumento = (int) ($_REQUEST['idTipoDocumento'] ?? 0);
$nombre = $_REQUEST['nombreDocumento'] ?? '';

if ($idTipoDocumento <= 0 || $nombre === '') {
    http_response_code(400);
    echo json_encode(['resultado' => '0', 'mensaje' => 'Faltan parametros obligatorios']);
    exit;
}

$sql = 'UPDATE TIPODOCUMENTO SET NOMBREDOCUMENTO = ? WHERE IDTIPODOCUMENTO = ?';

$stmt = mysqli_prepare($conexion, $sql);

if (!$stmt) {
    http_response_code(500);
    echo json_encode(['resultado' => '0', 'mensaje' => mysqli_error($conexion)]);
    exit;
}

mysqli_stmt_bind_param($stmt, 'si', $nombre, $idTipoDocumento);

if (mysqli_stmt_execute($stmt)) {
    if (mysqli_stmt_affected_rows($stmt) > 0) {
        echo json_encode(['resultado' => '1', 'mensaje' => 'Actualizado en la nube']);
    } else {
        $check = mysqli_prepare($conexion, 'SELECT IDTIPODOCUMENTO FROM TIPODOCUMENTO WHERE IDTIPODOCUMENTO = ?');
        mysqli_stmt_bind_param($check, 'i', $idTipoDocumento);
        mysqli_stmt_execute($check);
        $exists = mysqli_stmt_get_result($check)->num_rows > 0;
        mysqli_stmt_close($check);

        if ($exists) {
            echo json_encode(['resultado' => '1', 'mensaje' => 'Sin cambios en la nube']);
        } else {
            echo json_encode(['resultado' => '0', 'mensaje' => 'No se encontro el tipo de documento']);
        }
    }
} else {
    http_response_code(500);
    echo json_encode(['resultado' => '0', 'mensaje' => mysqli_error($conexion)]);
}

mysqli_stmt_close($stmt);
mysqli_close($conexion);