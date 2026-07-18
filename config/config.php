<?php

// URL base donde corre el proyecto de Web Services en C# (WCF)
define('WS_BASE_URL', 'http://localhost:50898');

// URL especifica del servicio de autenticacion (CORE4)
define(
    'URL_SERVICIO_AUTENTICACION',
    'http://localhost:50898/ServicioAutenticacion.svc'
);

// URL especifica del servicio de oferentes (CORE5)
define('URL_SERVICIO_OFERENTES', WS_BASE_URL . '/ServicioOferentes.svc');