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

                <hr>
                <h4 class="mb-3">Animales Afectados</h4>
                <div id="animales-container">
                    <div class="row animal-entry">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Especie</label>
                            <select name="especie[]" class="form-select" required>
                                <option value="" selected disabled>Seleccione especie</option>
                                <option value="bovino">Bovino</option>
                                <option value="equino">Equino</option>
                                <option value="ovino">Ovino</option>
                                <option value="caprino">Caprino</option>
                                <option value="porcino">Porcino</option>
                                <option value="felino">Felino</option>
                                <option value="canino">Canino</option>
                                <option value="aves_corral">Aves de Corral</option>
                                <option value="exoticos">Exóticos</option>
                                <option value="fauna_silvestre">Fauna Silvestre</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-bold">N° Atendidos</label>
                            <input type="number" name="n_atendidos[]" class="form-control" min="0">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-bold">N° Fallecidos</label>
                            <input type="number" name="n_fallecidos[]" class="form-control" min="0">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-bold">N° Pendientes</label>
                            <input type="number" name="n_pendientes[]" class="form-control" min="0">
                        </div>
                    </div>
                </div>

                <button type="button" class="btn btn-success mb-3" onclick="agregarFila()">+ Agregar Animal</button>

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

<script>
    function agregarFila() {
        let container = document.getElementById('animales-container');
        let nuevaFila = document.createElement('div');
        nuevaFila.classList.add('row', 'animal-entry');
        nuevaFila.innerHTML = `
                <div class="col-md-4">
                <label class="form-label fw-bold">Especie</label>
                <select name="especie[]" class="form-select" required>
                    <option value="" selected disabled>Seleccione especie</option>
                    <option value="bovino">Bovino</option>
                    <option value="equino">Equino</option>
                    <option value="ovino">Ovino</option>
                    <option value="caprino">Caprino</option>
                    <option value="porcino">Porcino</option>
                    <option value="felino">Felino</option>
                    <option value="canino">Canino</option>
                    <option value="aves_corral">Aves de Corral</option>
                    <option value="exoticos">Exóticos</option>
                    <option value="fauna_silvestre">Fauna Silvestre</option>
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <input type="number" name="n_atendidos[]" class="form-control" min="0">
            </div>
            <div class="col-md-3 mb-3">
                <input type="number" name="n_fallecidos[]" class="form-control" min="0">
            </div>
            <div class="col-md-3 mb-3">
                <input type="number" name="n_pendientes[]" class="form-control" min="0">
            </div>
        `;
        container.appendChild(nuevaFila);
    }
</script>

<?php
include_once('../plantillas/DecFin.inc.php');
?>
