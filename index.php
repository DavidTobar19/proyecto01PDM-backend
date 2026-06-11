<?php
header('Content-Type: application/json; charset=utf-8');

echo json_encode([
    'resultado' => '1',
    'mensaje' => 'Backend PDM activo',
    'servicios' => [
        'certificacion' => [
            'insert' => '/certificacion/ws_certificacion_insert.php',
            'query' => '/certificacion/ws_certificacion_query.php',
            'update' => '/certificacion/ws_certificacion_update.php',
            'delete' => '/certificacion/ws_certificacion_delete.php'
        ],
        'experiencia' => [
            'insert' => '/experiencia/ws_experiencia_insert.php',
            'query' => '/experiencia/ws_experiencia_query.php',
            'update' => '/experiencia/ws_experiencia_update.php',
            'delete' => '/experiencia/ws_experiencia_delete.php'
        ],
        'referencia' => [
            'insert' => '/referencia/ws_referencia_insert.php',
            'query' => '/referencia/ws_referencia_query.php',
            'update' => '/referencia/ws_referencia_update.php',
            'delete' => '/referencia/ws_referencia_delete.php'
        ]
    ]
]);
