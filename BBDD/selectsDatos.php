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

            // Consulta SQL para verificar si el correo electrónico existe y la contraseña coincide
            $sql = "SELECT * FROM paciente WHERE correoElectronico = '$email'";
            $resultado = $conexion->query($sql);

            if ($resultado->num_rows > 0) {
                // El correo electrónico existe, ahora verificamos la contraseña
                $row = $resultado->fetch_assoc();
                if ($row['contraseña'] == $password) {
                    // El correo electrónico y la contraseña coinciden, el usuario ha iniciado sesión con éxito
                    $response = array('status' => 'success', 'message' => 'Inicio de sesión exitoso');
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
            $doctorIndex = $_POST['idDoctor'];

            // Consultar la base de datos para obtener la información del doctor
            $sql = "SELECT * FROM especialista LIMIT 1 OFFSET $doctorIndex";
            $resultado = $conexion->query($sql);

            if ($resultado->num_rows > 0) {
                // Si se encuentran datos del doctor, asignar la respuesta de éxito a la variable $response
                $doctor = $resultado->fetch_assoc();
                $response = array('status' => 'success', 'doctor' => $doctor);
            } else {
                // Si no se encuentra el doctor, asignar la respuesta de error a la variable $response
                $response = array('status' => 'error', 'message' => 'Doctor no encontrado');
            }
            break;

        case 'editarPerfil':
            $email = $_POST['email'];
            // Consulta SQL para verificar si el correo electrónico existe y la contraseña coincide
            $sql = "SELECT * FROM paciente WHERE correoElectronico = '$email'";
            $resultado = $conexion->query($sql);

            if ($resultado->num_rows > 0) {
                $paciente = $resultado->fetch_assoc();
                $response = array('status' => 'success', 'paciente' => $paciente);
            } else {
                // No se encontró el correo electrónico
                $response = array('status' => 'error', 'message' => 'Error en la consulta');
            }
            break;
        case 'mostrarPaciente':
            $email = $_POST['email'];
            // Consulta SQL para verificar si el correo electrónico existe y la contraseña coincide
            $sql = "SELECT * FROM paciente WHERE correoElectronico = '$email'";
            $resultado = $conexion->query($sql);

            if ($resultado->num_rows > 0) {
                $paciente = $resultado->fetch_assoc();
                $response = array('status' => 'success', 'paciente' => $paciente);
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
            
            
    }

    // Enviar la respuesta como JSON
    header('Content-Type: application/json');
    echo json_encode($response);

    // Cerrar la conexión
    $conexion->close();
}

