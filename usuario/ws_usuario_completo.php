<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../conexion.php';

// ==========================================
// 1. RECIBIR DATOS COMUNES DE LA CUENTA
// ==========================================
$nomUsuario = $_REQUEST['nomUsuario'] ?? '';
$clave = $_REQUEST['clave'] ?? '';
$rol = $_REQUEST['rol'] ?? ''; // Tiene que venir como 'Postulante' o 'Empresa'

// Validación inicial básica
if ($nomUsuario === '' || $clave === '' || $rol === '') {
    http_response_code(400);
    echo json_encode(['resultado' => '0', 'mensaje' => 'Faltan credenciales basicas o el rol']);
    exit;
}

// !!! INICIO DE LA TRANSACCIÓN !!!
mysqli_begin_transaction($conexion);

try {
    // PASO 1 GENERAL: Insertar en la tabla Usuario con los IDs foráneos en NULL temporalmente
    $sqlUser = "INSERT INTO Usuario (nomUsuario, clave, rol, idPostulante, idEmpresa) VALUES (?, ?, ?, NULL, NULL)";
    $stmtUser = mysqli_prepare($conexion, $sqlUser);
    if (!$stmtUser) throw new Exception("Error preparando Usuario: " . mysqli_error($conexion));

    mysqli_stmt_bind_param($stmtUser, 'sss', $nomUsuario, $clave, $rol);
    if (!mysqli_stmt_execute($stmtUser)) throw new Exception("Error guardando Usuario: " . mysqli_stmt_error($stmtUser));

    // Capturamos el ID autogenerado del nuevo usuario (ej. 5)
    $idUsuarioGenerado = mysqli_insert_id($conexion);
    mysqli_stmt_close($stmtUser);

    // ==========================================
    // 2. BIFURCACIÓN SEGÚN EL ROL
    // ==========================================
    if ($rol === 'Postulante') {

        // Recibir datos específicos del Postulante
        $idGenero = (int) ($_REQUEST['idGenero'] ?? 0);
        $idTipoDocumento = (int) ($_REQUEST['idTipoDocumento'] ?? 0);
        $nombrePostulante = $_REQUEST['nombrePostulante'] ?? '';
        $apellidoPostulante = $_REQUEST['apellidoPostulante'] ?? '';
        $fechaNac = $_REQUEST['fechaNac'] ?? '';
        $dui = $_REQUEST['dui'] ?? '';
        $nit = $_REQUEST['nit'] ?? '';
        $direccion = $_REQUEST['direccion'] ?? '';
        $correo = $_REQUEST['correo'] ?? '';

        if ($idGenero <= 0 || $nombrePostulante === '') {
            throw new Exception("Faltan datos obligatorios del postulante");
        }

        // Insertar POSTULANTE amarrando el IDUSUARIO2
        $sqlPost = "INSERT INTO POSTULANTE (IDGENERO, IDTIPODOCUMENTO, IDUSUARIO2, NOMBREPOSTULANTE, APELLIDOPOSTULANTE, FECHANAC, DUI, NIT, DIRECCION, CORREO)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmtPost = mysqli_prepare($conexion, $sqlPost);
        if (!$stmtPost) throw new Exception(mysqli_error($conexion));

        mysqli_stmt_bind_param($stmtPost, 'iiisssssss', $idGenero, $idTipoDocumento, $idUsuarioGenerado, $nombrePostulante, $apellidoPostulante, $fechaNac, $dui, $nit, $direccion, $correo);
        if (!mysqli_stmt_execute($stmtPost)) throw new Exception(mysqli_stmt_error($stmtPost));

        $idPostulanteGenerado = mysqli_insert_id($conexion);
        mysqli_stmt_close($stmtPost);

        // Actualizar tabla Usuario con el IDPOSTULANTE
        $sqlUnion = "UPDATE Usuario SET idPostulante = ? WHERE idUsuario = ?";
        $stmtUnion = mysqli_prepare($conexion, $sqlUnion);
        mysqli_stmt_bind_param($stmtUnion, 'ii', $idPostulanteGenerado, $idUsuarioGenerado);
        mysqli_stmt_execute($stmtUnion);
        mysqli_stmt_close($stmtUnion);

        $respuesta = [
            'resultado' => '1',
            'mensaje' => 'Registro de Postulante completo exitoso',
            'idUsuario' => $idUsuarioGenerado,
            'idPostulante' => $idPostulanteGenerado
        ];

    } elseif ($rol === 'Empresa') {

        // Recibir datos específicos de la Empresa
        $nombreEmpresa = $_REQUEST['nombreEmpresa'] ?? '';
        $razonSocial = $_REQUEST['razonSocial'] ?? '';
        $correoEmpresa = $_REQUEST['correoEmpresa'] ?? '';
        $telefonoEmpresa = $_REQUEST['telefonoEmpresa'] ?? '';

        if ($nombreEmpresa === '' || $razonSocial === '') {
            throw new Exception("Faltan datos obligatorios de la empresa");
        }

        // Insertar EMPRESA amarrando el IDUSUARIO
        $sqlEmp = "INSERT INTO EMPRESA (IDUSUARIO, NOMBREEMPRESA, RAZONSOCIAL, CORREOEMPRESA, TELEFONOEMPRESA)
                   VALUES (?, ?, ?, ?, ?)";
        $stmtEmp = mysqli_prepare($conexion, $sqlEmp);
        if (!$stmtEmp) throw new Exception(mysqli_error($conexion));

        mysqli_stmt_bind_param($stmtEmp, 'issss', $idUsuarioGenerado, $nombreEmpresa, $razonSocial, $correoEmpresa, $telefonoEmpresa);
        if (!mysqli_stmt_execute($stmtEmp)) throw new Exception(mysqli_stmt_error($stmtEmp));

        $idEmpresaGenerada = mysqli_insert_id($conexion);
        mysqli_stmt_close($stmtEmp);

        // Actualizar tabla Usuario con el IDEMPRESA
        $sqlUnion = "UPDATE Usuario SET idEmpresa = ? WHERE idUsuario = ?";
        $stmtUnion = mysqli_prepare($conexion, $sqlUnion);
        mysqli_stmt_bind_param($stmtUnion, 'ii', $idEmpresaGenerada, $idUsuarioGenerado);
        mysqli_stmt_execute($stmtUnion);
        mysqli_stmt_close($stmtUnion);

        $respuesta = [
            'resultado' => '1',
            'mensaje' => 'Registro de Empresa completo exitoso',
            'idUsuario' => $idUsuarioGenerado,
            'idEmpresa' => $idEmpresaGenerada
        ];

    } else {
        throw new Exception("Rol '$rol' no valido. Debe ser 'Postulante' o 'Empresa'.");
    }

    // Si TODO sale bien hasta aquí, aplicamos los cambios definitivamente en Aiven
    mysqli_commit($conexion);
    echo json_encode($respuesta);

} catch (Exception $e) {
    // Si ALGO falló, cancelamos todo el proceso para que no quede un usuario huérfano
    mysqli_rollback($conexion);
    http_response_code(500);
    echo json_encode(['resultado' => '0', 'mensaje' => 'Error en el servidor: ' . $e->getMessage()]);
}

mysqli_close($conexion);