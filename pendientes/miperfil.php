<?php
include_once('plantillas/DecInc.inc.php');

// Supongamos que obtenemos la información del perfil desde la base de datos
// Esto es solo un ejemplo. En una aplicación real, estos datos vendrían de una base de datos.
$usuario = [
    'nombre' => 'Juan Pérez',
    'correo' => 'juan.perez@correo.com',
    'telefono' => '(2) 9876 5432',
    'region' => 'Metropolitana',
    'foto_perfil' => 'foto_perfil_juan.jpg', // Foto actual
];

?>

<div class="container mt-5">
    <h2>Mi Perfil</h2>

    <!-- Información del perfil -->
    <form action="guardarPerfil.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="nombre">Nombre Completo</label>
            <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $usuario['nombre']; ?>" required>
        </div>
        <div class="form-group">
            <label for="correo">Correo Electrónico</label>
            <input type="email" class="form-control" id="correo" name="correo" value="<?php echo $usuario['correo']; ?>" required>
        </div>
        <div class="form-group">
            <label for="telefono">Teléfono</label>
            <input type="text" class="form-control" id="telefono" name="telefono" value="<?php echo $usuario['telefono']; ?>" required>
        </div>
        <div class="form-group">
            <label for="region">Región</label>
            <select class="form-control" id="region" name="region" required>
                <option value="Metropolitana" <?php echo $usuario['region'] == 'Metropolitana' ? 'selected' : ''; ?>>Metropolitana</option>
                <option value="O'Higgins" <?php echo $usuario['region'] == "O'Higgins" ? 'selected' : ''; ?>>O'Higgins</option>
                <!-- Agregar más regiones según sea necesario -->
            </select>
        </div>

        <!-- Campo para cambiar la foto de perfil -->
        <div class="form-group">
            <label for="fotoPerfil">Foto de Perfil</label>
            <input type="file" class="form-control-file" id="fotoPerfil" name="fotoPerfil">
            <small class="form-text text-muted">Deja este campo vacío si no deseas cambiar la foto de perfil.</small>
        </div>

        <!-- Mostrar foto actual -->
        <div class="form-group">
            <label for="fotoActual">Foto Actual</label><br>
            <img src="<?php echo $usuario['foto_perfil']; ?>" alt="Foto de Perfil" style="width: 150px; height: auto;">
        </div>

        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
    </form>
</div>

<?php
include_once('plantillas/DecFin.inc.php');
?>
