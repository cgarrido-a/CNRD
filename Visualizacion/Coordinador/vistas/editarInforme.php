<?php
require_once __DIR__ . "/../../../app/class.inc.php";
session_start();

if (!isset($_SESSION['UserLog'])) {
    die("<div class='container mt-5 alert alert-danger'>Error: No tienes acceso a esta página.</div>");
}

if (!isset($_GET['id'])) {
    die("<div class='container mt-5 alert alert-danger'>Error: No se proporcionó un ID de informe.</div>");
}

$id_informe = $_GET['id'];

try {
    $conexion = new PDO("mysql:host=localhost;dbname=cnrd_nueva", "root", "");
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obtener datos del informe
    $stmt = $conexion->prepare("SELECT * FROM informes WHERE id = ?");
    $stmt->execute([$id_informe]);
    $informe = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$informe) {
        die("<div class='container mt-5 alert alert-warning'>Error: Informe no encontrado.</div>");
    }

    // Obtener animales afectados
    $stmtAnimales = $conexion->prepare("SELECT * FROM animales_afectados WHERE informe_id = ?");
    $stmtAnimales->execute([$id_informe]);
    $animales = $stmtAnimales->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("<div class='container mt-5 alert alert-danger'>Error en la base de datos: " . $e->getMessage() . "</div>");
}

include_once('../plantillas/LLamstan.inc.php');
include_once('../plantillas/DecInc.inc.php');
?>

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-warning text-white">
            <h3 class="mb-0"><i class="fas fa-edit"></i> Editar Informe</h3>
        </div>
        <div class="card-body">
            <form action="guardarEdicionInforme.php" method="POST">
                <input type="hidden" name="id_informe" value="<?php echo htmlspecialchars($id_informe); ?>">

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Fecha</label>
                        <input type="date" name="fecha" class="form-control" value="<?php echo htmlspecialchars($informe['fecha']); ?>" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Región</label>
                        <input type="text" name="region" class="form-control" value="<?php echo htmlspecialchars($informe['region']); ?>" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Provincia</label>
                        <input type="text" name="provincia" class="form-control" value="<?php echo htmlspecialchars($informe['provincia']); ?>" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Comuna</label>
                        <input type="text" name="comuna" class="form-control" value="<?php echo htmlspecialchars($informe['comuna']); ?>" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Ubicación Georreferencial</label>
                        <input type="text" name="ubicacion_georreferencial" class="form-control" value="<?php echo htmlspecialchars($informe['ubicacion_georreferencial']); ?>">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Dirección</label>
                        <input type="text" name="direccion" class="form-control" value="<?php echo htmlspecialchars($informe['direccion']); ?>" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Tipo de Zona</label>
                        <select name="tipo_zona" class="form-select" required>
                            <option value="urbana" <?php echo ($informe['tipo_zona'] == 'urbana') ? 'selected' : ''; ?>>Urbana</option>
                            <option value="rural" <?php echo ($informe['tipo_zona'] == 'rural') ? 'selected' : ''; ?>>Rural</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Tipo de Evento</label>
                        <select name="tipo_evento" class="form-select" required>
                            <?php
                            $eventos = ["sismo", "incendio_forestal", "inundacion", "actividad_volcanica", "deslizamiento", "tsunami", "quimico", "otro"];
                            foreach ($eventos as $evento) {
                                $selected = ($informe['tipo_evento'] == $evento) ? 'selected' : '';
                                echo "<option value='$evento' $selected>" . ucfirst($evento) . "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Categoría</label>
                        <select name="categoria" class="form-select" required>
                            <option value="emergencia" <?php echo ($informe['categoria'] == 'emergencia') ? 'selected' : ''; ?>>Emergencia</option>
                            <option value="catastrofe" <?php echo ($informe['categoria'] == 'catastrofe') ? 'selected' : ''; ?>>Catástrofe</option>
                            <option value="desastre" <?php echo ($informe['categoria'] == 'desastre') ? 'selected' : ''; ?>>Desastre</option>
                        </select>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label fw-bold">Descripción del Evento</label>
                        <textarea name="descripcion_evento" class="form-control" rows="3" placeholder="Describa lo ocurrido..."><?php echo htmlspecialchars($informe['descripcion_evento']); ?></textarea>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label fw-bold">Procesos Realizados</label>
                        <textarea name="procesos_realizados" class="form-control" rows="3" placeholder="Ej: Evaluación de daños, rescate, etc."><?php echo htmlspecialchars($informe['procesos_realizados']); ?></textarea>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label fw-bold">Decisiones Tomadas</label>
                        <textarea name="decisiones_tomadas" class="form-control" rows="3" placeholder="Ej: Evacuación, coordinación con autoridades, etc."><?php echo htmlspecialchars($informe['decisiones_tomadas']); ?></textarea>
                    </div>

                </div>

                <hr>
                <h4 class="mb-3">Animales Afectados</h4>
                <div id="animales-container">
                    <?php foreach ($animales as $index => $animal) : ?>
                        <div class="row animal-entry">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Especie</label>
                                <select name="animales[<?php echo $index; ?>][especie]" class="form-select" required>
                                    <?php
                                    $especies = ['bovino', 'equino', 'ovino', 'caprino', 'porcino', 'felino', 'canino', 'aves_corral', 'exoticos', 'fauna_silvestre'];
                                    foreach ($especies as $especie) {
                                        $selected = ($animal['especie'] == $especie) ? 'selected' : '';
                                        echo "<option value='$especie' $selected>$especie</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold">N° Atendidos</label>
                                <input type="number" name="animales[<?php echo $index; ?>][n_atendidos]" class="form-control" value="<?php echo htmlspecialchars($animal['n_atendidos']); ?>" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold">N° Fallecidos</label>
                                <input type="number" name="animales[<?php echo $index; ?>][n_fallecidos]" class="form-control" value="<?php echo htmlspecialchars($animal['n_fallecidos']); ?>" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold">N° Pendientes</label>
                                <input type="number" name="animales[<?php echo $index; ?>][n_pendientes]" class="form-control" value="<?php echo htmlspecialchars($animal['n_pendientes']); ?>" required>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="informes.php" class="btn btn-secondary">Volver</a>
                    <button type="submit" class="btn btn-warning">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>
