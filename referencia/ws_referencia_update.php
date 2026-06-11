<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../conexion.php';

$idRef = (int) ($_REQUEST['idReferencia'] ?? 0);
$nombre = $_REQUEST['nombre'] ?? '';
$parentesco = $_REQUEST['parentesco'] ?? '';
$telefono = $_REQUEST['telefono'] ?? '';

if ($idRef <= 0 || $nombre === '' || $parentesco === '' || $telefono === '') {
    http_response_code(400);
    echo json_encode(['resultado' => '0', 'mensaje' => 'Faltan parametros obligatorios']);
    exit;
}

$sql = 'UPDATE REFERENCIA SET NOMBREFERENCIA = ?, PARENTESCO = ?, TELEFONO = ? WHERE IDREFERENCIA = ?';
$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, 'sssi', $nombre, $parentesco, $telefono, $idRef);

if (mysqli_stmt_execute($stmt)) {
    if (mysqli_stmt_affected_rows($stmt) > 0) {
        echo json_encode(['resultado' => '1', 'mensaje' => 'Actualizado en la nube']);
    } else {
        $check = mysqli_prepare($conexion, 'SELECT IDREFERENCIA FROM REFERENCIA WHERE IDREFERENCIA = ?');
        mysqli_stmt_bind_param($check, 'i', $idRef);
        mysqli_stmt_execute($check);
        $exists = mysqli_stmt_get_result($check)->num_rows > 0;
        mysqli_stmt_close($check);

        if ($exists) {
            echo json_encode(['resultado' => '1', 'mensaje' => 'Sin cambios en la nube']);
        } else {
            echo json_encode(['resultado' => '0', 'mensaje' => 'No se encontro la referencia']);
        }
    }
} else {
    http_response_code(500);
    echo json_encode(['resultado' => '0', 'mensaje' => mysqli_error($conexion)]);
}

mysqli_stmt_close($stmt);
mysqli_close($conexion);
