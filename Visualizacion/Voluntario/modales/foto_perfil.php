
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
                    <input type="hidden" id="id_usuario" name="id" value="<?php echo $_SESSION['UserLog']->obtener_id(); ?>">
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