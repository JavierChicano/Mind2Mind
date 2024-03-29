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
            codigo_postal INT(5),
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
            ubicacion VARCHAR(255),
            imagen VARCHAR(255)
        )";

        // Terapia
        $sqlTerapia = "CREATE TABLE terapia (
            idTerapia INT AUTO_INCREMENT PRIMARY KEY,
            nombre VARCHAR(255),
            imagen VARCHAR (255),
            coste DECIMAL(10, 2),
            mini_descripcion VARCHAR(255),
            descripcion VARCHAR(1000),
            parte1 VARCHAR(1000),
            parte2 VARCHAR(1000),
            parte3 VARCHAR(1000),
            parte4 VARCHAR(1000),
            link_al_video VARCHAR(255)
        )";

        // Consulta
        $sqlConsulta = "CREATE TABLE consulta (
            correoElectronico VARCHAR(255) PRIMARY KEY,
            nombre VARCHAR(255),
            apellidos VARCHAR(255),
            telefono INT(9),
            consulta VARCHAR(255)
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
        $conexion->query($sqlConsulta);
        $conexion->query($sqlCita);
        $conexion->query($sqlTerapiaPaciente);
        $conexion->query($sqlTestimonio);
        $conexion->query($sqlChat);

    //Funcion que crea los admins
    function insertarAdmin($conexion, $correoElectronico, $nombre, $apellidos, $contraseña){
        $contraseñaHasheada = password_hash($contraseña, PASSWORD_DEFAULT);
        $sql = "INSERT INTO paciente (correoElectronico, nombre, apellidos, contraseña) VALUES ('$correoElectronico', '$nombre', '$apellidos', '$contraseñaHasheada')";
        if ($conexion->query($sql) === TRUE) {
            echo "Datos insertados correctamente, en paciente.<br>";
        } else {
            die("Error al insertar datos, en paciente: " . $conexion->error);
        }
        return true;
    }
    insertarAdmin($conexion, "admin1@doctor.com", "Dr. Garcia", "Vaquero", "admin1");
    insertarAdmin($conexion, "admin2@doctor.com", "Dr. Flor", "Esgueva", "admin2");
    insertarAdmin($conexion, "admin3@doctor.com", "Dr. Don", "Simón", "admin3");
    insertarAdmin($conexion, "admin4@doctor.com", "Dra. Häagen", "Dazs", "admin4");
    insertarAdmin($conexion, "admin5@doctor.com", "Dra. María", "Gutierrez", "admin5");

    //Insertar 10 pacientes como prueba
    insertarAdmin($conexion, "paciente1@paciente.com", "Juan", "Garcia", "paciente1");
    insertarAdmin($conexion, "paciente2@paciente.com", "Maria", "Martinez", "paciente2");
    insertarAdmin($conexion, "paciente3@paciente.com", "Carlos", "Sanchez", "paciente3");
    insertarAdmin($conexion, "paciente4@paciente.com", "Ana", "Lopez", "paciente4");
    insertarAdmin($conexion, "paciente5@paciente.com", "David", "Fernandez", "paciente5");
    insertarAdmin($conexion, "paciente6@paciente.com", "Laura", "Gonzalez", "paciente6");
    insertarAdmin($conexion, "paciente7@paciente.com", "Pedro", "Rodriguez", "paciente7");
    insertarAdmin($conexion, "paciente8@paciente.com", "Sofia", "Perez", "paciente8");
    insertarAdmin($conexion, "paciente9@paciente.com", "Javier", "Hernandez", "paciente9");
    insertarAdmin($conexion, "paciente10@paciente.com", "Elena", "Diaz", "paciente10");

     // Función para insertar un especialista
    function insertarEspecialista($conexion, $nombre, $apellidos, $modalidad, $horario, $especialidad, $ubicacion, $imagen)
    {
        $sql = "INSERT INTO especialista (nombre, apellidos, modalidad, horario, especialidad, ubicacion, imagen)
                VALUES ('$nombre', '$apellidos', '$modalidad', '$horario', '$especialidad', '$ubicacion', '$imagen')";

        if ($conexion->query($sql) === TRUE) {
            echo "Datos insertados correctamente, en especialista.<br>";
        } else {
            die("Error al insertar datos, en especialista: " . $conexion->error);
        }

        return true;
    }
    // Insertar datos de especialistas con imágenes
    insertarEspecialista($conexion, "Dr. Garcia", "Vaquero", "Presencial", "Tardes", "Psicólogo", "Mind 2 Mind", "1.png");
    insertarEspecialista($conexion, "Dr. Flor", "Esgueva", "Videoconsulta", "Mañanas", "Psicólogo", "Mind 2 Mind", "2.png");
    insertarEspecialista($conexion, "Dr. Don", "Simón", "Presencial", "Tardes", "Psiquiatra", "Mind 2 Mind", "4.png");
    insertarEspecialista($conexion, "Dra. Häagen", "Dazs", "Telefónica", "Tardes", "Psicólogo", "Mind 2 Mind", "5.png");
    insertarEspecialista($conexion, "Dra. María", "Gutierrez", "Telefónica", "Todo el día", "Psiquiatra", "Mind 2 Mind", "6.png");

     // Función para insertar una terapia
    function insertarTerapia($conexion, $nombre, $imagen, $coste, $miniDescripcion, $descripcion, $parte1, $parte2, $parte3, $parte4, $linkAlVideo)
    {
        $sql = "INSERT INTO terapia (nombre, imagen, coste, mini_descripcion, descripcion, parte1, parte2, parte3, parte4, link_al_video)
                VALUES ('$nombre', '$imagen', '$coste', '$miniDescripcion', '$descripcion', '$parte1', '$parte2', '$parte3', '$parte4', '$linkAlVideo')";

        if ($conexion->query($sql)) {
            echo "Datos insertados correctamente, en terapia.";
        } else {
            echo "Error al insertar datos, en terapia: " . $conexion->error;
        }

        return true;
    }


        // Insertar datos de terapia
        insertarTerapia($conexion, "Est. Transcraneal", "Estimulación_magnética_transcraneal.jpg", "249", "Explora las posibilidades transformadoras de la Estimulación Magnética Transcraneal, una vanguardia en el cuidado mental.", "Estimulación Neuronal No Invasiva: La Terapia de Estimulación Magnética Transcraneal (TMS) emplea pulsos magnéticos para activar áreas específicas del cerebro, regulando así la actividad neuronal asociada al trastorno bipolar. • Restauración del Equilibrio Neurotransmisor: La TMS busca equilibrar los neurotransmisores clave en el cerebro, como la serotonina y la dopamina, implicados en el control del estado de ánimo en pacientes con trastorno bipolar. • Modulación de Circuitos Cerebrales Disfuncionales: Al dirigirse a circuitos cerebrales específicos, como la corteza prefrontal dorsolateral, la TMS ayuda a regular la emotividad y la regulación emocional en pacientes con trastorno bipolar. • Promoción de la Plasticidad Cerebral: La TMS puede fomentar cambios duraderos en la actividad cerebral, fortaleciendo conexiones sinápticas y mejorando la función cerebral en el tratamiento del trastorno.", "Evaluación y Selección de Candidatos: Se lleva a cabo una evaluación exhaustiva inicial por parte de un profesional de la salud mental o neuropsiquiatra para determinar la idoneidad del paciente para la terapia de Estimulación Magnética Transcraneal (TMS). Durante esta fase, se revisa el historial médico y psiquiátrico del paciente, así como se realizan pruebas específicas para evaluar la respuesta potencial a la TMS.", "Personalización del Tratamiento: Una vez seleccionado como candidato adecuado, se personaliza el protocolo de tratamiento de TMS para el paciente. Esto incluye determinar la ubicación precisa de la estimulación en el cerebro, la intensidad de la estimulación y la duración de las sesiones, todo ello basado en las necesidades individuales del paciente y los hallazgos de la evaluación inicial.", "Sesiones de Estimulación: Las sesiones de TMS se llevan a cabo en un entorno clínico por un profesional capacitado. Durante la sesión, se aplica una serie de pulsos magnéticos a través de una bobina colocada sobre el cuero cabelludo del paciente. Estos pulsos inducen corrientes eléctricas en regiones específicas del cerebro, modulando la actividad neuronal y regulando los circuitos implicados en el trastorno bipolar.", "Seguimiento y Ajustes Terapéuticos: A lo largo del tratamiento, se realiza un seguimiento estrecho del progreso del paciente y se realizan ajustes según sea necesario. Esto puede implicar modificaciones en la ubicación de la estimulación, la intensidad de los pulsos magnéticos o la frecuencia de las sesiones, todo ello con el objetivo de optimizar la eficacia terapéutica y minimizar los efectos secundarios. La supervisión continua por parte del equipo médico garantiza un enfoque de tratamiento seguro y eficaz para el trastorno bipolar.", "https://www.youtube.com/embed/YMD81r3AB4E?si=VhLZkGCFntOUglux");
        insertarTerapia($conexion, "Terapia de Luz", "Terapia_de_luz.jpg", "579", "Sumérgete en la suave luz terapéutica que revitaliza mente y cuerpo, proporcionando un resplandor de esperanza en cada sesión.", "Función de la Terapia de Luz: La Terapia de Luz utiliza una fuente de luz brillante, similar a la luz natural del sol, para regular los ritmos circadianos y mejorar el estado de ánimo en pacientes con trastorno bipolar. Al exponerse a esta luz, se estimulan los receptores en la retina que envían señales al cerebro, regulando así los neurotransmisores asociados con el estado de ánimo, como la serotonina y la melatonina.", "Evaluación y Selección de Candidatos: Antes de iniciar la terapia, se realiza una evaluación exhaustiva para determinar la idoneidad del paciente y establecer un plan de tratamiento personalizado. Se revisan los síntomas, la historia clínica y cualquier contraindicación médica que pueda influir en la respuesta del paciente a la terapia de luz.", "Personalización del Tratamiento: Basándose en la evaluación inicial, se personaliza el protocolo de tratamiento para cada paciente. Esto incluye determinar la intensidad, duración y frecuencia óptimas de la exposición a la luz, así como la elección del momento del día más beneficioso para la terapia.", "Sesiones de Terapia de Luz: Durante las sesiones de terapia, el paciente se sienta frente a una caja de luz especial que emite una intensidad de al menos 10,000 lux. La duración de la sesión y la distancia del paciente a la fuente de luz se ajustan según las recomendaciones del profesional de la salud, generalmente entre 20 a 60 minutos al día, preferiblemente por la mañana.", "Seguimiento y Ajustes Terapéuticos: A lo largo del tratamiento, se realiza un seguimiento cercano del progreso del paciente y se realizan ajustes en el protocolo de tratamiento según sea necesario. Esto puede implicar cambios en la duración o la intensidad de las sesiones de luz, así como la incorporación de otras intervenciones terapéuticas complementarias para maximizar los beneficios terapéuticos.", "https://www.youtube.com/embed/7vAhnwQiR44?si=XnzOpUmYbSh1Kkig");
        insertarTerapia($conexion, "Electroconvulsiva", "Electroconvulsiva.jpg", "999", "Explora la Terapia Electroconvulsiva para mejorar tu bienestar mental, aprovechando cada sesión para avanzar hacia una vida más equilibrada.", "Función de la Terapia Electroconvulsiva: La Terapia Electroconvulsiva (TEC) es un tratamiento que induce convulsiones controladas mediante estimulación eléctrica para aliviar los síntomas graves del trastorno bipolar, especialmente la depresión resistente al tratamiento. Aunque su mecanismo exacto de acción no se comprende completamente, se cree que la TEC afecta la actividad neuronal y los neurotransmisores en el cerebro, ayudando a restablecer el equilibrio químico y la función cerebral.", "Evaluación y Selección de Candidatos: Antes de someterse a la TEC, el paciente se somete a una evaluación exhaustiva para evaluar su idoneidad y minimizar los riesgos asociados con el procedimiento. Se revisan los antecedentes médicos, los síntomas actuales y cualquier contraindicación que pueda influir en la respuesta del paciente al tratamiento.", "Preparación y Anestesia: Antes de cada sesión de TEC, el paciente recibe anestesia general para garantizar su comodidad y seguridad durante el procedimiento. Se le administra también un relajante muscular para prevenir lesiones durante las convulsiones inducidas.", "Aplicación de Estimulación Eléctrica: Durante la sesión de TEC, se colocan electrodos en el cuero cabelludo del paciente y se administra una corriente eléctrica breve y controlada para inducir una convulsión generalizada. La estimulación eléctrica altera temporalmente la actividad neuronal y los neurotransmisores en el cerebro, lo que se cree que contribuye a los efectos terapéuticos de la TEC en el trastorno bipolar.", "Monitoreo y Recuperación: Durante y después del procedimiento, el paciente es monitoreado de cerca para detectar cualquier complicación y asegurar una recuperación segura. Se realizan ajustes en la dosis y la duración de las sesiones según la respuesta individual del paciente, con el objetivo de maximizar los beneficios terapéuticos y minimizar los efectos secundarios.", "https://www.youtube.com/embed/jxvXnEQhjb4?si=SrCFsOV60Sxh87jA");
        insertarTerapia($conexion, "Psicoterapia", "psychotherapy.jpg", "199", "Embárcate en un viaje de autodescubrimiento a través de la psicoterapia, donde cada sesión es una oportunidad para comprender y sanar.", "Función de la Psicoterapia: La Psicoterapia proporciona un espacio seguro y confidencial donde los pacientes pueden explorar sus pensamientos, emociones y comportamientos relacionados con el trastorno bipolar, con el objetivo de mejorar la comprensión de sí mismos y desarrollar estrategias efectivas de afrontamiento. Al trabajar en colaboración con un terapeuta capacitado, los pacientes pueden identificar y abordar los desafíos específicos que enfrentan, promoviendo así el crecimiento personal y el bienestar emocional a largo plazo.", "Evaluación Inicial y Formulación de Objetivos: Durante las primeras sesiones, se realiza una evaluación exhaustiva de los síntomas, la historia personal y los factores desencadenantes del paciente. Se establecen objetivos terapéuticos en colaboración con el paciente, identificando áreas de cambio y crecimiento potencial.", "Implementación de Técnicas Terapéuticas: Se utilizan diversas técnicas terapéuticas, como la terapia cognitivo-conductual, la terapia interpersonal o la terapia de aceptación y compromiso, adaptadas a las necesidades individuales del paciente y los objetivos terapéuticos establecidos. A través del diálogo y la exploración, se fomenta la autoconciencia y se promueve el cambio positivo.", "Exploración y Procesamiento Emocional: Durante las sesiones, se anima al paciente a explorar y procesar emociones difíciles, identificando patrones de pensamiento y comportamiento que puedan estar contribuyendo a su malestar emocional. Se ofrece apoyo emocional y se fomenta la expresión abierta y honesta de sentimientos.", "Integración y Planificación a Largo Plazo: A medida que avanza la terapia, se trabaja en la integración de los insights y las habilidades adquiridas en la vida cotidiana del paciente, promoviendo así la autonomía y el empoderamiento. Se desarrolla un plan de acción a largo plazo para mantener y mejorar el bienestar emocional, incluyendo estrategias de prevención de recaídas y manejo del estrés.", "https://www.youtube.com/embed/Livf43NubpA?si=ShrN1uQ2kbmgZTPe");
        insertarTerapia($conexion, "Mindfulness", "TerapiaMindfulness.jpg", "99", "Descubre cómo cultivar la atención plena para manejar el estrés y las emociones, aprendiendo a estar presente en el momento presente y fomentando la calma mental y la claridad.", "Función de la Terapia de Mindfulness: La Terapia de Mindfulness enseña a los pacientes a cultivar la conciencia plena del momento presente, ayudándoles a desarrollar una relación más equilibrada y compasiva consigo mismos y sus experiencias. Al practicar la atención plena, los pacientes pueden aprender a responder de manera más flexible a los desafíos de la vida y reducir el estrés asociado con el trastorno bipolar.", "Introducción a la Práctica de Mindfulness: Se inicia con una introducción a los conceptos básicos de la atención plena y la práctica de la meditación. Se enseñan ejercicios de respiración y técnicas de relajación para desarrollar la capacidad de estar presente en el momento presente.", "Aplicación en la Vida Diaria: Se exploran formas de integrar la atención plena en la vida cotidiana, aplicándola a situaciones estresantes, relaciones interpersonales y desafíos relacionados con el trastorno bipolar. Se fomenta la práctica informal de mindfulness en actividades diarias como comer, caminar o interactuar con los demás.", "Cultivo de la Autorregulación Emocional: Se practican técnicas de regulación emocional basadas en la atención plena para gestionar eficazmente los cambios de humor y los desencadenantes emocionales asociados con el trastorno bipolar. Se promueve la aceptación y la compasión hacia uno mismo y los demás.", "Mantenimiento y Continuidad: Se fomenta la práctica continua de mindfulness como una herramienta de autocuidado a largo plazo. Se ofrecen recursos y apoyo para mantener una práctica regular y sostenible en el tiempo, incluyendo grupos de apoyo y aplicaciones de mindfulness. Se enfatiza la importancia de la consistencia y la perseverancia en el desarrollo de la capacidad de atención plena.", "https://www.youtube.com/embed/LE2tlZLmzG4?si=_H0A-OoGlDXG_97n");
        insertarTerapia($conexion, "Arte Expresivo", "TerapiaExpresiva.jpg", "499", "Sumérgete en un viaje creativo para explorar y procesar tus emociones a través del arte, utilizando diferentes formas de expresión para promover la autoexpresión y el bienestar emocional.", "Función de la Terapia de Arte Expresivo: La Terapia de Arte Expresivo utiliza diversas formas de expresión artística, como la pintura, la escritura, la música y la danza, como herramientas terapéuticas para ayudar a los pacientes a explorar y procesar sus emociones, pensamientos y experiencias de manera no verbal. A través del proceso creativo, los pacientes pueden expresar libremente lo que les resulta difícil de comunicar verbalmente, promoviendo así la autoexpresión, la autoexploración y la sanación emocional.", "Exploración Creativa: Durante las sesiones de terapia, se anima al paciente a experimentar libremente con diferentes medios artísticos y técnicas sin preocuparse por el resultado final. Se proporcionan materiales artísticos y se establece un espacio seguro y de apoyo donde los pacientes pueden dejar volar su creatividad y explorar libremente sus emociones.", "Comunicación No Verbal: El arte se utiliza como un medio de comunicación no verbal para expresar emociones, pensamientos y experiencias que pueden ser difíciles de expresar verbalmente. A través de la creación artística, los pacientes pueden externalizar sus sentimientos y darles forma, lo que les permite procesar y comprender mejor sus experiencias internas.", "Proceso de Reflexión: Después de completar una obra de arte, se invita al paciente a reflexionar sobre su trabajo y explorar el significado personal detrás de las imágenes o símbolos creados. Se fomenta la autoconciencia y la autorreflexión, permitiendo al paciente profundizar en su comprensión de sí mismo y de sus emociones.", "Integración y Transformación: A lo largo del proceso de terapia, se trabaja en la integración de los insights y las emociones surgidas durante la creación artística en la vida cotidiana del paciente. Se exploran formas de aplicar las lecciones aprendidas y las experiencias vividas en la terapia a situaciones fuera del entorno terapéutico, promoviendo así la autenticidad, el crecimiento personal y la transformación emocional a largo plazo.", "http://localhost/ProyectoIntegrador/Mind2Mind/terapiaEspecifica.html");

        // Función para insertar un testimonio
        function insertarTestimonio($conexion, $comentario, $calificacion, $fecha, $correoElectronico)
        {
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
        function insertarChat($conexion, $usuario, $mensaje, $fecha, $correoElectronico, $idMedico)
        {
            $sql = "INSERT INTO chat (usuario, mensaje, fecha, correoElectronico, idMedico)
                    VALUES ('$usuario', '$mensaje', '$fecha', '$correoElectronico', $idMedico)";

            if ($conexion->query($sql)) {
                echo "Datos insertados correctamente, en chat.";
            } else {
                echo "Error al insertar datos, en pacientes: " . mysqli_error($conexion);
            }

            return true;
        }

        //Funcion para insertar CITAS, esto vinculara al paciente con un doctor
        function insertarCita($conexion, $especialidad, $modalidad, $numeroTelefono, $diagnostico, $fecha, $aseguradoraNombre, $correoElectronico, $idMedico)
        {
            $sql = "INSERT INTO cita (especialidad, modalidad, numeroTelefono, diagnostico, fecha, aseguradoraNombre, correoElectronico, idMedico)
                    VALUES ('$especialidad', '$modalidad', $numeroTelefono, '$diagnostico', '$fecha', '$aseguradoraNombre', '$correoElectronico', $idMedico)";

            if ($conexion->query($sql)) {
                echo "Datos insertados correctamente en la tabla cita.";
            } else {
                echo "Error al insertar datos en la tabla cita: " . mysqli_error($conexion);
            }

            return true;
        }

        insertarCita($conexion, "psicologo", "presencial", "871444555", "Me duele la tripa", "2024-04-04", "NULL", "paciente1@paciente.com", "1");
        insertarCita($conexion, "psiquiatra", "presencial", "871123456", "Depresión leve", "2024-03-01", "Asisas", "paciente2@paciente.com", "3");
        insertarCita($conexion, "psicologo", "presencial", "871987654", "Ansiedad", "2024-03-05", "NULL", "paciente3@paciente.com", "1");
        insertarCita($conexion, "psicologo", "telefónica", "871555444", "Problemas de pareja", "2024-03-07", "NULL", "paciente4@paciente.com", "4");
        insertarCita($conexion, "psiquiatra", "presencial", "871888999", "Trastorno obsesivo-compulsivo", "2024-03-10", "NULL", "paciente5@paciente.com", "3");
        insertarCita($conexion, "psicologo", "videoconsulta", "871222333", "Estrés laboral", "2024-03-15", "NULL", "paciente6@paciente.com", "2");
        insertarCita($conexion, "psiquiatra", "presencial", "871333666", "Trastorno bipolar", "2024-03-20", "NULL", "paciente7@paciente.com", "3");
        insertarCita($conexion, "psicologo", "presencial", "871111222", "Trastorno de ansiedad generalizada", "2024-03-25", "NULL", "paciente8@paciente.com", "1");
        insertarCita($conexion, "psiquiatra", "telefónica", "871444777", "Esquizofrenia", "2024-03-28", "NULL", "paciente9@paciente.com", "5");
        insertarCita($conexion, "psiquiatra", "presencial", "871666999", "Trastorno de estrés postraumático", "2024-03-30", "NULL", "paciente10@paciente.com", "3");
        insertarCita($conexion, "psicologo", "presencial", "871777888", "Trastorno de la personalidad", "2024-04-01", "NULL", "paciente1@paciente.com", "1");



        echo "Tablas creadas correctamente";
    } else {
        echo "Error al crear la base de datos: " . $conexion->error;
    }
}
echo "La BBDD " . $base_datos . " ya existe";

// Cerrar la conexión
$conexion->close();
