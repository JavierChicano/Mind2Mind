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

            // Resto del código...

            break;
    }

    // Enviar la respuesta como JSON
    header('Content-Type: application/json');
    echo json_encode($response);

    // Cerrar la conexión
    $conexion->close();
}

