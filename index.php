<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_DEPRECATED);

include_once(__DIR__ . '/app/class.inc.php');
include_once(__DIR__ . '/app/func.inc.php');
session_start();

if (!isset($_SESSION['user_type'])) {
    header('Location: login.html'); // Redirige al login si no hay sesión activa
    exit();
}


$ruta2 = __DIR__ . '/Visualizacion/Voluntario/';

include_once($ruta2 . 'plantillas/LLamstan.inc.php');

$ruta =  'Visualizacion/Voluntario/vistas/';
include_once($ruta2 . 'plantillas/DecInc.inc.php');
?>
