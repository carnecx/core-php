<?php

session_start();

if (isset($_SESSION['usuario_id'])) {
    header('Location: paginas/bienvenida.php');
    exit;
}

header('Location: paginas/login.php');
exit;