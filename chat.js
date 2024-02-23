// $(document).ready(function() {
//     // Actualiza el intervalo de actualización según tus necesidades
//     setInterval(cargarMensajes, 1000);

//     $("#formularioChat").submit(function(e) {
//         e.preventDefault();
//         enviarMensaje();
//     });
// });

// function enviarMensaje() {
//     var mensaje = $("#mensaje").val();
//     var correoElectronico = sessionStorage.getItem("correoUsuario");
//     var idMedico = sessionStorage.getItem("idMedico");

//     if (mensaje.trim() !== "") {
//         // Envia el mensaje al servidor
//         $.ajax({
//             url: "insertarDatos.php",
//             type: "POST",
//             data: {
//                 funcion: "enviarMensaje",
//                 usuario: correoElectronico, // Cambié "usuario" a "correoElectronico"
//                 mensaje: mensaje,
//                 correoElectronico: correoElectronico,
//                 idMedico: idMedico,
//             },
//             success: function() {
//                 cargarMensajes();
//                 // Limpiar el campo de mensaje después de enviar
//                 $("#mensajeChat").val("");
//                 // Actualiza los mensajes después de enviar uno nuevo
//             },
//             error: function(error) {
//                 console.error("Error al enviar mensaje:", error);
//             },
//         });
//     }
// }

// function cargarMensajes() {
//     var correoElectronico = sessionStorage.getItem("correoUsuario");
//     var idMedico = sessionStorage.getItem("idMedico");

//     $.ajax({
//         url: "selectsDatos.php",
//         type: "POST",
//         data: {
//             funcion: "mostrarDatosChat",
//             correoElectronico: correoElectronico,
//             idMedico: idMedico,
//         },
//         success: function(data) {
//             if (data.status === 'success') {
//                 mostrarMensajes(data.mensajes);
//             } else {
//                 console.error("Error al obtener mensajes:", data.message);
//             }
//         },
//         error: function(error) {
//             console.error("Error al obtener mensajes:", error);
//         },
//     });
// }

// function mostrarMensajes(mensajes) {
//     var mensajesHTML = "";

//     mensajes.forEach(function(mensaje) {
//         mensajesHTML += '<p class="' + mensaje.usuario.toLowerCase() + '"><span>' + mensaje.mensaje + '</span></p>';
//     });

//     $("#mensajesChat").html(mensajesHTML);
// }

// function setSessionInformation(usuario, correoElectronico, idMedico) {
//     sessionStorage.setItem("usuario", usuario);
//     sessionStorage.setItem("correoElectronico", correoElectronico);
//     sessionStorage.setItem("idMedico", idMedico);
// }

// setSessionInformation("paciente", "juancortes1bx@hotmail.es", 1);


// Función para enviar el mensaje al servidor usando AJAX
function enviarMensajeAlServidor(usuario, mensaje, correoElectronico, idMedico) {
    var idMedico = sessionStorage.getItem("idMedico");
    // Utilizar AJAX para enviar el mensaje al servidor
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "BBDD/insertarDatos.php", true);
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
    var correoElectronico = sessionStorage.getItem("correoUsuario");
    var idMedico = sessionStorage.getItem("idMedico");
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
        enviarMensajeAlServidor(usuario, mensaje, correoElectronico, idMedico);
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
    mensajeBienvenida.className = "recibir";
    mensajeBienvenida.innerHTML = "<span>Buenos días, ¿en qué puedo ayudarte?</span>";
    mensajesChat.appendChild(mensajeBienvenida);


    var correoElectronico = sessionStorage.getItem("correoUsuario");
    var idMedico = sessionStorage.getItem("idMedico");

    $.ajax({
        url: "BBDD/selectsDatos.php",
        type: "POST",
        data: {
            funcion: "mostrarDatosChat",
            correoElectronico: correoElectronico,
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

    if (mensajes.length > 0) {
        mensajes.forEach(function(mensaje) {
            var nuevoMensaje = document.createElement("p");
            nuevoMensaje.className = (mensaje.correoElectronico === sessionStorage.getItem("correoUsuario")) ? "enviar" : "recibir";
            nuevoMensaje.innerHTML = "<span>" + mensaje.mensaje + "</span>";
            mensajesChat.appendChild(nuevoMensaje);
        });

        mensajesChat.scrollTop = mensajesChat.scrollHeight;
    } else {
        console.error("No hay mensajes para mostrar.");
    }
}

var botonDerecha = document.getElementById("nextDoctorDerecha");
var botonIzquierda = document.getElementById("nextDoctorIzquierda");

if (botonDerecha && botonIzquierda) {
    // Evento de clic para la flecha derecha
    botonDerecha.addEventListener("click", function() {
        cargarMensajes();
    });

    // Evento de clic para la flecha izquierda
    botonIzquierda.addEventListener("click", function() {
        cargarMensajes();
    });
}
// Llamar a cargarMensajes al abrir servicios.html
$(document).ready(function() {
    cargarMensajes();
});

function limpiarChat() {
    // Obtener el contenedor de mensajes del chat
    var mensajesChat = document.getElementById("mensajesChat");

    // Limpiar todos los elementos hijos del contenedor de mensajes
    while (mensajesChat.firstChild) {
        mensajesChat.removeChild(mensajesChat.firstChild);
    }
}