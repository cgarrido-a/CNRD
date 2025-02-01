<?php
include_once('app/conex.inc.php');
include_once('app/func.inc.php'); 

// Obtener usuarios desde la base de datos
$usuarios = Usuario::obtenerUsuarios(); // La función debe retornar un JSON de usuarios
include_once('plantillas/DecInc.inc.php');
?>

<div class="container mt-5">
    <h2>Gestión de Usuarios</h2>

    <!-- Botón para abrir el modal -->
    <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#modalCrearUsuario">
        Crear Nuevo Usuario
    </button>

    <!-- Filtros -->
    <div class="row g-3 mb-3">
        <div class="col">
            <input type="text" id="filtroNombre" class="form-control" placeholder="Nombre" oninput="filtrarTabla()">
        </div>
       
        <div class="col"  <?php echo ($_SESSION['region'] !== 'Nacional') ? 'readonly style="display:none;"' : ''; ?> >
            <select id="filtroRegion" class="form-control" onchange="filtrarTabla()">
                <option value="">Región</option>
                <!-- Opciones de región -->
                <?php
                $regiones = ["Arica y Parinacota", "Tarapacá", "Antofagasta", "Atacama", "Coquimbo", "Valparaíso", "Metropolitana", "O'Higgins", "Maule", "Ñuble", "Biobío", "La Araucanía", "Los Ríos", "Los Lagos", "Aysén", "Magallanes"];
                foreach ($regiones as $region) {
                    echo "<option value=\"$region\">$region</option>";
                }
                ?>
            </select>
        </div>
        <div class="col"  <?php echo ($_SESSION['region'] !== 'Nacional') ? 'readonly style="display:none;"' : ''; ?> >
            <select id="filtroConsejo" class="form-control" onchange="filtrarTabla()">
                <option value="">Consejo Regional</option>
                <!-- Opciones de consejo -->
                <?php
                $consejos = ["Aconcagua", "Archipiélago de Chiloé", "Arica y Parinacota", "Atacama", "Aysén", "Biobío", "Coquimbo - La Serena", "La Araucanía", "Llanquihue - Puerto Montt - Osorno", "Los Ríos", "Magallanes", "Maule", "Metropolitano", "Ñuble", "O'Higgins", "Tarapacá", "Valparaíso - Marga Marga", "Valparaíso - San Antonio"];
                foreach ($consejos as $consejo) {
                    echo "<option value=\"$consejo\">$consejo</option>";
                }
                ?>
            </select>
        </div>
        <div class="col">
            <select id="filtroEstado" class="form-control" onchange="filtrarTabla()">
                <option value="">Estado</option>
                <option value="habilitado">Habilitado</option>
                <option value="deshabilitado">Deshabilitado</option>
            </select>
        </div>
    </div>

    <!-- Tabla de Usuarios -->
    <table class="table table-striped" id="tablaUsuarios">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Región</th>
                <th>Consejo</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <!-- Llenado dinámico con JavaScript -->
        </tbody>
    </table>

    <!-- Paginador -->
    <nav>
        <ul class="pagination" id="pagination"></ul>
    </nav>

    <!-- Modal para crear un nuevo usuario -->
    <div class="modal fade" id="modalCrearUsuario" tabindex="-1" role="dialog" aria-labelledby="modalCrearUsuarioLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Crear Nuevo Usuario</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formCrearUsuario">
                        <div class="form-group">
                            <label for="nombreUsuario">Nombre</label>
                            <input type="text" class="form-control" id="nombreUsuario" name="nombreUsuario" required>
                        </div>
                        <div class="form-group">
                            <label for="correoUsuario">Correo</label>
                            <input type="email" class="form-control" id="correoUsuario" name="correoUsuario" required>
                        </div>
                        <div class="form-group">
                            <label for="claveUsuario">Clave</label>
                            <input type="password" class="form-control" id="claveUsuario" name="claveUsuario" required>
                        </div>
                        <div class="form-group"  <?php echo ($_SESSION['region'] !== 'Nacional') ? 'readonly style="display:none;"' : ''; ?> >
                            <label for="regionUsuario">Región</label>
                            <select class="form-control" id="regionUsuario" name="regionUsuario"
                                <?php echo ($_SESSION['region'] !== 'Nacional') ? 'readonly style="display:none;"' : ''; ?> required>
                                <option value="">Seleccione una región</option>
                                <option value="Nacional" <?php echo ($_SESSION['region'] === 'Nacional') ? 'selected' : ''; ?>>Nacional</option>
                                <?php
                                foreach ($regiones as $region) {
                                    $selected = ($_SESSION['region'] === $region) ? 'selected' : '';
                                    echo "<option value=\"$region\" $selected>$region</option>";
                                }
                                ?>
                            </select>
                            <?php if ($_SESSION['region'] !== 'Nacional'): ?>
                                <input type="hidden" id="regionUsuario" name="regionUsuario" value="<?php echo htmlspecialchars($_SESSION['region']); ?>">
                            <?php endif; ?>
                        </div>
                        <div class="form-group"  <?php echo ($_SESSION['region'] !== 'Nacional') ? 'readonly style="display:none;"' : ''; ?> >
                            <label for="consejoUsuario">Consejo Regional</label>
                            <select class="form-control" id="consejoUsuario" name="consejoUsuario"
                                <?php echo ($_SESSION['region'] !== 'Nacional') ? 'readonly style="display:none;"' : ''; ?> required>
                                <option value="">Seleccione un consejo</option>
                                <?php
                                foreach ($consejos as $consejo) {
                                    $selected = ($_SESSION['consejo_regional'] === $consejo) ? 'selected' : '';
                                    echo "<option value=\"$consejo\" $selected>$consejo</option>";
                                }
                                ?>
                            </select>
                            <?php if ($_SESSION['region'] !== 'Nacional'): ?>
                                <input type="hidden" id="consejoUsuario" name="consejoUsuario" value="<?php echo htmlspecialchars($_SESSION['consejo_regional']); ?>">
                            <?php endif; ?>
                        </div>
                    </form>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" name="btnCrearUsuario" id="btnCrearUsuario">Crear Usuario</button>
                </div>
            </div>
        </div>
    </div>
</div>



<?php
include_once('plantillas/DecFin.inc.php');
?>