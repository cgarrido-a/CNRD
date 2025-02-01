<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_type'])) {
    header('Location: login.html');
    exit();
}
include_once('app/func.inc.php');
$user_type = $_SESSION['user_type'];
$servidor = 'http://cnrd-intranet.free.nf/'
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CNRD de COLMEVET</title>
    <!-- Bootstrap CSS -->
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="app/styles.css">
    <script src="js/qrCode.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body data-user-type="<?php echo $_SESSION['user_type']; ?>" data-user-id="<?php echo $_SESSION['user_id']; ?>">

    <!-- Navigation Bar -->
            <nav class="navbar navbar-expand navbar-light bg-light">
                <a class="navbar-brand" href="#">CNRD - COLMEVET</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <?php if ($user_type == 'voluntario'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="certificados.php">Mis certificados</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo 'MiCredencial.php?id=' . $_SESSION['user_id'] ?>">Mi Credencial</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="asistencia.php">Asistencia</a>
                            </li>
                        <?php endif; ?>
        
                        <?php if ($user_type == 'Coordinacion'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="voluntarios.php">Voluntarios</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="clinicas.php">Clincias</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="usuario.php">Usuarios</a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Asistencia
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="despvol.php">Voluntarios desplegados</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item" href="hisasisvol.php">Historial asistencia</a></li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo 'MiCredencial.php?id=c-' . $_SESSION['user_id'] ?>">Mi Credencial</a>
                            </li>
                        <?php endif; ?>
        
                        <?php if ($user_type == 'clinica'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="clinicas.php">Clínicas</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="miperfil.php">Mi Perfil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="cerrar_sesion.php">Cerrar Sesión</a>
                        </li>
                    </ul>
                </div>
            </nav>