<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../conexion.php';

$nombreCategoria = $_REQUEST['nombreCategoriaHabilidad'] ?? '';

// Validación básica
if ($nombreCategoria === '') {
    http_response_code(400);
    echo json_encode(['resultado' => '0', 'mensaje' => 'El nombre de la categoria es obligatorio']);
    exit;
}

$sql = 'INSERT INTO CATEGORIAHABILIDAD (NOMBRECATEGORIAHABILIDAD) VALUES (?)';
$stmt = mysqli_prepare($conexion, $sql);

if (!$stmt) {
    http_response_code(500);
    echo json_encode(['resultado' => '0', 'mensaje' => mysqli_error($conexion)]);
    exit;
}
mysqli_stmt_bind_param($stmt, 's', $nombreCategoria);

if (mysqli_stmt_execute($stmt)) {
    $nuevoId = mysqli_insert_id($conexion);
    echo json_encode(['resultado' => '1', 'mensaje' => 'Categoria guardada correctamente', 'idCategoria' => $nuevoId]);
} else {
    http_response_code(500);
    echo json_encode(['resultado' => '0', 'mensaje' => 'Error al guardar la categoria']);
}

mysqli_stmt_close($stmt);
mysqli_close($conexion);
?>