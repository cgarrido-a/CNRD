<?php
// Incluir conexión a la base de datos
require_once 'app/conex.inc.php';

// Verificar si la sesión está iniciada
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

// Obtener el ID del usuario desde el formulario
$userId = $_POST['id'] ?? null;

if (!$userId || $userId != $_SESSION['user_id']) {
    echo "Error: Usuario no autorizado.";
    exit();
}

// Verificar si se trata de la actualización de la foto de perfil
if (!empty($_FILES['newFotoPerfil']['name'])) {
    $foto = $_FILES['newFotoPerfil'];

    // Validar archivo (tipo y tamaño)
    $permitidos = ['image/jpeg', 'image/png', 'image/gif'];
    $maxSize = 2 * 1024 * 1024; // 2 MB

    if (!in_array($foto['type'], $permitidos)) {
        echo "Error: El archivo debe ser una imagen (JPG, PNG o GIF).";
        exit();
    }

    if ($foto['size'] > $maxSize) {
        echo "Error: El archivo excede el tamaño permitido (2 MB).";
        exit();
    }

    // Definir ruta de almacenamiento
    $directorioSubida = 'uploads/';
    $nombreArchivo = uniqid() . '_' . basename($foto['name']);
    $rutaDestino = $directorioSubida . $nombreArchivo;

    if (!move_uploaded_file($foto['tmp_name'], $rutaDestino)) {
        echo "Error: No se pudo subir el archivo.";
        exit();
    }

    // Actualizar ruta en la base de datos
    $stmt = $conn->prepare("UPDATE usuarios SET foto_perfil = ? WHERE id_usuario = ?");
    $stmt->bind_param('si', $rutaDestino, $userId);

    if ($stmt->execute()) {
        echo "Foto de perfil actualizada correctamente.";
        header("Location: perfil.php");
    } else {
        echo "Error al actualizar la foto en la base de datos.";
    }
    $stmt->close();
    exit();
}

// Verificar si se trata del cambio de clave
if (!empty($_POST['NuevaClave'])) {
    $nuevaClave = $_POST['NuevaClave'];

    // Validar la clave (ejemplo: longitud mínima)
    if (strlen($nuevaClave) < 8) {
        echo "Error: La clave debe tener al menos 8 caracteres.";
        exit();
    }

    // Encriptar clave antes de guardarla
    $claveEncriptada = password_hash($nuevaClave, PASSWORD_DEFAULT);

    // Actualizar clave en la base de datos
    $stmt = $conn->prepare("UPDATE usuarios SET clave = ? WHERE id_usuario = ?");
    $stmt->bind_param('si', $claveEncriptada, $userId);

    if ($stmt->execute()) {
        echo "Clave actualizada correctamente.";
        header("Location: perfil.php");
    } else {
        echo "Error al actualizar la clave en la base de datos.";
    }
    $stmt->close();
    exit();
}

// Si no se recibió ninguna acción válida
echo "No se recibió ninguna acción válida.";
exit();
?>
