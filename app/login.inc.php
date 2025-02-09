<?php
session_start(); // Iniciamos la sesión al principio

$response = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validar que los datos estén presentes
    if (empty($_POST['usuario']) || empty($_POST['clave']) || empty($_POST['tipo'])) {
        $response['error'] = 'Todos los campos son obligatorios';
    } else {
        // Limpiar y validar los datos recibidos
        $usuario = htmlspecialchars(trim($_POST['usuario']), ENT_QUOTES, 'UTF-8');
        $contrasena = trim($_POST['clave']); // No sanitizamos para no afectar el hash
        $tipo = htmlspecialchars(trim($_POST['tipo']), ENT_QUOTES, 'UTF-8');

        try {
            include_once('conex.inc.php'); // Conexión a la base de datos
            include_once('func.inc.php'); // Funciones adicionales
            
            // Llamar al método de login
            $resultado = Usuario::login($usuario, $contrasena, $tipo);

            if (isset($resultado['error'])) {
                $response['error'] = $resultado['error'];
            } else {
                // ✅ Guardar SIEMPRE el ID del usuario en la sesión
                $_SESSION['user_id'] = $_SESSION['UserLog']->obtener_id(); // Guardamos el ID
                $_SESSION['TypeUser'] =$_SESSION['UserLog']->obtener_TypeUser(); // Guardamos el tipo de usuario
                $_SESSION['user'] = $resultado; // Guardamos todos los datos del usuario

                $response['success'] = true;
                $response['user'] = $resultado;
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
