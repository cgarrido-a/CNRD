<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Destruir todas las variables de sesión
session_unset();

// Destruir la sesión
session_destroy();

// Redirigir al inicio de sesión (ajusta la ruta según sea necesario)
header("Location: login.html");
exit();
?>
