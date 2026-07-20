<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

$nombreCompleto = $_SESSION['nombre_completo'] ?? 'Usuario';
$rol = $_SESSION['rol'] ?? '';

// obtiene la primera letra del nombre para mostrarla en el avatar
$inicialUsuario = strtoupper(
    substr(
        trim($nombreCompleto),
        0,
        1
    )
);
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

    <link
        rel="stylesheet"
        href="../css/plantilla.css"
    >
</head>

<body>

<div class="layout-core">

    <aside class="menu-lateral">

        <div class="menu-logo">

            <img
                src="../img/logo.png"
                alt="Logo"
                class="logo-menu"
            >

            <span class="nombre-sistema">
                Core Servicios Médicos
            </span>

        </div>

        <nav class="menu-opciones">

            <a href="bienvenida.php">
                Inicio
            </a>

            <a href="puestos.php">
                Puestos
            </a>

        </nav>

    </aside>

    <div class="area-derecha">

        <header class="header-core">

            <div class="header-usuario">

                <div class="usuario-avatar">
                    <?= htmlspecialchars($inicialUsuario) ?>
                </div>

                <div class="usuario-informacion">

                    <span class="usuario-nombre">
                        <?= htmlspecialchars($nombreCompleto) ?>
                    </span>

                    <span class="usuario-rol">
                        <?= htmlspecialchars($rol) ?>
                    </span>

                </div>

                <a
                    href="logout.php"
                    class="boton-logout"
                >
                    Cerrar sesión
                </a>

            </div>

        </header>

        <main class="contenido-principal">