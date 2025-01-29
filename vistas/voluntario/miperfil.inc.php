<?php
$usuario = Usuario::obtenerVoluntarioPorId($_SESSION['user_id']);

?>


<div class="modal fade" id="modalFotoPerfil" tabindex="-1" role="dialog" aria-labelledby="modalFotoPerfilLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalFotoPerfilLabel">Subir Nueva Foto de Perfil</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="guardararchivo2.php" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" id="id_usuario" name="id" value="<?php echo htmlspecialchars($usuario['id']); ?>">
                    <input type="file" class="form-control-file" id="newFotoPerfil" name="newFotoPerfil">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary" onclick="uploadNewFile('Fotoperfil')">Subir Foto</button>
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
<div class="container mt-5">
    <h1 class="mb-4">Mi Perfil</h1>

    <div class="card">
        <div class="card-body">
            <form>
                <!-- Foto de perfil -->
                <div class="form-group mb-3">
                    <label for="fotoPerfil">Foto de Perfil</label><br>
                    <img src="<?php echo htmlspecialchars($usuario['Fotoperfil']); ?>" alt="Foto de perfil" class="img-thumbnail" width="100"><br>
                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modalFotoPerfil">Subir Nueva Foto</button>
                </div>

                <!-- Nombre -->
                <div class="form-group mb-3">
                    <label for="nombre">Nombre</label>
                    <input type="text" id="nombre" class="form-control" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" readonly>
                </div>
                
                <!-- Celular -->
                <div class="form-group mb-3">
                    <label for="nombre">Telefono</label>
                    <input type="text" id="Telefono" class="form-control" value="<?php echo htmlspecialchars($usuario['telefono']); ?>" readonly>
                    <button id="btnCambiarTelefono" type="button" class="btn btn-primary" onclick="mostrarModal('Telefono')">Cambiar Teléfono</button>
                </div>
                
                <!-- Correo -->
                <div class="form-group mb-3">
                    <label for="correo">Correo</label>
                    <input type="email" id="Correo" class="form-control" value="<?php echo htmlspecialchars($usuario['correo']); ?>" readonly>
                    
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
                    <input type="text" id="region" class="form-control" value="<?php echo htmlspecialchars($usuario['region']); ?>" readonly>
                </div>

                <!-- Consejo Regional -->
                <div class="form-group mb-3">
                    <label for="consejoRegional">Comuna</label>
                    <input type="text" id="consejoRegional" class="form-control" value="<?php echo htmlspecialchars($usuario['comuna']); ?>" readonly>
                </div>

                <!-- Estado -->
                <div class="form-group mb-3">
                    <label for="estado">Estado</label>
                    <input type="text" id="estado" class="form-control" value="<?php echo htmlspecialchars($usuario['estado']); ?>" readonly>
                </div>

            </form>
        </div>
    </div>
</div>