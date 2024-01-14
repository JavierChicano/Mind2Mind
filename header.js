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

//Ventana modal
const abrirModal = document.getElementById('openRegistro');
const contendorModal = document.getElementById('modal-Registro');
const cerrarModal = document.getElementById('closeRegistro');

abrirModal.addEventListener("click", ()=>{
    contendorModal.classList.add('show');
});
cerrarModal.addEventListener("click", ()=>{
    contendorModal.classList.remove('show');
});