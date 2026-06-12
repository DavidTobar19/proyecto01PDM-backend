<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../conexion.php';

// Selecciona a todos sin límite
$sql = 'SELECT IDPOSTULANTE, IDGENERO, IDTIPODOCUMENTO, IDUSUARIO2, NOMBREPOSTULANTE, APELLIDOPOSTULANTE, FECHANAC, DUI, NIT, DIRECCION, CORREO FROM POSTULANTE';
$result = mysqli_query($conexion, $sql);

$postulantes = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $postulantes[] = $row;
    }
    echo json_encode($postulantes); // Devuelve un Array
} else {
    http_response_code(500);
    echo json_encode(['resultado' => '0', 'mensaje' => mysqli_error($conexion)]);
}
mysqli_close($conexion);
?>