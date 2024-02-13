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

    // Inicializar las variables
    $nombre = $apellidos = $email = $password = $dni = $telefono = $provincia = $domicilio = $sexo = $profesional = $modalidad = $diagnostico = $fConsulta = $poseeAseguradora = $aseguradora = $consulta = "";

    switch ($funcion) {
        case 'registro':
            $nombre = $_POST['nombre'];
            $apellidos = $_POST['apellidos'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Preparar la consulta de inserción para el caso de registro
            $verificarExistencia = "SELECT correoElectronico FROM paciente WHERE correoElectronico = '$email'";
            $resultadoVerificacion = $conexion->query($verificarExistencia);

            if ($resultadoVerificacion->num_rows > 0) {
                // Ya existe un registro con esa clave primaria
                $response = array('status' => 'error', 'message' => 'Correo electrónico ya registrado');
            } else {
                // No existe un registro con esa clave primaria, proceder con la inserción
                $sql = "INSERT INTO paciente (correoElectronico, nombre, apellidos, contraseña) VALUES ('$email', '$nombre', '$apellidos', '$password')";

                if ($conexion->query($sql) === TRUE) {
                    // La inserción fue exitosa
                    $response = array('status' => 'success', 'message' => 'Registro exitoso');
                } else {
                    // Hubo un error en la inserción
                    $response = array('status' => 'error', 'message' => 'Error en el registro: ' . $conexion->error);
                }
            }
            break;

        case 'contacto':
            $nombre = $_POST['nombre'];
            $apellidos = $_POST['apellidos'];
            $email = $_POST['email'];
            $telefono = $_POST['telefono'];
            $consulta = $_POST['consulta'];

            // Preparar la consulta de inserción para el caso de contacto
            $sql = "INSERT INTO consulta (correoElectronico, nombre, apellidos, telefono, consulta) VALUES ('$email','$nombre', '$apellidos', '$telefono', '$consulta')";
            if ($conexion->query($sql) === TRUE) {
                // La inserción fue exitosa
                $response = array('status' => 'success', 'message' => 'Consulta enviada con éxito');
            } else {
                // Hubo un error en la inserción
                $response = array('status' => 'error', 'message' => 'Error al enviar la consulta: ' . $conexion->error);
            }
            break;

        case 'pedirCita':
            $email = $_POST['email'];
            $contraseña = $_POST['contraseña'];
            $nombre = $_POST['nombre'];
            $apellidos = $_POST['apellidos'];
            $dni = $_POST['dni'];
            $provincia = $_POST['provincia'];
            $domicilio = $_POST['domicilio'];
            $postal = $_POST['postal'];
            $sexo = $_POST['sexo'];
            $profesional = $_POST['profesional'];
            $modalidad = $_POST['modalidad'];
            $telefono = $_POST['telefono'];
            $diagnostico = $_POST['diagnostico'];
            $fConsulta = $_POST['fConsulta'];
            $poseeAseguradora = $_POST['poseeAseguradora'];
            $aseguradora = $_POST['aseguradora'];

            // Insertar datos en la tabla paciente
            $sqlPaciente = "INSERT INTO paciente (correoElectronico, nombre, apellidos, contraseña, dni, provincia, domicilio, codigo_postal, genero) 
                            VALUES ('$email', '$nombre', '$apellidos', '$contraseña', '$dni', '$provincia', '$domicilio', '$postal', '$sexo')";

            if ($conexion->query($sqlPaciente) === TRUE) {
                // Obtener el ID insertado (correoElectronico) para usarlo en la tabla cita
                $idPaciente = $email;

                // Buscar el idMedico en la tabla especialista basándote en la modalidad y especialidad proporcionadas
                $sqlBuscarMedico = "SELECT idMedico FROM especialista WHERE modalidad = '$modalidad' AND especialidad = '$profesional'";
                $resultadoMedico = $conexion->query($sqlBuscarMedico);

                if ($resultadoMedico->num_rows > 0) {
                    $filaMedico = $resultadoMedico->fetch_assoc();
                    $idMedico = $filaMedico['idMedico'];

                    // Insertar datos en la tabla cita
                    $sqlCita = "INSERT INTO cita (especialidad, modalidad, numeroTelefono, diagnostico, fecha, aseguradoraNombre, correoElectronico, idMedico) 
                                VALUES ('$profesional', '$modalidad', '$telefono', '$diagnostico', '$fConsulta', '$aseguradora', '$idPaciente', '$idMedico')";

                    if ($conexion->query($sqlCita) === TRUE) {
                        $response = array('status' => 'success', 'message' => 'Datos insertados correctamente en paciente y cita');
                    } else {
                        $response = array('status' => 'error', 'message' => 'Error al insertar datos en cita: ' . $conexion->error);
                    }
                } else {
                    $response = array('status' => 'error', 'message' => 'No se encontró un médico con la modalidad y especialidad proporcionadas');
                }
            } else {
                $response = array('status' => 'error', 'message' => 'Error al insertar datos en paciente: ' . $conexion->error);
            }
            break;
        case 'insertarPerfil':
            $correoElectronico = $_POST['correoElectronico'];
            $nombre = $_POST['nombre'];
            $apellidos = $_POST['apellidos'];
            $contraseña = $_POST['contraseña'];
            $dni = $_POST['dni'];
            $provincia = $_POST['provincia'];
            $domicilio = $_POST['domicilio'];
            $genero = $_POST['genero'];

            // Preparar la consulta de actualización
            $sql = "UPDATE paciente SET nombre='$nombre', apellidos='$apellidos', contraseña='$contraseña', dni='$dni', provincia='$provincia', domicilio='$domicilio', genero='$genero' WHERE correoElectronico='$correoElectronico'";

            if ($conexion->query($sql) === TRUE) {
                // Actualización exitosa
                $response = array('status' => 'success', 'message' => 'Perfil actualizado correctamente');
            } else {
                // Error en la actualización
                $response = array('status' => 'error', 'message' => 'Error al actualizar el perfil: ' . $conexion->error);
            }
            break;
        case 'vincularTerapia':
            $nombre = $_POST['nombre'];
            $paciente = $_POST['idPaciente'];

            // Consulta para obtener el idTerapia correspondiente al nombre de la terapia
            $sqlObtenerIdTerapia = "SELECT idTerapia FROM terapia WHERE nombre = '$nombre'";
            $resultado = $conexion->query($sqlObtenerIdTerapia);

            if ($resultado->num_rows > 0) {
                // Obtener el idTerapia
                $fila = $resultado->fetch_assoc();
                $idTerapia = $fila['idTerapia'];

                // Consulta para insertar la vinculación en la tabla terapiaPaciente
                $sqlInsertarTerapiaPaciente = "INSERT INTO terapiaPaciente (correoElectronico, idTerapia) 
                                                   VALUES ('$paciente', $idTerapia)";

                // Ejecutar la consulta
                if ($conexion->query($sqlInsertarTerapiaPaciente) === TRUE) {
                    // Inserción exitosa
                    $response = array('status' => 'success', 'message' => 'Terapia vinculada correctamente');
                } else {
                    // Error en la inserción
                    $response = array('status' => 'error', 'message' => 'Error al vincular la terapia: ' . $conexion->error);
                }
            } else {
                // No se encontró la terapia con el nombre proporcionado
                $response = array('status' => 'error', 'message' => 'No se encontró la terapia con el nombre proporcionado');
            }
            break;

        case 'eliminarTerapia':
            $nombre = $_POST['nombre'];
            $paciente = $_POST['idPaciente'];

            // Consulta para obtener el idTerapia correspondiente al nombre de la terapia
            $sqlObtenerIdTerapia = "SELECT idTerapia FROM terapia WHERE nombre = '$nombre'";
            $resultado = $conexion->query($sqlObtenerIdTerapia);

            if ($resultado->num_rows > 0) {
                // Obtener el idTerapia
                $fila = $resultado->fetch_assoc();
                $idTerapia = $fila['idTerapia'];

                // Consulta para eliminar la vinculación en la tabla terapiaPaciente
                $sqlEliminarTerapiaPaciente = "DELETE FROM terapiaPaciente 
                                                       WHERE correoElectronico = '$paciente' 
                                                       AND idTerapia = $idTerapia";

                // Ejecutar la consulta
                if ($conexion->query($sqlEliminarTerapiaPaciente) === TRUE) {
                    // Eliminación exitosa
                    $response = array('status' => 'success', 'message' => 'Terapia eliminada correctamente');
                } else {
                    // Error en la eliminación
                    $response = array('status' => 'error', 'message' => 'Error al eliminar la terapia: ' . $conexion->error);
                }
            } else {
                // No se encontró la terapia con el nombre proporcionado
                $response = array('status' => 'error', 'message' => 'No se encontró la terapia con el nombre proporcionado');
            }
            break;

        default:
            // Manejar el caso por defecto (si la función no coincide con ninguna)
            break;
    }

    // Enviar la respuesta como JSON
    header('Content-Type: application/json');
    echo json_encode($response);

    // Cerrar la conexión
    $conexion->close();
}

