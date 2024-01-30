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
    $nombre = $apellidos = $email = $password = $dni = $telefono = $provincia = $domicilio = $sexo = $profesional = $modalidad = $diagnostico = $fConsulta = $poseeAseguradora = $aseguradora = "";

    switch ($funcion) {
        case 'registro':
            $nombre = $_POST['nombre'];
            $apellidos = $_POST['apellidos'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Preparar la consulta de inserción para el caso de registro
            $sql = "INSERT INTO Pacientes (nombre, apellidos, email, contraseña) VALUES ('$nombre', '$apellidos', '$email', '$password')";
            break;

        case 'login':
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Preparar la consulta de inserción para el caso de login
            $sql = "INSERT INTO Pacientes (email, contraseña) VALUES ('$email', '$password')";
            break;

        case 'contacto':
            $nombre = $_POST['nombre'];
            $apellidos = $_POST['apellidos'];
            $email = $_POST['email'];
            $telefono = $_POST['telefono'];

            // Preparar la consulta de inserción para el caso de contacto
            $sql = "INSERT INTO Pacientes (nombre, apellidos, email, telefono) VALUES ('$nombre', '$apellidos', '$email', '$telefono')";
            break;

        case 'pedirCita':
            $nombre = $_POST['nombre'];
            $apellidos = $_POST['apellidos'];
            $dni = $_POST['dni'];
            $mail = $_POST['mail'];
            $provincia = $_POST['provincia'];
            $domicilio = $_POST['domicilio'];
            $sexo = $_POST['sexo'];
            $profesional = $_POST['profesional'];
            $modalidad = $_POST['modalidad'];
            $telefono = $_POST['telefono'];
            $diagnostico = $_POST['diagnostico'];
            $fConsulta = $_POST['fConsulta'];
            $poseeAseguradora = $_POST['poseeAseguradora'];
            $aseguradora = $_POST['aseguradora'];

            // Preparar la consulta de inserción para el caso de pedir cita
            $sql = "INSERT INTO Pacientes (nombre, apellidos, dni, mail, provincia, domicilio, sexo, profesional, modalidad, telefono, diagnostico, fConsulta, poseeAseguradora, aseguradora) 
            VALUES ('$nombre', '$apellidos', '$dni', '$mail', '$provincia', '$domicilio', '$sexo', '$profesional', '$modalidad', '$telefono', '$diagnostico', '$fConsulta', '$poseeAseguradora', '$aseguradora')";
            break;

        default:
            // Manejar el caso por defecto (si la función no coincide con ninguna)
            break;
    }

    // Ejecutar la consulta
    if ($conexion->query($sql) === TRUE) {
        echo "Registro insertado correctamente";
    } else {
        echo "Error al insertar el registro: " . $conexion->error;
    }

    // Cerrar la conexión
    $conexion->close();
}

