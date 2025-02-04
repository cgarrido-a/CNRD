<div class="modal fade" id="modalFotoPerfil" tabindex="-1" aria-labelledby="modalFotoPerfilLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalFotoPerfilLabel">Cambiar Foto de Perfil</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="guardararchivo.php" method="post" enctype="multipart/form-data">
          <div class="modal-body">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($voluntario->obtener_id()); ?>">
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
</div>