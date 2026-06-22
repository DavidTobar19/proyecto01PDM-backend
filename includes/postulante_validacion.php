<?php

function normalizar_dui(string $dui): string
{
    return strtoupper(preg_replace('/[\s\-]+/', '', trim($dui)));
}

function dui_duplicado(mysqli $conexion, string $dui, int $excluirIdPostulante = 0): bool
{
    $norm = normalizar_dui($dui);
    if ($norm === '') {
        return false;
    }

    $sql = "SELECT COUNT(*) AS total
            FROM POSTULANTE
            WHERE REPLACE(REPLACE(UPPER(TRIM(DUI)), '-', ''), ' ', '') = ?
              AND (? <= 0 OR IDPOSTULANTE <> ?)";

    $stmt = mysqli_prepare($conexion, $sql);
    if (!$stmt) {
        throw new Exception('Error validando DUI: ' . mysqli_error($conexion));
    }

    mysqli_stmt_bind_param($stmt, 'sii', $norm, $excluirIdPostulante, $excluirIdPostulante);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    return ((int) ($row['total'] ?? 0)) > 0;
}

function validar_fecha_nac(string $fechaNac): ?string
{
    $fecha = trim($fechaNac);

    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) {
        return 'La fecha de nacimiento debe tener formato YYYY-MM-DD.';
    }

    $dt = DateTime::createFromFormat('Y-m-d', $fecha);
    if (!$dt || $dt->format('Y-m-d') !== $fecha) {
        return 'La fecha de nacimiento no es valida.';
    }

    $hoy = new DateTime('today');
    if ($dt > $hoy) {
        return 'La fecha de nacimiento no puede ser futura.';
    }

    if ($dt->diff($hoy)->y < 18) {
        return 'Debes ser mayor de 18 anos para registrarte.';
    }

    return null;
}
