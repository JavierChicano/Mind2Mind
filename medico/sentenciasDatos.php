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
    $mensaje = null;
    $correoElectronico = null;
    // Verificar la conexión
    if ($conexion->connect_error) {
        die("La conexión a la base de datos ha fallado: " . $conexion->connect_error);
    }
    
    switch ($funcion) {
        case 'consultarDoctor':
            $correoElectronico = $_POST['correoElectronico'];
        
            // Consulta SQL para obtener el nombre y apellidos del paciente
            $sql_paciente = "SELECT nombre, apellidos FROM paciente WHERE correoElectronico = '$correoElectronico'";
            $resultado_paciente = $conexion->query($sql_paciente);
        
            // Verificar si se obtuvieron resultados
            if ($resultado_paciente->num_rows > 0) {
                // Obtener los datos del paciente
                $datos_paciente = $resultado_paciente->fetch_assoc();
                $nombre_paciente = $datos_paciente['nombre'];
                $apellidos_paciente = $datos_paciente['apellidos'];
        
                // Consulta SQL para verificar si el paciente es también un especialista
                $sql_especialista = "SELECT * FROM especialista WHERE nombre = '$nombre_paciente' AND apellidos = '$apellidos_paciente'";
                $resultado_especialista = $conexion->query($sql_especialista);
        
                // Verificar si se encontró un especialista con los mismos datos del paciente
                if ($resultado_especialista->num_rows > 0) {
                    // Obtener los datos del especialista
                    $doctor = $resultado_especialista->fetch_assoc();
                    $response = array('status' => 'success', 'message' => 'Doctor encontrado', 'doctor' => $doctor);
                } else {
                    $response = array('status' => 'error', 'message' => 'No se encontró ningún especialista con los datos del paciente');
                }
            } else {
                $response = array('status' => 'error', 'message' => 'No se encontró ningún paciente con el correo electrónico proporcionado');
            }
            break;
            case 'pacientesAsignados':
                // Obtener el id del médico
                $idMedico = $_POST['idMedico'];
            
                // Consulta SQL para seleccionar los pacientes que tienen citas con el médico dado
                $sql = "SELECT * FROM paciente WHERE correoElectronico IN 
                        (SELECT correoElectronico FROM cita WHERE idMedico = $idMedico)";
            
                // Ejecutar la consulta
                $resultado = $conexion->query($sql);
            
                // Verificar si se obtuvieron resultados
                if ($resultado->num_rows > 0) {
                    // Crear un array para almacenar los datos de los pacientes
                    $pacientes = array();
                    // Recorrer los resultados y almacenarlos en el array
                    while ($fila = $resultado->fetch_assoc()) {
                        $pacientes[] = $fila;
                    }
                    // Crear respuesta con los pacientes seleccionados
                    $response = array('status' => 'success', 'pacientes' => $pacientes);
                } else {
                    // No se encontraron pacientes
                    $response = array('status' => 'error', 'message' => 'No se encontraron pacientes para este médico');
                }
                break;
            case 'proximasCitas':
                // Obtener el id del médico
                $idMedico = $_POST['idMedico'];
                $correoElectronico = $_POST['idPaciente'];
                // Consulta SQL para seleccionar todas las citas del médico dado
                $sql = "SELECT * FROM cita WHERE idMedico = $idMedico";
                $sql2 = "SELECT idCita, diagnostico, fecha FROM cita WHERE idMedico = $idMedico AND correoElectronico = '$correoElectronico'";
                // Ejecutar la consulta
                $resultado = $conexion->query($sql);
                $resultado2 = $conexion->query($sql2);
                // Verificar si se obtuvieron resultados
                if ($resultado->num_rows > 0) {
                    // Crear un array para almacenar los datos de las citas
                    $citas = array();
                    // Recorrer los resultados y almacenarlos en el array
                    while ($fila = $resultado->fetch_assoc()) {
                        $citas[] = $fila;
                    }
                    // Crear respuesta con las citas seleccionadas
                    $response = array('status' => 'success', 'citas' => $citas);
                    if($resultado2->num_rows > 0) {
                        // Crear un array para almacenar los datos de las citas
                        $citasPaciente = array();
                        // Recorrer los resultados y almacenarlos en el array
                        while ($fila = $resultado2->fetch_assoc()) {
                            $citasPaciente[] = $fila;
                        }
                        // Crear respuesta con las citas seleccionadas
                        $response = array('status' => 'success', 'citas' => $citas, 'citasPaciente'=>$citasPaciente);
                    }
                } else {
                    // No se encontraron citas
                    $response = array('status' => 'error', 'message' => 'No se encontraron citas para este médico');
                }
                break;
            
                case 'eliminarCita':
                    // Obtener el ID de la cita a eliminar
                    $idCita = $_POST['cita'];
                
                    // Consulta SQL para eliminar la cita
                    $sql_eliminar_cita = "DELETE FROM cita WHERE idCita = $idCita";
                
                    // Ejecutar la consulta
                    if ($conexion->query($sql_eliminar_cita) === TRUE) {
                        $response = array('status' => 'success', 'message' => 'La cita ha sido eliminada correctamente');
                    } else {
                        $response = array('status' => 'error', 'message' => 'Error al eliminar la cita: ' . $conexion->error);
                    }
                    break;

                case 'enviarMensajeAlServidor':
                        echo json_encode(array(
                            'usuario' => $usuario,
                            'mensaje' => $mensaje,
                            'correoElectronico' => $correoElectronico
                        ));
                        $usuario = $_POST['usuario'];
                        $mensaje = $_POST['mensaje'];
                        $correoElectronico = $_POST['correoElectronico'];
                        $idMedico = $_POST['idMedico'];
                        
                            $sqlInsertarMensaje = "INSERT INTO chat (usuario, mensaje, correoElectronico, idMedico)
                                                   VALUES ('$usuario', '$mensaje', '$correoElectronico', '$idMedico')";
                        
                            if ($conexion->query($sqlInsertarMensaje)) {
                                $response = array('status' => 'success', 'message' => 'Mensaje enviado correctamente');
                            } else {
                                $response = array('status' => 'error', 'message' => 'Error al enviar el mensaje: ' . $conexion->error);
                            }
                            break;
        case 'mostrarDatosChat':
            $correoElectronico = $_POST['correoElectronico'];
            $idMedico = $_POST['idMedico'];
                
            $sqlMensajes = "SELECT * FROM chat WHERE correoElectronico = '$correoElectronico' AND idMedico = $idMedico ORDER BY fecha";
            $resultadoMensajes = $conexion->query($sqlMensajes);
                
            if ($resultadoMensajes->num_rows > 0) {
                $mensajes = array();
                while ($mensaje = $resultadoMensajes->fetch_assoc()) {
                    $mensajes[] = $mensaje;
                }
                $response = array('status' => 'success', 'mensajes' => $mensajes);
            } else {
                $response = array('status' => 'error', 'message' => 'No se encontraron mensajes para el usuario y médico proporcionados');
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

