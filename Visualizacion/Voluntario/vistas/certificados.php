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
$certificados = Voluntarios::ObtenerCertificados($_SESSION['UserLog']->obtener_id());
?>
<div class="container mt-5">
    <h2>Lista de Certificados</h2>

    <?php if (count($certificados) > 0) { ?>
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>Nombre del Certificado</th>
                    <th>Descargar</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($certificados as $certificado) { ?>
                    <tr>
                        <td><?php echo $certificado['Titulo']; ?></td>
                        <td>
                            <a target="_blank" href="<?php echo $certificado['Ubicacion']; ?>">
                                <i class="fa-solid fa-download"></i>
                            </a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        <div class="alert alert-warning" role="alert">
            No tienes certificados disponibles.
        </div>
    <?php } ?>
</div>

<?php
include_once('../plantillas/DecFin.inc.php');
?>