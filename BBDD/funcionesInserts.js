//Insert del registro
export default function insertarRegistro(nombre,apellidos,email,password){
    //Inserccion en BBDD
  //Pagina Registro
  $.ajax({
    type: "POST",
    url: "BBDD/insertarDatos.php", // Nombre de tu script PHP
    data: {
       nombre: nombre,
       apellidos: apellidos,
       email: email,
       password: password
    },
    success: function(response) {
       console.log(response); // Manejar la respuesta del servidor
    }
  });
  }