//Carrousel perfil
const contenedorPerfil = document.querySelector("#perfilInfo");
const linksPerfil = document.querySelectorAll(".divisiones");

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
//-------------------------------------------------FUNCIONES MOSTRAR PERFIL----------------------------------------------
var mostrarCuenta = document.getElementById("account");

mostrarCuenta.addEventListener("click", function () {
  consultaEditarPerfil();
});

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

//------------------------------------------------------ACCIONES DEL PEFIL---------------------------------------------------------------------------

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
    success: function (response) {
      //Comprobacion de la consulta
      if (response.status === "success") {
        console.log(response.paciente);

        mostrarPerfil(response.paciente);
      } else {
        //Acciones que hace si es erroneo el login
        console.log(response.message);
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.error("Error en la solicitud Ajax:", textStatus, errorThrown);
    },
  });
}
function mostrarPerfil(email) {
  // Actualizar elementos HTML en servicios.html con la información del doctor
  $("#correoElectronico").val(email.correoElectronico || "");

  $("#nombre").val(email.nombre || "");

  $("#apellidos").val(email.apellidos || "");

  $("#contraseña").val(email.contraseña || "");

  $("#dni").val(email.dni || "");

  $("#provincia").val(email.provincia || "");

  $("#domicilio").val(email.domicilio || "");

  $("#genero").val(email.genero || "");
}

function insertarEditarPerfil() {
  var correoElectronico = document.getElementById("correoElectronico").value;
  var nombre = document.getElementById("nombre").value;
  var apellidos = document.getElementById("apellidos").value;
  var contraseña = document.getElementById("contraseña").value;
  var dni = document.getElementById("dni").value;
  var provincia = document.getElementById("provincia").value;
  var domicilio = document.getElementById("domicilio").value;
  var genero = document.getElementById("genero").value;
}
