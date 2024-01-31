const nav = document.querySelector("#nav");
const abrir = document.querySelector("#abrir");
const cerrar = document.querySelector("#cerrar");

abrir.addEventListener("click", () => {
  nav.classList.add("visible");
  console.log(nav);
});
cerrar.addEventListener("click", () => {
  nav.classList.remove("visible");
});

//Ventana modal Registro
const abrirModal = document.querySelector(".openRegistro");
const contendorModal = document.querySelector(".modal-Registro");
const cerrarModal = document.querySelector(".closeRegistro");
const abrilModalCuenta = document.getElementById("creaCuenta");

abrirModal.addEventListener("click", () => {
  contendorModal2.classList.remove("show");
  contendorModal.classList.add("show");
});
if (abrilModalCuenta) {
  abrilModalCuenta.addEventListener("click", () => {
    contendorModal.classList.add("show");
  });
}

cerrarModal.addEventListener("click", () => {
  contendorModal.classList.remove("show");
});

//Ventana modal LogIn
const abrirModal2 = document.querySelector(".openLogIn");
const contendorModal2 = document.querySelector(".modal-LogIn");
const cerrarModal2 = document.querySelector(".closeLogIn");

abrirModal2.addEventListener("click", () => {
  contendorModal.classList.remove("show");
  contendorModal2.classList.add("show");
});

cerrarModal2.addEventListener("click", () => {
  contendorModal2.classList.remove("show");
});

//Intercambio de ventanas
const abrirLogIn = document.getElementById("link-A-LogIn");
const abrirRegistro = document.getElementById("link-A-Registro");

abrirLogIn.addEventListener("click", () => {
  //Cerrar la pestaña de registro
  contendorModal.classList.remove("show");

  //Abrir la pestaña de logIn
  contendorModal2.classList.add("show");
});

abrirRegistro.addEventListener("click", () => {
  //Cerrar la pestaña de LogIn
  contendorModal2.classList.remove("show");

  //Abrir la pestaña de Registro
  contendorModal.classList.add("show");
});

//Conexion de la BBDD con el index.html
$(document).ready(function () {
  $.ajax({
    url: "BBDD/BBDD.php",
    type: "GET",
    success: function (response) {
      console.log(response);
    },
    error: function (error) {
      console.error("Error al ejecutar el código PHP:", error);
    },
  });
});

//Carrousel imagemes
const grande = document.querySelector(".contenedorIMG");
const punto = document.querySelectorAll(".punto");

let currentIndex = 0;

// Función para cambiar el div según el índice
function cambiarDiv(index) {
  let operacion = index * -25;
  grande.style.transform = `translateX(${operacion}%)`;

  punto.forEach((cadaPunto, i) => {
    punto[i].classList.remove("activo");
  });
  punto[index].classList.add("activo");
}

if (grande) {
  // Evento de clic en los puntos
  punto.forEach((cadaPunto, i) => {
    punto[i].addEventListener("click", () => {
      currentIndex = i;
      cambiarDiv(currentIndex);
    });
  });

  // Cambiar automáticamente cada 5 segundos (ajusta según tus necesidades)
  setInterval(() => {
    currentIndex = (currentIndex + 1) % punto.length;
    cambiarDiv(currentIndex);
  }, 5000); // Cambia cada 5 segundos (5000 milisegundos)
}

// Carrousel Tratamientos
const contenedorTratamientos = document.querySelector("#contenedorTratamientos");
const linksTratamientos = document.querySelectorAll(".linksTratamientos");

let currentIndexTratamientos = 0;

// Función para cambiar el div según el índice
function cambiarDivTratamientos(index) {
  let operation = index * -33.333;
  contenedorTratamientos.style.transform = `translateX(${operation}%)`;

  linksTratamientos.forEach((link, i) => {
    linksTratamientos[i].classList.remove("selected");
  });
  linksTratamientos[index].classList.add("selected");
}
if (linksTratamientos) {
  // Evento de clic en los puntos
  linksTratamientos.forEach((link, i) => {
    linksTratamientos[i].addEventListener("click", () => {
      currentIndexTratamientos = i;
      cambiarDivTratamientos(currentIndexTratamientos);
    });
  });
}
