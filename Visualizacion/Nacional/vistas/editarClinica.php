<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_DEPRECATED);

include_once('../plantillas/LLamstan.inc.php');
session_start();

$ruta = '';
include_once('../plantillas/DecInc.inc.php');

// Validar si se pasa el ID de la clínica
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<div class='alert alert-danger'>ID de clínica no proporcionado.</div>";
    exit;
}

// Obtener el ID de la clínica desde el parámetro GET
$clinicaId = $_GET['id'];

// Llamar a la función para obtener los datos de la clínica
$clinicaJson = Clinicas::get_clinica_by_id($clinicaId);

// Decodificar el JSON recibido a un array asociativo
$clinica = json_decode($clinicaJson, true);

// Validar si la clínica existe
if (!$clinica || isset($clinica['error'])) {
    echo "<div class='alert alert-danger'>La clínica no existe o hubo un error al obtener los datos.</div>";
    exit;
}
?>

<div class="container mt-5">
    <h2>Editar Clínica</h2>

    <!-- Formulario para editar los detalles de la clínica -->
    <form action="guardarEdicionClinica.php" method="post">
        <div class="form-group">
            <label for="id">ID</label>
            <input type="text" class="form-control" id="id" name="id" value="<?php echo htmlspecialchars($clinica['ID']); ?>" readonly>
        </div>

        <div class="form-group">
            <label for="id_region">ID Región</label>
            <input type="text" class="form-control" id="id_region" name="id_region" value="<?php echo htmlspecialchars($clinica['id_region']); ?>" required>
        </div>

        <div class="form-group">
            <label for="tipo">Tipo</label>
            <input type="text" class="form-control" id="tipo" name="tipo" value="<?php echo htmlspecialchars($clinica['Tipo']); ?>" required>
        </div>

        <div class="form-group">
            <label for="direccion">Dirección</label>
            <input type="text" class="form-control" id="direccion" name="direccion" value="<?php echo htmlspecialchars($clinica['Direccion']); ?>" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($clinica['Email']); ?>" required>
        </div>


        <div class="form-group">
            <label for="nombre">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($clinica['nombre']); ?>" required>
        </div>

        <!-- Campo oculto para pasar el ID de la clínica -->
        <input type="hidden" name="clinicaId" value="<?php echo htmlspecialchars($_GET['id']); ?>">
        <a class="btn btn-warning" href="<?php echo 'pdfqr.php?id='.htmlspecialchars($_GET['id']).'&nombre='.htmlspecialchars($clinica['nombre']);?>" target="_blank">Abrir QR</a>
        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        <a href="ubicaciones.php" class="btn btn-secondary">Volver</a>
    </form>
</div>

<?php
include_once('../plantillas/DecFin.inc.php');
?>
