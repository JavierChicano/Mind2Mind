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
    }

    // Enviar la respuesta como JSON
    header('Content-Type: application/json');
    echo json_encode($response);

    // Cerrar la conexión
    $conexion->close();
}

