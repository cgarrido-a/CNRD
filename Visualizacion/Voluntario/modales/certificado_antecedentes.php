<div class="modal fade" id="modalCertificadoAntecedentes" tabindex="-1" aria-labelledby="modalCertificadoAntecedentesLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalCertificadoAntecedentesLabel">Subir Certificado de Antecedentes</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="../src/savearchmp.php" method="post" enctype="multipart/form-data">
          <div class="modal-body">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($_SESSION['UserLog']->obtener_id()); ?>">
            <input type="file" class="form-control-file" id="newCertificadoAntecedentes" name="newCertificadoAntecedentes">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-primary" onclick="uploadNewFile('certificadoAntecedentes')">Subir Certificado</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>