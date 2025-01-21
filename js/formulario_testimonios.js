"use strict"

let formulario=document.getElementById("form");
let descripcion = document.getElementById('contenido');


 

contenido.addEventListener("input",
  () =>{
    validarContenido();
   
  }

);


formulario.addEventListener("submit", 
  (evento) => {
  let validaciones = [validarContenido];
  let esValido = true;
  for (let validar of validaciones) {
      if (!validar()) {
          esValido = false;
          break;
      }
  }
  if (!esValido) {
      evento.preventDefault();
  }
});




const validarContenido = () => {

    let valor=contenido.value.trim();
    let span_error=contenido.nextElementSibling;
    if (valor === "") {
      span_error.style.display = "inline";
      span_error.innerText = "El campo contenido no puede estar vacío.";
      return false;
      
    }else{
      span_error.innerText="";
      span_error.style.display = "none";

    }

    return true;
 
};

