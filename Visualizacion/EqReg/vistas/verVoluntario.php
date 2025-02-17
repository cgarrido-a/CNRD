<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_DEPRECATED);
//HAY QUE CORREGIR CON LOS CAMBIOS EN LA BASE DE DATOS

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

if (!$voluntario) {
    mostrarError("No se encontraron datos para el voluntario especificado.");
    exit;
}

$institucion = 'CNRD ' . $voluntario->obtener_id_region();

// Función para mostrar mensajes de error
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
                    <?php generarDetalle([
                        'Nombre Completo' => $voluntario->obtener_nombre(),
                        'RUT' => $voluntario->obtener_rut(),
                        'Teléfono' => $voluntario->obtener_telefono(),
                        'Correo' => $voluntario->obtener_correo()
                    ]); ?>
                    <div class="form-group mb-3">
                        <?php

                        ?>
                        <button type="button" id="btnCambiarClave" class="btn btn-warning">Cambiar Clave</button>

                    </div>
                </div>
                <div class="col-md-6">
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
                    <h5 class="text-muted">Estado y acciones</h5>
                    <hr>
                    <h5 class="text-muted">Estado: <strong><?php echo $voluntario->obtener_estado(); ?></strong></h5>

                </div>
                <div class="col-md-6 text-center">

                    <?php if ($voluntario->obtener_estado() != 'rechazado') { ?>
                        <?php if ($credencial) { ?>
                            <h5>Credencial</h5>
                            <hr>
                            <a class="btn btn-info" target="_blank" href="<?php echo 'MiCredencial-vol.php?id=' . $idVoluntario; ?>">Ver Credencial</a>
                    <?php }
                    } ?>
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
function generarDetalle($detalles)
{
    foreach ($detalles as $label => $valor) {
        echo '<p><strong>' . htmlspecialchars($label) . ':</strong> ' . htmlspecialchars($valor) . '</p>';
        if ($label === 'Profesión') {
?>
    

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

}


include_once('../plantillas/DecFin.inc.php');
?>