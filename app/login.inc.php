<?php
$response = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validar que los datos estén presentes
    if (empty($_POST['usuario']) || empty($_POST['clave']) || empty($_POST['tipo'])) {
        $response['error'] = 'Todos los campos son obligatorios';
    } else {
        // Limpiar y validar los datos recibidos
        $usuario = htmlspecialchars(trim($_POST['usuario']), ENT_QUOTES, 'UTF-8');
        $contrasena = trim($_POST['clave']); // Contraseña no se sanitiza para mantener la integridad del hash
        $tipo = htmlspecialchars(trim($_POST['tipo']), ENT_QUOTES, 'UTF-8');

        try {
            include_once('conex.inc.php'); // Conexión a la base de datos
            include_once('func.inc.php'); // Funciones adicionales
            
            // Simulación del método de login
            
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $resultado = Usuario::login($usuario, $contrasena, $tipo);

            if (isset($resultado['error'])) {
                $response['error'] = $resultado['error'];
            } else {
                // Sesión exitosa: guardar datos de sesión y enviar respuesta
                $_SESSION['user'] = $resultado; // Suponiendo que `$resultado` tiene los datos del usuario
                $response['success'] = true;
                $response['user'] = $resultado; // Puedes devolver datos relevantes del usuario
            }
        } catch (Exception $e) {
            $response['error'] = 'Error al procesar la solicitud: ' . $e->getMessage();
        }
    }
} else {
    $response['error'] = 'Método no permitido';
}

echo json_encode($response);
exit();

?>
