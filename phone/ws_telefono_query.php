<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../conexion.php';

$idPostulante = (int) ($_REQUEST['idPostulante'] ?? 0);

if ($idPostulante <= 0) {
    http_response_code(400);
    echo json_encode(['resultado' => '0', 'mensaje' => 'ID de postulante invalido']);
    exit;
}
$sql = 'SELECT IDTELEFONO, IDPOSTULANTE, NUMEROTELEFONO, TIPOTELEFONO FROM PHONE WHERE IDPOSTULANTE = ?';
$stmt = mysqli_prepare($conexion, $sql);

if (!$stmt) {
    http_response_code(500);
    echo json_encode(['resultado' => '0', 'mensaje' => mysqli_error($conexion)]);
    exit;
}

mysqli_stmt_bind_param($stmt, 'i', $idPostulante);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$telefonos = [];
while ($row = mysqli_fetch_assoc($result)) {
    $telefonos[] = $row;
}

echo json_encode($telefonos);

mysqli_stmt_close($stmt);
mysqli_close($conexion);
?>