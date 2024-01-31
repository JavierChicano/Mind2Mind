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
console.log(linksPerfil);
console.log(contenedorPerfil)
console.log(linksPerfil[1])
// Evento de clic en los puntos
linksPerfil.forEach((link, i) => {
  linksPerfil[i].addEventListener("click", () => {
    console.log("ldsalfd");
    currentIndexPerfil = i;
    cambiarDivPerfil(currentIndexPerfil);
  });
});
