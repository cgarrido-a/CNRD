<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_DEPRECATED);

include_once('../plantillas/LLamstan.inc.php');
session_start();

$ruta = '';
include_once('../plantillas/DecInc.inc.php');

// Validación de ID del voluntario
$idVoluntario = $_GET['id'] ?? null;
if (!$idVoluntario) {
    mostrarError("No se especificó un ID válido para el voluntario.");
    exit;
}

// Obtener datos del voluntario
$voluntario = Voluntarios::obtenerVoluntarioPorId($idVoluntario);
$credencial = Usuario::get_cedusuario($idVoluntario);
$idreg= $voluntario->obtener_region();
$regiones = Usuario::ObtenerConsejos($idreg);

if (!$voluntario) {
    mostrarError("No se encontraron datos para el voluntario especificado.");
    exit;
}

$institucion = 'CNRD ' . $voluntario->obtener_id_region();

function mostrarError($mensaje)
{
    echo '<div class="alert alert-danger text-center mt-5">' . htmlspecialchars($mensaje) . '</div>';
    include_once('../plantillas/DecFin.inc.php');
}

foreach (glob("../modales-vol/*.php") as $archivo) {
    include_once $archivo;
}
?>



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
                    <div class="form-group mb-3">
                        <?php generarDetalle([
                            'Nombre Completo' => $voluntario->obtener_nombre(),
                            'RUT' => $voluntario->obtener_rut(),
                            'Teléfono' => $voluntario->obtener_telefono(),
                            'Correo' => $voluntario->obtener_correo()
                        ]); ?>

                        <button type="button" id="btnCambiarClave" class="btn btn-warning">Cambiar Clave</button>

                    </div>
                </div>
                <div class="col-md-6">
                    <br>
                    <hr>
                    <?php generarDetalle([
                        'Profesión' => $voluntario->obtener_profesion(),
                        'Tipo de Alimentación' => $voluntario->obtener_tipo_alimentacion(),
                        'Grupo Sanguíneo' => $voluntario->obtener_grupo_sanguineo(),
                        'Enfermedades Crónicas' => $voluntario->obtener_enfermedades_cronicas(),
                        'Estado' => $voluntario->obtener_estado() ? 'Habilitado' : 'Deshabilitado',
                        'Fecha de Registro' => $voluntario->obtener_fecha_registro()
                    ]); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <h5 class="text-muted">Historial de voluntario</h5>
                    <hr>
                    <?php generarDetalle([
                        'Area de desempeño' => $voluntario->obtener_area_desempeno(),
                        'Actividades' => $voluntario->obtener_actividades(),
                        'Experencia en emergencias' => $voluntario->obtener_experiencia_emergencias(),
                        'Experencia de trabajo con animales' => $voluntario->obtener_experiencia_animales(),
                        'Experiencia en desastres' => $voluntario->obtener_experiencia_desastres()
                    ]); ?>
                </div>
            </div>
            <!-- Ubicación y Hobbies -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <h5 class="text-muted">Ubicación</h5>
                    <hr>
                    <?php generarDetalle([
                        'Región' => $voluntario->obtener_region(),
                        'Comuna' => $voluntario->obtener_comuna()
                    ]); ?>
                </div>
                <div class="col-md-6">
                    <h5 class="text-muted">Hobbies y Recursos Propios</h5>
                    <hr>
                    <?php generarDetalle([
                        'Hobbies' => $voluntario->obtener_hobbies(),
                        'Recursos Propios' => $voluntario->obtener_recursos_propios()
                    ]); ?>
                </div>
            </div>

            <!-- Documentos y Foto de Perfil -->
            <div class="row mt-4">
                <div class="col-md-6 text-center">
                    <h5 class="text-muted">Foto de Perfil</h5>
                    <hr>
                    <?php generarImagen($voluntario->obtener_Fotoperfil(), 'Foto de Perfil', '#modalFotoPerfil'); ?>
                </div>
                <div class="col-md-6">
                    <h5 class="text-muted">Documentos</h5>
                    <hr>
                    <?php generarDocumento('Certificado de Título', $voluntario->obtener_certificado_titulo(), '#modalCertificadoTitulo'); ?>
                    <hr>
                    <?php generarDocumento('Certificado de Antecedentes', $voluntario->obtener_certificado_antecedentes(), '#modalCertificadoAntecedentes'); ?>
                </div>
            </div>
        </div>


        <div class="card-footer">
            <div class="row">
                <div class="col-md-6 text-center">
                    <h5 class="text-muted">Administrativo</h5>
                    <hr>
                    <h6 class="text-muted">Estado: <strong><?php echo $voluntario->obtener_estado(); ?>

                            <?php if ($voluntario->obtener_estado() === 'habilitado') { ?>
                                <button type="button" value="deshabilitado" onclick="cambiarestado(this.value)" class="btn btn-outline-danger">Deshabilitar</button>
                            <?php } elseif ($voluntario->obtener_estado() === 'rechazado') { ?>
                                <strong>Contactar con soporte</strong>
                            <?php } else { ?>
                                <button type="button" value="habilitado" onclick="cambiarestado(this.value)" class="btn btn-outline-success">Habilitar</button>
                                <button type="button" value="rechazado" onclick="cambiarestado(this.value)" class="btn btn-outline-danger">Rechazar</button>
                            <?php } ?>
                        </strong>
                    </h6>
                    
                    <h6 for="tipoUsuario">Tipo de Usuario
                        <select class="form-select-sm" style=" width: 200px;  /* Ancho personalizado */height: 35px;  /* Altura personalizada */font-size: 12px;  /* Tamaño de fuente */padding: 4px;  /* Espaciado interno */border-radius: 5px;  /* Bordes redondeados */border: 1px solid #ccc;  /* Borde con color gris claro */" 
                        id="tipoUsuario" name="tipoUsuario" onchange="Mostrarbtn()">
                            <option value="Voluntario" <?php echo $voluntario->obtener_TypeUser() == 'Voluntario' ? 'selected' : ''; ?>>Voluntario</option>
                            <option value="Nacional" <?php echo $voluntario->obtener_TypeUser() == 'Nacional' ? 'selected' : ''; ?>>Nacional</option>
                            <option value="Coordinador" <?php echo $voluntario->obtener_TypeUser() == 'Coordinador' ? 'selected' : ''; ?>>Coordinador regional</option>
                            <option value="EqReg" <?php echo $voluntario->obtener_TypeUser() == 'EqReg' ? 'selected' : ''; ?>>Equipo regional</option>
                        </select>
                    </h6>
                    <?php
                     if($voluntario->obtener_TypeUser() == 'Coordinador'){
                        echo ' <h6 id="SelectorConsejo"for="seleccionconsejo">Elegir consejo';
                     }else{
                        echo ' <h6 id="SelectorConsejo" hidden for="seleccionconsejo">Elegir consejo';
                     } ?>
                   
                        <select name="region" id="SelectorConsejo2">
                            <option value=""></option>
                            <?php foreach ($regiones as $region): ?>
                                <option value="<?php echo $region['id']; ?>"
                                    <?php echo ($region['id_coordinador'] == $voluntario->obtener_id()) ? 'selected' : ''; ?>>
                                    <?php echo $region['nombre']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </h6>
                    <button type="button" id="btnConTip" onclick="cambTyUs()" hidden class="btn  btn-warning">Cambiar</button>
                   
                </div>
                <div class="col-md-6 text-center">

                    <?php if ($voluntario->obtener_estado() != 'rechazado') { ?>
                        <?php if ($credencial) { ?>
                            <h5>Credencial</h5>
                            <hr>
                            <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#modalCredencial">Editar credencial</button>
                            <a class="btn btn-info" target="_blank" href="<?php echo 'MiCredencial-vol.php?id=' . $idVoluntario; ?>">Ver Credencial</a>
                        <?php } else { ?>
                            <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#modalCredencial">Generar credencial</button>
                        <?php } ?>
                    <?php } ?>
                </div>
            </div>
            <hr>
            <div class="col-md-12 text-center">
                <a href="voluntarios.php" class="btn btn-secondary">Volver</a>
                <a href="verVoluntario.php?id=<?php echo $idVoluntario + 1; ?>" class="btn btn-secondary">Siguiente</a>
            </div>
        </div>
    </div>
</div>

<?php

// Funciones auxiliares
function obtenerTipoUsuario($typeUser)
{
    switch ($typeUser) {
        case 'Voluntario':
            return 'Voluntario';
        case 'Nacional':
            return 'Nacional';
        case 'Coordinador':
            return 'Coordinador regional';
        case 'EqReg':
            return 'Equipo regional';
        default:
            return 'Desconocido';
    }
}

function generarDetalle($detalles)
{
    foreach ($detalles as $label => $valor) {
        echo '<p><strong>' . htmlspecialchars($label) . ':</strong> ' . htmlspecialchars($valor) . '</p>';
        if ($label === 'Profesión') {
?>
            <button type="button" class="btn btn-xs btn-primary" data-bs-toggle="modal" data-bs-target="#modalProfesion">
                Cambiar Profesión
            </button>

            <?php
        }
        switch ($label) {
            case 'Teléfono':
            ?>
                <input type="text" id="Telefono" hidden value="<?php echo htmlspecialchars($valor) ?>">
                <button id="btnCambiarTelefono" type="button" class="btn btn-primary" onclick="mostrarModal('Telefono')">Cambiar Teléfono</button>
            <?php
                break;
            case 'Correo':
            ?>
                <input type="text" id="Correo" hidden value="<?php echo htmlspecialchars($valor) ?>">
                <button id="btnCambiarCorreo" type="button" class="btn btn-primary" onclick="mostrarModal('Correo')">Cambiar Correo</button>
<?php
                break;
        }
    }
}

function generarImagen($url, $alt, $modalTarget)
{
    if (!empty($url)) {
        echo '<img src="' . htmlspecialchars($url) . '" alt="' . htmlspecialchars($alt) . '" class="rounded-circle img-thumbnail" style="max-width: 150px;">';
    } else {
        echo '<p>No hay una ' . htmlspecialchars($alt) . ' cargada.</p>';
    }
    echo '<br><button type="button" class="btn btn-info" data-toggle="modal" data-target="' . htmlspecialchars($modalTarget) . '">Subir Nueva Foto</button>';
}

function generarDocumento($label, $url, $modalTarget)
{
    echo '<p><strong>' . htmlspecialchars($label) . ':</strong><br>';

    // Verificar que la URL no está vacía después de limpiar espacios
    $urlLimpia = trim($url);

    // Extraer el nombre del archivo y verificar que no esté vacío
    $nombreArchivo = basename($urlLimpia);

    if (!empty($urlLimpia) && $nombreArchivo !== '' && strpos($nombreArchivo, '.') !== 0) {
        echo '<a href="' . htmlspecialchars($urlLimpia) . '" class="btn btn-link" target="_blank">Ver Documento</a>';
    } else {
        echo '<span class="text-danger">No disponible</span>';
    }

    echo ' <button type="button" class="btn btn-info" data-toggle="modal" data-target="' . htmlspecialchars($modalTarget) . '">Subir Nuevo Documento</button></p>';
}


include_once('../plantillas/DecFin.inc.php');
?>