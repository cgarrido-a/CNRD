<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_DEPRECATED);
include_once('plantillas/DecInc.inc.php');

// Verificar que se ha recibido el ID del voluntario por GET
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<div class="alert alert-danger text-center mt-5">No se especificó un ID válido para el voluntario.</div>';
    include_once('plantillas/DecFin.inc.php');
    exit;
}

$idVoluntario = $_GET['id'];
// Obtener datos del voluntario
$voluntario = Usuario::obtenerVoluntarioPorId($idVoluntario);
$credencial = Usuario::get_cedusuario($idVoluntario);

if (!$voluntario) {
    echo '<div class="alert alert-danger text-center mt-5">No se encontraron datos para el voluntario especificado.</div>';
    include_once('plantillas/DecFin.inc.php');
    exit;
}
$institucion = 'CNRD ' . $voluntario['region'];
?>
<!-- Modal  credencial -->
<div class="modal fade" id="modalcredencial" tabindex="-1" role="dialog" aria-labelledby="modalcredencialLabel" aria-hidden="true">
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
                            <input type="text" class="form-control" maxlength="23" id="nombrecred" name="nombre" required value="<?php echo htmlspecialchars(substr($voluntario['nombre'], 0, 23)); ?>">
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
                            <input type="text" class="form-control" maxlength="23" id="nombrecred" name="nombre" required value="<?php echo htmlspecialchars(substr($voluntario['nombre'], 0, 23)); ?>">
                        </div>
                        <!-- RUT OK -->
                        <div class="form-group">
                            <label for="institucion">Institución</label>
                            <input type="text" class="form-control" id="institucioncred" name="institucion" required value="<?php echo htmlspecialchars($institucion); ?>">
                        </div>
                        <!-- Telefono OK -->
                        <div class="form-group">
                            <label for="telefono">Cargo</label>
                            <input type="text" class="form-control" id="cargocred" name="telefono" required value="Voluntario">
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
<!-- Modal Foto perfil -->
<div class="modal fade" id="modalFotoPerfil" tabindex="-1" role="dialog" aria-labelledby="modalFotoPerfilLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalFotoPerfilLabel">Subir Nueva Foto de Perfil</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <form action="guardararchivo.php" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($voluntario['id']); ?>">
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

<!-- Modal Certificado de Título -->
<div class="modal fade" id="modalCertificadoTitulo" tabindex="-1" role="dialog" aria-labelledby="modalCertificadoTituloLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCertificadoTituloLabel">Subir Nuevo Certificado de Título</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="guardararchivo.php" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($voluntario['id']); ?>">
                    <input type="file" class="form-control-file" id="newCertificadoTitulo" name="newCertificadoTitulo">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary" onclick="uploadNewFile('certificado_titulo')">Subir Certificado</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Certificado de Antecedentes -->
<div class="modal fade" id="modalCertificadoAntecedentes" tabindex="-1" role="dialog" aria-labelledby="modalCertificadoAntecedentesLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCertificadoAntecedentesLabel">Subir Nuevo Certificado de Antecedentes</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="guardararchivo.php" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($voluntario['id']); ?>">
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
<!-- Modal profesion -->
<div class="modal fade" id="modalProfesion" tabindex="-1" role="dialog" aria-labelledby="modalProfesionLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCertificadoAntecedentesLabel">Cambiar profesion</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="form-group">
                <label for="profesion">Profesión u Ocupación</label>
                <select class="form-control" id="profesion" name="profesion" required>
                    <option value="">Selecciona una opción</option>
                    <option value="Medico Veterinario">Médico Veterinario</option>
                    <option value="Tecnico Veterinario">Técnico Veterinario</option>
                    <option value="Estudiante Medicina Veterinaria">Estudiante Medicina Veterinaria</option>
                    <option value="Estudiante Técnico Veterinario">Estudiante Técnico Veterinario</option>
                    <option value="Otra">Otra</option>
                </select>
                <div id="otraprof"></div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="cambiarprof()">Subir Certificado</button>
            </div>
            </form>

        </div>
    </div>

    <div class="container mt-5">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white text-center">
                <h3>Detalles del Voluntario</h3>
            </div>
            <div class="card-body">
                <!-- Información Personal -->
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="text-muted">Información Personal</h5>
                        <hr>
                        <p><strong>Nombre Completo:</strong> <?php echo htmlspecialchars($voluntario['nombre']); ?></p>
                        <p><strong>RUT:</strong> <?php echo htmlspecialchars($voluntario['rut']); ?></p>
                        <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($voluntario['telefono']); ?></p>
                        <p><strong>Correo:</strong> <?php echo htmlspecialchars($voluntario['correo']); ?></p>
                        <p><strong>Profesión:</strong> <?php echo htmlspecialchars($voluntario['profesion']); ?></p>
                    </div>
                    <div class="col-md-6">
                        <h5 class="text-muted">Información Adicional</h5>
                        <hr>
                        <p><strong>Tipo de Alimentación:</strong> <?php echo htmlspecialchars($voluntario['tipo_alimentacion']); ?></p>
                        <p><strong>Grupo Sanguíneo:</strong> <?php echo htmlspecialchars($voluntario['grupo_sanguineo']); ?></p>
                        <p><strong>Enfermedades Crónicas:</strong> <?php echo htmlspecialchars($voluntario['enfermedades_cronicas']); ?></p>
                        <p><strong>Estado:</strong> <?php echo htmlspecialchars($voluntario['estado'] ? 'Habilitado' : 'Deshabilitado'); ?></p>
                        <p><strong>Fecha de Registro:</strong> <?php echo htmlspecialchars($voluntario['fecha_registro']); ?></p>
                    </div>
                </div>

                <!-- Ubicación -->
                <div class="row mt-4">
                    <div class="col-md-6">
                        <h5 class="text-muted">Ubicación</h5>
                        <hr>
                        <p><strong>Región:</strong> <?php echo htmlspecialchars($voluntario['region']); ?></p>
                        <p><strong>Comuna:</strong> <?php echo htmlspecialchars($voluntario['comuna']); ?></p>
                    </div>
                    <div class="col-md-6">
                        <h5 class="text-muted">Hobbies y Recursos Propios</h5>
                        <hr>
                        <p><strong>Hobbies:</strong> <?php echo htmlspecialchars($voluntario['hobbys']); ?></p>
                        <p><strong>Recursos Propios:</strong> <?php echo htmlspecialchars($voluntario['recursos_propios']); ?></p>
                    </div>
                </div>

                <!-- Documentos -->
                <div class="row mt-4">
                    <div class="col-md-6 text-center">
                        <h5 class="text-muted">Foto de Perfil</h5>
                        <hr>
                        <?php if (!empty($voluntario['Fotoperfil'])): ?>
                            <img src="<?php echo htmlspecialchars($voluntario['Fotoperfil']); ?>" alt="Foto de Perfil" class="rounded-circle img-thumbnail" style="max-width: 150px;">
                        <?php else: ?>
                            <p>No hay una foto de perfil cargada.</p>
                        <?php endif; ?>
                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modalFotoPerfil">Subir Nueva Foto</button>
                    </div>
                    <hr>
                    <div class="col-md-6">
                        <h5 class="text-muted">Documentos</h5>
                        <p><strong>Certificado de Título:</strong><br>
                            <?php if (!empty($voluntario['certificado_titulo'])): ?>
                                <a href="<?php echo htmlspecialchars($voluntario['certificado_titulo']); ?>" class="btn btn-link" target="_blank">Ver Certificado</a>
                            <?php else: ?>
                                <span class="text-danger">No disponible</span>
                            <?php endif; ?>
                            <hr>
                            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modalCertificadoTitulo">Subir Nuevo Certificado</button>
                        </p>
                        <hr>
                        <p><strong>Certificado de Antecedentes:</strong><br>
                            <?php if (!empty($voluntario['certificadoAntecedentes'])): ?>
                                <a href="<?php echo htmlspecialchars($voluntario['certificadoAntecedentes']); ?>" class="btn btn-link" target="_blank">Ver Certificado</a>
                            <?php else: ?>
                                <span class="text-danger">No disponible</span>
                            <?php endif; ?>
                            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modalCertificadoAntecedentes">Subir Nuevo Certificado</button>
                        </p>
                    </div>
                </div>
 
                <!-- Experiencias -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <h5 class="text-muted">Experiencias</h5>
                        <hr>
                        <p><strong>Experiencia como Voluntario:</strong> <?php echo htmlspecialchars($voluntario['experiencia_voluntario']); ?></p>
                        <p><strong>Experiencia en Emergencias:</strong> <?php echo htmlspecialchars($voluntario['experiencia_emergencias']); ?></p>
                        <p><strong>Experiencia con Animales:</strong> <?php echo htmlspecialchars($voluntario['experiencia_animales']); ?></p>
                        <p><strong>Experiencia en Desastres:</strong> <?php echo htmlspecialchars($voluntario['experiencia_desastres']); ?></p>
                        <p><strong>Experiencia en Otras Emergencias:</strong> <?php echo htmlspecialchars($voluntario['experiencia_otra_emergencia']); ?></p>
                    </div>
                </div>
            </div>
            <?php

            if ($_SESSION['region'] === 'Nacional') {
            ?>

                <div class="card-footer text-center">
                    <h5 class="text-muted">Estado: <strong> <?php echo $voluntario['estado']; ?></strong></h5>
                    <label for="estado">Acción</label>
                    <br>
                    <?php
                    if ($voluntario['estado'] === 'habilitado') {
                    ?>
                        <button type="button" value="deshabilitado" onclick="cambiarestado(this.value)" class="btn btn-outline-danger">Deshabilitar</button>

                    <?php
                    } elseif ($voluntario['estado'] === 'rechazado') {
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
                    if ($voluntario['estado'] != 'rechazado') {
                        if ($credencial) {
                    ?>
                            <h2>Credencial</h2>
                            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modalcredencial">Editar Credencial</button>
                        <?php
                        } else {

                        ?>
                            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modalcredencial">Generar Credencial</button>
                    <?php
                        }
                    }
                    ?>
                    <a type="button" class="btn btn-info" target="_blank" href="<?php echo 'MiCredencial.php?id=' . $idVoluntario; ?>">Ver Credencial</a>
                    <hr>
                    <a href="voluntarios.php" class="btn btn-secondary">Volver</a>
                </div>
            <?php
            } else {
            ?>

                <div class="card-footer text-center">
                    <h5 class="text-muted">Estado: <strong> <?php echo $voluntario['estado']; ?></strong></h5>

                    <hr>
                    <a href="voluntarios.php" class="btn btn-secondary">Volver</a>
                </div>
            <?php
            }
            ?>


        </div>
    </div>


    <?php
    include_once('plantillas/DecFin.inc.php');
    ?>