<?php

session_start();

require_once __DIR__ . '/../servicios/AutenticacionServicioClient.php';

// si ya existe una sesión activa, enviar a bienvenida
if (isset($_SESSION['usuario_id'])) {
    header('Location: bienvenida.php');
    exit;
}

$mensaje = '';
$tipoMensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $contrasena = $_POST['contrasena'] ?? '';

    if ($usuario === '' || $contrasena === '') {
        $mensaje = 'Debe completar el usuario y la contraseña.';
        $tipoMensaje = 'error';
    } else {
        $cliente = new AutenticacionServicioClient();
        $respuesta = $cliente->autenticar($usuario, $contrasena);

        if (!empty($respuesta['Exito'])) {
            session_regenerate_id(true);

            $_SESSION['usuario_id'] = $respuesta['IdUsuario'];
            $_SESSION['nombre_completo'] = $respuesta['NombreCompleto'];
            $_SESSION['rol'] = $respuesta['Rol'];

            header('Location: bienvenida.php');
            exit;
        }

        $mensaje = $respuesta['Mensaje']
            ?? 'Usuario y/o contraseña incorrectos.';

        $tipoMensaje = 'error';
    }
}
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

    <link rel="stylesheet" href="../css/estilos.css">

</head>

<body class="pagina-login">

    <main class="login-card">

        <img
            src="../img/logo.png"
            alt="Logo Core Servicios Médicos"
            class="logo-core"
        >

        <h1>Core Servicios Médicos</h1>

        <p class="subtitulo">
            Ingreso al sistema Core
        </p>

        <?php if ($mensaje !== ''): ?>
            <div
                class="mensaje <?= htmlspecialchars($tipoMensaje, ENT_QUOTES, 'UTF-8') ?>"
            >
                <?= htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <form method="post" action="">

            <label for="usuario">
                Usuario
            </label>

            <input
                type="text"
                id="usuario"
                name="usuario"
                value="<?= htmlspecialchars($_POST['usuario'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                autocomplete="username"
                autofocus
                required
            >

            <label for="contrasena">
                Contraseña
            </label>

            <input
                type="password"
                id="contrasena"
                name="contrasena"
                autocomplete="current-password"
                required
            >

            <button type="submit">
                Ingresar
            </button>

        </form>

    </main>

</body>

</html>