"use strict"

let formulario=document.getElementById("form");
let titulo = document.getElementById('titulo');
let contenido = document.getElementById('contenido');
let input_ficheros  = document.getElementById("imagen");
let fecha=document.getElementById('fecha');


titulo.addEventListener("input",
  () =>{
    validarTitulo();
   
  }

);
contenido.addEventListener("input",
  () =>{
    validarContenido();
  }
);

input_ficheros.addEventListener("change",
    ()=> {
      validarFichero();
    }
  )

fecha.addEventListener('input', 
    ()=>{
        validarFechaPublicacion();
    }
    
);


formulario.addEventListener("submit", 
  (evento) => {
  let validaciones = [validarTitulo, validarContenido,validarFichero,validarFechaPublicacion];
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

const validarTitulo = () => {

    let valor=titulo.value.trim();
    let span_error=titulo.nextElementSibling;
    if (valor === "") {
      span_error.style.display = "inline";
      span_error.innerText = "El campo de titulo no puede estar vacío.";
      return false;
    }

    else if(titulo.value.trim().length <3){

      span_error.classList.add("mal");
      span_error.style.display = "inline";
      span_error.classList.remove("bien");
      span_error.innerHTML="tiene que tener más de 3 caracteres";
      return false;
      
    }else{
      span_error.classList.remove("mal");
      span_error.classList.add("bien");
      span_error.innerText="";
      span_error.style.display = "none";

    }


    return true;
 
};

const validarContenido = () => {

    let spanError = contenido.nextElementSibling;

    if (contenido.value.trim().length < 3) {
        spanError.style.display = "inline";
        spanError.classList.add("mal");
        spanError.classList.remove("bien");
        spanError.innerText = "El contenido debe tener al menos 3 caracteres.";
        return false;
    } else {
        spanError.style.display = "none";
        spanError.classList.remove("mal");
        spanError.classList.add("bien");
        spanError.innerText = "";
    }

    return true;
 
};


  const validarFichero=()=>{

    let fichero;
    const tipos_admitidos=["image/jpeg"];
    let span_error=input_ficheros.nextElementSibling;

    if(input_ficheros.files.length>0){    

        fichero=input_ficheros.files[0];
        //Objeto file
        if(!tipos_admitidos.includes(fichero.type)){
          span_error.style.display="inline";
          span_error.classList.add("mal");
          span_error.classList.remove("bien");
          span_error.innerText="la imagen seleccionada tiene que tenes extension .jepg";
            return false;
        }

        if(fichero.size>5000000){
          span_error.style.display="inline";
          span_error.classList.add("mal");
          span_error.classList.remove("bien");
          span_error.innerText="imagen muy grande";
            return false;
        }
    }
    span_error.classList.remove("mal");
    span_error.classList.add("bien");
    span_error.style.display="none";
    return true;


}

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
