<div class="modal fade" id="modalCertificadoTitulo" tabindex="-1" aria-labelledby="modalCertificadoTituloLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalCertificadoTituloLabel">Subir Certificado de Título</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="../src/savearchmp.php" method="post" enctype="multipart/form-data">
          <div class="modal-body">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($_SESSION['UserLog']->obtener_id()); ?>">
            <input type="file" class="form-control-file" id="newCertificadoTitulo" name="newCertificadoTitulo">
            <label for="selectProfesion" class="form-label">Elija su profesión</label>
            <select class="form-select" id="selectProfesion" name="profesion" onchange="(Mostrar1234(this))">
              <option value="">Selecciona una opción</option>
              <option value="Medico Veterinario">Médico Veterinario</option>
              <option value="Tecnico Veterinario">Técnico Veterinario</option>
              <option value="Estudiante Medicina Veterinaria">Estudiante Medicina Veterinaria</option>
              <option value="Estudiante Técnico Veterinario">Estudiante Técnico Veterinario</option>
              <option value="Otra">Otra</option>
            </select>
          </div>
          <div id="otraprof" class="mb-3"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary" onclick="uploadNewFile('certificado_titulo')">Subir Certificado</button>
      </div>
      </form>
    </div>
  </div>
</div>
</div>

<script>
  function Mostrar1234(variable) {
    var divinp = document.getElementById('otraprof')
    if(variable.value==='Otra'){
        divinp.innerHTML ='<label for="OtraProf">Señale:</label><input type="text" class="form-control" id="OtraProf" name="OtraProf">'
        
    }else{
      divinp.innerHTML=''
    }
  }
</script>