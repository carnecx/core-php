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