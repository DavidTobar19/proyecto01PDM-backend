<?php
header('Content-Type: application/json; charset=utf-8');

echo json_encode([
    'resultado' => '1',
    'mensaje' => 'Backend PDM activo',
    'servicios' => [
        'insert' => '/certificacion/ws_certificacion_insert.php',
        'query' => '/certificacion/ws_certificacion_query.php',
        'update' => '/certificacion/ws_certificacion_update.php',
        'delete' => '/certificacion/ws_certificacion_delete.php'
    ]
]);
