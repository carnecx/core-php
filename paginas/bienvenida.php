<?php

session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

$nombreCompleto = $_SESSION['nombre_completo'] ?? 'Usuario';
$rol = $_SESSION['rol'] ?? 'Sin rol';

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Core Servicios Médicos</title>

    <link rel="stylesheet" href="../css/bienvenida.css">

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        rel="stylesheet"
    >

</head>

<body>

<div class="contenedor-principal">

    <div class="tarjeta-bienvenida">

        <div class="encabezado-medico">

            <img
                src="../img/logo.png"
                alt="Logo"
                class="logo-medico"
            >

            <h1 class="titulo-principal">
                Core Servicios Médicos
            </h1>

            <p class="subtitulo">
                Sistema de administración y servicios médicos
            </p>

        </div>

        <div class="contenido">

            <div class="mensaje-exito">

                <i class="bi bi-check-circle-fill"></i>

                <span>
                    Autenticación realizada correctamente.
                </span>

            </div>

            <div class="dato">

                <div class="icono icono-usuario">
                    <i class="bi bi-person-fill"></i>
                </div>

                <div>

                    <div class="titulo">
                        Usuario
                    </div>

                    <div class="valor">
                        <?= htmlspecialchars($nombreCompleto) ?>
                    </div>

                </div>

            </div>

            <div class="dato">

                <div class="icono icono-rol">
                    <i class="bi bi-shield-check"></i>
                </div>

                <div>

                    <div class="titulo">
                        Rol
                    </div>

                    <div class="valor">
                        <?= htmlspecialchars($rol) ?>
                    </div>

                </div>

            </div>

            <a
                href="logout.php"
                class="boton-salir"
            >
                <i class="bi bi-box-arrow-right"></i>

                Cerrar sesión
            </a>

        </div>

    </div>

</div>

</body>

</html>