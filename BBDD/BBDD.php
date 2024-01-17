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

        // Crear la tabla Testimonios
        crearTablaSiNoExiste($conexion, "Testimonios", "
            id INT AUTO_INCREMENT PRIMARY KEY,
            nombre_cliente VARCHAR(255),
            comentario TEXT,
            calificacion INT,
            fecha_testimonio DATE
        ");

        // Crear la tabla Chat
        crearTablaSiNoExiste($conexion, "Chat", "
            id INT AUTO_INCREMENT PRIMARY KEY,
            usuario VARCHAR(255),
            mensaje TEXT,
            fecha_envio DATETIME,
            leido BOOLEAN
        ");

        // Crear la tabla Descuentos
        crearTablaSiNoExiste($conexion, "Descuentos", "
            id INT AUTO_INCREMENT PRIMARY KEY,
            porcentaje DECIMAL(5,2),
            descripcion TEXT,
            codigo_descuento VARCHAR(20),
            fecha_expiracion DATE
        ");

        // Crear la tabla Profesionales
        crearTablaSiNoExiste($conexion, "Profesionales", "
            id INT AUTO_INCREMENT PRIMARY KEY,
            nombre VARCHAR(255),
            especialidad VARCHAR(255),
            experiencia INT,
            ubicacion VARCHAR(100),
            telefono VARCHAR(15)
        ");

        // Crear la tabla Medicamentos
        crearTablaSiNoExiste($conexion, "Medicamentos", "
            id INT AUTO_INCREMENT PRIMARY KEY,
            nombre VARCHAR(255),
            descripcion TEXT,
            tipo VARCHAR(50),
            dosis VARCHAR(20),
            efectos_secundarios TEXT
        ");

        // Crear la tabla Pacientes
        crearTablaSiNoExiste($conexion, "Pacientes", "
            id INT AUTO_INCREMENT PRIMARY KEY,
            nombre VARCHAR(255),
            apellido VARCHAR(255),
            edad INT,
            genero VARCHAR(10),
            fecha_registro DATE,
            testimonio_id INT,
            chat_id INT,
            FOREIGN KEY (testimonio_id) REFERENCES Testimonios(id),
            FOREIGN KEY (chat_id) REFERENCES Chat(id)
        ");

        // Crear la tabla Terapias
        crearTablaSiNoExiste($conexion, "Terapias", "
            id INT AUTO_INCREMENT PRIMARY KEY,
            nombre VARCHAR(255),
            descripcion TEXT,
            duracion INT,
            costo DECIMAL(10,2),
            tipo VARCHAR(20),
            paciente_id INT,
            FOREIGN KEY (paciente_id) REFERENCES Pacientes(id)
        ");

        // Insertar datos de ejemplo
        insertarDatosEjemplo($conexion);

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

// Función para insertar datos de ejemplo
function insertarDatosEjemplo($conexion)
{
    // Insertar datos en Testimonios
    $conexion->query("INSERT INTO Testimonios (nombre_cliente, comentario, calificacion, fecha_testimonio) VALUES 
        ('Cliente 1', 'Excelente experiencia con el servicio', 5, '2022-06-01'),
        ('Cliente 2', 'Muy satisfecho con los resultados', 4, '2022-06-02'),
        ('Cliente 3', 'Recomendaría a todos mis conocidos', 5, '2022-06-03'),
        ('Cliente 4', 'Profesionales altamente cualificados', 4, '2022-06-04'),
        ('Cliente 5', 'Servicio excepcional', 5, '2022-06-05')");

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
    $conexion->query("INSERT INTO Pacientes (nombre, apellido, edad, genero, fecha_registro, testimonio_id, chat_id) VALUES 
        ('Juan', 'Pérez', 30, 'Masculino', '2022-01-01', 1, 1),
        ('María', 'Gómez', 25, 'Femenino', '2022-02-15', 2, 2),
        ('Carlos', 'Rodríguez', 35, 'Masculino', '2022-03-20', 3, 3),
        ('Ana', 'Martínez', 28, 'Femenino', '2022-04-10', 4, 4),
        ('Pedro', 'Sánchez', 40, 'Masculino', '2022-05-05', 5, 5)");

    // Insertar datos en Terapias
    $conexion->query("INSERT INTO Terapias (nombre, descripcion, duracion, costo, tipo, paciente_id) VALUES 
        ('Terapia A', 'Descripción de la Terapia A', 60, 80.00, 'Individual', 1),
        ('Terapia B', 'Descripción de la Terapia B', 45, 60.00, 'Grupal', 2),
        ('Terapia C', 'Descripción de la Terapia C', 90, 100.00, 'Parejas', 3),
        ('Terapia D', 'Descripción de la Terapia D', 75, 90.00, 'Familiar', 4),
        ('Terapia E', 'Descripción de la Terapia E', 60, 75.00, 'Individual', 5)");
}

// Cerrar la conexión
$conexion->close();
?>
