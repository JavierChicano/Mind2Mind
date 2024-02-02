// import insertarRegistro from "./BBDD/funcionesInserts.js";

//Formularios
var formRegistro = document.getElementById("formRegistro");
var formLogIn = document.getElementById("formLogIn");
var formContacto = document.getElementById("formContacto");
var formPedirCita = document.getElementById("formPedirCita");

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
    insertarRegistro(nombre, apellidos, email, password);
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

    consultarLogin(email, password);
  });
}

//Formulario contacto
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
    if (!/^\d$/.test(telefono)) {
      displayErrores.textContent =
        "El teléfono debe contener 9 dígitos numéricos";
      document.getElementById("telefonoContacto").style.border =
        "1px solid red";
      return false;
    } else {
      document.getElementById("telefonoContacto").style.border = "";
      displayErrores.textContent = "";
    }
    insertarContacto(nombre, apellidos, email, telefono);
  });
}

//Formulario pedirCita
if (formPedirCita) {
  //Guia
  var primeraParte = document.getElementsByClassName("primeraParte")[0];
  var segundaParte = document.getElementsByClassName("segundaParte")[0];
  var terceraParte = document.getElementsByClassName("terceraParte")[0];

  //Botones
  var bFase1 = document.getElementById("botonSiguiente1");
  var bFase2S = document.getElementById("botonSiguiente2");
  var bFase2A = document.getElementById("botonAnterior1");
  var bFase3A = document.getElementById("botonAnterior2");
  var bFase3E = document.getElementById("botonEnviar");

  //Apartados
  var DPersonales = document.getElementById("datosPersonales");
  var DConsulta = document.getElementById("datosConsulta");
  var ProcesoFinal = document.getElementById("procesoFinal");
  var Enviado = document.getElementById("formEnviado");

  bFase1.addEventListener("click", function (event) {
    var displayErrores = document.getElementById("displayErroresPedirCita");

    // Obtener los valores de los campos del formulario
    var nombre = document.getElementById("nombrePC").value;
    var apellidos = document.getElementById("apellidosPC").value;
    var dni = document.getElementById("dniPC").value;
    var mail = document.getElementById("mailPC").value;
    var provincia = document.getElementById("provinciaPC").value;
    var domicilio = document.getElementById("domicilioPC").value;
    var sexo = document.getElementById("sexoPC").value;

    //Comprobar que los campos estan rellenados
    if (nombre === "") {
      document.getElementById("nombrePC").style.border = "1px solid red";
      displayErrores.textContent = "Por favor, ingrese el nombre.";
      return false;
    } else {
      document.getElementById("nombrePC").style.border = "";
      displayErrores.textContent = "";
    }
    if (apellidos === "") {
      document.getElementById("apellidosPC").style.border = "1px solid red";
      displayErrores.textContent = "Por favor, ingrese los apellidos.";
      return false;
    } else {
      document.getElementById("apellidosPC").style.border = "";
      displayErrores.textContent = "";
    }
    if (dni === "") {
      document.getElementById("dniPC").style.border = "1px solid red";
      displayErrores.textContent = "Por favor, ingrese el DNI.";
      return false;
    } else {
      document.getElementById("dniPC").style.border = "";
      displayErrores.textContent = "";
    }
    if (mail === "") {
      document.getElementById("mailPC").style.border = "1px solid red";
      displayErrores.textContent = "Por favor, ingrese el correo electrónico.";
      return false;
    } else {
      document.getElementById("mailPC").style.border = "";
      displayErrores.textContent = "";
    }
    if (provincia === "") {
      document.getElementById("provinciaPC").style.border = "1px solid red";
      displayErrores.textContent = "Por favor, ingrese la provincia.";
      return false;
    } else {
      document.getElementById("provinciaPC").style.border = "";
      displayErrores.textContent = "";
    }
    if (domicilio === "") {
      document.getElementById("domicilioPC").style.border = "1px solid red";
      displayErrores.textContent = "Por favor, ingrese el domicilio.";
      return false;
    } else {
      document.getElementById("domicilioPC").style.border = "";
      displayErrores.textContent = "";
    }

    // Validacion de formularios
    var textoLetras = /^[A-Za-z]+$/;

    if (!textoLetras.test(nombre)) {
      displayErrores.textContent = "El nombre contiene caracteres invalidos";
      document.getElementById("nombrePC").style.border = "1px solid red";
      return false;
    } else {
      document.getElementById("nombrePC").style.border = "";
      displayErrores.textContent = "";
    }

    if (!textoLetras.test(apellidos)) {
      displayErrores.textContent =
        "Los apellidos contienen caracteres invalidos";
      document.getElementById("apellidosPC").style.border = "1px solid red";
      return false;
    } else {
      document.getElementById("apellidosPC").style.border = "";
      displayErrores.textContent = "";
    }

    var formatoDNI = /^\d{8}[a-zA-Z]$/;

    if (!formatoDNI.test(dni)) {
      displayErrores.textContent =
        "El DNI no tiene el formato correcto (8 Números y 1 Letra)";
      document.getElementById("dniPC").style.border = "1px solid red";
      return false;
    } else {
      document.getElementById("dniPC").style.border = "";
      displayErrores.textContent = "";
    }

    var formatoMail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!formatoMail.test(mail)) {
      displayErrores.textContent =
        "Por favor, ingrese un correo electrónico válido.";
      document.getElementById("mailPC").style.border = "1px solid red";
      return false;
    } else {
      document.getElementById("mailPC").style.border = "";
      displayErrores.textContent = "";
    }
    //Si todo es correcto
    DPersonales.style.display = "none";
    DConsulta.style.display = "grid";
    primeraParte.classList.remove("seleccionado");
    primeraParte.classList.add("completado");
    segundaParte.classList.add("seleccionado");
  });

  //Parte 2
  bFase2S.addEventListener("click", function (event) {
    var displayErrores = document.getElementById("displayErroresPedirCita2");
    var displayStyle = window.getComputedStyle(
      document.getElementById("campoTelefonoPC")
    ).display;

    //Valores Datos Consulta
    var profesional = document.getElementById("profesionalPC").value;
    var modalidad = document.getElementById("modalidadPC").value;
    var telefono = document.getElementById("numeroTelpc").value;
    var diagnostico = document.getElementById("autodiagnosticoPC").value;

    //Comprobar que los campos estan rellenados
    if (displayStyle === "block" && telefono === "") {
      document.getElementById("numeroTelpc").style.border = "1px solid red";
      displayErrores.textContent = "Por favor, ingrese el numero de telefono.";
      return false;
    } else {
      document.getElementById("numeroTelpc").style.border = "";
      displayErrores.textContent = "";
    }

    // Validar que el teléfono solo contenga 9 digitos (Solo si esta visible por pantalla)
    if (displayStyle === "block" && !/^\d{9}$/.test(telefono)) {
      displayErrores.textContent =
        "El teléfono debe contener 9 dígitos numéricos";
      document.getElementById("campoTelefonoPC").style.border = "1px solid red";
      return false;
    } else {
      document.getElementById("campoTelefonoPC").style.border = "";
      displayErrores.textContent = "";
    }

    //Si todo es correcto
    DConsulta.style.display = "none";
    ProcesoFinal.style.display = "grid";
    segundaParte.classList.remove("seleccionado");
    segundaParte.classList.add("completado");
    terceraParte.classList.add("seleccionado");
  });
  //Volver a la primera
  bFase2A.addEventListener("click", () => {
    DPersonales.style.display = "flex";
    DConsulta.style.display = "none";
    segundaParte.classList.remove("seleccionado");
    primeraParte.classList.remove("completado");
    primeraParte.classList.add("seleccionado");
  });

  //Parte 3
  bFase3E.addEventListener("click", function (event) {
    var displayErrores = document.getElementById("displayErroresPedirCita3");
    var displayStyle = window.getComputedStyle(
      document.getElementById("aseguradoraEspecificarPC")
    ).display;
    var checkboxCaja = document.getElementById("checkBox");

    //Obtener valores
    var fConsulta = document.getElementById("fechaConsultaPC").value;
    var poseeAseguradora = document.getElementById("aseguradoraSiNoPC").value;
    var aseguradora = document.getElementById("aseguradoraEspecificaPC").value;
    var checkbox = document.getElementById("aceptoTerminosRegistroPC").value;

    var fechaConsulta = new Date(fConsulta);
    var fechaMinima = new Date();
    //De mañana en adelante
    fechaMinima.setDate(fechaMinima.getDate() + 0.5);

    //Comprobar que los campos estan rellenados
    if (fConsulta === "") {
      document.getElementById("fechaConsultaPC").style.border = "1px solid red";
      displayErrores.textContent = "Por favor, seleccione una fecha.";
      return false;
    } else {
      document.getElementById("fechaConsultaPC").style.border = "";
      displayErrores.textContent = "";
    }
    if (displayStyle === "flex" && aseguradora === "") {
      document.getElementById("aseguradoraEspecificarPC").style.border =
        "1px solid red";
      displayErrores.textContent =
        "Por favor, ingrese el nombre de su aseguradora.";
      return false;
    } else {
      document.getElementById("aseguradoraEspecificarPC").style.border = "";
      displayErrores.textContent = "";
    }
    // console.log(checkbox);
    // if (!checkbox.checked) {
    //   checkboxCaja.style.border = "1px solid red";
    //   displayErrores.textContent =
    //     "Por favor, acepte las condiciones del servicio.";
    //   return false;
    // } else {
    //   checkboxCaja.style.border = "";
    //   displayErrores.textContent = "";
    // }

    //Comprobar la validez de los datos
    if (fechaConsulta < fechaMinima) {
      displayErrores.textContent = "La fecha debe ser a partir de mañana";
      document.getElementById("fechaConsultaPC").style.border = "1px solid red";
      return false;
    } else {
      document.getElementById("fechaConsultaPC").style.border = "";
      displayErrores.textContent = "";
    }

    //Si todo es correcto
    ProcesoFinal.style.display = "none";
    Enviado.style.display = "flex";
    terceraParte.classList.remove("seleccionado");
    terceraParte.classList.add("completado");
    insertarPedirCita(
      nombre,
      apellidos,
      dni,
      mail,
      provincia,
      domicilio,
      sexo,
      profesional,
      modalidad,
      telefono,
      diagnostico,
      fConsulta,
      poseeAseguradora,
      aseguradora
    );
  });

  //Volver a la segunda
  bFase3A.addEventListener("click", () => {
    ProcesoFinal.style.display = "none";
    DConsulta.style.display = "grid";
    terceraParte.classList.remove("seleccionado");
    segundaParte.classList.remove("completado");
    segundaParte.classList.add("seleccionado");
  });
}

function mostrarTelefono() {
  var modalidad = document.getElementById("modalidadPC");
  var campoTelefono = document.getElementById("campoTelefonoPC");

  if (modalidad.value === "telefonica") {
    campoTelefono.style.display = "block";
  } else {
    campoTelefono.style.display = "none";
  }
}
function mostrarAseguradora() {
  var poseeAseguradora = document.getElementById("aseguradoraSiNoPC");
  var aseguradora = document.getElementById("aseguradoraEspecificarPC");

  if (poseeAseguradora.value === "si") {
    aseguradora.style.display = "flex";
  } else {
    aseguradora.style.display = "none";
  }
}

//-------------------------------------------------INSERT BBDD----------------------------------------------

//Insert del registro
function insertarRegistro(nombre, apellidos, email, password) {
  //Inserccion en BBDD
  //Pagina Registro
  $.ajax({
    type: "POST",
    url: "BBDD/insertarDatos.php", // Nombre de tu script PHP
    data: {
      funcion: "registro",
      nombre: nombre,
      apellidos: apellidos,
      email: email,
      password: password,
    },
    success: function (response) {
      console.log(response); // Manejar la respuesta del servidor
    },
  });
}

//Insert del form contacto
function insertarContacto(nombre, apellidos, email, telefono) {
  // Página de registro
  $.ajax({
    type: "POST",
    url: "BBDD/insertarDatos.php", // Nombre de tu script PHP para el registro
    data: {
      funcion: "contacto",
      nombre: nombre,
      apellidos: apellidos,
      email: email,
      telefono: telefono,
    },
    success: function (response) {
      console.log(response); // Manejar la respuesta del servidor
      // Puedes redirigir a otra página o realizar acciones adicionales según la respuesta
    },
  });
}

//Insertar pedir cita
function insertarPedirCita(
  nombre,
  apellidos,
  dni,
  mail,
  provincia,
  domicilio,
  sexo,
  profesional,
  modalidad,
  telefono,
  diagnostico,
  fConsulta,
  poseeAseguradora,
  aseguradora
) {
  // Página de procesamiento del formulario
  $.ajax({
    type: "POST",
    url: "BBDD/procesarFormulario.php", // Nombre de tu script PHP para procesar el formulario
    data: {
      funcion: "pedirCita",
      nombre: nombre,
      apellidos: apellidos,
      dni: dni,
      email: mail,
      provincia: provincia,
      domicilio: domicilio,
      sexo: sexo,
      profesional: profesional,
      modalidad: modalidad,
      telefono: telefono,
      diagnostico: diagnostico,
      fConsulta: fConsulta,
      poseeAseguradora: poseeAseguradora,
      aseguradora: aseguradora,
    },
    success: function (response) {
      console.log(response); // Manejar la respuesta del servidor
      // Puedes redirigir a otra página o realizar acciones adicionales según la respuesta
    },
  });
}

//-------------------------------------------------CONSULTAS BBDD----------------------------------------------

//Consulta del logIn
function consultarLogin(email, password) {
  var mensajeError = document.getElementById("displayErroresLogin");
  var ocultarRegistro = document.getElementsByClassName("openRegistro");
  var ocultarLogin = document.getElementsByClassName("openLogIn");
  var mostrarCuenta = document.getElementsByClassName("account");

  //Pagina logIn
  $.ajax({
    type: "POST",
    url: "BBDD/selectsDatos.php", // Nombre de tu script PHP
    data: {
      funcion: "login",
      email: email,
      password: password,
    },
    success: function (response) {
      //Comprobacion de la consulta
      if (response.status === "success") {
        //Acciones que hace si es correcto el login
        console.log("bienvenido");

        window.location.href = "perfil/perfilMain.html";

        // Ocultar elementos
        ocultarRegistro.style.display = "none";
        ocultarLogin.style.display = "none";
        mostrarCuenta.style.display = "block";
        console.log("hola1");
      } else {
        //Acciones que hace si es erroneo el login
        mensajeError.textContent = response.message;
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.error("Error en la solicitud Ajax:", textStatus, errorThrown);
    },
  });
}
