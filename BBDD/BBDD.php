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

            // Crear las tablas
            crearTablaSiNoExiste($conexion, "Profesionales", "
                id INT AUTO_INCREMENT PRIMARY KEY,
                nombre VARCHAR(255),
                especialidad VARCHAR(255),
                experiencia INT,
                ubicacion VARCHAR(100),
                telefono VARCHAR(15)
            ");

            crearTablaSiNoExiste($conexion, "Pacientes", "
                id INT AUTO_INCREMENT PRIMARY KEY,
                nombre VARCHAR(255),
                apellido VARCHAR(255),
                edad INT,
                genero VARCHAR(10),
                fecha_registro DATE
            ");

            crearTablaSiNoExiste($conexion, "Testimonios", "
                id INT AUTO_INCREMENT PRIMARY KEY,
                nombre_cliente VARCHAR(255),
                comentario TEXT,
                calificacion INT,
                fecha_testimonio DATE
            ");

            crearTablaSiNoExiste($conexion, "Chat", "
                id INT AUTO_INCREMENT PRIMARY KEY,
                usuario VARCHAR(255),
                mensaje TEXT,
                fecha_envio DATETIME,
                leido BOOLEAN
            ");

            crearTablaSiNoExiste($conexion, "Descuentos", "
                id INT AUTO_INCREMENT PRIMARY KEY,
                porcentaje INT,
                descripcion TEXT,
                codigo_descuento VARCHAR(20),
                fecha_expiracion DATE
            ");

            crearTablaSiNoExiste($conexion, "Terapias", "
                id INT AUTO_INCREMENT PRIMARY KEY,
                nombre VARCHAR(255),
                descripcion TEXT,
                duracion INT,
                costo DECIMAL(10,2),
                tipo VARCHAR(20)
            ");

            crearTablaSiNoExiste($conexion, "Medicamentos", "
                id INT AUTO_INCREMENT PRIMARY KEY,
                nombre VARCHAR(255),
                descripcion TEXT,
                tipo VARCHAR(50),
                dosis VARCHAR(20),
                efectos_secundarios TEXT
            ");

            // Insertar datos de ejemplo si no existen
            if (!existenDatosEjemplo($conexion)) {
                insertarDatosEjemplo($conexion);
            }

            // Añadir las relaciones a la tabla Medicamentos
            $conexion->query("ALTER TABLE Medicamentos ADD COLUMN terapia_id INT");
            $conexion->query("ALTER TABLE Medicamentos ADD FOREIGN KEY (terapia_id) REFERENCES Terapias(id)");

            $conexion->query("ALTER TABLE Medicamentos ADD COLUMN profesional_id INT");
            $conexion->query("ALTER TABLE Medicamentos ADD FOREIGN KEY (profesional_id) REFERENCES Profesionales(id)");

            $conexion->query("ALTER TABLE Medicamentos ADD COLUMN paciente_id INT");
            $conexion->query("ALTER TABLE Medicamentos ADD FOREIGN KEY (paciente_id) REFERENCES Pacientes(id)");

            // Añadir las relaciones a la tabla Pacientes
            $conexion->query("ALTER TABLE Pacientes ADD COLUMN testimonio_id INT");
            $conexion->query("ALTER TABLE Pacientes ADD FOREIGN KEY (testimonio_id) REFERENCES Testimonios(id)");

            $conexion->query("ALTER TABLE Pacientes ADD COLUMN chat_id INT");
            $conexion->query("ALTER TABLE Pacientes ADD FOREIGN KEY (chat_id) REFERENCES Chat(id)");

            $conexion->query("ALTER TABLE Pacientes ADD COLUMN descuento_id INT");
            $conexion->query("ALTER TABLE Pacientes ADD FOREIGN KEY (descuento_id) REFERENCES Descuentos(id)");

            // Añadir las relaciones a la tabla Testimonios
            $conexion->query("ALTER TABLE Testimonios ADD COLUMN paciente_id INT");
            $conexion->query("ALTER TABLE Testimonios ADD FOREIGN KEY (paciente_id) REFERENCES Pacientes(id)");

            // Añadir las relaciones a la tabla Chat
            $conexion->query("ALTER TABLE Chat ADD COLUMN profesional_id INT");
            $conexion->query("ALTER TABLE Chat ADD FOREIGN KEY (profesional_id) REFERENCES Profesionales(id)");

            $conexion->query("ALTER TABLE Chat ADD COLUMN paciente_id INT");
            $conexion->query("ALTER TABLE Chat ADD FOREIGN KEY (paciente_id) REFERENCES Pacientes(id)");

            // Añadir las relaciones a la tabla Descuentos
            $conexion->query("ALTER TABLE Descuentos ADD COLUMN paciente_id INT");
            $conexion->query("ALTER TABLE Descuentos ADD FOREIGN KEY (paciente_id) REFERENCES Pacientes(id)");

            // Añadir las relaciones a la tabla Terapias
            $conexion->query("ALTER TABLE Terapias ADD COLUMN paciente_id INT");
            $conexion->query("ALTER TABLE Terapias ADD FOREIGN KEY (paciente_id) REFERENCES Pacientes(id)");

            // Añadir las relaciones a la tabla Profesionales
            $conexion->query("ALTER TABLE Profesionales ADD COLUMN testimonio_id INT, ADD FOREIGN KEY (testimonio_id) REFERENCES Testimonios(id)");
            $conexion->query("ALTER TABLE Profesionales ADD COLUMN chat_id INT, ADD FOREIGN KEY (chat_id) REFERENCES Chat(id)");
            $conexion->query("ALTER TABLE Profesionales ADD COLUMN descuento_id INT, ADD FOREIGN KEY (descuento_id) REFERENCES Descuentos(id)");
            $conexion->query("ALTER TABLE Profesionales ADD COLUMN terapia_id INT, ADD FOREIGN KEY (terapia_id) REFERENCES Terapias(id)");

            echo "<br>Proceso de creación completado<br>";
        } else {
            echo "Error al crear la base de datos: " . $conexion->error;
        }
    } else {
        echo "La base de datos '$base_datos' ya existe, no se realizaron cambios<br>";
    }

    // Función para crear una tabla si no existe
    function crearTablaSiNoExiste($conexion, $nombreTabla, $columnas)
    {
        // Verificar si la tabla ya existe
        $resultado = $conexion->query("SHOW TABLES LIKE '$nombreTabla'");
        if ($resultado->num_rows == 0) {
            // La tabla no existe, crearla
            $sql = "CREATE TABLE $nombreTabla ($columnas)";
            $conexion->query($sql);

            echo "<br>La tabla '$nombreTabla' creada correctamente<br>";
        } else {
            echo "<br>La tabla '$nombreTabla' ya existe<br>";
        }
    }

    //Función para verificar si existen datos de ejemplo
    function existenDatosEjemplo($conexion)
    {
        // Verificar si ya existen datos en una de las tablas de ejemplo (por ejemplo, Testimonios)
        $resultado = $conexion->query("SELECT * FROM Testimonios LIMIT 1");

        return $resultado->num_rows > 0;
    }

    //Función para insertar datos de ejemplo
    function insertarDatosEjemplo($conexion)
    {
        // Insertar datos en Testimonios
        $conexion->query("INSERT INTO Testimonios (nombre_cliente, comentario, calificacion, fecha_testimonio) VALUES
        ('Aaron', 'Terminé la terapia de luz con una mejora en mi ánimo y energía, aliviando los síntomas de mi trastorno.', 5, '2022-06-01'),
        ('Ángela', 'La psicoterapia transformó mi vida al proporcionarme un espacio para explorar y comprender mis emociones.', 4, '2022-06-02'),
        ('Vicky', 'Después de concluir mi terapia de luz, experimenté una transformación increíble en mi estado de ánimo.', 5, '2022-06-03'),
        ('Javier', 'La TMS mejoró mi claridad mental y agudeza cognitiva, ofreciendo una innovadora forma de cuidar mi cuerpo.', 4, '2022-06-04'),
        ('Carlos', 'Servicio excepcional', 5, '2022-06-05')");

        // Insertar datos en Chat
        $conexion->query("INSERT INTO Chat (usuario, mensaje, fecha_envio, leido) VALUES
        ('Usuario 1', 'Hola, ¿cómo estás?', '2022-06-01 08:00:00', true),
        ('Usuario 2', 'Estoy interesado en obtener más información', '2022-06-02 09:30:00', false),
        ('Usuario 3', '¿Cuáles son los horarios disponibles?', '2022-06-03 11:45:00', true),
        ('Usuario 4', '¿Ofrecen servicios en línea?', '2022-06-04 13:15:00', false),
        ('Usuario 5', 'Gracias por la ayuda', '2022-06-05 15:20:00', true)");

        // Insertar datos en Descuentos
        $conexion->query("INSERT INTO Descuentos (porcentaje, descripcion, codigo_descuento, fecha_expiracion) VALUES
        (10.00, 'Descuento para nuevos clientes', 'NUEVO10', '2022-07-01'),
        (5.50, 'Descuento por pago adelantado', 'ADEL5', '2022-07-15'),
        (15.75, 'Descuento por referir a un amigo', 'REFER15', '2022-08-01'),
        (8.25, 'Descuento en la segunda sesión', 'SESION8', '2022-08-15'),
        (12.50, 'Descuento para estudiantes', 'ESTUDIANTE12', '2022-09-01')");

        // Insertar datos en Profesionales
        $conexion->query("INSERT INTO Profesionales (nombre, especialidad, experiencia, ubicacion, telefono) VALUES
        ('Dr. Smith', 'Psicología', 15, 'Ciudad X', '123-456-7890'),
        ('Dra. Johnson', 'Psiquiatría', 20, 'Ciudad Y', '987-654-3210'),
        ('Lic. Brown', 'Terapia Familiar', 10, 'Ciudad Z', '555-555-5555'),
        ('Psic. Davis', 'Terapia Infantil', 8, 'Ciudad W', '111-222-3333'),
        ('Dr. Wilson', 'Psicoterapia', 12, 'Ciudad V', '999-888-7777')");

        // Insertar datos en Medicamentos
        $conexion->query("INSERT INTO Medicamentos (nombre, descripcion, tipo, dosis, efectos_secundarios) VALUES
        ('Medicamento A', 'Descripción del Medicamento A', 'Analgésico', '10mg', 'Somnolencia, Mareos'),
        ('Medicamento B', 'Descripción del Medicamento B', 'Antidepresivo', '20mg', 'Náuseas, Insomnio'),
        ('Medicamento C', 'Descripción del Medicamento C', 'Ansiolítico', '5mg', 'Fatiga, Boca Seca'),
        ('Medicamento D', 'Descripción del Medicamento D', 'Antipsicótico', '15mg', 'Aumento de Peso, Temblores'),
        ('Medicamento E', 'Descripción del Medicamento E', 'Hipnótico', '30mg', 'Sedación, Confusión')");

        // Insertar datos en Pacientes
        $conexion->query("INSERT INTO Pacientes (nombre, apellido, edad, genero, fecha_registro) VALUES
        ('Juan', 'Pérez', 30, 'Masculino', '2022-01-01'),
        ('María', 'Gómez', 25, 'Femenino', '2022-02-15'),
        ('Carlos', 'Rodríguez', 35, 'Masculino', '2022-03-20'),
        ('Ana', 'Martínez', 28, 'Femenino', '2022-04-10'),
        ('Pedro', 'Sánchez', 40, 'Masculino', '2022-05-05')");
    }

    // Cerrar la conexión
    $conexion->close();
    ?>
