"use strict"

let formulario=document.getElementById("formulario");
let input_ficheros  = document.getElementById("imagen");
let nombre = document.getElementById('nombre');
let precio = document.getElementById('precio');
let descripcion = document.getElementById('descripcion');
let stock = document.getElementById('stock');
let membresia = document.getElementById('membresia');



input_ficheros.addEventListener("change",
    ()=> {
      validarFichero();
    }
  )


nombre.addEventListener("input", () => {
    validarNombre();
});

precio.addEventListener("input", () => {
    validarPrecio();
});

descripcion.addEventListener("input", () => {
    validarDescripcion();
});

stock.addEventListener("input", () => {
    validarStock();
});

formulario.addEventListener("submit", 
  (evento) => {
  let validaciones = [validarFichero, validarNombre, validarPrecio, validarDescripcion, validarStock];
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


function validarNombre() {
    let valor = nombre.value.trim();
    if (valor === "") {
        mostrarError(nombre, "El nombre del producto es requerido.");
        return false;
    } else {
        limpiarError(nombre);
        return true;
    }
}

const validarPrecio=()=>{

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
function validarStock() {
    let valor = parseInt(stock.value, 10);
    if (isNaN(valor) || valor < 0) {
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
}


