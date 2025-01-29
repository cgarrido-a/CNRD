<?php
if (isset($_POST['hobbys'])) {
    include_once('app/registrovol.inc.php');
  
    // Verifica las respuestas usando if-else
    if (strpos($resultadosins, 'Voluntario registrado con éxito.') !== false) {
        include_once('plantillareg/avis1.html');
    } elseif (strpos($resultadosins, 'Error al subir el archivo:') !== false) {
        include_once('plantillareg/aviso2.html');
    } elseif (strpos($resultadosins, 'El RUT o correo ya están registrados.') !== false) {
        include_once('plantillareg/aviso4.html');
    } else {
        // Error desconocido o no esperado
        echo "Error desconocido o no esperado: " . $resultadosins;
    }
} else {
    include_once('plantillareg/form1.html');
}
