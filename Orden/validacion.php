<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_DEPRECATED);

if (!isset($_GET['validador'])) {
    header('Location: index.php');
    exit();
} else {
    require_once('app/func.inc.php');

    $usuario_cedencial = Usuario::get_cedver($_GET['validador']);
    $getId = $usuario_cedencial['id_voluntario'];

    if ($getId) {
        $id = $usuario_cedencial['id_voluntario'];
        if (strpos($getId, 'c-') === 0) {  
            $idConPrefijo = $getId;
            $idSinPrefijo = str_replace('c-', '', $getId);
            $usuario2 = Usuario::obtenerUsuariosId($idSinPrefijo);
            $usuario = Usuario::get_cedusuario($idConPrefijo);
            $fotoperfil = $usuario2['foto_perfil'];
        } else { 
            $id = $getId;
            $usuario2 = Usuario::obtenerVoluntarioPorId($id);  
            $usuario = Usuario::get_cedusuario($id);  
            $fotoperfil = $usuario2['Fotoperfil'];
        }
    }

}


if ($usuario) {

    if ($usuario2['estado'] === 'habilitado') {
        include('vistas/Credencial/Vista1.php');
    } else {
        include('vistas/Credencial/Vista2.php');
    }
} else {
    include('vistas/Credencial/Vista3.php');
}
