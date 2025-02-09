<?php
// Iniciar sesión si es necesario
session_start();

// Aquí puedes verificar permisos o autenticación si es necesario

// Mostrar la vista
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>En Mantenimiento</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .container {
            text-align: center;
            background-color: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #e74c3c;
            font-size: 36px;
            margin-bottom: 20px;
        }
        p {
            font-size: 18px;
            color: #555;
        }
        .footer {
            margin-top: 30px;
            font-size: 14px;
            color: #888;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Estamos en Mantenimiento</h1>
        <p>Por favor, vuelve más tarde. Estamos trabajando para mejorar nuestro servicio.</p>
        <div class="footer">
            <p>&copy; 2025 </p>
        </div>
    </div>

</body>
</html>
