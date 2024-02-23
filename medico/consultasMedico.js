// Obtén el elemento chat
var chatElement = document.getElementById("chat");

// Haz scroll hacia abajo al cargar la página
chatElement.scrollTop = chatElement.scrollHeight;

// Si el usuario hace scroll hacia arriba, deshabilita el scroll automático
chatElement.addEventListener("scroll", function() {
    if (
        chatElement.scrollTop <
        chatElement.scrollHeight - chatElement.clientHeight
    ) {
        autoScrollEnabled = false;
    } else {
        autoScrollEnabled = true;
    }
});

// Función para asegurar que el chat esté siempre scrolleado hacia abajo
function scrollChatToBottom() {
    if (autoScrollEnabled) {
        chatElement.scrollTop = chatElement.scrollHeight;
    }
}

function infoDoctor() {
    var mail = sessionStorage.getItem("medico");

    //Pagina logIn
    $.ajax({
        type: "POST",
        url: "sentenciasDatos.php", // Nombre de tu script PHP
        data: {
            funcion: "consultarDoctor",
            correoElectronico: mail,
        },
        success: function(response) {
            //Comprobacion de la consulta
            if (response.status === "success") {
                console.log(response.doctor);
                mostrarInfo(response.doctor);
                sessionStorage.setItem("doctor", response.doctor.idMedico);
                console.log("ID MEDICO MODIFICADO:" + response.doctor.idMedico)
                pacientesRelacionados();
            } else {
                console.log(response);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error("Error en la solicitud Ajax:", textStatus, errorThrown);
        },
    });
}
infoDoctor();

function mostrarInfo(doctor) {
    $("#imgDoctor").attr("src", "../img/Equipo/" + doctor.imagen);
    $("#nombreDoctor").text(doctor.nombre + " " + doctor.apellidos);
    $("#horario").append(doctor.horario);
    $("#especialidad").append(doctor.especialidad);
    $("#modalidad").append(doctor.modalidad);
    $("#clinica").append(doctor.ubicacion);
}

var selects = document.getElementById("selectsPacientes");

function pacientesRelacionados() {
    var idDoctor = sessionStorage.getItem("doctor")

    $.ajax({
        type: "POST",
        url: "sentenciasDatos.php", // Nombre de tu script PHP
        data: {
            funcion: "pacientesAsignados",
            idMedico: idDoctor,
        },
        success: function(response) {
            //Comprobacion de la consulta
            if (response.status === "success") {
                console.log(response.pacientes);
                generarSelectsPacientes(response.pacientes);
                proximasCitas(idDoctor);
            } else {
                console.log(response);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error("Error en la solicitud Ajax:", textStatus, errorThrown);
        },
    });
}

function generarSelectsPacientes(pacientes) {
    // Obtener el select existente
    var selectPacientes = document.getElementById("selectsPacientes");

    // Obtener el primer paciente
    var primerPaciente = pacientes[0];

    // Eliminar las opciones existentes antes de agregar nuevas
    selectPacientes.innerHTML = "";

    // Generar una opción para cada paciente y agregarla al select
    pacientes.forEach(function(paciente) {
        var option = document.createElement("option");
        option.value = paciente.correoElectronico; // Valor de la opción
        option.textContent = `${paciente.correoElectronico} - ${paciente.nombre} ${paciente.apellidos}`; // Texto de la opción
        selectPacientes.appendChild(option);
    });

    // Almacenar los datos del primer paciente en un array
    var primerPacienteArray = {
        id: primerPaciente.correoElectronico,
        nombre: primerPaciente.nombre,
        apellidos: primerPaciente.apellidos,
    };

    // Almacenar el array de datos del primer paciente en sessionStorage si no hay datos ya almacenados
    sessionStorage.setItem("pacienteActual", JSON.stringify(primerPacienteArray));
    console.log("paciente actual:" + sessionStorage.getItem("pacienteActual"));
    mostrarInfoPaciente();
    cargarMensajes();

    // Manejar el almacenamiento de datos cuando se selecciona un paciente
    selectPacientes.addEventListener("change", function() {
        var pacienteId = selectPacientes.value;
        var pacienteNombre =
            selectPacientes.options[selectPacientes.selectedIndex].textContent;

        // Obtener el nombre del paciente
        var nombreCompleto = pacienteNombre.split(" - ")[1].trim(); // Se asume que el correo electrónico está al principio y separado por ' - '

        // Dividir el nombre completo del paciente en nombre y apellidos
        var nombreArray = nombreCompleto.split(" ");
        var nombre = nombreArray[0];
        var apellidos = nombreArray.slice(1).join(" ");

        // Crear el array de datos del paciente seleccionado
        var pacienteSeleccionadoArray = {
            id: pacienteId,
            nombre: nombre,
            apellidos: apellidos,
        };

        // Almacenar el array de datos del paciente seleccionado en sessionStorage
        sessionStorage.setItem("pacienteActual", JSON.stringify(pacienteSeleccionadoArray));
        mostrarInfoPaciente();
        proximasCitas(sessionStorage.getItem("doctor"));
        cargarMensajes();

        console.log("paciente actual:" + sessionStorage.getItem("pacienteActual"));
    });
}

function mostrarInfoPaciente() {
    // Obtener el objeto paciente de sessionStorage y convertirlo de nuevo a un objeto JavaScript
    var pacienteString = sessionStorage.getItem("pacienteActual");
    var paciente = JSON.parse(pacienteString);

    // Mostrar los datos del paciente
    $("#datosPaciente").text(paciente.nombre + " " + paciente.apellidos);
    $("#nombrePaciente").text("Chat paciente: " + paciente.nombre + " " + paciente.apellidos);
}

function proximasCitas(idMedico) {
    var pacienteString = sessionStorage.getItem("pacienteActual");
    var paciente = JSON.parse(pacienteString);
    console.log("ID MEDICO: " + idMedico)
    $.ajax({
        type: "POST",
        url: "sentenciasDatos.php", // Nombre de tu script PHP
        data: {
            funcion: "proximasCitas",
            idMedico: idMedico,
            idPaciente: paciente.id,
        },
        success: function(response) {
            //Comprobacion de la consulta
            if (response.status === "success") {
                console.log(response.citas)
                mostrarCitas(response.citas, response.citasPaciente)
                generarSelectsCitas(response.citasPaciente)
            } else {
                console.log(response);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error("Error en la solicitud Ajax:", textStatus, errorThrown);
        },
    });
}

function mostrarCitas(citas, citasPaciente) {
    var pacienteString = sessionStorage.getItem("pacienteActual");
    var paciente = JSON.parse(pacienteString);

    $("#numConsultas").text("Totales: " + citas.length);
    $("#numConsultasPaciente").text("Paciente " + paciente.nombre + ": " + citasPaciente.length);
}

function generarSelectsCitas(citas) {
    var pacienteString = sessionStorage.getItem("pacienteActual");
    var paciente = JSON.parse(pacienteString);

    // Obtener el select existente
    var selectCitas = document.getElementById("selectsCitas");

    // Eliminar las opciones existentes antes de agregar nuevas
    selectCitas.innerHTML = "";

    // Generar una opción para cada cita y agregarla al select
    citas.forEach(function(cita) {
        var option = document.createElement("option");
        option.value = cita.idCita; // Suponiendo que el ID de la cita se puede usar como valor
        var diagnostico = cita.diagnostico ? cita.diagnostico : "no hay diagnóstico";
        option.textContent = `${cita.idCita} - ${paciente.nombre} ${paciente.apellidos} - ${diagnostico} - ${cita.fecha}`;
        selectCitas.appendChild(option);
    });

    // Almacenar los datos de la primera cita en sessionStorage si no hay datos ya almacenados
    var primeraCita = citas[0];
    var primeraCitaArray = {
        id: primeraCita.idCita,
        paciente: paciente.nombre + ' ' + paciente.apellidos,
        fecha: primeraCita.fecha,
    };
    sessionStorage.setItem("citaActual", JSON.stringify(primeraCitaArray));
    console.log("cita actual:" + sessionStorage.getItem("citaActual"));

    // Manejar el almacenamiento de datos cuando se selecciona una cita
    selectCitas.addEventListener("change", function() {
        var citaId = selectCitas.value;
        var citaInfo = selectCitas.options[selectCitas.selectedIndex].textContent;

        // Obtener la información de la cita seleccionada
        var citaInfoArray = citaInfo.split(" - ");
        var paciente = citaInfoArray[1].trim();
        var fecha = citaInfoArray[3].trim(); // Ahora la fecha está en la posición 3

        // Crear el array de datos de la cita seleccionada
        var citaSeleccionadaArray = {
            id: citaId,
            paciente: paciente,
            fecha: fecha,
        };

        // Almacenar el array de datos de la cita seleccionada en sessionStorage
        sessionStorage.setItem("citaActual", JSON.stringify(citaSeleccionadaArray));
        console.log("cita actual despues del cambio:" + sessionStorage.getItem("citaActual"));
    });
}

var eliminarCita = document.getElementById("eliminarCita");
eliminarCita.addEventListener("click", function() {
    eliminarCitaSeleccionada();

});

function eliminarCitaSeleccionada() {
    var citaString = sessionStorage.getItem("citaActual");
    var cita = JSON.parse(citaString);
    console.log("ID CITA PACIENTE: " + cita.id)
    $.ajax({
        type: "POST",
        url: "sentenciasDatos.php", // Nombre de tu script PHP
        data: {
            funcion: "eliminarCita",
            cita: cita.id,
        },
        success: function(response) {
            //Comprobacion de la consulta
            if (response.status === "success") {
                console.log(response.message);
                pacientesRelacionados()
            } else {
                console.log(response);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error("Error en la solicitud Ajax:", textStatus, errorThrown);
        },
    });
}


function enviarMensajeAlServidor(usuario, mensaje, correoElectronico, idMedico) {
    var idMedico = sessionStorage.getItem("doctor");

    console.log("Compprobación de datos para enviar al servidor:" + "usuario" + usuario);
    console.log("Compprobación de datos para enviar al servidor:" + "mensaje" + mensaje);
    console.log("Compprobación de datos para enviar al servidor:" + "correo" + correoElectronico);
    console.log("Compprobación de datos para enviar al servidor:" + "idMedico" + idMedico);
    // Utilizar AJAX para enviar el mensaje al servidor
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "sentenciasDatos.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    // Preparar los datos a enviar
    var data = "funcion=enviarMensajeAlServidor&usuario=" + encodeURIComponent(usuario) + "&mensaje=" + encodeURIComponent(mensaje) + "&correoElectronico=" + encodeURIComponent(correoElectronico) + "&idMedico=" + encodeURIComponent(idMedico);

    // Definir la función de devolución de llamada cuando la solicitud se completa
    xhr.onload = function() {
        if (xhr.status === 200) {
            // La solicitud fue exitosa, manejar la respuesta aquí si es necesario
            console.log(xhr.responseText);
        } else {
            // La solicitud falló, manejar el error aquí
            console.error("Error al enviar el mensaje al servidor. Código de estado: " + xhr.status);
        }
    };

    // Enviar los datos
    xhr.send(data);
}
document.getElementById("formularioChat").addEventListener("submit", function(event) {
    event.preventDefault();

    // Recuperar la información del usuario desde sessionStorage
    var pacienteString = sessionStorage.getItem("pacienteActual")
    var correoElectronico = JSON.parse(pacienteString);

    console.log("Camionnnn!!!!!: " + correoElectronico)
    console.log("Caretilla: " + correoElectronico.id)
    var idMedico = sessionStorage.getItem("doctor");
    console.log(idMedico);
    // Determinar quién está obteniendo la información
    var usuarioTipo = (correoElectronico !== null) ? "paciente" : (idMedico !== null) ? "medico" : "Tipo de usuario no reconocido";

    // Asignar el valor correcto a la variable 'usuario' basándose en 'usuarioTipo'
    var usuario = (usuarioTipo === "paciente") ? correoElectronico : (usuarioTipo === "medico") ? idMedico : "Usuario no reconocido";

    // Registrar la información del usuario recuperada
    console.log("Correo Electrónico:", correoElectronico);
    console.log("ID Médico:", idMedico);
    console.log("Usuario:", usuarioTipo + ": " + usuario);

    var mensajeInput = document.getElementById("mensaje");
    var mensaje = mensajeInput.value.trim();

    if (mensaje !== "") {
        // Enviar el mensaje al servidor usando AJAX
        enviarMensajeAlServidor(sessionStorage.getItem("doctor"), mensaje, correoElectronico.id, idMedico);
        // Resto del código para mostrar el mensaje en el chat del cliente
        var mensajesChat = document.getElementById("mensajesChat");
        var nuevoMensaje = document.createElement("p");
        nuevoMensaje.className = "enviar";
        nuevoMensaje.innerHTML = "<span>" + mensaje + "</span>";
        mensajesChat.appendChild(nuevoMensaje);
        mensajesChat.scrollTop = mensajesChat.scrollHeight;

        mensajeInput.value = ""; // Limpiar el campo de entrada
    }
});




function cargarMensajes() {

    var mensajesChat = document.getElementById("mensajesChat");

    // Limpiar los mensajes anteriores
    mensajesChat.innerHTML = "";

    // Mostrar el mensaje de bienvenida
    var mensajeBienvenida = document.createElement("p");
    mensajeBienvenida.className = "enviar";
    mensajeBienvenida.innerHTML = "<span>Buenos días, ¿en qué puedo ayudarte?</span>";
    mensajesChat.appendChild(mensajeBienvenida);


    var pacienteString = sessionStorage.getItem("pacienteActual")
    var correoElectronico = JSON.parse(pacienteString);
    var idMedico = sessionStorage.getItem("doctor");

    $.ajax({
        url: "sentenciasDatos.php",
        type: "POST",
        data: {
            funcion: "mostrarDatosChat",
            correoElectronico: correoElectronico.id,
            idMedico: idMedico,
        },
        success: function(response) {
            if (response.status === 'success') {
                console.log(response.mensajes);
                mostrarMensajes(response.mensajes)
            } else {
                console.error("Error al obtener mensajes:", response.message);
            }
        },
        error: function(error) {
            console.error("Error al obtener mensajes:", error);
        },
    });
}

function mostrarMensajes(mensajes) {
    var mensajesChat = document.getElementById("mensajesChat");
    var pacienteString = sessionStorage.getItem("pacienteActual")
    var correoElectronico = JSON.parse(pacienteString);
    console.log("MEnsajes!!!!!!!!!: " + mensajes)

    if (mensajes.length > 0) {
        mensajes.forEach(function(mensaje) {
            var nuevoMensaje = document.createElement("p");
            nuevoMensaje.className = (mensaje.usuario === sessionStorage.getItem("doctor")) ? "enviar" : "recibir";
            nuevoMensaje.className = (mensaje.usuario === correoElectronico.id) ? "recibir" : "enviar";
            nuevoMensaje.innerHTML = "<span>" + mensaje.mensaje + "</span>";
            mensajesChat.appendChild(nuevoMensaje);
        });

        mensajesChat.scrollTop = mensajesChat.scrollHeight;
    } else {
        console.error("No hay mensajes para mostrar.");
    }
}