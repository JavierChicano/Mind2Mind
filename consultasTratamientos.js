var botonTranscraneal = document.getElementById("Transcraneal");
var botonTerapiaLuz = document.getElementById("TerapiaLuz");
var botonElectroconvulsiva = document.getElementById("Electroconvulsiva");
var botonPsicoterapia = document.getElementById("Psicoterapia");
var botonMindfulness = document.getElementById("Mindfulness");
var botonArteExpresivo = document.getElementById("ArteExpresivo");

var bandera = false;
if (botonTranscraneal) {
    bandera = true;
    botonTranscraneal.addEventListener("click", () => {
        mostrarDatosTerapia("Est. Transcraneal");
        window.location.href = "terapiaEspecifica.html";
    });

    botonTerapiaLuz.addEventListener("click", () => {
        mostrarDatosTerapia("Terapia de Luz");
        window.location.href = "terapiaEspecifica.html";
    });

    botonElectroconvulsiva.addEventListener("click", () => {
        mostrarDatosTerapia("Electroconvulsiva");
        window.location.href = "terapiaEspecifica.html";
    });

    botonPsicoterapia.addEventListener("click", () => {
        mostrarDatosTerapia("Psicoterapia");
        window.location.href = "terapiaEspecifica.html";
    });

    botonMindfulness.addEventListener("click", () => {
        mostrarDatosTerapia("Mindfulness");
        window.location.href = "terapiaEspecifica.html";
    });

    botonArteExpresivo.addEventListener("click", () => {
        mostrarDatosTerapia("Arte Expresivo");
        window.location.href = "terapiaEspecifica.html";
    });
}
if (!bandera && sessionStorage.getItem("terapia")) {
    var terapia = JSON.parse(sessionStorage.getItem("terapia"));
    console.log(terapia)
    mostrarDatosTerapia(terapia.nombre);
    bandera = true;
}

function mostrarDatosTerapia(nombre) {
    console.log(nombre);
    $.ajax({
        type: "POST",
        url: "BBDD/selectsDatos.php",
        data: {
            funcion: "mostrarDatosTerapia",
            nombre: nombre,
        },
        success: function(response) {
            //Comprobacion de la consulta
            if (response.status === "success") {
                sessionStorage.setItem("terapia", JSON.stringify(response.terapia));
                var terapia = JSON.parse(sessionStorage.getItem("terapia"));
                console.log(terapia)
                datosXPantalla(terapia);
                comprobarTerapia();

            } else {
                console.log(response.message);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error("Error en la solicitud Ajax:", textStatus, errorThrown);
        },
    });
}

function datosXPantalla(terapia) {
    // Parsear los datos y mostrar la parte por defecto
    mostrarDatosTerapia2(terapia, "parte1");

    // También puedes mostrar el nombre de la terapia al cargar la página
    $(".nombreTerapia").text(terapia.nombre);
    $(".descripcion1").text(terapia.descripcion);
    $(".descripcion2").text(terapia.mini_descripcion);
    $(".precioAlto").text(Math.floor(terapia.coste * 1.2) + "€");
    $(".precio").text(Math.floor(terapia.coste) + "€");
    $(".imgTerapia").attr("src", "img/Tratamientos/" + terapia.imagen);
    $(".video").attr("src", terapia.link_al_video);
}
//mostrar los datos
function mostrarDatosTerapia2(terapia, parte) {
    // Mostrar cada parte en el elemento correspondiente
    $(".textoTratamiento").text(terapia[parte]);
    // $(".nombreTerapia").text(terapia.nombre);
}
// Inicializar el índice del doctor en 0
let terapiasIndex = 0;

// Definir la cantidad total de doctores (puedes ajustar esto según tus necesidades)
const cantidadTerapias = 5;

var parte1 = document.getElementById("parte1");
var parte2 = document.getElementById("parte2");
var parte3 = document.getElementById("parte3");
var parte4 = document.getElementById("parte4");

if (parte1 && parte2 && parte3 && parte4) {
    // Evento de clic para parte1
    parte1.addEventListener("click", function() {
        quitarEstiloParte();
        parte1.classList.add("parteVisualizando");
        mostrarDatosTerapia2(
            JSON.parse(sessionStorage.getItem("terapia")),
            "parte1"
        );
    });

    // Evento de clic para parte2
    parte2.addEventListener("click", function() {
        quitarEstiloParte();
        parte2.classList.add("parteVisualizando");
        mostrarDatosTerapia2(
            JSON.parse(sessionStorage.getItem("terapia")),
            "parte2"
        );
    });

    // Evento de clic para parte3
    parte3.addEventListener("click", function() {
        quitarEstiloParte();
        parte3.classList.add("parteVisualizando");
        mostrarDatosTerapia2(
            JSON.parse(sessionStorage.getItem("terapia")),
            "parte3"
        );
    });

    // Evento de clic para parte4
    parte4.addEventListener("click", function() {
        quitarEstiloParte();
        parte4.classList.add("parteVisualizando");
        mostrarDatosTerapia2(
            JSON.parse(sessionStorage.getItem("terapia")),
            "parte4"
        );
    });
}

function quitarEstiloParte() {
    var parte1 = document.getElementById("parte1");
    var parte2 = document.getElementById("parte2");
    var parte3 = document.getElementById("parte3");
    var parte4 = document.getElementById("parte4");

    // Remover la clase de cada parte
    parte1.classList.remove("parteVisualizando");
    parte2.classList.remove("parteVisualizando");
    parte3.classList.remove("parteVisualizando");
    parte4.classList.remove("parteVisualizando");
}

//Visualizacion de la pagina en funcion de si esta logueado o no
var cajaPrecio = document.getElementById("cajaPrecio");
var corazonVacio = document.getElementById("corazonVacio");
var corazonLleno = document.getElementById("corazonLleno");
var sesionIniciada = sessionStorage.getItem("sesionIniciada");

//Visualizacion sesion NO INICIADA
if (cajaPrecio && sesionIniciada !== "true") {
    var logueo = document.querySelector(".modal-LogIn");

    cajaPrecio.addEventListener("click", function() {
        logueo.classList.add("show");
    });
    corazonVacio.addEventListener("click", function() {
        logueo.classList.add("show");
    });

    //Visualiacion sesion INICIADA (CORAZONES)
} else if (cajaPrecio && sesionIniciada === "true") {
    console.log("hola buebnos dias")
    corazonVacio.addEventListener("click", function() {
        corazonVacio.style.display = "none";
        corazonLleno.style.display = "block";
        agregarTerapia();
    });
    corazonLleno.addEventListener("click", function() {
        corazonLleno.style.display = "none";
        corazonVacio.style.display = "block";
        eliminarTerapia();
    });
}

//----------------------------------------------------------FUNCIONES---------------------------------------------------------------
//Agregar terapia a la BBDD
function agregarTerapia() {
    //Mostrar los datos en la pagina TerapiaEspecifica
    var datosTerapia = sessionStorage.getItem("terapia");
    var datosUsuario = sessionStorage.getItem("correoUsuario");
    var terapia = JSON.parse(datosTerapia);

    //Pagina logIn
    $.ajax({
        type: "POST",
        url: "BBDD/insertarDatos.php", // Nombre de tu script PHP
        data: {
            funcion: "vincularTerapia",
            nombre: terapia.nombre,
            idPaciente: datosUsuario
        },
        success: function(response) {
            //Comprobacion de la consulta
            if (response.status === "success") {
                console.log(response.message);
            } else {
                console.log(response.message);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error("Error en la solicitud Ajax:", textStatus, errorThrown);
        },
    });
}
//Eliminar terapia de la BBDD
function eliminarTerapia() {
    //Mostrar los datos en la pagina TerapiaEspecifica
    var datosTerapia = sessionStorage.getItem("terapia");
    var datosUsuario = sessionStorage.getItem("correoUsuario");
    var terapia = JSON.parse(datosTerapia);

    $.ajax({
        type: "POST",
        url: "BBDD/insertarDatos.php", // Nombre de tu script PHP
        data: {
            funcion: "eliminarTerapia",
            nombre: terapia.nombre,
            idPaciente: datosUsuario
        },
        success: function(response) {
            //Comprobacion de la consulta
            if (response.status === "success") {
                console.log(response.message);
            } else {
                console.log(response.message);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error("Error en la solicitud Ajax:", textStatus, errorThrown);
        },
    });
}

function comprobarTerapia() {
    var corazonVacio = document.getElementById("corazonVacio");
    var corazonLleno = document.getElementById("corazonLleno");
    //Mostrar los datos en la pagina TerapiaEspecifica
    var datosTerapia = sessionStorage.getItem("terapia");
    var datosUsuario = sessionStorage.getItem("correoUsuario");
    var terapia = JSON.parse(datosTerapia);
    console.log("TERAPIA FINAL: " + terapia)
    console.log(datosUsuario)
    console.log(terapia.idTerapia)
    $.ajax({
        type: "POST",
        url: "BBDD/selectsDatos.php", // Nombre de tu script PHP
        data: {
            funcion: "comprobarTerapia",
            idTerapia: terapia.idTerapia,
            idPaciente: datosUsuario
        },
        success: function(response) {
            //Comprobacion de la consulta
            if (response.status === "success") {
                console.log("hola desaparece")
                corazonVacio.style.display = "none"
                console.log(response.message)
                corazonLleno.style.display = "block"
            } else {
                console.log("hola aparece")
                console.log(response.message)
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error("Error en la solicitud Ajax:", textStatus, errorThrown);
        },
    });
}