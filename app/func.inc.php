<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once('conex.inc.php');
class Clinicas
{
    public static function generarCadena($idClinica)
    {
        if (!is_numeric($idClinica) || $idClinica <= 0) {
            throw new InvalidArgumentException("El ID de la clínica no es válido.");
        }
        $prefijo = bin2hex(random_bytes(5));
        $sufijo = bin2hex(random_bytes(5));
        $cadena = $prefijo . "|" . base64_encode($idClinica) . "|" . $sufijo;
        return $cadena;
    }

    // Ejemplo de cómo manejar la carga de archivos
    public static function manejarArchivo($archivo, $directorio)
    {
        if ($archivo['error'] === UPLOAD_ERR_OK) {
            $nombreArchivo = basename($archivo['name']);
            $rutaDestino = $directorio . $nombreArchivo;

            if (move_uploaded_file($archivo['tmp_name'], $rutaDestino)) {
                return $rutaDestino; // Retorna la ruta del archivo subido
            } else {
                throw new Exception("No se pudo mover el archivo.");
            }
        } else {
            throw new Exception("Error al subir el archivo.");
        }
    }

    public static function actualizarHabilitacion($clinicaId, $habilitacion)
    {
        try {
            $pdo = Database::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Definir la consulta para actualizar el estado de habilitación
            $sql = "UPDATE clinicas SET habilitacion = :habilitacion WHERE id = :clinicaId";
            $stmt = $pdo->prepare($sql);

            // Ejecutar la consulta
            $stmt->execute([
                ':habilitacion' => $habilitacion,
                ':clinicaId' => $clinicaId
            ]);

            return ['success' => true, 'message' => 'Estado de habilitación actualizado.'];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Error en la base de datos: ' . $e->getMessage()];
        } finally {
            $pdo = null;
        }
    }

    public static function get_clinica_by_id($id)
    {
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            // Consulta para buscar la clínica por id
            $sql = "SELECT * FROM clinicas WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            // Ejecutar la consulta
            $stmt->execute();

            // Obtener el resultado
            $clinica = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($clinica) {
                // Retornar los datos de la clínica en formato JSON
                return json_encode($clinica);
            } else {
                // Retornar un mensaje de error si no se encuentra la clínica
                return json_encode(['error' => 'No se encontró la clínica con el ID especificado.']);
            }
        } catch (PDOException $e) {
            // Retornar un mensaje de error en caso de excepción
            return json_encode(['error' => 'Error en la base de datos: ' . $e->getMessage()]);
        } finally {
            // Cerrar la conexión
            $pdo = null;
        }
    }

    public static function guardar($datos)
    {


        // Validar campos requeridos
        if (
            empty($datos['nombreClinica']) || empty($datos['nombreRepresentante']) || empty($datos['direccion']) ||
            empty($datos['region']) || empty($datos['comuna']) || empty($datos['telefono']) || empty($datos['correo'])
        ) {
            return ['success' => false, 'message' => 'Todos los campos son obligatorios.'];
        }

        // Validar formato del correo
        if (!filter_var($datos['correo'], FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'El correo electrónico no es válido.'];
        }

        // Definir clave predeterminada
        $clave = 'qwety1234567890';

        try {
            $pdo = Database::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $contrasenaHashed = password_hash($clave, PASSWORD_BCRYPT);
            $query = "INSERT INTO clinicas (nombre, nombre_representante, direccion, region, comuna, telefono, correo, clave, acuerdo_clinica)
                      VALUES (:nombre_clinica, :nombre_representante, :direccion, :region, :comuna, :telefono, :correo, :clave, :acuerdo_clinica)";
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                ':nombre_clinica' => $datos['nombreClinica'],
                ':nombre_representante' => $datos['nombreRepresentante'],
                ':direccion' => $datos['direccion'],
                ':region' => $datos['region'],
                ':comuna' => $datos['comuna'],
                ':telefono' => $datos['telefono'],
                ':correo' => $datos['correo'],
                ':clave' => $contrasenaHashed,
                ':acuerdo_clinica' => $datos['acuerdoClinica'],
            ]);

            return ['success' => true, 'message' => 'Clínica guardada exitosamente.'];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Error al guardar la clínica: ' . $e->getMessage()];
        }
    }
    public static function get_clinicas()
    {
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            // Definir la consulta según el nivel del usuario
            if ($_SESSION['region'] === "Nacional") {
                $sql = "SELECT * FROM clinicas";
                $stmt = $pdo->prepare($sql);
            } else {
                $sql = "SELECT * FROM clinicas WHERE region = :region";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':region', $_SESSION['region'], PDO::PARAM_STR);
            }

            // Ejecutar la consulta
            $stmt->execute();

            // Obtener los resultados
            $clinicas = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Retornar los datos codificados en JSON
            return json_encode($clinicas);
        } catch (PDOException $e) {
            // Retornar un mensaje de error en JSON
            return json_encode(['error' => 'Error en la base de datos: ' . $e->getMessage()]);
        } catch (Exception $e) {
            // Manejo de errores genéricos
            return json_encode(['error' => $e->getMessage()]);
        } finally {
            // Cerrar la conexión
            $pdo = null;
        }
    }
}

class Usuario
{
    public static function obtenerVoluntarioPorId($id)
    {
        try {
            $pdo = Database::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "SELECT * FROM voluntarios WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id);

            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($resultado) {
                return $resultado;
            } else {
                return "";
            }
        } catch (PDOException $e) {
            print 'Error: ' . $e->getMessage();
        }
    }
    public static function obtenerVoluntariosConIngreso()
    {
        $conn =  Database::connect();
        try {
            // Consulta SQL
            $sql = "
            SELECT 
                v.nombre AS nombre_voluntario,
                v.tipo_alimentacion AS tipo_alimentacion,
                c.nombre_clinica AS nombre_clinica,
                c.region AS region_clinica,
                c.comuna AS comuna_clinica
            FROM 
                voluntarios v
            JOIN 
                RegistroEntradasSalidas r ON v.id_voluntario = r.id_voluntario
            JOIN 
                clinicas c ON r.id_clinica = c.id_clinica
            WHERE 
                r.fecha_salida IS NULL
        ";

            // Preparar la consulta
            $stmt = $conn->prepare($sql);

            // Ejecutar la consulta
            $stmt->execute();

            // Obtener los resultados como un array asociativo
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Manejo de errores
            error_log("Error en obtenerVoluntariosConIngreso: " . $e->getMessage());
            return [];
        }
    }

    public static function registrarSalida($voluntarioId, $lugarId)
    {
        $conn =  Database::connect();
        if ($conn === null) {
            return "Error de conexión a la base de datos.";
        }

        $fechaActual = date('Y-m-d H:i:s'); // Fecha y hora actual

        try {
            // Verificar si hay un registro de entrada sin salida
            $sqlCheck = "SELECT id FROM RegistroEntradasSalidas 
                         WHERE voluntario_id = :voluntario_id 
                         AND lugar_id = :lugar_id 
                         AND fecha_hora_salida IS NULL 
                         ORDER BY fecha_hora_entrada DESC LIMIT 1";
            $stmt = $conn->prepare($sqlCheck);
            $stmt->bindParam(':voluntario_id', $voluntarioId);
            $stmt->bindParam(':lugar_id', $lugarId);
            $stmt->execute();

            if ($stmt->rowCount() === 0) {
                return "No hay un registro de entrada sin salida para este voluntario.";
            }

            // Obtener el ID del registro de entrada sin salida
            $registro = $stmt->fetch(PDO::FETCH_ASSOC);
            $registroId = $registro['id'];

            // Actualizar la fecha y hora de salida
            $sqlUpdate = "UPDATE RegistroEntradasSalidas 
                          SET fecha_hora_salida = :fecha_hora_salida 
                          WHERE id = :id";
            $stmt = $conn->prepare($sqlUpdate);
            $stmt->bindParam(':fecha_hora_salida', $fechaActual);
            $stmt->bindParam(':id', $registroId);
            $stmt->execute();

            return "Salida registrada exitosamente.";
        } catch (PDOException $e) {
            return "Error al registrar la salida: " . $e->getMessage();
        } finally {
            $conn = null;
        }
    }

    public static function registrarEntrada($voluntarioId, $lugarId)
    {
        $conn = Database::connect();
        if ($conn === null) {
            return "Error de conexión a la base de datos.";
        }

        $fechaActual = date('Y-m-d H:i:s'); // Fecha y hora actual

        try {

            // Verificar si ya existe una entrada sin salida para este voluntario
            $sqlCheck = "SELECT id FROM RegistroEntradasSalidas 
                         WHERE voluntario_id = :voluntario_id 
                         AND lugar_id = :lugar_id 
                         AND fecha_hora_salida IS NULL";
            $stmt = $conn->prepare($sqlCheck);
            $stmt->bindParam(':voluntario_id', $voluntarioId);
            $stmt->bindParam(':lugar_id', $lugarId);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return "Ya existe un registro de entrada sin salida para este voluntario.";
            }

            // Insertar la nueva entrada
            $sqlInsert = "INSERT INTO RegistroEntradasSalidas (voluntario_id, lugar_id, fecha_hora_entrada) 
                          VALUES (:voluntario_id, :lugar_id, :fecha_hora_entrada)";
            $stmt = $conn->prepare($sqlInsert);
            $stmt->bindParam(':voluntario_id', $voluntarioId);
            $stmt->bindParam(':lugar_id', $lugarId);
            $stmt->bindParam(':fecha_hora_entrada', $fechaActual);
            $stmt->execute();

            return "Entrada registrada exitosamente.";
        } catch (PDOException $e) {
            return "Error al registrar la entrada: " . $e->getMessage();
        } finally {
            $conn = null;
        }
    }

        public static function actualizarVol($idVoluntario, $campo, $rutaArchivo)
        {
            $pdo = Database::connect();
            // La consulta debe utilizar los nombres de columna sin comillas simples


            try {
                switch ($campo) {
                    case 'CamEstUs':
                        $sql = "UPDATE voluntarios SET estado = :rutaArchivo WHERE id = :idVoluntario";
                        $claveEncriptada = $rutaArchivo;
                        break;
                    case 'foto':
                        $sql = "UPDATE voluntarios SET foto_perfil = :rutaArchivo WHERE id = :idVoluntario";
                        $claveEncriptada = $rutaArchivo;
                        break;
                    case 'CambClavVol':
                        $sql = "UPDATE voluntarios SET clave = :rutaArchivo WHERE id = :idVoluntario";
                        $claveEncriptada = password_hash($rutaArchivo, PASSWORD_DEFAULT);
                        break;
                    case 'Correo':
                        $sql = "UPDATE voluntarios SET correo = :rutaArchivo WHERE id = :idVoluntario";
                        $claveEncriptada = $rutaArchivo;
                        break;
                    case 'Telefono':
                        $sql = "UPDATE voluntarios SET telefono = :rutaArchivo WHERE id = :idVoluntario";
                        $claveEncriptada = $rutaArchivo;
                        break;

                    default:
                        # code...
                        break;
                }
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':rutaArchivo', $claveEncriptada);
                $stmt->bindParam(':idVoluntario', $idVoluntario);
                return $stmt->execute();  // Ejecuta la consulta y devuelve el resultado
            } catch (PDOException $e) {
                // Manejo de errores en caso de fallo
                echo "Error al actualizar: " . $e->getMessage();
                return false;
            }
        }
    public static function actualizarUsuario($idVoluntario, $campo, $rutaArchivo)
    {
        $pdo = Database::connect();
        // La consulta debe utilizar los nombres de columna sin comillas simples


        try {
            switch ($campo) {
                case 'CambProf':
                    $sql = "UPDATE voluntarios SET profesion = :rutaArchivo WHERE id = :idVoluntario";
                    $claveEncriptada = $rutaArchivo;
                    break;
                case 'CamEstUs':
                    $sql = "UPDATE usuarios SET estado = :rutaArchivo WHERE id_usuario = :idVoluntario";
                    $claveEncriptada = $rutaArchivo;
                    break;
                case 'foto':
                    $sql = "UPDATE usuarios SET foto_perfil = :rutaArchivo WHERE id_usuario = :idVoluntario";
                    $claveEncriptada = $rutaArchivo;
                    break;
                case 'CambClavUs':
                    $sql = "UPDATE usuarios SET clave = :rutaArchivo WHERE id_usuario = :idVoluntario";
                    $claveEncriptada = password_hash($rutaArchivo, PASSWORD_DEFAULT);
                    break;
                case 'Correo':
                    $sql = "UPDATE usuarios SET correo = :rutaArchivo WHERE id_usuario = :idVoluntario";
                    $claveEncriptada = $rutaArchivo;
                    break;
                case 'Telefono':
                    $sql = "UPDATE usuarios SET telefono = :rutaArchivo WHERE id_usuario = :idVoluntario";
                    $claveEncriptada = $rutaArchivo;
                    break;
            }
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':rutaArchivo', $claveEncriptada);
            $stmt->bindParam(':idVoluntario', $idVoluntario);
            return $stmt->execute();  // Ejecuta la consulta y devuelve el resultado
        } catch (PDOException $e) {
            // Manejo de errores en caso de fallo
            echo "Error al actualizar: " . $e->getMessage();
            return false;
        }
    }
    public static function actualizarCredencial($idVoluntario, $nombre, $institucion, $cargo)
    {
        try {
            $pdo = Database::connect();
            // Preparar consulta SQL para actualizar los datos
            $sql = "UPDATE credenciales 
                SET nombre = :nombre, institucion = :institucion, cargo = :cargo 
                WHERE id_voluntario = :id_voluntario";

            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindParam(':institucion', $institucion, PDO::PARAM_STR);
            $stmt->bindParam(':cargo', $cargo, PDO::PARAM_STR);
            $stmt->bindParam(':id_voluntario', $idVoluntario, PDO::PARAM_STR);

            // Ejecutar consulta
            if ($stmt->execute()) {
                return "correcto";
            } else {
                return "incorrecto";
            }
        } catch (PDOException $e) {
            print 'Error: ' . $e->getMessage();
        }
    }
    public static function get_cedver($id)
    {
        try {
            $pdo = Database::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "SELECT * FROM credenciales WHERE codigo_verificacion = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id);

            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($resultado) {
                return $resultado;
            } else {
                return "";
            }
        } catch (PDOException $e) {
            print 'Error: ' . $e->getMessage();
        }
    }
    public static function get_cedusuario($id)
    {
        try {
            $pdo = Database::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "SELECT * FROM credenciales WHERE id_voluntario = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id);

            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($resultado) {
                return $resultado;
            } else {
                return "";
            }
        } catch (PDOException $e) {
            print 'Error: ' . $e->getMessage();
        }
    }
    public static function insertarCredencial($idVoluntario, $nombre, $institucion, $cargo)
    {
        $pdo = Database::connect();

        // Función para generar un código de verificación único
        function generarCodigoVerificacion($longitud = 10)
        {
            return substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, $longitud);
        }

        // Generar un código único y comprobar existencia en la base de datos
        do {
            $codigoVerificacion = generarCodigoVerificacion();
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM credenciales WHERE codigo_verificacion = ?");
            $stmt->execute([$codigoVerificacion]);
            $existe = $stmt->fetchColumn() > 0;
        } while ($existe);

        // Preparar consulta para insertar los datos
        $sql = "INSERT INTO credenciales (id_voluntario, nombre, institucion, cargo, codigo_verificacion) 
            VALUES (:id_voluntario, :nombre, :institucion, :cargo, :codigo_verificacion)";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_voluntario', $idVoluntario, PDO::PARAM_STR); // Cambiar a STR
        $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->bindParam(':institucion', $institucion, PDO::PARAM_STR);
        $stmt->bindParam(':cargo', $cargo, PDO::PARAM_STR);
        $stmt->bindParam(':codigo_verificacion', $codigoVerificacion, PDO::PARAM_STR);

        // Ejecutar consulta
        if ($stmt->execute()) {
            return "correcto";
        } else {
            return "incorrecto";
        }
    }

    public static function CamEstVol($id, $estado)
    {
        try {
            $pdo = Database::connect();

            // Inicia una transacción
            $pdo->beginTransaction();

            // Construir la consulta SQL
            $sql = "
                UPDATE voluntarios
                SET estado = :estado
                WHERE id = :id
            ";

            $stmt = $pdo->prepare($sql);

            // Asignar parámetros
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':estado', $estado, PDO::PARAM_STR);


            // Ejecutar la consulta
            $stmt->execute();

            // Confirmar la transacción
            $pdo->commit();

            return true;
        } catch (PDOException $e) {
            // Revertir la transacción si algo falla
            $pdo->rollBack();
            error_log("Error al actualizar el voluntario con ID {$id}: " . $e->getMessage());
            return false;
        }
    }
    public static function actualizarFotoPerfil($userId, $foto)
    {

        $conn = Database::connect();
        // Validar archivo (tipo y tamaño)
        $permitidos = ['image/jpeg', 'image/png', 'image/gif'];
        $maxSize = 2 * 1024 * 1024; // 2 MB

        if (!in_array($foto['type'], $permitidos)) {
            return "Error: El archivo debe ser una imagen (JPG, PNG o GIF).";
        }

        if ($foto['size'] > $maxSize) {
            return "Error: El archivo excede el tamaño permitido (2 MB).";
        }

        // Definir ruta de almacenamiento
        $directorioSubida = 'uploads/';
        $nombreArchivo = uniqid() . '_' . basename($foto['name']);
        $rutaDestino = $directorioSubida . $nombreArchivo;

        if (!move_uploaded_file($foto['tmp_name'], $rutaDestino)) {
            return "Error: No se pudo subir el archivo.";
        }

        // Actualizar ruta en la base de datos
        $stmt = $conn->prepare("UPDATE usuarios SET foto_perfil = ? WHERE id_usuario = ?");
        $stmt->bind_param('si', $rutaDestino, $userId);

        if ($stmt->execute()) {
            $stmt->close();
            return true; // Indica éxito
        } else {
            $stmt->close();
            return "Error al actualizar la foto en la base de datos.";
        }
    }

    public static function actualizarClave($userId, $nuevaClave)
    {
        $conn = Database::connect();

        // Validar la clave (ejemplo: longitud mínima)
        if (strlen($nuevaClave) < 8) {
            return "Error: La clave debe tener al menos 8 caracteres.";
        }

        // Encriptar clave antes de guardarla
        $claveEncriptada = password_hash($nuevaClave, PASSWORD_DEFAULT);

        // Actualizar clave en la base de datos
        $stmt = $conn->prepare("UPDATE usuarios SET clave = ? WHERE id_usuario = ?");
        $stmt->bind_param('si', $claveEncriptada, $userId);

        if ($stmt->execute()) {
            $stmt->close();
            return true; // Indica éxito
        } else {
            $stmt->close();
            return "Error al actualizar la clave en la base de datos.";
        }
    }
    public static function actualizarArchivo($idVoluntario, $campo, $rutaArchivo)
    {
        $pdo = Database::connect();
        // La consulta debe utilizar los nombres de columna sin comillas simples
        $sql = "UPDATE voluntarios SET $campo = :rutaArchivo WHERE id = :idVoluntario";

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':rutaArchivo', $rutaArchivo);
            $stmt->bindParam(':idVoluntario', $idVoluntario);
            return $stmt->execute();  // Ejecuta la consulta y devuelve el resultado
        } catch (PDOException $e) {
            // Manejo de errores en caso de fallo
            echo "Error al actualizar el archivo: " . $e->getMessage();
            return false;
        }
    }

    public static function actualizarVoluntario($datos)
    {
        try {
            $pdo = Database::connect();

            // Inicia una transacción
            $pdo->beginTransaction();

            $id = $datos['id']; // ID del voluntario

            // Crear carpeta basada en el ID si no existe
            $uploadDir = 'uploads/' . $id . '/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true); // Crear la carpeta con permisos 777
            }



            // Función para eliminar archivos
            function eliminarArchivo($archivo)
            {
                if (file_exists($archivo)) {
                    unlink($archivo);
                }
            }

            // Construir la consulta SQL
            $sql = "
                UPDATE voluntarios
                SET
                    nombre = :nombre,
                    rut = :rut,
                    telefono = :telefono,
                    correo = :correo,
                    profesion = :profesion,
                    region = :region,
                    comuna = :comuna,
                    experiencia_voluntario = :experiencia_voluntario,
                    experiencia_otra_emergencia = :experiencia_otra_emergencia,
                    recursos_propios = :recursos_propios,
                    hobbys = :hobbys,
                    tipo_alimentacion = :tipo_alimentacion,
                    grupo_sanguineo = :grupo_sanguineo,
                    enfermedades_cronicas = :enfermedades_cronicas,
                    actividades = :actividades,
                    area_desempeno = :area_desempeno,
                    experiencia_emergencias = :experiencia_emergencias,
                    experiencia_animales = :experiencia_animales,
                    experiencia_desastres = :experiencia_desastres,
                    estado = :estado
                WHERE id = :id
            ";

            // Si la contraseña no es parte del formulario, no la incluimos
            if (!empty($datos['contrasena'])) {
                $sql = str_replace("contrasena = :contrasena,", "", $sql);  // Eliminar el campo de la consulta
            }

            $stmt = $pdo->prepare($sql);

            // Asignar parámetros
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':nombre', $datos['nombre'], PDO::PARAM_STR);
            $stmt->bindParam(':rut', $datos['rut'], PDO::PARAM_STR);
            $stmt->bindParam(':telefono', $datos['telefono'], PDO::PARAM_STR);
            $stmt->bindParam(':correo', $datos['correo'], PDO::PARAM_STR);
            $stmt->bindParam(':profesion', $datos['profesion'], PDO::PARAM_STR);
            $stmt->bindParam(':region', $datos['region'], PDO::PARAM_STR);
            $stmt->bindParam(':comuna', $datos['comuna'], PDO::PARAM_STR);
            $stmt->bindParam(':experiencia_voluntario', $datos['experiencia_voluntario'], PDO::PARAM_STR);
            $stmt->bindParam(':experiencia_otra_emergencia', $datos['experiencia_otra_emergencia'], PDO::PARAM_STR);
            $stmt->bindParam(':recursos_propios', $datos['recursos_propios'], PDO::PARAM_STR);
            $stmt->bindParam(':hobbys', $datos['hobbys'], PDO::PARAM_STR);
            $stmt->bindParam(':tipo_alimentacion', $datos['tipo_alimentacion'], PDO::PARAM_STR);
            $stmt->bindParam(':grupo_sanguineo', $datos['grupo_sanguineo'], PDO::PARAM_STR);
            $stmt->bindParam(':enfermedades_cronicas', $datos['enfermedades_cronicas'], PDO::PARAM_STR);
            $stmt->bindParam(':actividades', $datos['actividades'], PDO::PARAM_STR);
            $stmt->bindParam(':area_desempeno', $datos['area_desempeno'], PDO::PARAM_STR);
            $stmt->bindParam(':experiencia_emergencias', $datos['experiencia_emergencias'], PDO::PARAM_STR);
            $stmt->bindParam(':experiencia_animales', $datos['experiencia_animales'], PDO::PARAM_STR);
            $stmt->bindParam(':experiencia_desastres', $datos['experiencia_desastres'], PDO::PARAM_STR);

            $stmt->bindParam(':estado', $datos['estado'], PDO::PARAM_STR);

            // Si la contraseña fue proporcionada, agregarla a la consulta
            if (!empty($datos['contrasena'])) {
                $stmt->bindParam(':contrasena', $datos['contrasena'], PDO::PARAM_STR);
            }

            // Ejecutar la consulta
            $stmt->execute();

            // Confirmar la transacción
            $pdo->commit();

            return true;
        } catch (PDOException $e) {
            // Revertir la transacción si algo falla
            $pdo->rollBack();
            error_log("Error al actualizar el voluntario con ID {$id}: " . $e->getMessage());
            return false;
        }
    }
    public static function obtenerUsuariosId($id)
    {
        // Iniciar sesión si no está iniciada
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }



        // Conexión a la base de datos
        $conexion = Database::connect();
        if (!$conexion) {
            die('Error de conexión a la base de datos');
        }

        try {
            // Preparar la consulta
            $sql = "SELECT * FROM usuarios WHERE id_usuario = :id_usuario";
            $sentencia = $conexion->prepare($sql);

            // Vincular el parámetro
            $sentencia->bindParam(':id_usuario', $id, PDO::PARAM_INT);

            // Ejecutar la consulta
            $sentencia->execute();

            // Obtener el resultado
            $resultado = $sentencia->fetch(PDO::FETCH_ASSOC);

            if (!$resultado) {
                error_log("No se encontró el usuario con ID: " . $id);
                return null; // Devuelve nulo si no encuentra resultados
            }

            return $resultado;
        } catch (PDOException $ex) {
            error_log("Error al obtener usuarios: " . $ex->getMessage());
            return null; // Devuelve nulo en caso de error
        } finally {
            // Cerrar la conexión
            $conexion = null;
        }
    }

    public static function obtenerUsuarios()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Conexión a la base de datos
        $conexion = Database::connect();
        if (!$conexion) {
            die('Error de conexión a la base de datos');
        }

        $usuarios = [];
        try {
            if ($_SESSION['region'] != 'Nacional') {
                $sql = "SELECT * FROM usuarios WHERE region = :region";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(':region', $_SESSION['region'], PDO::PARAM_STR);
            } else {
                $sql = "SELECT * FROM usuarios";
                $sentencia = $conexion->prepare($sql);
            }


            $sentencia->execute();

            $resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);

            foreach ($resultado as $fila) {
                $usuarios[] = [
                    "id" => htmlspecialchars($fila["id_usuario"]),
                    "nombre" => htmlspecialchars($fila["nombre"]),
                    "correo" => htmlspecialchars($fila["correo"]),
                    "region" => htmlspecialchars($fila["region"]),
                    "consejo" => $fila["consejo_regional"],
                    "estado" => htmlspecialchars($fila["estado"]) === "habilitado" ? "habilitado" : "deshabilitado"
                ];
            }
        } catch (PDOException $ex) {
            error_log("Error al obtener usuarios: " . $ex->getMessage());
        }

        $conexion = null;


        return $usuarios;
    }
    public static function obtenerVoluntarios()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Conexión a la base de datos
        $conexion = Database::connect();
        if (!$conexion) {
            die('Error de conexión a la base de datos');
        }

        $usuarios = [];
        try {
            $sql = "SELECT * FROM voluntarios";
            $sentencia = $conexion->prepare($sql);


            $sentencia->execute();

            $fila = $sentencia->fetchAll(PDO::FETCH_ASSOC);

            foreach ($fila as $resultado) {
                $usuarios[] = [
                    "id" => htmlspecialchars($resultado["id"]),
                    "nombre" => htmlspecialchars($resultado["nombre"]),
                    "rut" => htmlspecialchars($resultado["rut"]),
                    "telefono" => htmlspecialchars($resultado["telefono"]),
                    "correo" => htmlspecialchars($resultado["correo"]),
                    "clave" => htmlspecialchars($resultado["clave"]),
                    "profesion" => htmlspecialchars($resultado["profesion"]),
                    "region" => htmlspecialchars($resultado["region"]),
                    "comuna" => htmlspecialchars($resultado["comuna"]),
                    "experiencia_voluntario" => htmlspecialchars($resultado["experiencia_voluntario"]),
                    "experiencia_otra_emergencia" => htmlspecialchars($resultado["experiencia_otra_emergencia"]),
                    "recursos_propios" => htmlspecialchars($resultado["recursos_propios"]),
                    "hobbys" => htmlspecialchars($resultado["hobbys"]),
                    "tipo_alimentacion" => htmlspecialchars($resultado["tipo_alimentacion"]),
                    "grupo_sanguineo" => htmlspecialchars($resultado["grupo_sanguineo"]),
                    "enfermedades_cronicas" => htmlspecialchars($resultado["enfermedades_cronicas"]),
                    "actividades" => htmlspecialchars($resultado["actividades"]),
                    "area_desempeno" => htmlspecialchars($resultado["area_desempeno"]),
                    "experiencia_emergencias" => htmlspecialchars($resultado["experiencia_emergencias"]),
                    "experiencia_animales" => htmlspecialchars($resultado["experiencia_animales"]),
                    "experiencia_desastres" => htmlspecialchars($resultado["experiencia_desastres"]),
                    "certificado_titulo" => htmlspecialchars($resultado["certificado_titulo"]),
                    "estado" => htmlspecialchars($resultado["estado"]),
                    "fecha_registro" => htmlspecialchars($resultado["fecha_registro"]),
                    "Fotoperfil" => htmlspecialchars($resultado["Fotoperfil"]),
                    "certificadoAntecedentes" => htmlspecialchars($resultado["certificadoAntecedentes"])
                ];
            }
        } catch (PDOException $ex) {
            error_log("Error al obtener voluntarios: " . $ex->getMessage());
        }

        $conexion = null;


        return $usuarios;
    }


    public static function guardarUsuario($nombre, $correo, $clave, $region, $consejo)
    {
        // Conectar a la base de datos
        $pdo = Database::connect();

        // Encriptar la clave
        $clave_encriptada = password_hash($clave, PASSWORD_DEFAULT);

        // Preparar la consulta SQL para insertar el nuevo usuario
        $sql = "INSERT INTO usuarios (nombre, correo, clave, region, consejo_regional, estado)
                VALUES (:nombre, :correo, :clave, :region, :consejo, 'habilitado')";

        try {
            // Preparar la declaración
            $stmt = $pdo->prepare($sql);

            // Vincular los parámetros
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':correo', $correo);
            $stmt->bindParam(':clave', $clave_encriptada);
            $stmt->bindParam(':region', $region);
            $stmt->bindParam(':consejo', $consejo);

            // Ejecutar la consulta
            $stmt->execute();

            // Retornar éxito
            return 'Usuario creado exitosamente.';
        } catch (PDOException $e) {
            // Manejar errores de ejecución
            return 'Error al guardar el usuario: ' . $e->getMessage();
        }
    }
    public static function login($email, $password, $type)
    {
        // Determinar la tabla según el tipo de usuario
        $table = '';
        switch ($type) {
            case 'voluntario':
                $query = "SELECT * FROM voluntarios WHERE correo = :email";
                break;
            case 'Coordinacion':
                $query = "SELECT * FROM usuarios WHERE correo = :email";
                break;
            case 'Clinica':
                $query = "SELECT * FROM clinicas WHERE correo = :email";
                break;
            default:
                return ['error' => 'Tipo de usuario no válido'];
        }

        try {
            // Consultar la base de datos para encontrar el usuario
            $pdo = Database::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                return ['error' => 'Usuario no encontrado'];
            }

            // Verificar la contraseña
            if (!password_verify($password, $user['clave'])) {
                return ['error' => 'Contraseña incorrecta'];
            }

            // Si es un voluntario, verificar si está habilitado
            if ($type === 'voluntario' && ($user['estado'] === 'rechazado' || $user['estado'] === 'deshabilitado')) {
                session_unset(); // Limpiar las variables de sesión
                session_destroy(); // Destruir la sesión
                return ['error' => 'Este voluntario está deshabilitado'];
            }


            session_status();
            switch ($type) {
                case 'voluntario':
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = htmlspecialchars($user['nombre']);
                    $_SESSION['user_email'] = htmlspecialchars($user['correo']);
                    $_SESSION['user_type'] = $type;

                    break;
                case 'Coordinacion':
                    $_SESSION['user_id'] = htmlspecialchars($user['id_usuario']);
                    $_SESSION['user_name'] = htmlspecialchars($user['nombre']);
                    $_SESSION['user_email'] = htmlspecialchars($user['correo']);
                    $_SESSION['user_type'] = $type;
                    if ($type === 'Coordinacion') {
                        $_SESSION['region'] = htmlspecialchars($user['region']);
                        $_SESSION['consejo_regional'] = htmlspecialchars($user['consejo_regional']);
                    }
                    break;
                case 'Clinicas':
                    # code...
                    break;
            }
            // Guardar información del usuario en la sesión


            return ['success' => true];
        } catch (PDOException $e) {
            return ['error' => 'Error en la base de datos: ' . $e->getMessage()];
        }
    }
}
