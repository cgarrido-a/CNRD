<?php
// Incluye las clases necesarias
include_once('conex.inc.php');
include_once('func.inc.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Captura de datos enviados por el formulario
    $datos = [
        'id' => $_POST['id'],
        'nombre' => $_POST['nombre'],
        'rut' => $_POST['rut'],
        'telefono' => $_POST['telefono'],
        'correo' => $_POST['correo'],
        'contrasena' => $_POST['contrasena'] ?? null, // Si hay campo de contraseña
        'profesion' => $_POST['profesion'],
        'region' => $_POST['region'],
        'comuna' => $_POST['comuna'],
        'experiencia_voluntario' => $_POST['experiencia_voluntario'] ?? null,
        'experiencia_otra_emergencia' => $_POST['experiencia_otra_emergencia'] ?? null,
        'recursos_propios' => $_POST['recursos_propios'] ?? null,
        'hobbys' => $_POST['hobbys'] ?? null,
        'tipo_alimentacion' => $_POST['tipo_alimentacion'] ?? null,
        'grupo_sanguineo' => $_POST['grupo_sanguineo'] ?? null,
        'enfermedades_cronicas' => $_POST['enfermedades_cronicas'] ?? null,
        'actividades' => $_POST['actividades'] ?? null,
        'area_desempeno' => $_POST['area_desempeno'] ?? null,
        'experiencia_emergencias' => $_POST['experiencia_emergencias'] ?? null,
        'experiencia_animales' => $_POST['experiencia_animales'] ?? null,
        'experiencia_desastres' => $_POST['experiencia_desastres'] ?? null,
        'estado' => $_POST['estado']
    ];



    try {
     
        


        // Llamada a la función para actualizar los datos del voluntario
        $resultado = Usuario::actualizarVoluntario($datos);

        if ($resultado) {
            // Redireccionar con mensaje de éxito y el id del voluntario
            header('Location: ../editarVoluntario.php?id=' . urlencode($datos['id']));
            exit;
        } else {
            // Si no se actualizó, mostrar mensaje de error
            throw new Exception("No se pudo actualizar el voluntario. Por favor, inténtalo nuevamente.");
        }
    } catch (Exception $e) {
        // Mostrar mensaje de error en pantalla con un hipervínculo para volver
        echo "<div style='background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; border: 1px solid #f5c6cb; margin: 20px auto; max-width: 600px;'>
                <strong>Error:</strong> {$e->getMessage()}<br>
                <a href='../editarVoluntario.php?id=" . urlencode($datos['id']) . "' style='color: #004085; text-decoration: underline;'>Volver a editar</a>
              </div>";
    }
} else {
    // Si no es una solicitud POST, redirige al listado de voluntarios
    header('Location: ../voluntarios.php');
    exit;
}
