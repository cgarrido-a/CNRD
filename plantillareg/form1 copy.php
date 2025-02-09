<?php
include_once('app/func.inc.php');
$regiones = Usuario::obtener_regiones();

?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Registro de Voluntario</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</head>

<body>

    <div style='text-align: center; margin-top: 50px;'>
        <img src='img/cnrd.png' alt='Logo CNRD' style='width: 200px; margin-bottom: -75px;'>
        <div style='font-family: Arial, sans-serif;'>

        </div>
    </div>
    <div id="loader" style=" text-align: center; display:none;">
        <div class="spinner-border text-primary" role="status">
            <span class="sr-only">Cargando...</span>
        </div>
        <p>Enviando datos, por favor espera...</p>
    </div>
    <div id="Contenedor">

        <div class="container mt-5">
            <h2 class="text-center">Registro de Nuevo Voluntario</h2>
            <form method="POST" action="" enctype="multipart/form-data" class="mt-4">

                <!-- Parte 1 -->
                <h4>Información personal</h4>
                <!-- NOmbre ok -->
                <div class="form-group">
                    <label for="nombre">Nombre Completo</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                </div>
                <!-- RUT OK -->
                <div class="form-group">
                    <label for="rut">RUT</label>
                    <input type="text" class="form-control" id="rut" name="rut" required placeholder="12.345.678-9">
                </div>
                <!-- Telefono OK -->
                <div class="form-group">
                    <label for="telefono">Teléfono</label>
                    <input type="text" class="form-control" id="telefono" name="telefono" required>
                </div>
                <!-- Mail OK -->
                <div class="form-group">
                    <label for="correo">Correo Electrónico</label>
                    <input type="email" class="form-control" id="correo" name="correo" required>
                </div>
                <!-- Contraseña OK -->
                <div class="form-group">
                    <label for="contrasena">Contraseña, minimo 8 caracteres para ingreso de a la plataforma.</label>
                    <input type="password" class="form-control" id="contrasena" name="contrasena" required>
                </div>
                <!-- Profesion Comprobar otra -->
                <div class="form-group">
                    <label for="profesion">Profesión u Ocupación</label>
                    <select class="form-control" id="profesion" name="profesion" required>
                        <option value="">Selecciona una opción</option>
                        <option value="Medico Veterinario">Médico Veterinario</option>
                        <option value="Tecnico Veterinario">Técnico Veterinario</option>
                        <option value="Estudiante Medicina Veterinaria">Estudiante Medicina Veterinaria</option>
                        <option value="Estudiante Técnico Veterinario">Estudiante Técnico Veterinario</option>
                        <option value="Otra">Otra</option>
                    </select>
                    <div id="otraprof"></div>

                </div>
                <!-- Region OK -->
                <div class="form-group">
                    <label for="region">Región</label>
                    <select class="form-control" id="region" name="region" required>
                        <option value="">Región</option>
                        <?php
                        foreach ($regiones as $region) {
                            echo '<option value="'.$region["id"].'">'.$region['nombre'].'</option>';
                        }
                        ?>

                        <!-- Agrega las demás regiones aquí -->
                    </select>
                </div>
                <!-- Comuna OK -->
                <div class="form-group">
                    <label for="comuna">Comuna</label>
                    <input type="text" class="form-control" id="comuna" name="comuna" required>
                </div>
                <!-- Alimentacion Comprobar -->
                <div class="form-group">
                    <label for="tipoAlimentacion">Tipo de alimentación:</label>
                    <select class="form-control" id="tipoAlimentacion" name="tipoAlimentacion" required>
                        <option value="" disabled selected>Seleccione una opción</option>
                        <option value="Omnivoro">Omnívoro</option>
                        <option value="Vegetariano Ovolacteo">Vegetariano Ovolacteo</option>
                        <option value="Vegetariano Pescetariano">Vegetariano Pescetariano</option>
                        <option value="Vegano">Vegano</option>
                    </select>
                </div>
                <!-- Sanguineo Comprobar -->
                <div class="form-group">
                    <label for="grupoSanguineo">Grupo sanguíneo:</label>
                    <select class="form-control" id="grupoSanguineo" name="grupoSanguineo" required>
                        <option value="" disabled selected>Seleccione una opción</option>
                        <option value="O+">O+</option>
                        <option value="O-">O-</option>
                        <option value="AB+">AB+</option>
                        <option value="AB-">AB-</option>
                        <option value="A+">A+</option>
                        <option value="A-">A-</option>
                        <option value="B+">B+</option>
                        <option value="B-">B-</option>
                    </select>
                </div>
                <!-- Cronicas Comprobar -->
                <div class="form-group">
                    <label for="enf_cronicas">Enfermedades cronicas y/o alergias</label>
                    <textarea class="form-control" id="enf_cronicas" name="enf_cronicas"></textarea>
                </div>
                <!-- Parte 2: Información adicional basada en la profesión -->
                <div id="parte2">
                    <!-- Los campos se agregarán aquí dinámicamente -->
                </div>

                <!-- Parte 3 -->
                <!-- Falta -->
                <h4>Experiencia como voluntario</h4>
                <div class="form-group">
                    <label for="experienciaVoluntario">¿Tiene experiencia previa como voluntario?</label>
                    <textarea class="form-control" id="experienciaVoluntario" name="experienciaVoluntario"></textarea>
                </div>
                <div class="form-group">
                    <label for="experienciaOtraEmergencia">¿Tiene experiencia en otras emergencias no relacionadas con el ámbito animal?</label>
                    <textarea class="form-control" id="experienciaOtraEmergencia" name="experienciaOtraEmergencia"></textarea>
                </div>
                <div class="form-group">
                    <label for="recursosPropios">¿Cuenta con recursos propios (EPP, vehículo, etc.)?</label>
                    <textarea class="form-control" id="recursosPropios" name="recursosPropios"></textarea>
                </div>
                <div class="form-group">
                    <label for="hobbys">Hobbys</label>
                    <textarea class="form-control" id="hobbys" name="hobbys"></textarea>
                </div>

                <!-- Parte 4: Documentación -->
                <h4>Documentación</h4>
                <div class="form-group">
                    <label for="certificadoAntecedentes">Certificado de Antecedentes</label>
                    <input type="file" class="form-control-file" id="certificadoAntecedentes" name="certificadoAntecedentes" required>
                </div>
                <div class="form-group">

                    <label for="fotoPerfil">Foto de Perfil</label>
                    <p>La foto de perfil debe estar en relacion 3:4 y como la imagen de referencia</p>
                    <img src="img/medio-cuerpo.jpg" alt="" srcset="" width="150px">
                    <br>
                    <input type="file" class="form-control-file" id="fotoPerfil" name="fotoPerfil">
                </div>

                <br><br>
                <!-- Confirmación y envío -->
                <div class="form-group">
                    <h3>Información importante</h3>
                    <p>
                        Toda la información proporcionada en esta plataforma debe ser <strong>auténtica y veraz</strong>, ya que cualquier falsedad puede derivar en la anulación de su registro. Es importante tener en cuenta que el hecho de registrarse en esta plataforma <strong>no implica autorización automática</strong> como voluntario de la Comisión Nacional de Respuesta a Desastres (CNRD).
                        Una vez que su solicitud sea revisada y aprobada, recibirá un <strong>correo electrónico de confirmación</strong>, a partir del cual podrá acceder a su credencial oficial. Cabe señalar que es <strong>responsabilidad exclusiva del voluntario imprimir y portar dicha credencial</strong> siempre visible durante el desempeño de sus funciones. Además, es fundamental mantener <strong>actualizada su información de contacto</strong> para asegurar una comunicación efectiva en caso de emergencias o notificaciones importantes.
                    </p>
                    <input type="checkbox" id="confirmacion" name="confirmacion">
                    <label for="confirmacion">Acepto los términos.</label>
                    </label>
                </div>
                <button type="submit" class="btn btn-primary" id="guardarBtn" disabled>Guardar</button>
            </form>
        </div>
    </div>

    <script>
        // Función para validar el RUT
        function validarRUT(rut) {
            rut = rut.replace(/\./g, '').replace(/-/g, ''); // Eliminar puntos y guión
            if (rut.length < 8) return false; // Validar longitud mínima
            let cuerpo = rut.slice(0, -1);
            let dv = rut.slice(-1).toUpperCase();
            let suma = 0;
            let multiplo = 2;

            for (let i = cuerpo.length - 1; i >= 0; i--) {
                suma += multiplo * parseInt(cuerpo.charAt(i));
                multiplo = multiplo === 7 ? 2 : multiplo + 1;
            }

            let dvEsperado = 11 - (suma % 11);
            dvEsperado = dvEsperado === 11 ? '0' : dvEsperado === 10 ? 'K' : dvEsperado.toString();

            return dv === dvEsperado;
        }

        // Función para validar el formulario
        function validarFormulario() {
            let esValido = true;
            let rut = $('#rut').val();
            if (!validarRUT(rut)) {
                esValido = false;
                alert('El RUT ingresado no es válido.');
            }

            // Validar otros campos requeridos
            $('form [required]').each(function() {
                if ($(this).val().trim() === '') {
                    esValido = false;
                    alert(`El campo ${$(this).attr('name')} es obligatorio.`);
                    return false; // Detener iteración
                }
            });

            return esValido;
        }

        // Función para dar formato al RUT mientras se escribe
        function formatoRUT(rut) {
            rut = rut.replace(/\./g, '').replace(/-/g, '');
            if (rut.length <= 1) return rut;
            let cuerpo = rut.slice(0, -1);
            let dv = rut.slice(-1);
            cuerpo = cuerpo.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            return `${cuerpo}-${dv}`;
        }

        // Contenido dinámico según la profesión
        const html2 =
            `<h4>Información académica y laboral</h4>
                <div id="preguntasMedico">
                    <div class="form-group">
                        <label for="areaDesempeno">Área de Desempeño</label>
                        <textarea class="form-control" id="areaDesempeno" name="areaDesempeno"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="experienciaEmergencias">Experiencia en emergencias, desastres o catástrofes. Detalle roles, funciones y responsabilidades</label>
                        <textarea class="form-control" id="experienciaEmergencias" name="experienciaEmergencias"></textarea>
                    </div>
                </div>
                <div id="preguntasOtraProfesion">
                    <div class="form-group">
                        <label for="experienciaAnimales">Experiencia en trabajo con animales</label>
                        <textarea class="form-control" id="experienciaAnimales" name="experienciaAnimales"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="certificadoTitulo">Certificado de Título o Alumno Regular</label>
                    <input type="file" class="form-control-file" id="certificadoTitulo" name="certificadoTitulo" required>
                </div>`;

        $(document).ready(function() {
            // Formatear RUT al escribir
            $('#rut').on('input', function() {
                $(this).val(formatoRUT($(this).val()));
            });

            // Mostrar/ocultar campos dinámicos según la profesión
            $('#profesion').change(function() {
                var profesion = $(this).val();
                var parte2 = $('#parte2');
                var otraprof = $('#otraprof');

                if (profesion === 'Otra') {
                    otraprof.html('<input type="text" class="form-control mt-2" id="otraProfesion" name="otraProfesion" placeholder="Especifica tu profesión u oficio">');
                    parte2.html(''); // Limpiar contenido de "parte2"
                } else {
                    otraprof.html('');
                    parte2.html(html2); // Insertar contenido dinámico
                }
            });

            $('#confirmacion').change(function () {
                if ($(this).is(':checked')) {
                    $('#guardarBtn').removeAttr('disabled');
                } else {
                    $('#guardarBtn').attr('disabled', true);
                }
            });
        });
    </script>


</body>

</html>