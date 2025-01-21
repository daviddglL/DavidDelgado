"use strict"

let formulario=document.getElementById("form");
let socio = document.getElementById('defecto_socio');
let servicio = document.getElementById('defecto_servicio');
let fecha=document.getElementById('fecha');


socio.addEventListener("change",
  ()=>{
    validarSocio();

  }
);
servicio.addEventListener("change",
  ()=>{
    validarServicio();
  }
);

fecha.addEventListener('input', 
    ()=>{
        validarFechaPublicacion();
    }
    
);


formulario.addEventListener("submit", 
  (evento) => {
  let validaciones = [validarSocio, validarServicio,validarFechaPublicacion];
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



const validarServicio = () => {

  let span_error =servicio.nextElementSibling;
  if (servicio.selectedIndex === 0) {
    
    span_error.style.display="inline";
    span_error.innerText="El campo del nombre no puede estar vacio";
    return false;
  }
  span_error.style.display="none";
  return true;
};





const validarSocio = () => {

  let span_error =socio.nextElementSibling;
  if (socio.selectedIndex === 0) {
    
    span_error.style.display="inline";
    span_error.innerText="El campo del nombre no puede estar vacio";
    return false;
  }
  span_error.style.display="none";
  return true;
};


const validarFechaPublicacion = () => {

    let spanError = fecha.nextElementSibling;

    // Obtener la fecha actual
    let fechaActual = new Date();
    fechaActual.setHours(0, 0, 0, 0); // Ignorar la hora actual, solo usar la fecha

    // Convertir el valor del input a un objeto Date
    let fechaIngresada = new Date(fecha.value);

    // Validar si la fecha es válida y posterior a la actual
    if (isNaN(fechaIngresada.getTime())) {
        spanError.style.display = "inline";
        spanError.classList.add("mal");
        spanError.classList.remove("bien");
        spanError.innerText = "La fecha ingresada no es válida.";
        return false;
    } else if (fechaIngresada <= fechaActual) {
        spanError.style.display = "inline";
        spanError.classList.add("mal");
        spanError.classList.remove("bien");
        spanError.innerText = "La fecha debe ser posterior a la fecha actual.";
        return false;
    } else {
        spanError.style.display = "none";
        spanError.classList.remove("mal");
        spanError.classList.add("bien");
        spanError.innerText = "";
    }

    return true;
};
