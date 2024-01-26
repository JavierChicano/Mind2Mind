//Validacion de formularios







//Formulario registro información
document.getElementById("formRegistro").addEventListener("submit", function(event) {
    event.preventDefault(); 

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
  
  function validacion() { 
    // Comprobacion del nombre vacío
    if (nombreVacio()) { 
       alert('[ERROR] El nombre no tiene contenido.')
       return false
    }
    if(telefonoVacio()){
        alert('[ERROR] El telefono no tiene contenido.');
        return false;
    }
    if(telefonoSoloNumeros()){
        alert('[ERROR] El telefono no tiene numeros.');
        return false;
    }
    if(!telefonoLongitudCorrecta()){
        alert('[ERROR] El telefono no tiene la longitud correcta.');
        return false;
    }
    if(!dniValido()){
        alert('[ERROR] El dni no es correcto.');
        return false;
    }
 
    return true
 }
 
 function nombreVacio() { 
 
    let valor = document.getElementById('nombre').value
 
    if (valor == null || valor.length == 0 || /^\s+$/.test(valor)) {
       return true
    } else { 
       return false
    }
 } 
 function telefonoVacio() { 
    let valor = document.getElementById('telefono').value
    if (valor == null || valor.length == 0 || /^\s+$/.test(valor)) {
       return true
    } else { 
       return false
    }
 } 
 function telefonoSoloNumeros(){
    let valor = document.getElementById('telefono').value
    if(isNaN(valor)){
        return true;
    }else{
        return false;
    }
 }
 function telefonoLongitudCorrecta(){
    let valor = document.getElementById('telefono').value
    if(/^\d{9}$/.test(valor)){
        return true;
    }else{
        return false;
    }
 }
function dniValido(){
    let valor = document.getElementById('dni').value
    if(/^\d{8}[A-Z]$/.test(valor)){
        return true;
    }else{
        return false;
    }
}