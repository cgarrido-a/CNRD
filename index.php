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
include_once('plantillas/DecInc.inc.php');
?>

<!-- Main Content -->
<div class="container mt-5">
    <h1>Bienvenido/a</h1>
    <p>Aquí puedes navegar a las secciones de voluntarios, clínicas y tu perfil.</p>
</div>

<?php
include_once('plantillas/DecFin.inc.php');
?>
