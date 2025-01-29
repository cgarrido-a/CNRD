<?php
// Ejemplo de datos de usuario simulados
$usuario = Usuario::obtenerUsuariosId($_SESSION['user_id']);
?>

<!-- Modal Cambiar Foto -->
<div class="modal fade" id="modalFoto" tabindex="-1" aria-labelledby="modalFotoLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formCambiarFoto" method="post" action="guardararchivo2.php" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalFotoLabel">Cambiar Foto de Perfil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="newFotoPerfil">Seleccionar nueva foto</label>
                        <input
                            type="file"
                            class="form-control"
                            id="newFotoPerfil"
                            name="newFotoPerfil"
                            accept="image/*"
                            required>
                        <input
                            type="hidden"
                            name="id"
                            id="id_usuario"
                            value="<?php echo 'c-' . htmlspecialchars($usuario['id_usuario']); ?>">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Subir Foto</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Cambiar Clave -->
<div class="modal fade" id="modalClave" tabindex="-1" aria-labelledby="modalClaveLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalClaveLabel">Cambiar Clave</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group mb-3">
                    <label for="nuevaClave">Nueva Clave</label>
                    <input
                        type="password"
                        class="form-control"
                        id="nuevaClave"
                        placeholder="Ingrese nueva clave">
                </div>
                <div class="form-group">
                    <label for="confirmarClave">Confirmar Clave</label>
                    <input
                        type="password"
                        class="form-control"
                        id="confirmarClave"
                        placeholder="Repita la nueva clave">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="btnCambiarClaveModal">Cambiar Clave</button>
            </div>
        </div>
    </div>
</div>

<!-- Página Mi Perfil -->
<div class="container mt-5">
    <h1 class="mb-4">Mi Perfil</h1>
    <div class="card">
        <div class="card-body">
            <form>
                <!-- Foto de Perfil -->
                <div class="form-group mb-4 text-center">
                    <label for="fotoPerfil" class="form-label">Foto de Perfil</label><br>
                    <img
                        src="<?php echo htmlspecialchars($usuario['foto_perfil']); ?>"
                        alt="Foto de perfil"
                        class="img-thumbnail"
                        width="200">
                    <br><br>
                    <button
                        type="button"
                        id="btnCambiarFoto"
                        class="btn btn-warning"
                        data-bs-toggle="modal"
                        data-bs-target="#modalFoto">
                        Cambiar Foto
                    </button>
                </div>

                <!-- Nombre -->
                <div class="form-group mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input
                        type="text"
                        id="nombre"
                        class="form-control"
                        value="<?php echo htmlspecialchars($usuario['nombre']); ?>"
                        readonly>
                </div>

                <!-- Correo -->
                <div class="form-group mb-3">
                    <label for="correo" class="form-label">Correo</label>
                    <input
                        type="email"
                        id="Correo"
                        class="form-control"
                        value="<?php echo htmlspecialchars($usuario['correo']); ?>"
                        readonly>

                    <button id="btnCambiarCorreo" type="button" class="btn btn-primary" onclick="mostrarModal('Correo')">Cambiar Correo</button>
                </div> 

                <!-- Clave -->
                <div class="form-group mb-3">
                    <label for="clave" class="form-label">Clave</label><br>
                    <button
                        type="button"
                        id="btnCambiarClave"
                        class="btn btn-warning"
                        data-bs-toggle="modal"
                        data-bs-target="#modalClave">
                        Cambiar Clave
                    </button>
                </div>

              
            </form>
        </div>
    </div>
</div>
<script>
    document.getElementById('btnCambiarFoto').addEventListener('click', function() {
        $('#modalFoto').modal('show');
    });

    document.getElementById('btnCambiarClave').addEventListener('click', function() {
        $('#modalClave').modal('show');
    });
</script>