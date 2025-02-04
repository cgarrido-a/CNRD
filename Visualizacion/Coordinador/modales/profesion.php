<div class="modal fade" id="modalProfesion" tabindex="-1" aria-labelledby="modalProfesionLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalProfesionLabel">Seleccionar Profesión</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <div class="mb-3">
            <label for="selectProfesion" class="form-label">Elija su profesión</label>
            <select class="form-select" id="selectProfesion" name="profesion">
              <option value="">Selecciona una opción</option>
              <option value="Medico Veterinario">Médico Veterinario</option>
              <option value="Tecnico Veterinario">Técnico Veterinario</option>
              <option value="Estudiante Medicina Veterinaria">Estudiante Medicina Veterinaria</option>
              <option value="Estudiante Técnico Veterinario">Estudiante Técnico Veterinario</option>
              <option value="Otra">Otra</option>
            </select>
          </div>
          <div id="otraprof" class="mb-3"></div>
          <button type="button" onclick="cambiarprof()" class="btn btn-primary">Guardar</button>
        
      </div>
    </div>
  </div>
</div>
