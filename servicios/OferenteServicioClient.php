<?php

require_once __DIR__ . '/../config/config.php';

function registrarOferente(array $datos): array
{
    $url = URL_SERVICIO_OFERENTES . '/RegistrarOferente';

    $opciones = [
        'http' => [
            'method'  => 'POST',
            'header'  => "Content-Type: application/json\r\n",
            'content' => json_encode($datos),
            'ignore_errors' => true,
        ],
    ];

    $contexto = stream_context_create($opciones);
    $respuestaJson = file_get_contents($url, false, $contexto);

    if ($respuestaJson === false) {
        return ['Exito' => false, 'Mensaje' => 'No se pudo conectar con el servicio de oferentes.'];
    }

    $resultado = json_decode($respuestaJson, true);

    return $resultado ?? ['Exito' => false, 'Mensaje' => 'Respuesta inválida del servicio.'];
}