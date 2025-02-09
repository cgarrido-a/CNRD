<?php
require_once __DIR__ . "/../../../app/class.inc.php";

session_start();

if (!isset($_SESSION['UserLog'])) {
    die("Error: No tienes acceso a esta página.");
}

$voluntario_id = $_SESSION['UserLog']->obtener_id();

include_once('../plantillas/LLamstan.inc.php');
include_once('../plantillas/DecInc.inc.php');

?>

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0">Crear Informe</h3>
        </div>
        <div class="card-body">
            <form action="guardarInforme.php" method="POST">
                <input type="hidden" name="voluntario_id" value="<?php echo htmlspecialchars($voluntario_id); ?>">

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Fecha</label>
                        <input type="date" name="fecha" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Región</label>
                        <input type="text" name="region" class="form-control" required placeholder="Ej: Metropolitana">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Provincia</label>
                        <input type="text" name="provincia" class="form-control" required placeholder="Ej: Santiago">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Comuna</label>
                        <input type="text" name="comuna" class="form-control" required placeholder="Ej: Providencia">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Ubicación Georreferencial</label>
                        <input type="text" name="ubicacion_georreferencial" class="form-control" placeholder="Ej: -33.4489, -70.6693">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Dirección</label>
                        <input type="text" name="direccion" class="form-control" required placeholder="Ej: Av. Principal 123">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Tipo de Zona</label>
                        <select name="tipo_zona" class="form-select" required>
                            <option value="urbana">Urbana</option>
                            <option value="rural">Rural</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Tipo de Evento</label>
                        <select name="tipo_evento" class="form-select" required>
                            <option value="sismo">Sismo</option>
                            <option value="incendio_forestal">Incendio Forestal</option>
                            <option value="inundacion">Inundación</option>
                            <option value="actividad_volcanica">Actividad Volcánica</option>
                            <option value="deslizamiento">Deslizamiento</option>
                            <option value="tsunami">Tsunami</option>
                            <option value="quimico">Químico</option>
                            <option value="otro">Otro</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Categoría</label>
                        <select name="categoria" class="form-select" required>
                            <option value="emergencia">Emergencia</option>
                            <option value="catastrofe">Catástrofe</option>
                            <option value="desastre">Desastre</option>
                        </select>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label fw-bold">Descripción del Evento</label>
                        <textarea name="descripcion_evento" class="form-control" rows="3" placeholder="Describa lo ocurrido..."></textarea>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label fw-bold">Procesos Realizados</label>
                        <textarea name="procesos_realizados" class="form-control" rows="3" placeholder="Ej: Evaluación de daños, rescate, etc."></textarea>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label fw-bold">Decisiones Tomadas</label>
                        <textarea name="decisiones_tomadas" class="form-control" rows="3" placeholder="Ej: Evacuación, coordinación con autoridades, etc."></textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="informes.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar Informe
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
include_once('../plantillas/DecFin.inc.php');
?>
