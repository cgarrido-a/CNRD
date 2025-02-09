<?php
class Registroo
{
    public static function guardarVoluntario(
        $nombre,
        $rut,
        $telefono,
        $correo, 
        $contrasena, 
        $profesion, 
        $region, 
        $comuna, 
        $experienciaVoluntario, 
        $experienciaOtraEmergencia, 
        $recursosPropios, 
        $hobbys, 
        $tipo_aliemntacion,
        $enfer_cronicas,
        $grupo_sanguineo,
        $areaDesempeno,
        $experienciaEmergencias,
        $experienciaAnimales, 
        $files
    )
    {
        $pdo = Database::connect();

        try {
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Verificar si el RUT o correo ya existen
            $sqlVerificar = "SELECT id FROM voluntarios WHERE rut = :rut OR correo = :correo";
            $stmtVerificar = $pdo->prepare($sqlVerificar);
            $stmtVerificar->bindParam(':rut', $rut);
            $stmtVerificar->bindParam(':correo', $correo);
            $stmtVerificar->execute();

            if ($stmtVerificar->rowCount() > 0) {
                // En lugar de echo, usamos return para que el valor pueda ser capturado
                return "El RUT o correo ya están registrados.";
            }

            // Insertar los datos del voluntario
            $sqlInsertar = "INSERT INTO voluntarios 
                        (nombre,
                        rut,
                        telefono, 
                        correo, 
                        clave, 
                        profesion, 
                        id_region, 
                        comuna, 
                        experiencia_voluntario, 
                        experiencia_otra_emergencia, 
                        recursos_propios,
                        hobbys, 
                        fecha_registro, 
                        tipo_alimentacion, 
                        enfermedades_cronicas, 
                        area_desempeno, 
                        experiencia_emergencias, 
                        experiencia_animales, 
                        grupo_sanguineo) 
                        VALUES 
                        (
                        :nombre,
                        :rut, 
                        :telefono, 
                        :correo, 
                        :contrasena, 
                        :profesion, 
                        :region, 
                        :comuna, 
                        :experiencia_voluntario, 
                        :experiencia_otra_emergencia, 
                        :recursos_propios, 
                        :hobbys, 
                        NOW(), 
                        :tipo_alimentacion, 
                        :enfermedades_cronicas, 
                        :areaDesempeno, 
                        :experiencia_emergencias, 
                        :experiencia_animales,
                        :grupo_sanguineo)";
            $stmtInsertar = $pdo->prepare($sqlInsertar);
            $stmtInsertar->bindValue(':nombre', $nombre);
            $stmtInsertar->bindValue(':rut', $rut);  // Usamos bindValue en lugar de bindParam
            $stmtInsertar->bindValue(':telefono', $telefono);
            $stmtInsertar->bindValue(':correo', $correo);
            $contrasenaHashed = password_hash($contrasena, PASSWORD_BCRYPT);
            $stmtInsertar->bindValue(':contrasena', $contrasenaHashed);
            $stmtInsertar->bindValue(':profesion', $profesion);
            $stmtInsertar->bindValue(':region', $region);
            $stmtInsertar->bindValue(':comuna', $comuna);
            $stmtInsertar->bindValue(':experiencia_voluntario', $experienciaVoluntario);
            $stmtInsertar->bindValue(':experiencia_otra_emergencia', $experienciaOtraEmergencia);
            $stmtInsertar->bindValue(':recursos_propios', $recursosPropios);
            $stmtInsertar->bindValue(':hobbys', $hobbys);
            $stmtInsertar->bindValue(':tipo_alimentacion', $tipo_aliemntacion);
            $stmtInsertar->bindValue(':enfermedades_cronicas', $enfer_cronicas);
            $stmtInsertar->bindValue(':grupo_sanguineo', $grupo_sanguineo);
            $stmtInsertar->bindValue(':areaDesempeno', $areaDesempeno);
            $stmtInsertar->bindValue(':experiencia_emergencias', $experienciaEmergencias);
            $stmtInsertar->bindValue(':experiencia_animales', $experienciaAnimales);

            if ($stmtInsertar->execute()) {
                $voluntarioId = $pdo->lastInsertId();

                // Crear carpeta para los documentos del voluntario
                $carpeta = "uploads/$voluntarioId/";
                if (!file_exists($carpeta)) {
                    mkdir($carpeta, 0777, true);
                }

                // Subir los documentos y almacenar rutas
                $archivosSubidos = [];
                foreach ($files as $campo => $archivo) {
                    if ($archivo['error'] === UPLOAD_ERR_OK) {
                        $nombreArchivo = basename($archivo['name']);
                        $rutaDestino = $carpeta . $nombreArchivo;

                        if (move_uploaded_file($archivo['tmp_name'], $rutaDestino)) {
                            $archivosSubidos[$campo] = $rutaDestino;
                        } else {
                            // Cambiado a return en lugar de echo
                            return "Error al subir el archivo: $nombreArchivo";
                        }
                    }
                }

                // Actualizar las rutas de los archivos en la base de datos
                $sqlActualizar = "UPDATE voluntarios 
                SET certificado_titulo = :certificadoTitulo, Fotoperfil = :fotoPerfil, certificadoAntecedentes = :certificadoAntecedentes 
                WHERE id = :id";
                $stmtActualizar = $pdo->prepare($sqlActualizar);
                $stmtActualizar->bindValue(':certificadoTitulo', $archivosSubidos['certificadoTitulo'] ?? null);
                $stmtActualizar->bindValue(':fotoPerfil', $archivosSubidos['fotoPerfil'] ?? null);
                $stmtActualizar->bindValue(':certificadoAntecedentes', $archivosSubidos['certificadoAntecedentes'] ?? null);
                $stmtActualizar->bindValue(':id', $voluntarioId);
                $stmtActualizar->execute();

                // Cambiado a return en lugar de echo
                return "Voluntario registrado con éxito.";
            } else {
                // Cambiado a return en lugar de echo
                return "Error al registrar al voluntario.";
            }
        } catch (PDOException $e) {
            // Manejo adecuado de error
            return "Error en la base de datos: " . $e->getMessage();
        }
    }
}
?>
