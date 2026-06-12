<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../conexion.php';

// Trae absolutamente todos los teléfonos de la tabla PHONE
$sql = 'SELECT IDTELEFONO, IDPOSTULANTE, NUMEROTELEFONO, TIPOTELEFONO FROM PHONE';
$result = mysqli_query($conexion, $sql);

$telefonos = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $telefonos[] = $row;
    }
    echo json_encode($telefonos);
} else {
    http_response_code(500);
    echo json_encode(['resultado' => '0', 'mensaje' => mysqli_error($conexion)]);
}
mysqli_close($conexion);
?>