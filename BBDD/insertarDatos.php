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
            $sql = "INSERT INTO paciente (correoElectronico, nombre, apellidos, contraseña) VALUES ('$email', '$nombre', '$apellidos', '$password')";
            break;

        case 'login':
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Consulta SQL para verificar si el correo electrónico existe y la contraseña coincide
            $sql = "SELECT * FROM paciente WHERE correoElectronico = '$email' AND contraseña = '$password'";
            $resultado = $conexion->query($sql);

            if ($resultado->num_rows > 0) {
                // El correo electrónico y la contraseña coinciden, el usuario ha iniciado sesión con éxito
                echo "Inicio de sesión exitoso\n";
            } else {
                // No se encontró el correo electrónico o la contraseña no coincide
                echo "Inicio de sesión fallido\n";
            }
            break;

        case 'contacto':
            $nombre = $_POST['nombre'];
            $apellidos = $_POST['apellidos'];
            $email = $_POST['email'];
            $telefono = $_POST['telefono'];

            // Preparar la consulta de inserción para el caso de contacto
            $sql = "INSERT INTO paciente (correoElectronico, nombre, apellidos, telefono) VALUES ('$email','$nombre', '$apellidos', '$telefono')";
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

            // Insertar datos en la tabla paciente
            $sqlPaciente = "INSERT INTO paciente (correoElectronico, nombre, apellidos, dni, provincia, domicilio, genero) 
                            VALUES ('$mail', '$nombre', '$apellidos', '$dni', '$provincia', '$domicilio', '$sexo')";
            
            if ($conexion->query($sqlPaciente) === TRUE) {
                // Obtener el ID insertado (correoElectronico) para usarlo en la tabla cita
                $idPaciente = $mail;
                
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
                        echo "Datos insertados correctamente en paciente y cita";
                    } else {
                        echo "Error al insertar datos en cita: " . $conexion->error;
                    }
                } else {
                    echo "No se encontró un médico con la modalidad y especialidad proporcionadas";
                }
            } else {
                echo "Error al insertar datos en paciente: " . $conexion->error;
            }
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

