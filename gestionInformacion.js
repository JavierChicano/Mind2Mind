//Formulario registro
document.getElementById("formRegistro").addEventListener("submit", function(event) {
    event.preventDefault(); // Evita que el formulario se envíe automáticamente

    // Obtener los valores de los campos del formulario
    var nombre = document.getElementById("nombreRegistro").value;
    var apellidos = document.getElementById("apellidosRegistro").value;
    var email = document.getElementById("emailRegistro").value;
    var password = document.getElementById("passwordRegistro").value;
    var aceptoTerminos = document.getElementById("aceptoTerminosRegistro").checked;

    // Puedes hacer lo que quieras con los datos, por ejemplo, imprimirlos en la consola
    console.log("Nombre:", nombre);
    console.log("Apellidos:", apellidos);
    console.log("Correo electrónico:", email);
    console.log("Contraseña:", password);
    console.log("Acepto los términos y condiciones:", aceptoTerminos);

    // Aquí podrías enviar los datos a un servidor mediante una solicitud AJAX o realizar otras acciones según tus necesidades
  });