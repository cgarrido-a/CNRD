<div class="modal fade" id="modalCredencial" tabindex="-1" aria-labelledby="modalCredencialLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalCredencialLabel">Subir Credencial</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <?php
      if ($credencial) {
      ?>
        <form class="form">
          <div class="modal-body">
            <div class="form-group">
              <label for="nombre">Nombre Completo</label>
              <input type="text" class="form-control" maxlength="23" id="nombrecred" name="nombre" required value="<?php echo htmlspecialchars($credencial['nombre']); ?>">
            </div>
            <!-- RUT OK -->
            <div class="form-group">
              <label for="institucion">Institución</label>
              <input type="text" class="form-control" id="institucioncred"  maxlength="32" name="institucion" required value="<?php echo htmlspecialchars($credencial['institucion']); ?>">
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
              <input type="text" class="form-control" maxlength="23" id="nombrecred" name="nombre" required value="<?php echo htmlspecialchars(substr($voluntario->obtener_nombre(), 0, 23)); ?>">
            </div>
            <!-- RUT OK -->
            <div class="form-group">
              <label for="institucion">Institución</label>
              <input type="text" class="form-control" id="institucioncred" maxlength="32" name="institucion" required value="<?php echo htmlspecialchars($institucion); ?>">
            </div>
            <!-- Telefono OK -->
            <div class="form-group">
              <label for="telefono">Cargo</label>
              <input type="text" class="form-control" id="cargocred"  name="telefono" required value="Voluntario">
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