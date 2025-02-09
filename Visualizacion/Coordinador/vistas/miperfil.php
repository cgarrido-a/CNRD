<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_DEPRECATED);
//HAY QUE CORREGIR CON LOS CAMBIOS EN LA BASE DE DATOS

include_once('../plantillas/LLamstan.inc.php');
session_start();

$ruta = '';
include_once('../plantillas/DecInc.inc.php');
echo $_SESSION['UserLog']->obtener_TypeUser();
$institucion = 'CNRD ' . $_SESSION['UserLog']->obtener_id_region();
$voluntario = Voluntarios::obtenerVoluntarioPorId($_SESSION['UserLog']->obtener_id());
foreach (glob("../modales/*.php") as $archivo) {
    include_once $archivo;
}
?>
<div class="container mt-4">
    <h1 class="mb-5 text-center text-primary">Mi Perfil</h1>

    <div class="card shadow-lg border-light">
        <div class="card-body">
            <div class="row">
                <!-- Columna para la Foto de Perfil -->
                <div class="col-md-4 text-center mb-4">
                    <label for="fotoPerfil" class="h5 font-weight-bold">Foto de Perfil</label><br>
                    <img src="<?php echo htmlspecialchars($_SESSION['UserLog']->obtener_Fotoperfil()); ?>" alt="Foto de perfil" class="img-fluid rounded-circle" style="width: 220px; height: 220px; object-fit: cover;"><br>
                    <button type="button" class="btn btn-info mt-3" data-toggle="modal" data-target="#modalFotoPerfil">Subir Nueva Foto</button>
                </div>

                <!-- Columna para los Datos del Usuario -->
                <div class="col-md-8">
                    <div class="row">
                        <!-- Nombre -->
                        <div class="col-md-6 mb-3">
                            <label for="nombre" class="font-weight-bold"><strong>Nombre</strong></label>
                            <p id="nombre" class="form-control-plaintext"><?php echo htmlspecialchars($_SESSION['UserLog']->obtener_nombre()); ?></p>
                        </div>

                        <!-- Celular -->
                        <div class="col-md-6 mb-3">
                            <label for="telefono" class="font-weight-bold"><strong>Teléfono</strong></label>
                            <p id="Telefono" class="form-control-plaintext"><?php echo htmlspecialchars($_SESSION['UserLog']->obtener_telefono()); ?></p>
                            <button id="btnCambiarTelefono" type="button" class="btn btn-outline-primary mt-2" onclick="mostrarModal('Telefono')">Cambiar Teléfono</button>
                        </div>

                        <!-- Correo -->
                        <div class="col-md-6 mb-3">
                            <label for="correo" class="font-weight-bold"><strong> Correo</strong> </label>
                            <p id="Correo" class="form-control-plaintext"><?php echo htmlspecialchars($_SESSION['UserLog']->obtener_correo()); ?></p>
                            <button id="btnCambiarCorreo" type="button" class="btn btn-outline-primary mt-2" onclick="mostrarModal('Correo')">Cambiar Correo</button>
                            <p style="color: red;">Importante corresponde al usuario de acceso</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="btnCambiarClave" class="font-weight-bold"><strong>Clave</strong></label>
                            <button type="button" id="btnCambiarClave" class="btn btn-warning btn-block">Cambiar Clave</button>
                        </div>

                        <!-- Región -->
                        <div class="col-md-6 mb-3">
                            <label for="region" class="font-weight-bold"><strong>Región</strong></label>
                            <p id="region" class="form-control-plaintext"><?php echo htmlspecialchars($_SESSION['UserLog']->obtener_region()); ?></p>
                        </div>

                        <!-- Comuna -->
                        <div class="col-md-6 mb-3">
                            <label for="consejoRegional" class="font-weight-bold"><strong>Comuna</strong></label>
                            <p id="consejoRegional" class="form-control-plaintext"><?php echo htmlspecialchars($_SESSION['UserLog']->obtener_comuna()); ?></p>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php
// Incluir pie de página (DecFin.inc.php)
if (file_exists('../plantillas/DecFin.inc.php')) {
    include_once('../plantillas/DecFin.inc.php');
} else {
    die('Error: No se encuentra el archivo DecFin.inc.php.');
}
?>