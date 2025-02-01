<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_DEPRECATED);
session_start();

if (!isset($_SESSION['user_type'])) {
    header('Location: login.html'); // Redirige al login si no hay sesión activa
    exit();
}
include_once('app/func.inc.php');
// Incluir encabezado (DecInc.inc.php)
if (file_exists('plantillas/DecInc.inc.php')) {
    include_once('plantillas/DecInc.inc.php');
} else {
    die('Error: No se encuentra el archivo DecInc.inc.php.');
}

// Incluir la vista correspondiente
$rutaVista = 'vistas/' . $_SESSION['user_type'] . '/miperfil.inc.php';
if (file_exists($rutaVista)) {
    
include_once('modales/modalCambios.php');
    include_once($rutaVista);
} else {
    die('Error: La vista no existe.');
}

// Incluir pie de página (DecFin.inc.php)
if (file_exists('plantillas/DecFin.inc.php')) {
    include_once('plantillas/DecFin.inc.php');
} else {
    die('Error: No se encuentra el archivo DecFin.inc.php.');
}
?>
