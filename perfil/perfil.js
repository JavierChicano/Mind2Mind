//Carrousel perfil
const contenedorPerfil = document.querySelector("#perfilInfo");
const linksPerfil = document.querySelectorAll(".divisiones");

 // Variables para almacenar el año y mes actual
 let añoActual = new Date().getFullYear();
 let mesActual = new Date().getMonth();

let currentIndexPerfil = 0;

// Función para cambiar el div según el índice
function cambiarDivPerfil(index) {
    let operation = index * -(100 / 7);
    contenedorPerfil.style.transform = `translateY(${operation}%)`;

    linksPerfil.forEach((link, i) => {
        linksPerfil[i].classList.remove("seleccionada");
    });
    linksPerfil[index].classList.add("seleccionada");
}

// Evento de clic en los puntos
linksPerfil.forEach((link, i) => {
    linksPerfil[i].addEventListener("click", () => {
        currentIndexPerfil = i;
        cambiarDivPerfil(currentIndexPerfil);
    });
});

//Ver contraseña
const ojo = document.querySelector(".bx");
const contraseña = document.getElementById("contraseña");

ojo.addEventListener("click", (e) => {
    if (contraseña.type === "password") {
        contraseña.type = "text";
        ojo.classList.add("bx-show-alt");
        ojo.classList.remove("bx-hide");
    } else {
        contraseña.type = "password";
        ojo.classList.remove("bx-show-alt");
        ojo.classList.add("bx-hide");
    }
});
//Validacion form editar perfil
var displayErrores = document.getElementById("displayInfoPerfil");

$("#botonEditarPerfil").on("click", function() {
    var nombre = document.getElementById("nombre").value;
    var apellidos = document.getElementById("apellidos").value;
    var contraseña = document.getElementById("contraseña").value;
    var dni = document.getElementById("dni").value;
    var provincia = document.getElementById("provincia").value;
    var domicilio = document.getElementById("domicilio").value;
    var genero = document.getElementById("genero").value;

    // Validacion de formularios
    var textoLetras = /^[A-Za-z\s]+$/;

    if (!textoLetras.test(nombre)) {
        displayErrores.textContent = "El nombre contiene caracteres invalidos";
        document.getElementById("nombre").style.border = "1px solid red";
        return false;
    } else {
        document.getElementById("nombre").style.border = "";
        displayErrores.textContent = "";
    }

    if (!textoLetras.test(apellidos)) {
        displayErrores.textContent = "Los apellidos contienen caracteres invalidos";
        document.getElementById("apellidos").style.border = "1px solid red";
        return false;
    } else {
        document.getElementById("apellidos").style.border = "";
        displayErrores.textContent = "";
    }
    var formatoDNI = /^\d{8}[a-zA-Z]$/;

    if (dni !== "" && !formatoDNI.test(dni)) {
        displayErrores.textContent =
            "El DNI no tiene el formato correcto (8 Números y 1 Letra)";
        document.getElementById("dni").style.border = "1px solid red";
        return false;
    } else {
        document.getElementById("dni").style.border = "";
        displayErrores.textContent = "";
    }

    insertarEditarPerfil(
        nombre,
        apellidos,
        contraseña,
        dni,
        provincia,
        domicilio,
        genero
    );
});
//-------------------------------------------------FUNCIONES MOSTRAR PERFIL----------------------------------------------
var mostrarCuenta = document.getElementById("account");

mostrarCuenta.addEventListener("click", function() {
    consultaEditarPerfil();
});
nombrePaciente();
var perfil = document.getElementById("perfil");
var misTerapias = document.getElementById("misTerapias");
var misCitas = document.getElementById("misCitas");
var suscripciones = document.getElementById("suscripciones");
var ayuda = document.getElementById("ayuda");
var certTratamientos = document.getElementById("certTratamientos");
var singOut = document.getElementById("signOut");

if (perfil) {
    consultaEditarPerfil();
}
if (misTerapias) {
    comprobarTerapias();
}
if(misCitas){
    mostrarCitas();
}
if (singOut) {
    logout();
}

function nombrePaciente() {
    var idPaciente = sessionStorage.getItem("correoUsuario");
    var nombrePaciente = document.getElementById("nombrePaciente");
    console.log(nombrePaciente)

    //Pagina perfil
    $.ajax({
        type: "POST",
        url: "../BBDD/selectsDatos.php", // Nombre de tu script PHP
        data: {
            funcion: "nombrePaciente",
            email: idPaciente,
        },
        success: function(response) {
            //Comprobacion de la consulta
            if (response.status === "success") {
                console.log(response.nombre)
                nombrePaciente.textContent = response.nombre;
            } else {
                console.log(response.message);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error("Error en la solicitud Ajax:", textStatus, errorThrown);
        },
    });
}
//------------------------------------------------------ACCIONES DEL PERFIL---------------------------------------------------------------------------

function consultaEditarPerfil() {
    var idPaciente = sessionStorage.getItem("correoUsuario");

    //Pagina perfil
    $.ajax({
        type: "POST",
        url: "../BBDD/selectsDatos.php", // Nombre de tu script PHP
        data: {
            funcion: "editarPerfil",
            email: idPaciente,
        },
        success: function(response) {
            //Comprobacion de la consulta
            if (response.status === "success") {
                mostrarPerfil(response.paciente);
            } else {
                //Acciones que hace si es erroneo el login
                console.log(response.message);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error("Error en la solicitud Ajax:", textStatus, errorThrown);
        },
    });
}

function mostrarPerfil(email) {
    // Actualizar elementos HTML en servicios.html con la información del doctor
    $("#correoElectronico")
        .val(email.correoElectronico || "")
        .prop("disabled", true);
    $("#nombre").val(email.nombre || "");
    $("#apellidos").val(email.apellidos || "");
    $("#contraseña").val(email.contraseña || "");
    $("#dni").val(email.dni || "");
    $("#provincia").val(email.provincia || "");
    $("#domicilio").val(email.domicilio || "");
    $("#genero").val(email.genero || "");
}

function insertarEditarPerfil(
    nombre,
    apellidos,
    contraseña,
    dni,
    provincia,
    domicilio,
    genero
) {
    var idPaciente = sessionStorage.getItem("correoUsuario");

    //Pagina perfil
    $.ajax({
        type: "POST",
        url: "../BBDD/insertarDatos.php", // Nombre de tu script PHP
        data: {
            funcion: "insertarPerfil",
            correoElectronico: idPaciente,
            nombre: nombre,
            apellidos: apellidos,
            contraseña: contraseña,
            dni: dni,
            provincia: provincia,
            domicilio: domicilio,
            genero: genero,
        },
        success: function(response) {
            //Comprobacion de la consulta
            if (response.status === "success") {
                console.log("Insert exitoso: " + response.message);
            } else {
                console.log(response.message);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error("Error en la solicitud Ajax:", textStatus, errorThrown);
        },
    });
}
//------------------------------------------------------ACCIONES DE MIS TERAPIAS---------------------------------------------------------------------------
function comprobarTerapias() {
    var idPaciente = sessionStorage.getItem("correoUsuario");
    var sugerenciaP = document.getElementById("parrafoSugerencias");
    var sugerenciaT = document.getElementById("sugerencias");

    //Pagina perfil
    $.ajax({
        type: "POST",
        url: "../BBDD/selectsDatos.php", // Nombre de tu script PHP
        data: {
            funcion: "comprobarTerapias",
            correoElectronico: idPaciente,
        },
        success: function(response) {
            //Comprobacion de la consulta
            if (response.status === "success") {
                //Se muestran las terapias
                sugerenciaP.style.display = "none";

                mostrarTerapiasAsociadas(idPaciente);
                //Se muestran las sugerencias si el paciente posee menos de 3 terapias
                if (response.numConsultas < 3) {
                    mostrarRestoTerapias(idPaciente);
                } else {
                    //Ocultamos sugerencias
                    sugerenciaT.style.display = "none";
                }
            } else {
                console.log("comprobar terapias fail");
                //Se muestras terapias como sugerencia
                mostrarTodasTerapias();
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error("Error en la solicitud Ajax:", textStatus, errorThrown);
        },
    });
}

function mostrarTodasTerapias() {
    var contenedorTerapias = document.getElementById("cajaTerapias");

    //Pagina perfil
    $.ajax({
        type: "POST",
        url: "../BBDD/selectsDatos.php", // Nombre de tu script PHP
        data: {
            funcion: "mostrarTodasTerapias",
        },
        success: function(response) {
            //Comprobacion de la consulta
            if (response.status === "success") {
                var terapias = response.terapias;
                mostrarDatosConsultasTerapias(terapias, contenedorTerapias);
            } else {
                console.log("fallo al mostrar Todas las terapias");
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error("Error en la solicitud Ajax:", textStatus, errorThrown);
        },
    });
}

function mostrarRestoTerapias(idPaciente) {
    var contenedorTerapias = document.getElementById("cajaTerapias");
    console.log(idPaciente)
        //Pagina perfil
    $.ajax({
        type: "POST",
        url: "../BBDD/selectsDatos.php", // Nombre de tu script PHP
        data: {
            funcion: "mostrarRestoTerapias",
            correoElectronico: idPaciente,
        },
        success: function(response) {
            //Comprobacion de la consulta
            if (response.status === "success") {
                var terapias = response.terapias;
                console.log(terapias)
                mostrarDatosConsultasTerapias(terapias, contenedorTerapias);
            } else {
                console.log("fallo al mostrar RESTO de las terapias");
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error("Error en la solicitud Ajax:", textStatus, errorThrown);
        },
    });
}

function mostrarTerapiasAsociadas(idPaciente) {
    var contenedorTerapiasFavoritas = document.getElementById("cajaTerapiasFavoritas");
    contenedorTerapiasFavoritas.style.padding = "20px 30px";

    //Pagina perfil
    $.ajax({
        type: "POST",
        url: "../BBDD/selectsDatos.php", // Nombre de tu script PHP
        data: {
            funcion: "mostrarTerapiasAsociadas",
            correoElectronico: idPaciente,
        },
        success: function(response) {
            //Comprobacion de la consulta
            if (response.status === "success") {
                var terapias = response.terapias;
                mostrarDatosConsultasTerapias(terapias, contenedorTerapiasFavoritas);
            } else {}
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error("Error en la solicitud Ajax:", textStatus, errorThrown);
        },
    });
}

function mostrarDatosConsultasTerapias(terapias, contenedorTerapias) {
    // Iterar sobre cada objeto terapia en el array
    terapias.forEach(function(terapia) {
        // Crear un nuevo div para la terapia
        var divTerapia = document.createElement("div");
        divTerapia.className = "divTerapia";

        // Crear la imagen
        var imagenTerapia = document.createElement("img");
        imagenTerapia.className = "portadaTerapia";
        imagenTerapia.src = "../img/Tratamientos/" + terapia.imagen;
        divTerapia.appendChild(imagenTerapia);

        // Crear el título
        var tituloTerapia = document.createElement("h1");
        tituloTerapia.className = "tituloTerapia";
        tituloTerapia.textContent = terapia.nombre;
        divTerapia.appendChild(tituloTerapia);

        // Crear la descripción
        var descripTerapia = document.createElement("article");
        descripTerapia.className = "descripTerapia";
        descripTerapia.textContent = terapia.mini_descripcion;
        divTerapia.appendChild(descripTerapia);

        // Crear el enlace
        var enlaceTerapia = document.createElement("a");
        enlaceTerapia.className = "linkTerapia";
        enlaceTerapia.textContent = "Ir a la terapia";

        // Convertir el objeto terapia a JSON y asignarlo al dataset
        enlaceTerapia.dataset.terapia = JSON.stringify(terapia);

        // Imagen del enlace
        var imgEnlace = document.createElement("img");
        imgEnlace.src = "../img/external-link.png";
        imgEnlace.className = "linkTarjetaImg";
        imgEnlace.alt = "Icono de enlace directo externo";

        // Agregar la imagen al enlace
        enlaceTerapia.appendChild(imgEnlace);

        // Agregar el enlace al divTerapia
        divTerapia.appendChild(enlaceTerapia);

        // Agregar el divTerapia al contenedor
        contenedorTerapias.appendChild(divTerapia);

        // Agregar el event listener al enlace
        enlaceTerapia.addEventListener("click", function() {
            // Acceder a la información específica del enlace que se hizo clic
            const terapia = JSON.parse(this.dataset.terapia);
            console.log(terapia);
            sessionStorage.setItem("terapia", JSON.stringify(terapia));
            window.location.href = '../terapiaEspecifica.html';
        });
    });
}

//------------------------------------------------------ACCIONES DEL LOGOUT---------------------------------------------------------------------------
function logout() {
    var botonLogOut = document.getElementById("logOutGif");

    botonLogOut.addEventListener("click", function() {
        // Establecer la sesión como no iniciada
        sessionStorage.setItem("sesionIniciada", "false");
        // Recargar la página para restaurar el estado inicial
        window.location.href = "../index.html";
    });
}
//------------------------------------------------------ACCIONES DE PEDIR CITA---------------------------------------------------------------------------
var citas = 0;
function mostrarCitas() {
    var idPaciente = sessionStorage.getItem("correoUsuario");

    //Pagina perfil
    $.ajax({
        type: "POST",
        url: "../BBDD/selectsDatos.php", // Nombre de tu script PHP
        data: {
            funcion: "consultarCitas",
            correoElectronico: idPaciente,
        },
        success: function(response) {
            //Comprobacion de la consulta
            if (response.status === "success") {
                console.log(citas)
                citas = response.citas;
                console.log(citas)
                generarCalendario(añoActual, mesActual, citas);
            } else {
                console.log(response)
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error("Error en la solicitud Ajax:", textStatus, errorThrown);
        },
    });
}
function diasEnMes(mes, año) {
    return new Date(año, mes + 1, 0).getDate();
  }
  function generarCalendario(año, mes, citas) {
    console.log("hola buens dias")
    const cuerpoCalendario = document.getElementById("cuerpo-calendario");
    cuerpoCalendario.innerHTML = "";
  
    const primerDia = new Date(año, mes, 1).getDay();
    const totalDias = diasEnMes(mes, año);
  
    let fecha = 1;
  
    for (let i = 0; i < 6; i++) {
      const fila = document.createElement("tr");
  
      for (let j = 0; j < 7; j++) {
        const celda = document.createElement("td");
  
        if (i === 0 && j < primerDia) {
          const textoCelda = document.createTextNode("");
          celda.appendChild(textoCelda);
        } else if (fecha > totalDias) {
          break;
        } else {
          const textoCelda = document.createTextNode(fecha);
          celda.appendChild(textoCelda);
  
          // Verificar si hay citas programadas para esta fecha
          const fechaActual = new Date(año, mes, fecha).toISOString().slice(0, 10);
          const citasEnFecha = citas.filter(cita => cita.fecha === fechaActual);
          
          // Si hay citas, agregarlas a la celda
          if (citasEnFecha.length > 0) {
            const listaCitas = document.createElement("ul");
            listaCitas.classList.add("lista-citas"); // Agrega la clase "lista-citas"
            citasEnFecha.forEach(cita => {
              const itemCita = document.createElement("li");
              itemCita.textContent = `- ${cita.especialidad} \n- ${cita.modalidad}`; 
              listaCitas.appendChild(itemCita);
            });
            celda.appendChild(listaCitas);
          }
  
          fecha++;
        }
  
        fila.appendChild(celda);
      }
  
      cuerpoCalendario.appendChild(fila);
    }
  
    // Actualizar el título con el mes y año actual
    document.getElementById("titulo-mes").textContent = new Date(año, mes).toLocaleDateString('es-ES', { month: 'long', year: 'numeric' });
  
    // Actualizar las variables del año y mes actual
    añoActual = año;
    mesActual = mes;
  }
  

  // Manejar la navegación entre meses
  document.getElementById("mes-anterior").addEventListener("click", function() {
    let añoAnterior = añoActual;
    let mesAnterior = mesActual - 1;
    if (mesAnterior < 0) {
      mesAnterior = 11;
      añoAnterior--;
    }
    generarCalendario(añoAnterior, mesAnterior, citas);
  });

  document.getElementById("mes-siguiente").addEventListener("click", function() {
    let añoSiguiente = añoActual;
    let mesSiguiente = mesActual + 1;
    if (mesSiguiente > 11) {
      mesSiguiente = 0;
      añoSiguiente++;
    }
    generarCalendario(añoSiguiente, mesSiguiente, citas);
  });

  // Botón "Mes actual"
  document.getElementById("mes-actual").addEventListener("click", function() {
    const fechaActual = new Date();
    generarCalendario(fechaActual.getFullYear(), fechaActual.getMonth(), citas);
  });
  
  let indiceCitaActual = 0;
  document.getElementById("mis-citas").addEventListener("click", function() {
    // Aumentar el índice de la cita actual
    indiceCitaActual++;
    // Si el índice excede el número de citas, volver al principio
    if (indiceCitaActual >= citas.length) {
        indiceCitaActual = 0;
    }
    // Obtener la fecha de la cita actual
    const fechaCitaActual = new Date(citas[indiceCitaActual].fecha);
    // Generar el calendario para el mes de la cita actual
    generarCalendario(fechaCitaActual.getFullYear(), fechaCitaActual.getMonth(), citas);
});