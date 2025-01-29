
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vista Voluntario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="text-center mb-4">
            <img src="img/cnrd.png" alt="Logo CNRD" class="img-fluid" style="max-width: 200px;">
        </div>
        <div class="card mx-auto shadow-sm" style="max-width: 500px;">
            <div class="card-body text-center">
                <img width="150" height="150" src="<?php echo htmlspecialchars($fotoperfil); ?>" alt="Foto de perfil" class="img-fluid">
                <h4 class="card-title"><?php echo htmlspecialchars($usuario['nombre']); ?></h4>
                <p class="card-text text-muted"><?php echo htmlspecialchars($usuario['cargo']); ?></p>
                <p class="card-text"><?php echo htmlspecialchars($usuario['institucion']); ?></p>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
