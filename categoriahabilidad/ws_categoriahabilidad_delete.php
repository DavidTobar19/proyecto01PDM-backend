<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../conexion.php';

$idCategoria = (int) ($_REQUEST['idCategoriaHabilidad'] ?? 0);

if ($idCategoria <= 0) {
    http_response_code(400);
    echo json_encode(['resultado' => '0', 'mensaje' => 'ID de categoria invalido']);
    exit;
}

$sql = 'DELETE FROM CATEGORIAHABILIDAD WHERE IDCATEGORIAHABILIDAD = ?';
$stmt = mysqli_prepare($conexion, $sql);

if (!$stmt) {
    http_response_code(500);
    echo json_encode(['resultado' => '0', 'mensaje' => mysqli_error($conexion)]);
    exit;
}

mysqli_stmt_bind_param($stmt, 'i', $idCategoria);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(['resultado' => '1', 'mensaje' => 'Categoria eliminada correctamente']);
} else {
    http_response_code(500);
    echo json_encode(['resultado' => '0', 'mensaje' => 'Error al eliminar la categoria']);
}

mysqli_stmt_close($stmt);
mysqli_close($conexion);
?>