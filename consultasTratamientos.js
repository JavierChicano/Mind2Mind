var botonTranscraneal = document.getElementById("Transcraneal");
var botonTerapiaLuz = document.getElementById("TerapiaLuz");
var botonElectroconvulsiva = document.getElementById("Electroconvulsiva");
var botonPsicoterapia = document.getElementById("Psicoterapia");
var botonMindfulness = document.getElementById("Mindfulness");
var botonArteExpresivo = document.getElementById("ArteExpresivo");

if (botonTranscraneal) {
    botonTranscraneal.addEventListener("click", () => {
        mostrarDatosTerapia("Est. Transcraneal");
    });

    botonTerapiaLuz.addEventListener("click", () => {
        mostrarDatosTerapia("Terapia de Luz");
    });

    botonElectroconvulsiva.addEventListener("click", () => {
        mostrarDatosTerapia("Electroconvulsiva");
    });

    botonPsicoterapia.addEventListener("click", () => {
        mostrarDatosTerapia("Psicoterapia");
    });

    botonMindfulness.addEventListener("click", () => {
        mostrarDatosTerapia("Terapia de Mindfulness");
    });

    botonArteExpresivo.addEventListener("click", () => {
        mostrarDatosTerapia("Terapia de Arte Expresivo");
    });
}

function mostrarDatosTerapia(nombre) {
    console.log(nombre)
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
                sessionStorage.setItem('terapia', JSON.stringify(response.terapia));
                window.location.href = "terapiaEspecifica.html";
            } else {
                console.log(response.message);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error("Error en la solicitud Ajax:", textStatus, errorThrown);
        },
    });
}

//Mostrar los datos en la pagina TerapiaEspecifica
var datosTerapia = sessionStorage.getItem('terapia');
// Verificar si los datos de la terapia están disponibles
if (datosTerapia) {
    // Parsear los datos y mostrar la parte por defecto
    var terapia = JSON.parse(datosTerapia);
    mostrarDatosTerapia2(terapia, "parte1");

    // También puedes mostrar el nombre de la terapia al cargar la página
    $(".nombreTerapia").text(terapia.nombre);
    $(".descripcion1").text(terapia.descripcion);
    $(".descripcion2").text(terapia.mini_descripcion);
    $(".precio").text(terapia.coste);
    $(".imgTerapia").attr("src", "img/Tratamientos/" + terapia.imagen);
} else {
    console.error("No se encontraron datos de terapia en sessionStorage");
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
        mostrarDatosTerapia2(JSON.parse(sessionStorage.getItem('terapia')), "parte1");
    });

    // Evento de clic para parte2
    parte2.addEventListener("click", function() {
        mostrarDatosTerapia2(JSON.parse(sessionStorage.getItem('terapia')), "parte2");
    });

    // Evento de clic para parte3
    parte3.addEventListener("click", function() {
        mostrarDatosTerapia2(JSON.parse(sessionStorage.getItem('terapia')), "parte3");
    });

    // Evento de clic para parte4
    parte4.addEventListener("click", function() {
        mostrarDatosTerapia2(JSON.parse(sessionStorage.getItem('terapia')), "parte4");
    });
}