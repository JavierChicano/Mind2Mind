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
    success: function (response) {
      //Comprobacion de la consulta
      if (response.status === "success") {
        sessionStorage.setItem('terapia', JSON.stringify(response.terapia));
        window.location.href = "terapiaEspecifica.html";
      } else {
        console.log(response.message);
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.error("Error en la solicitud Ajax:", textStatus, errorThrown);
    },
  });
}

//Mostrar los datos en la pagina TerapiaEspecifica
var datosTerapia = sessionStorage.getItem('terapia');
//Controlar que estas en esa pagina (TerapiaEspecifica)
//mostrar los datos