<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_DEPRECATED);
include_once('app/func.inc.php');
include_once('plantillas/DecInc.inc.php');
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<div class="alert alert-danger text-center mt-5">No se especificó un ID válido para el usuario.</div>';
    include_once('plantillas/DecFin.inc.php');
    exit;
}
$idUsuario = $_GET['id'];
$usuario = Usuario::obtenerUsuariosId($idUsuario);
$credencial = Usuario::get_cedusuario('c-' . $idUsuario);
if (!$usuario) {
    echo '<div class="alert alert-danger text-center mt-5">No se encontraron datos para el usuario especificado.</div>';
    include_once('plantillas/DecFin.inc.php');
    exit;
}
?>
<div class="modal fade" id="modalFoto" tabindex="-1" aria-labelledby="modalFotoLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formCambiarFoto" method="post" action="guardararchivo.php" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalFotoLabel">Cambiar Foto de Perfil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="newFotoPerfil">Seleccionar nueva foto</label>
                        <input type="file" class="form-control" id="newFotoPerfil" name="newFotoPerfil" accept="image/*" required>
                        <input type="hidden" name="id" id="id_usuario" value="<?php echo 'c-' . htmlspecialchars($usuario['id_usuario']); ?>">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary" id="btnSubirFoto">Subir Foto</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="modalClave" tabindex="-1" aria-labelledby="modalClaveLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="modalClaveLabel">Cambiar Clave</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="nuevaClave">Nueva Clave</label>
                    <input type="password" class="form-control" id="nuevaClave" placeholder="Ingrese nueva clave">
                </div>
                <div class="form-group">
                    <label for="confirmarClave">Confirmar Clave</label>
                    <input type="password" class="form-control" id="confirmarClave" placeholder="Repita la nueva clave">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="btnCambiarClaveModal">Cambiar Clave</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modalCredencial" tabindex="-1" aria-labelledby="modalCredencialLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalFotoPerfilLabel">Información de la Credencial</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php
            if ($credencial) {
            ?>
                <form class="form">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nombre">Nombre Completo</label>
                            <input type="text" class="form-control" maxlength="23" id="nombrecred" name="nombre" required value="<?php echo htmlspecialchars(substr($usuario['nombre'], 0, 23)); ?>">
                        </div>
                        <!-- RUT OK -->
                        <div class="form-group">
                            <label for="institucion">Institución</label>
                            <input type="text" class="form-control" id="institucioncred" name="institucion" required value="<?php echo htmlspecialchars($credencial['institucion']); ?>">
                        </div>
                        <!-- Telefono OK -->
                        <div class="form-group">
                            <label for="telefono">Cargo</label>
                            <input type="text" class="form-control" id="cargocred" name="telefono" required value="<?php echo htmlspecialchars($credencial['cargo']); ?>">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" onclick="ActCredencial()" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            <?php
            } else {
            ?>

                <form class="form">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nombre">Nombre Completo</label>
                            <input type="text" class="form-control" maxlength="23" id="nombrecred" name="nombre" required value="<?php echo htmlspecialchars(substr($usuario['nombre'], 0, 23)); ?>">
                        </div>
                        <!-- RUT OK -->
                        <div class="form-group">
                            <label for="institucion">Institución</label>
                            <input type="text" class="form-control" id="institucioncred" name="institucion" required value="<?php echo htmlspecialchars('CNRD'); ?>">
                        </div>
                        <!-- Telefono OK -->
                        <div class="form-group">
                            <label for="telefono">Cargo</label>
                            <input type="text" class="form-control" id="cargocred" name="telefono" required value="">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" onclick="crearCredencial()" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            <?php
            }
            ?>
        </div>
    </div>
</div>

<div class="container mt-5">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white text-center">
            <h3>Editar Usuario</h3>
        </div>
        <div class="card-body">
            <input type="hidden" name="id_usuario" id="id_usuario" value="<?php echo htmlspecialchars($usuario['id_usuario']); ?>">

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" class="form-control" id="nombre" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="correo">Correo</label>
                        <input type="email" class="form-control" id="correo" value="<?php echo htmlspecialchars($usuario['correo']); ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="clave">Clave</label>
                        <button type="button" id="btnCambiarClave" class="btn btn-warning">Cambiar Clave</button>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="region">Región</label>
                        <input type="text" class="form-control" id="region" value="<?php echo htmlspecialchars($usuario['region']); ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="consejo_regional">Consejo Regional</label>
                        <input type="text" class="form-control" id="consejo_regional" value="<?php echo htmlspecialchars($usuario['consejo_regional']); ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="estado">Estado</label>
                        <input type="text" class="form-control" id="estado" value="<?php echo htmlspecialchars($usuario['estado']); ?>" readonly>
                    </div>
                </div>
            </div>

            <div class="form-group text-center">
                <label for="foto_perfil">Foto de Perfil</label>
                <div class="mb-3">
                    <?php if (!empty($usuario['foto_perfil'])): ?>
                        <img src="<?php echo  htmlspecialchars($usuario['foto_perfil']); ?>" alt="Foto de Perfil" class="img-thumbnail" style="max-width: 150px;">
                    <?php else: ?>
                        <p>No hay una foto de perfil cargada.</p>
                    <?php endif; ?>
                </div>
                <button type="button" id="btnCambiarFoto" class="btn btn-warning">Cambiar Foto</button>
            </div>


        </div>
        <div class="card-footer text-center">
            <h5 class="text-muted">Estado: <strong> <?php echo $usuario['estado']; ?></strong></h5>
            <label for="estado">Acción</label>
            <br>
            <?php
            if ($usuario['estado'] === 'habilitado') {
            ?>
                <button type="button" value="deshabilitado" onclick="cambiarestado(this.value)" class="btn btn-outline-danger">Deshabilitar</button>

            <?php
            } elseif ($usuario['estado'] === 'rechazado') {
            ?>
                <h3>Contactar con soporte</h3>


            <?php
            } else {
            ?>
                <button type="button" value="habilitado" onclick="cambiarestado(this.value)" class="btn btn-outline-success">Habilitar</button>
                <button type="button" value="rechazado" onclick="cambiarestado(this.value)" class="btn btn-outline-danger">Rechazar</button>
            <?php
            }
            ?>
            <hr>
            <?php
            if ($usuario['estado'] != 'rechazado') {
                if ($credencial) {
            ?>
                    <h2>Credencial</h2>
                    <button type="button" class="btn btn-info" id="btnCrearCredencial" data-toggle="modal" data-target="#modalcredencial">Editar Credencial</button>
                <?php
                } else {

                ?>
                    <button type="button" class="btn btn-info" id="btnCrearCredencial" data-toggle="modal" data-target="#modalcredencial">Generar Credencial</button>
            <?php
                }
            }
            ?>
            <hr>
            <a href="usuarios.php" class="btn btn-secondary">Volver</a>
        </div>
    </div>
</div>



<?php include_once('plantillas/DecFin.inc.php'); ?>