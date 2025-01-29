<?php
include_once('plantillas/DecInc.inc.php');
include_once('app/func.inc.php'); // Asegúrate de incluir la clase Clinicas

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
    <form action="guardarEdicionClinica.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="nombreClinica">Nombre de la Clínica</label>
            <input type="text" class="form-control" id="nombreClinica" name="nombreClinica" value="<?php echo htmlspecialchars($clinica['nombre']); ?>" required>
        </div>
        <div class="form-group">
            <label for="direccion">Dirección</label>
            <input type="text" class="form-control" id="direccion" name="direccion" value="<?php echo htmlspecialchars($clinica['direccion']); ?>" required>
        </div>
        <div class="form-group">
            <label for="region">Región</label>
            <select class="form-control" id="region" name="region" required>
                <?php
                $regiones = [
                    "Arica y Parinacota", "Tarapacá", "Antofagasta", "Atacama", "Coquimbo",
                    "Valparaíso", "Metropolitana", "O'Higgins", "Maule", "Ñuble",
                    "Biobío", "La Araucanía", "Los Ríos", "Los Lagos", "Aysén", "Magallanes"
                ];

                foreach ($regiones as $region) {
                    $selected = ($clinica['region'] === $region) ? 'selected' : '';
                    echo "<option value='$region' $selected>$region</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="telefono">Teléfono</label>
            <input type="text" class="form-control" id="telefono" name="telefono" value="<?php echo htmlspecialchars($clinica['telefono']); ?>" required>
        </div>
        <div class="form-group">
            <label for="correo">Correo</label>
            <input type="email" class="form-control" id="correo" name="correo" value="<?php echo htmlspecialchars($clinica['correo']); ?>" required>
        </div>

        <!-- Mostrar enlace al archivo del acuerdo si existe -->
        <?php if (!empty($clinica['acuerdo_clinica'])): ?>
            <a href="<?php echo htmlspecialchars($clinica['acuerdo_clinica']); ?>" target="_blank">Ver acuerdo clínica</a>
        <?php else: ?>
            <p>No se ha subido un acuerdo clínico.</p>
        <?php endif; ?>

        <div class="form-group">
            <label for="habilitacion">Habilitado</label>
            <input type="checkbox" id="habilitacion" name="habilitacion" <?php echo $clinica['habilitacion'] ? 'checked' : ''; ?> required>
        </div>

        <!-- Campo oculto para pasar el ID de la clínica -->
        <input type="hidden" name="clinicaId" value="<?php echo htmlspecialchars($_GET['id']); ?>">

        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        
        <a class="btn btn-warning" href="<?php echo 'vistas/Clinica/pdfqr.php?id='.htmlspecialchars($_GET['id']).'&nombre='.htmlspecialchars($clinica['nombre']);?>" target="_blank">Abrir QR</a>
        <a href="clinicas.php" class="btn btn-secondary">Vovlver</a>
    </form>
</div>



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Detectar el cambio en el checkbox de habilitación
        $('#habilitacion').change(function() {
            // Obtener el estado del checkbox
            const habilitado = $(this).prop('checked') ? 1 : 0;
            
            // Obtener el ID de la clínica
            const clinicaId = $('input[name="clinicaId"]').val();

            // Enviar la solicitud AJAX
            $.ajax({
                url: 'actualizarHabilitacion.php', // Archivo que procesará la solicitud
                type: 'POST', // Método de envío
                data: {
                    clinicaId: clinicaId, // Enviar el ID de la clínica
                    habilitacion: habilitado, // Enviar el estado del checkbox
                    variable: 'edtClinHab' // Variable para evitar cache
                },
                success: function(response) {
                    // Analizar la respuesta JSON
                    const result = JSON.parse(response);

                    // Mostrar mensaje de éxito o error
                    if (result.success) {
                        alert('Estado de habilitación actualizado.');
                    } else {
                        alert('Error al actualizar el estado: ' + result.message);
                    }
                },
                error: function() {
                    alert('Hubo un error al procesar la solicitud.');
                }
            });
        });
    });
</script>

<?php
include_once('plantillas/DecFin.inc.php');
?>
