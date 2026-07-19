<?php

require_once __DIR__ . '/../config/config.php';

class AutenticacionServicioClient
{
    private SoapClient $cliente;

    public function __construct()
    {
        $this->cliente = new SoapClient(
            WS_BASE_URL . '/ServicioAutenticacion.svc?wsdl',
            [
                'trace' => true,
                'exceptions' => true,
                'cache_wsdl' => WSDL_CACHE_NONE
            ]
        );
    }

    public function autenticar(
        string $usuario,
        string $contrasena
    ): array {
        try {
            $respuesta = $this->cliente->Autenticar([
                'solicitud' => [
                    'Usuario' => $usuario,
                    'Contrasena' => $contrasena
                ]
            ]);

            if (
                !isset($respuesta->AutenticarResult) ||
                $respuesta->AutenticarResult === null
            ) {
                return [
                    'Exito' => false,
                    'Mensaje' => 'El servicio no devolvió una respuesta válida.'
                ];
            }

            $resultado = $respuesta->AutenticarResult;

            return [
                'Exito' => (bool) ($resultado->Exito ?? false),
                'Mensaje' => (string) (
                    $resultado->Mensaje
                    ?? 'Usuario y/o contraseña incorrectos.'
                ),
                'IdUsuario' => (int) ($resultado->IdUsuario ?? 0),
                'NombreCompleto' => (string) (
                    $resultado->NombreCompleto ?? ''
                ),
                'Rol' => (string) ($resultado->Rol ?? '')
            ];
        } catch (SoapFault $error) {
            return [
                'Exito' => false,
                'Mensaje' => 'No se pudo conectar con el servicio de autenticación: '
                    . $error->getMessage()
            ];
        } catch (Throwable $error) {
            return [
                'Exito' => false,
                'Mensaje' => 'Ocurrió un error al autenticar: '
                    . $error->getMessage()
            ];
        }
    }
}