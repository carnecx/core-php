<?php

require_once __DIR__ . '/../config/config.php';

function obtenerPuestosActivos(): array
{
    $url = WS_BASE_URL . '/ServicioPuestos.svc/ObtenerPuestosActivos';

    $respuestaJson = file_get_contents($url);

    if ($respuestaJson === false) {
        return [];
    }

    $puestos = json_decode($respuestaJson, true);

    return $puestos ?? [];
}

function obtenerConcursosVigentes(): array
{
    $url = WS_BASE_URL . '/ServicioPuestos.svc/ObtenerConcursosVigentes';

    $respuestaJson = @file_get_contents($url);

    if ($respuestaJson === false) {
        return [];
    }

    $concursos = json_decode($respuestaJson, true);

    return $concursos ?? [];
}

function obtenerConcursoPorId(int $idConcurso): ?array
{
    $url = WS_BASE_URL . '/ServicioPuestos.svc/ObtenerConcursoPorId/' . urlencode((string)$idConcurso);

    $respuestaJson = @file_get_contents($url);

    if ($respuestaJson === false) {
        return null;
    }

    $concurso = json_decode($respuestaJson, true);

    return $concurso ?: null;
}