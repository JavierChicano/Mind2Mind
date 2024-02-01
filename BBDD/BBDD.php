<?php
// Configuración de la conexión a la base de datos
$host = "localhost";
$usuario = "root";
$contrasena = "";
$base_datos = "mind2mind";

$conexion = new mysqli($host, $usuario, $contrasena);

// Verificar la conexión
if ($conexion->connect_error) {
    die("La conexión a la base de datos ha fallado: " . $conexion->connect_error);
}

// Verificar si la base de datos ya existe
$resultado = $conexion->query("SHOW DATABASES LIKE '$base_datos'");
if ($resultado->num_rows == 0) {
    // La base de datos no existe, crearla
    $sql = "CREATE DATABASE $base_datos";
    if ($conexion->query($sql) === TRUE) {
        echo "Base de datos '$base_datos' creada correctamente<br>";

        // Seleccionar la base de datos
        $conexion->select_db($base_datos);

        // Paciente
        $sqlPaciente = "CREATE TABLE paciente (
            correoElectronico VARCHAR(255) PRIMARY KEY,
            nombre VARCHAR(255),
            apellidos VARCHAR(255),
            contraseña VARCHAR(255),
            dni VARCHAR(9) UNIQUE,
            provincia VARCHAR(255),
            domicilio VARCHAR(255),
            genero VARCHAR(255)
        )";

        // Especialista
        $sqlEspecialista = "CREATE TABLE especialista (
            idMedico INT AUTO_INCREMENT PRIMARY KEY,
            nombre VARCHAR(255),
            apellidos VARCHAR(255),
            modalidad VARCHAR(255),
            horario VARCHAR(255),
            especialidad VARCHAR(255),
            ubicacion VARCHAR(255)
        )";

        // Terapia
        $sqlTerapia = "CREATE TABLE terapia (
            idTerapia INT AUTO_INCREMENT PRIMARY KEY,
            nombre VARCHAR(255),
            coste DECIMAL(10, 2)
        )";

        // Codigo de Descuento
        $sqlDescuento = "CREATE TABLE descuento (
            codDescuento VARCHAR(7) PRIMARY KEY,
            nombre VARCHAR(255),
            fecha DATE
        )";

        // Cita
        $sqlCita = "CREATE TABLE cita (
            idCita INT AUTO_INCREMENT PRIMARY KEY,
            especialidad VARCHAR(255),
            modalidad VARCHAR(255),
            numeroTelefono INT(9),
            diagnostico VARCHAR(255),
            fecha DATE,
            aseguradoraNombre VARCHAR(255),
            correoElectronico VARCHAR(255),
            idMedico INT,
            FOREIGN KEY (correoElectronico) REFERENCES paciente(correoElectronico),
            FOREIGN KEY (idMedico) REFERENCES especialista(idMedico)
        )";

        // Terapia Paciente
        $sqlTerapiaPaciente = "CREATE TABLE terapiaPaciente (
            idTerapiaPaciente INT AUTO_INCREMENT PRIMARY KEY,
            correoElectronico VARCHAR(255),
            idTerapia INT,
            FOREIGN KEY (correoElectronico) REFERENCES paciente(correoElectronico),
            FOREIGN KEY (idTerapia) REFERENCES terapia(idTerapia)
        )";

        // Terapia Descuento
        $sqlTerapiaDescuento = "CREATE TABLE terapiaDescuento (
            idTerapiaDescuento INT AUTO_INCREMENT PRIMARY KEY,
            idTerapia INT,
            codDescuento VARCHAR(7),
            FOREIGN KEY (idTerapia) REFERENCES terapia(idTerapia),
            FOREIGN KEY (codDescuento) REFERENCES descuento(codDescuento)
        )";

        // Testimonio
        $sqlTestimonio = "CREATE TABLE testimonio (
            idTestimonio INT AUTO_INCREMENT PRIMARY KEY,
            comentario VARCHAR(255),
            calificacion VARCHAR(255),
            fecha DATE,
            correoElectronico VARCHAR(255),
            FOREIGN KEY (correoElectronico) REFERENCES paciente(correoElectronico)
        )";

        // Chat
        $sqlChat = "CREATE TABLE chat (
            idChat INT AUTO_INCREMENT PRIMARY KEY,
            usuario VARCHAR(255),
            mensaje VARCHAR(255),
            fecha DATE,
            correoElectronico VARCHAR(255),
            idMedico INT,
            FOREIGN KEY (correoElectronico) REFERENCES paciente(correoElectronico),
            FOREIGN KEY (idMedico) REFERENCES especialista(idMedico)
        )";

        // Ejecutar las consultas
        $conexion->query($sqlPaciente);
        $conexion->query($sqlEspecialista);
        $conexion->query($sqlTerapia);
        $conexion->query($sqlDescuento);
        $conexion->query($sqlCita);
        $conexion->query($sqlTerapiaPaciente);
        $conexion->query($sqlTerapiaDescuento);
        $conexion->query($sqlTestimonio);
        $conexion->query($sqlChat);

        // Función para insertar un paciente
        function insertarPaciente($conexion, $correo, $nombre, $apellidos, $contrasena, $dni, $provincia, $domicilio, $genero) {
            $sql = "INSERT INTO paciente (correoElectronico, nombre, apellidos, contraseña, dni, provincia, domicilio, genero)
                    VALUES ('$correo', '$nombre', '$apellidos', '$contrasena', '$dni', '$provincia', '$domicilio', '$genero')";

            if ($conexion->query($sql)) {
                echo "Datos insertados correctamente, en paciente.";
            } else {
                echo "Error al insertar datos, en pacientes: " . $conexion->error;
            }

            return true;
        }

        // Función para insertar un especialista
        function insertarEspecialista($conexion, $nombre, $apellidos, $modalidad, $horario, $especialidad, $ubicacion) {
            $sql = "INSERT INTO especialista (nombre, apellidos, modalidad, horario, especialidad, ubicacion)
                    VALUES ('$nombre', '$apellidos', '$modalidad', '$horario', '$especialidad', '$ubicacion')";

            if ($conexion->query($sql) === TRUE) {
                echo "Datos insertados correctamente, en especialista.<br>";
            } else {
                die("Error al insertar datos, en especialista: " . $conexion->error);
            }

            return true;
        }

        // Insertar datos de especialistas
        insertarEspecialista($conexion, "Dr. Garcia", "Vaquero", "Presencial", "Tardes", "Diagnósticos", "Hospital XYZ");
        insertarEspecialista($conexion, "Dr. Flor", "Esgueva", "Virtual", "Mañanas", "Tratamientos", "Clínica ABC");
        insertarEspecialista($conexion, "Dr. Don", "Simón", "Presencial", "Tardes", "Medicamentos", "Hospital ABC");
        insertarEspecialista($conexion, "Dra. Häagen", "Dazs", "Virtual", "Tardes", "Terapias", "Clínica XYZ");
        insertarEspecialista($conexion, "Dra. María", "Gutierrez", "Presencial", "Todo el día", "Consultora", "Hospital XYZ");

        // Función para insertar una terapia
        function insertarTerapia($conexion, $nombre, $coste) {
            $sql = "INSERT INTO terapia (nombre, coste)
                    VALUES ('$nombre', '$coste')";

            if ($conexion->query($sql)) {
                echo "Datos insertados correctamente, en terapia.";
            } else {
                echo "Error al insertar datos, en terapia: " . $conexion->error;
            }

            return true;
        }

        // Insertar datos de terapia
        insertarTerapia($conexion, "Terapia de Estimulación Transcraneal", "50.00");
        insertarTerapia($conexion, "Terapia de Luz", "75.00");
        insertarTerapia($conexion, "Terapia Electroconvulsiva", "60.00");
        insertarTerapia($conexion, "Psicoterapia", "65.00");
        insertarTerapia($conexion, "Mindfulness", "55.00");
        insertarTerapia($conexion, "Arte Expresivo", "80.00");

        // Función para insertar un código de descuento
        function insertarDescuento($conexion, $codDescuento, $nombre, $fecha) {
            $sql = "INSERT INTO descuento (codDescuento, nombre, fecha)
                    VALUES ('$codDescuento', '$nombre', '$fecha')";

            if ($conexion->query($sql)) {
                echo "Datos insertados correctamente, en descuento.";
            } else {
                echo "Error al insertar datos, en descuento: " . $conexion->error;
            }

            return true;
        }

        // Insertar datos de descuento
        insertarDescuento($conexion, "123456A", "Descuento 1", "12/02/24");
        insertarDescuento($conexion, "123456B", "Descuento 2", "20/10/24");
        insertarDescuento($conexion, "123456C", "Descuento 3", "02/08/23");

        // Función para insertar un testimonio
        function insertarTestimonio($conexion, $comentario, $calificacion, $fecha, $correoElectronico) {
            $sql = "INSERT INTO testimonio (comentario, calificacion, fecha, correoElectronico)
                    VALUES ('$comentario', '$calificacion', '$fecha', '$correoElectronico')";

            if ($conexion->query($sql)) {
                echo "Datos insertados correctamente, en testimonio.";
            } else {
                echo "Error al insertar datos, en pacientes: " . mysqli_error($conexion);
            }

            return true;
        }

        // Función para insertar un mensaje de chat
        function insertarChat($conexion, $usuario, $mensaje, $fecha, $correoElectronico, $idMedico) {
            $sql = "INSERT INTO chat (usuario, mensaje, fecha, correoElectronico, idMedico)
                    VALUES ('$usuario', '$mensaje', '$fecha', '$correoElectronico', $idMedico)";

            if ($conexion->query($sql)) {
                echo "Datos insertados correctamente, en chat.";
            } else {
                echo "Error al insertar datos, en pacientes: " . mysqli_error($conexion);
            }

            return true;
        }





        echo "Tablas creadas correctamente";
    } else {
        echo "Error al crear la base de datos: " . $conexion->error;
    }
}
echo "La BBDD ".$base_datos." ya existe";

// Cerrar la conexión
$conexion->close();
?>