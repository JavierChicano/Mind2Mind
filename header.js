const nav = document.querySelector("#nav");
const abrir = document.querySelector("#abrir");
const cerrar = document.querySelector("#cerrar");

abrir.addEventListener("click", ()=>{
    nav.classList.add("visible");
    console.log(nav)
})
cerrar.addEventListener("click", ()=>{
    nav.classList.remove("visible");
})

//Ventana modal Registro
const abrirModal = document.getElementById('openRegistro');
const contendorModal = document.getElementById('modal-Registro');
const cerrarModal = document.getElementById('closeRegistro');
const abrilModalCuenta = document.getElementById('creaCuenta');

abrirModal.addEventListener("click", ()=>{
    contendorModal2.classList.remove('show');
    contendorModal.classList.add('show');
});
abrilModalCuenta.addEventListener("click", ()=>{
    contendorModal.classList.add('show');
});
cerrarModal.addEventListener("click", ()=>{
    contendorModal.classList.remove('show');
});

//Ventana modal LogIn
const abrirModal2 = document.getElementById('openLogIn');
const contendorModal2 = document.getElementById('modal-LogIn');
const cerrarModal2 = document.getElementById('closeLogIn');

abrirModal2.addEventListener("click", ()=>{
    contendorModal.classList.remove('show');
    contendorModal2.classList.add('show');
});
cerrarModal2.addEventListener("click", ()=>{
    contendorModal2.classList.remove('show');
});

//Intercambio de ventanas
const abrirLogIn = document.getElementById('link-A-LogIn');
const abrirRegistro = document.getElementById('link-A-Registro');

abrirLogIn.addEventListener("click", ()=>{
    //Cerrar la pestaña de registro
    contendorModal.classList.remove('show');

    //Abrir la pestaña de logIn
    contendorModal2.classList.add('show');
});

abrirRegistro.addEventListener("click", ()=>{
    //Cerrar la pestaña de LogIn
    contendorModal2.classList.remove('show');

    //Abrir la pestaña de Registro
    contendorModal.classList.add('show');
});

$(document).ready(function() {
    
    $.ajax({
        url: 'BBDD/BBDD.php',
        type: 'GET',
        success: function(response) {
            console.log(response);
        },
        error: function(error) {
            console.error('Error al ejecutar el código PHP:', error);
        }
    });
});