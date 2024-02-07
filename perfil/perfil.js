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

$("#botonEditarPerfil").on("click", function () {
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
if (singOut) {
  logout();
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
    success: function (response) {
      //Comprobacion de la consulta
      if (response.status === "success") {
        console.log("Insert exitoso: " + response.message);
      } else {
        console.log(response.message);
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.error("Error en la solicitud Ajax:", textStatus, errorThrown);
    },
  });
}
function logout() {
  var botonLogOut = document.getElementById("svgLogout");

  botonLogOut.addEventListener("click", function () {
    // Establecer la sesión como no iniciada
    sessionStorage.setItem("sesionIniciada", "false");
    // Recargar la página para restaurar el estado inicial
    window.location.href = "../index.html";
  });
}
