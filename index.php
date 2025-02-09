<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_DEPRECATED);

include_once(__DIR__ . '/app/class.inc.php');
include_once(__DIR__ . '/app/func.inc.php');
session_start();

if (!isset($_SESSION['UserLog'])) {
    header('Location: login.html'); // Redirige al login si no hay sesión activa
    exit();
}
if (!is_object($_SESSION['UserLog']) || !method_exists($_SESSION['UserLog'], 'obtener_TypeUser')) {
    session_destroy(); // Borra la sesión si los datos no son válidos
    header('Location: login.html');
    exit();
}
$type = $_SESSION['UserLog']->obtener_TypeUser();

$ruta2 = __DIR__ . '/Visualizacion/'.$type.'/';
include_once($ruta2 . 'plantillas/LLamstan.inc.php');

$ruta =  'Visualizacion/'.$type.'/vistas/';
include_once($ruta2 . 'plantillas/DecInc.inc.php');
echo date('Y-m-d H:i:s');
include_once($ruta2 . 'plantillas/DecFin.inc.php');
?>
