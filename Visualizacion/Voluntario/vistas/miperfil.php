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


$ruta ='';

include_once('../plantillas/DecInc.inc.php');

foreach (glob("../modales/*.php") as $archivo) {
    include_once $archivo;

}
?>
<div class="container mt-5">
    <h1 class="mb-4">Mi Perfil</h1>

    <div class="card">
        <div class="card-body">
            <form>
                <!-- Foto de perfil -->
                <div class="form-group mb-3">
                    <label for="fotoPerfil">Foto de Perfil</label><br>
                    <img src="<?php echo $_SESSION['UserLog']->obtener_fotoperfil(); ?>" alt="Foto de perfil" class="img-thumbnail" width="100"><br>
                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modalFotoPerfil">Subir Nueva Foto</button>
                </div>

                <!-- Nombre -->
                <div class="form-group mb-3">
                    <label for="nombre">Nombre</label>
                    <input type="text" id="nombre" class="form-control" value="<?php echo $_SESSION['UserLog']->obtener_nombre(); ?>" readonly>
                </div>

                <!-- Celular -->
                <div class="form-group mb-3">
                    <label for="nombre">Telefono</label>
                    <input type="text" id="Telefono" class="form-control" value="<?php echo $_SESSION['UserLog']->obtener_telefono(); ?>" readonly>
                    <button id="btnCambiarTelefono" type="button" class="btn btn-primary" onclick="mostrarModal('Telefono')">Cambiar Teléfono</button>
                </div>

                <!-- Correo -->
                <div class="form-group mb-3">
                    <label for="correo">Correo</label>
                    <input type="email" id="Correo" class="form-control" value="<?php echo $_SESSION['UserLog']->obtener_correo(); ?>" readonly>

                    <button id="btnCambiarCorreo" type="button" class="btn btn-primary" onclick="mostrarModal('Correo')">Cambiar Correo</button>
                </div>

                <!-- Clave -->
                <div class="form-group mb-3">
                    <label for="btnCambiarClave">Clave</label>
                    <button type="button" id="btnCambiarClave" class="btn btn-warning">Cambiar Clave</button>

                </div>

                <!-- Región -->
                <div class="form-group mb-3">
                    <label for="region">Región</label>
                    <input type="text" id="region" class="form-control" value="<?php echo $_SESSION['UserLog']->obtener_region(); ?>" readonly>
                </div>

                <!-- Consejo Regional -->
                <div class="form-group mb-3">
                    <label for="consejoRegional">Comuna</label>
                    <input type="text" id="consejoRegional" class="form-control" value="<?php echo $_SESSION['UserLog']->obtener_comuna(); ?>" readonly>
                </div>

                <!-- Estado -->
                <div class="form-group mb-3">
                    <label for="estado">Estado</label>
                    <input type="text" id="estado" class="form-control" value="<?php echo $_SESSION['UserLog']->obtener_estado(); ?>" readonly>
                </div>

            </form>
        </div>
    </div>
</div>
<?php
include_once('../plantillas/DecFin.inc.php');
?>