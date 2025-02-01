<?php
include_once('app/conex.inc.php');
include_once('app/func.inc.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoger datos desde la solicitud POST
    $datos = [
        'nombreClinica' => $_POST['nombreClinica'] ?? '',
        'nombreRepresentante' => $_POST['nombreRepresentante'] ?? '',
        'direccion' => $_POST['direccion'] ?? '',
        'region' => $_POST['region'] ?? '',
        'comuna' => $_POST['comuna'] ?? '',
        'telefono' => $_POST['telefono'] ?? '',
        'correo' => $_POST['correo'] ?? ''
    ];

    // Verificar si se ha enviado el archivo "acuerdoClinica"
    if (isset($_FILES['acuerdoClinica']) && $_FILES['acuerdoClinica']['error'] == 0) {
        $acuerdoClinica = $_FILES['acuerdoClinica'];

        // Obtener información sobre el archivo
        $nombreArchivo = $acuerdoClinica['name'];
        $tipoArchivo = $acuerdoClinica['type'];
        $tmpArchivo = $acuerdoClinica['tmp_name'];
        $tamanoArchivo = $acuerdoClinica['size'];

        // Definir un directorio para guardar el archivo
        $directorioDestino = 'uploads/acuerdos/';
        if (!is_dir($directorioDestino)) {
            mkdir($directorioDestino, 0777, true);
        }

       
            $rutaDestino = $directorioDestino . basename($nombreArchivo);

            // Mover el archivo al directorio de destino
            if (move_uploaded_file($tmpArchivo, $rutaDestino)) {
                // Agregar la ruta del archivo al array de datos
                $datos['acuerdoClinica'] = $rutaDestino;

                // Llamar a la función estática para guardar la clínica
                $resultado = Clinicas::guardar($datos);

                // Responder en formato JSON
                echo json_encode($resultado);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al subir el archivo.']);
            }
       
    } else {
        echo json_encode(['success' => false, 'message' => 'No se ha enviado el archivo o hubo un error en la carga.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
}
?>
