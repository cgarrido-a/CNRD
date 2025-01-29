<?php

include_once('conex.inc.php');
include_once('Funesp.inc.php'); // Asegúrate de tener una conexión PDO aquí





if (isset($_POST)) {
    if ($_POST['profesion'] === 'Otra') {
        $profesion = $_POST['otraProfesion'];
        $areaDesempeno = null;
        $experienciaEmergencias = null;
        $experienciaAnimales = null;
    } else {
        $profesion = $_POST['profesion'];
        $areaDesempeno = $_POST['areaDesempeno'];
        $experienciaEmergencias = $_POST['experienciaEmergencias'];
        $experienciaAnimales = $_POST['experienciaAnimales'];
    }
    $resultadosins = Registroo::guardarVoluntario(
        $_POST['nombre'],
        $_POST['rut'],
        $_POST['telefono'],
        $_POST['correo'],
        $_POST['contrasena'],
        $profesion,
        $_POST['region'],
        $_POST['comuna'],
        $_POST['experienciaVoluntario'],
        $_POST['experienciaOtraEmergencia'],
        $_POST['recursosPropios'],
        $_POST['hobbys'],
        $_POST['tipoAlimentacion'],
        $_POST['enf_cronicas'],
        $_POST['grupoSanguineo'],
        $areaDesempeno,
        $experienciaEmergencias,
        $experienciaAnimales,
        $_FILES
    );
}
