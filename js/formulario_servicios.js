"use strict"

let formulario=document.getElementById("form");
let formModificar = document.getElementById("form-modificar");
let descripcion = document.getElementById('descripcion');
let precio_servicio = document.getElementById('precio_servicio');
let duracion_servicio=document.getElementById("duracion_servicio");
 






document.addEventListener('DOMContentLoaded', () => {
  // Validación para el formulario principal

  if (formulario) {
      formulario.addEventListener("submit", (evento) => {
          let validaciones = [validarDescripcion, validarDuracion_servicio, validarPrecio_servicio];
          let esValido = validaciones.every(validar => validar());
          if (!esValido) {
              evento.preventDefault();
          }
      });
  }

  // Delegación para el formulario dinámico
  document.body.addEventListener('submit', (evento) => {
      if (evento.target && evento.target.id === 'form-modificar') {
          let validaciones = [validarDescripcion, validarDuracion_servicio, validarPrecio_servicio];
          let esValido = validaciones.every(validar => validar());
          if (!esValido) {
              evento.preventDefault();
          }
      }
  });
});



const validarDescripcion = () => {

    let valor=descripcion.value.trim();
    let span_error=descripcion.nextElementSibling;
    if (valor === "") {
      span_error.style.display = "inline";
      span_error.innerText = "El campo de la descripcion no puede estar vacío.";
      return false;
    }

    else if(!/[A-Za-z]{3,50}/.test(descripcion.value.trim())){

      span_error.classList.add("mal");
      span_error.style.display = "inline";
      span_error.classList.remove("bien");
      span_error.innerHTML="tiene que tener entre 3 y 50 caracteres";
      return false;
      
    }else{
      span_error.classList.remove("mal");
      span_error.classList.add("bien");
      span_error.innerText="";
      span_error.style.display = "none";

    }

    return true;
 
};


const validarPrecio_servicio=()=>{

        let valor_numero =parseInt(precio_servicio.value.trim());
        let span_error=precio_servicio.nextElementSibling;
        if(isNaN(valor_numero)){
          span_error.style.display="inline";
          span_error.innerText="Debe ser un numero";
            return false;
        }

        else if (valor_numero <= 0){
          span_error.style.display="inline";
          span_error.classList.add("mal");
          span_error.classList.remove("bien");
          span_error.innerText="el precio del servicio debe ser mayor de 0";
            return false;
        }else{
          span_error.classList.remove("mal");
          span_error.classList.add("bien");
          span_error.innerText="";
          span_error.style.display="none";
        }
        
        return true;
};


  const validarDuracion_servicio=()=>{
    let duracion =parseInt(duracion_servicio.value.trim());
    let span_error=duracion_servicio.nextElementSibling;

    if(isNaN(duracion)){
      span_error.style.display="inline";
      span_error.classList.add("mal");
      span_error.classList.remove("bien");
      span_error.innerText="Debe ser un numero";
        return false;
    }
    else if(duracion < 15){
      span_error.style.display="inline";
      span_error.classList.add("mal");
      span_error.classList.remove("bien");
      span_error.innerHTML="Debe durar al menos 15 minutos";
      return false;
      
    }else{
      span_error.innerText="";
      span_error.classList.remove("mal");
      span_error.classList.add("bien");
      
      span_error.style.display = "none";
    }
    
    return true;
};



descripcion.addEventListener("input",
  () =>{
    validarDescripcion();
   
  }

);
precio_servicio.addEventListener("input",
  () =>{
    validarPrecio_servicio();
  }
);

duracion_servicio.addEventListener("input",
  () =>{
    validarDuracion_servicio();
  }
);

