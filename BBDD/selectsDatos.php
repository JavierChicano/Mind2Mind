<?php
// insertar_datos.php

// Recibir datos del formulario

if (isset($_POST['funcion'])) {
    $funcion = $_POST['funcion'];

    // Configuración de la conexión a la base de datos
    $host = "localhost";
    $usuario = "root";
    $contrasena = "";
    $base_datos = "mind2mind";

    $conexion = new mysqli($host, $usuario, $contrasena, $base_datos);

    // Verificar la conexión
    if ($conexion->connect_error) {
        die("La conexión a la base de datos ha fallado: " . $conexion->connect_error);
    }

    switch ($funcion) {
        case 'login':
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Consulta SQL para verificar si el correo electrónico existe
            $sql = "SELECT * FROM paciente WHERE correoElectronico = '$email'";
            $resultado = $conexion->query($sql);

            if ($resultado->num_rows > 0) {
                // El correo electrónico existe, ahora verificamos la contraseña
                $row = $resultado->fetch_assoc();
                $hashedPassword = $row['contraseña'];
                if (password_verify($password, $hashedPassword)) {
                    // La contraseña coincide
                    // Verificar si las credenciales coinciden con alguna de las 5 duplas específicas
                    $duplas_admin = array(
                        "admin1@doctor.com" => "admin1",
                        "admin2@doctor.com" => "admin2",
                        "admin3@doctor.com" => "admin3",
                        "admin4@doctor.com" => "admin4",
                        "admin5@doctor.com" => "admin5"
                    );
                    if (array_key_exists($email, $duplas_admin) && $duplas_admin[$email] == $password) {
                        // Las credenciales coinciden con una de las 5 duplas específicas
                        $response = array('status' => 'admin', 'message' => 'Inicio de sesión exitoso como administrador');
                    } else {
                        // Las credenciales coinciden pero no son una de las 5 duplas específicas
                        $response = array('status' => 'success', 'message' => 'Inicio de sesión exitoso como paciente');
                    }
                } else {
                    // La contraseña no coincide
                    $response = array('status' => 'error', 'message' => 'Contraseña no válida');
                }
            } else {
                // No se encontró el correo electrónico
                $response = array('status' => 'error', 'message' => 'Correo no existente');
            }
            break;


        case 'obtenerDoctor':
            // En caso de obtener información del doctor
            // Consultar la base de datos para obtener la información del doctor
            $sql = "SELECT * FROM especialista";
            $resultado = $conexion->query($sql);

            if ($resultado->num_rows > 0) {
                // Si se encuentran datos de doctores, asignar la respuesta de éxito a la variable $response
                $doctores = array();

                // Iterar a través de los resultados y agregar cada doctor al array
                while ($doctor = $resultado->fetch_assoc()) {
                    $doctores[] = $doctor;
                }
                $response = array('status' => 'success', 'doctores' => $doctores);
            } else {
                // Si no se encuentra el doctor, asignar la respuesta de error a la variable $response
                $response = array('status' => 'error', 'message' => 'Doctor no encontrado');
            }
            break;
        case 'nombrePaciente':
            // Obtener el email del paciente desde la solicitud POST
            $email = $_POST['email'];

            // Consultar la base de datos para obtener la información del paciente
            $sql = "SELECT nombre FROM paciente WHERE correoElectronico = '$email'";
            $resultado = $conexion->query($sql);

            if ($resultado->num_rows > 0) {
                // Si se encuentra el paciente, obtener el nombre y asignar la respuesta de éxito a la variable $response
                $paciente = $resultado->fetch_assoc();
                $nombrePaciente = $paciente['nombre'];
                $response = array('status' => 'success', 'nombre' => $nombrePaciente);
            } else {
                // Si no se encuentra el paciente, asignar la respuesta de error a la variable $response
                $response = array('status' => 'error', 'message' => 'Paciente no encontrado');
            }
            break;
        case 'editarPerfil':
            $email = $_POST['email'];
            // Consulta SQL para verificar si el correo electrónico existe y la contraseña coincide
            $sql = "SELECT * FROM paciente WHERE correoElectronico = '$email'";
            $resultado = $conexion->query($sql);

            if ($resultado->num_rows > 0) {
                $paciente = $resultado->fetch_assoc();
                // Eliminar la contraseña del resultado si no deseas pasarla hasheada
                $contraseña = $paciente['contraseña'];
                unset($paciente['contraseña']);
                $response = array('status' => 'success', 'paciente' => $paciente, 'contraseña' => $contraseña);
            } else {
                // No se encontró el correo electrónico
                $response = array('status' => 'error', 'message' => 'Error en la consulta');
            }
            break;

        case 'comprobarCuentaPedirCita':
            $email = $_POST['email'];

            // Verificar si el correo electrónico ya existe en la base de datos
            $sqlVerificarEmail = "SELECT correoElectronico FROM paciente WHERE correoElectronico = '$email'";
            $resultadoEmail = $conexion->query($sqlVerificarEmail);

            if ($resultadoEmail->num_rows > 0) {
                // El correo electrónico ya existe en la base de datos, envía un mensaje de error
                $response = array('status' => 'error', 'message' => 'Ese correo ya esta registrado, inicie sesión');
            } else {
                $response = array('status' => 'success');
            }
            break;
        //Casos de pagina terapias especificas
        case 'comprobarTerapia':
            $idTerapia = $_POST['idTerapia'];
            $idPaciente = $_POST['idPaciente'];

            // Consulta SQL para verificar si ya existe una tupla con los valores proporcionados
            $sqlComprobarTerapia = "SELECT * FROM terapiaPaciente WHERE correoElectronico = '$idPaciente' AND idTerapia = $idTerapia";
            $resultadoComprobarTerapia = $conexion->query($sqlComprobarTerapia);

            // Verificar si hay resultados
            if ($resultadoComprobarTerapia->num_rows > 0) {
                $response = array('status' => 'success', 'message' => 'La tupla ya existe en la tabla terapiaPaciente');
            } else {
                $response = array('status' => 'error', 'message' => 'La tupla no existe en la tabla terapiaPaciente');
            }
            break;
        //Casos de las consultas de tratamientos
        case 'mostrarDatosTerapia':
            $nombre = $_POST['nombre'];

            $sqlTerapia = "SELECT * FROM terapia WHERE nombre = '$nombre'";
            $resultadoTerapia = $conexion->query($sqlTerapia);

            if ($resultadoTerapia->num_rows > 0) {
                $terapia = $resultadoTerapia->fetch_assoc();
                $response = array('status' => 'success', 'terapia' => $terapia);
            } else {
                $response = array('status' => 'error', 'message' => 'No se encontraron datos de terapia para el nombre proporcionado');
            }
            break;
        case 'comprobarTerapias':
            $correoElectronico = $_POST['correoElectronico'];

            // Consulta SQL para verificar la existencia de tuplas
            $sqlComprobarTerapias = "SELECT idTerapia FROM terapiaPaciente WHERE correoElectronico = '$correoElectronico'";
            $resultadoComprobarTerapias = $conexion->query($sqlComprobarTerapias);

            // Verificar si hay resultados
            $numConsultas = $resultadoComprobarTerapias->num_rows; // Número de consultas resultantes

            if ($numConsultas > 0) {
                $idsTerapias = array();
                while ($fila = $resultadoComprobarTerapias->fetch_assoc()) {
                    $idsTerapias[] = $fila['idTerapia']; // Guardar los IDs de las terapias
                }
                $response = array('status' => 'success', 'message' => 'Existen terapias asociadas al correo electrónico proporcionado', 'numConsultas' => $numConsultas, 'idsTerapias' => $idsTerapias);
            } else {
                $response = array('status' => 'error', 'message' => 'No se encontraron terapias asociadas al correo electrónico proporcionado');
            }
            break;
        case 'mostrarTodasTerapias':
            $sqlTerapia = "SELECT nombre, imagen, mini_descripcion FROM terapia";
            $resultadoTerapia = $conexion->query($sqlTerapia);

            if ($resultadoTerapia->num_rows > 0) {
                $terapias = array();
                while ($terapia = $resultadoTerapia->fetch_assoc()) {
                    $terapias[] = $terapia;
                }
                $response = array('status' => 'success', 'terapias' => $terapias);
            } else {
                $response = array('status' => 'error', 'message' => 'No se encontraron Terapias');
            }
            break;
        case 'mostrarTerapiasAsociadas':
            $correoElectronico = $_POST['correoElectronico'];

            // Consulta SQL para seleccionar las terapias asociadas con el correo electrónico proporcionado
            $sqlTerapiaAsociada = "SELECT nombre, imagen, mini_descripcion FROM terapia 
                                       INNER JOIN terapiaPaciente ON terapia.idTerapia = terapiaPaciente.idTerapia 
                                       WHERE terapiaPaciente.correoElectronico = '$correoElectronico'";

            $resultadoTerapiaAsociada = $conexion->query($sqlTerapiaAsociada);

            if ($resultadoTerapiaAsociada->num_rows > 0) {
                $terapias = array();
                while ($terapia = $resultadoTerapiaAsociada->fetch_assoc()) {
                    $terapias[] = $terapia;
                }
                $response = array('status' => 'success', 'terapias' => $terapias);
            } else {
                $response = array('status' => 'error', 'message' => 'No se encontraron Terapias asociadas para el correo electrónico proporcionado');
            }
            break;
        case 'mostrarRestoTerapias':
            $correoElectronico = $_POST['correoElectronico'];

            // Consulta SQL para seleccionar las terapias no asociadas al correo electrónico proporcionado
            $sqlMostrarRestoTerapias = "SELECT nombre, imagen, mini_descripcion FROM terapia 
                                            WHERE idTerapia NOT IN 
                                            (SELECT idTerapia FROM terapiaPaciente WHERE correoElectronico = '$correoElectronico')";

            $resultadoMostrarRestoTerapias = $conexion->query($sqlMostrarRestoTerapias);

            // Verificar si hay resultados
            if ($resultadoMostrarRestoTerapias->num_rows > 0) {
                $terapias = array();
                while ($terapia = $resultadoMostrarRestoTerapias->fetch_assoc()) {
                    $terapias[] = $terapia;
                }
                $response = array('status' => 'success', 'terapias' => $terapias);
            } else {
                $response = array('status' => 'error', 'message' => 'No se encontraron Terapias no asociadas para el correo electrónico proporcionado');
            }
            break;
        case 'consultarCitas':
            $correo_electronico_deseado = $_POST['correoElectronico'];

            // Consulta SQL para obtener la fecha, especialidad y modalidad en función del correo electrónico
            $sql_select_cita = "SELECT fecha, especialidad, modalidad
                    FROM cita
                    WHERE correoElectronico = '$correo_electronico_deseado'";

            // Ejecutar la consulta
            $resultado_select_cita = $conexion->query($sql_select_cita);

            // Verificar si se obtuvieron resultados
            if ($resultado_select_cita->num_rows > 0) {
                $citas = array();
                // Recorrer los resultados y almacenarlos en un array
                while ($fila = $resultado_select_cita->fetch_assoc()) {
                    $citas[] = array(
                        'fecha' => $fila['fecha'],
                        'especialidad' => $fila['especialidad'],
                        'modalidad' => $fila['modalidad']
                    );
                }
                // Crear respuesta con el array de citas
                $response = array('status' => 'success', 'citas' => $citas);
            } else {
                // No se encontraron resultados
                $response = array('status' => 'error', 'message' => 'No se encontraron citas para el correo electrónico especificado');
            }
            break;
        case 'eliminarPaciente':
            // Obtener el correo electrónico del paciente a eliminar
            $correo_electronico_especifico = $_POST['correoElectronico'];

            // Eliminar las filas relacionadas en la tabla 'terapiaPaciente'
            $sql_eliminar_terapiaPaciente = "DELETE FROM terapiaPaciente WHERE correoElectronico = '$correo_electronico_especifico'";
            $conexion->query($sql_eliminar_terapiaPaciente);

            // Eliminar la fila correspondiente en la tabla 'paciente'
            $sql_eliminar_paciente = "DELETE FROM paciente WHERE correoElectronico = '$correo_electronico_especifico'";
            $conexion->query($sql_eliminar_paciente);

            // Verificar si se realizaron las eliminaciones correctamente
            if ($conexion->affected_rows > 0) {
                // Eliminación exitosa
                $response = array('status' => 'success', 'message' => 'Paciente y sus datos relacionados eliminados correctamente');
            } else {
                // No se encontraron registros para eliminar
                $response = array('status' => 'error', 'message' => 'No se encontraron registros para eliminar');
            }
            break;

        case 'mostrarDatosChat':
            $correoElectronico = $_POST['correoElectronico'];
            $idMedico = $_POST['idMedico'];

            $sqlMensajes = "SELECT * FROM chat WHERE correoElectronico = '$correoElectronico' AND idMedico = $idMedico ORDER BY fecha";
            $resultadoMensajes = $conexion->query($sqlMensajes);

            if ($resultadoMensajes->num_rows > 0) {
                $mensajes = array();
                while ($mensaje = $resultadoMensajes->fetch_assoc()) {
                    $mensajes[] = $mensaje;
                }
                $response = array('status' => 'success', 'mensajes' => $mensajes);
            } else {
                $response = array('status' => 'error', 'message' => 'No se encontraron mensajes para el usuario y médico proporcionados');
            }
            break;



    }

    // Enviar la respuesta como JSON
    header('Content-Type: application/json');
    echo json_encode($response);

    // Cerrar la conexión
    $conexion->close();
}

