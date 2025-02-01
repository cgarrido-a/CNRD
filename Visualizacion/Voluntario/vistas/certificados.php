<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_DEPRECATED);


include_once('../plantillas/LLamstan.inc.php');
session_start();

if (!isset($_SESSION['user_type'])) {
    header('Location: login.html'); // Redirige al login si no hay sesión activa
    exit();
}


$ruta = '';

include_once('../plantillas/DecInc.inc.php');

foreach (glob("../modales/*.php") as $archivo) {
    include_once $archivo;

}
?>

<div class="container mt-5">
    <h2>Lista de Certificados</h2>
    <table class="table table-bordered table-striped">
        <thead class="thead-dark">
            <tr>
                <th>Nombre del Certificado</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <!-- Fila de ejemplo, puedes agregar más filas aquí -->
            <tr>
                <td>Certificado 1</td>
                <td><a href="certificados/certificado1.pdf" class="btn btn-primary" target="_blank">Ver</a></td>
            </tr>S
            <!-- Agregar más filas según lo necesites -->
        </tbody>
    </table>
</div>
<?php
include_once('../plantillas/DecFin.inc.php');
?>