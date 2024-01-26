//Formularios
var formRegistro = document.getElementById("formRegistro");
var formLogIn = document.getElementById("formLogIn");
var formContacto = document.getElementById("formContacto");

if (formRegistro) {
  formRegistro.addEventListener("submit", function (event) {
    event.preventDefault();

    // Obtener los valores de los campos del formulario
    var nombre = document.getElementById("nombreRegistro").value;
    var apellidos = document.getElementById("apellidosRegistro").value;
    var email = document.getElementById("emailRegistro").value;
    var password = document.getElementById("passwordRegistro").value;
    var displayErrores = document.getElementById("displayErroresRegistro");

    // Validacion de formularios
    var textoLetras = /^[A-Za-z]+$/;

    if (!textoLetras.test(nombre)) {
      displayErrores.textContent = "El nombre contiene caracteres invalidos";
      document.getElementById("nombreRegistro").style.border = "1px solid red";
      return false;
    } else {
      document.getElementById("nombreRegistro").style.border = "";
      displayErrores.textContent = "";
    }

    if (!textoLetras.test(apellidos)) {
      displayErrores.textContent =
        "Los apellidos contienen caracteres invalidos";
      document.getElementById("apellidosRegistro").style.border =
        "1px solid red";
      return false;
    } else {
      document.getElementById("apellidosRegistro").style.border = "";
      displayErrores.textContent = "";
    }

    // Validar el formato del correo electrónico
    var comprobacionCorreo = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!comprobacionCorreo.test(email)) {
      displayErrores.textContent =
        "El correo electrónico tiene un formato inválido";
      document.getElementById("emailRegistro").style.border = "1px solid red";
      return false;
    } else {
      document.getElementById("emailRegistro").style.border = "";
      displayErrores.textContent = "";
    }

    // Validar la robustez de la contraseña
    // var comprobacionContraseña = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;

    // if (!comprobacionContraseña.test(password)) {
    //     mostrarError(document.getElementById("passwordRegistro"), "La contraseña no cumple con los criterios de seguridad");
    //     return false;
    // }

    // Si todas las validaciones pasan, puedes continuar con el envío del formulario
    console.log("Formulario válido. Proceder con el envío.");
    return true;
  });
}

//Formulario login informacion

if (formLogIn) {
  formLogIn.addEventListener("submit", function (event) {
    event.preventDefault();

    //Obtener valores del formulario
    var email = document.getElementById("emailLogin").value;
    var password = document.getElementById("passwordLogin").value;
    var displayErrores = document.getElementById("displayErroresRegistro");

    // Validar el formato del correo electrónico
    var comprobacionCorreo = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!comprobacionCorreo.test(email)) {
      console.log("El correo electrónico tiene un formato inválido");
      return false;
    }

    // Validar la robustez de la contraseña
    var comprobacionContraseña = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;
    if (!comprobacionContraseña.test(password)) {
      console.log("La contraseña no cumple con los criterios de seguridad");
      return false;
    }
  });
}

//Formulario login informacion
if (formContacto) {
  formContacto.addEventListener("submit", function (event) {
    event.preventDefault();

    // Obtener los valores de los campos del formulario
    var nombre = document.getElementById("nombreContacto").value;
    var apellidos = document.getElementById("apellidosContacto").value;
    var email = document.getElementById("emailContacto").value;
    var telefono = document.getElementById("telefonoContacto").value;
    var displayErrores = document.getElementById("displayErroresRegistro2");

    //  var info = document.getElementById("consultaContacto").value;

    // Validacion de formularios
    var textoLetras = /^[A-Za-z]+$/;

    if (!textoLetras.test(nombre)) {
      displayErrores.textContent = "El nombre contiene caracteres invalidos";
      document.getElementById("nombreContacto").style.border = "1px solid red";
      return false;
    } else {
      document.getElementById("nombreContacto").style.border = "";
      displayErrores.textContent = "";
    }

    if (!textoLetras.test(apellidos)) {
      displayErrores.textContent =
        "Los apellidos contienen caracteres invalidos";
      document.getElementById("apellidosContacto").style.border =
        "1px solid red";
      return false;
    } else {
      document.getElementById("apellidosContacto").style.border = "";
      displayErrores.textContent = "";
    }

    // Validar el formato del correo electrónico
    var comprobacionCorreo = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!comprobacionCorreo.test(email)) {
      console.log("El correo electrónico tiene un formato inválido");
      return false;
    }
    // Validar que el teléfono solo contenga números
    if (!/^\d+$/.test(telefono)) {
      displayErrores.textContent = "El teléfono contiene caracteres inválidos";
      document.getElementById("telefonoContacto").style.border =
        "1px solid red";
      return false;
    } else {
      document.getElementById("telefonoContacto").style.border = "";
      displayErrores.textContent = "";
    }
  });
}
