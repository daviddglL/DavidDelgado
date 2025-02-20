"use strict"

let formulario=document.getElementById("form-modificar");
let nombre = document.getElementById('nombre');
let usuario = document.getElementById('usuario');
let edad=document.getElementById("edad");
let telefono=document.getElementById("telefono");
let contrasena = document.getElementById('contrasena');
let input_ficheros  = document.getElementById("foto");


nombre.addEventListener("input",
  () =>{
   validarNombre();
   
  }

);
usuario.addEventListener("input",
  () =>{
    validarUsuario();
  }
);

edad.addEventListener("input",
  () =>{
    validarEdad();
  }
);

telefono.addEventListener("input",
  ()=>{
    validarTelefono();
  }
);

contrasena.addEventListener("input",
    ()=>{
      validarContrasena();
    }
  );

input_ficheros.addEventListener("change",
  ()=> {
    validarFichero();
  }
)




formulario.addEventListener("submit", 
  (evento) => {
  let validaciones = [validarNombre, validarUsuario, validarEdad, validarTelefono, validarContrasena, validarFichero];
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


// Agregar evento al formulario de modificar
let formModificar = document.getElementById("form-modificar");

if (formModificar) {
    formModificar.addEventListener("submit", (evento) => {
        let validaciones = [validarNombre, validarUsuario, validarEdad, validarTelefono, validarContrasena, validarFichero];
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
}


const validarNombre = () => {

    let valor=nombre.value.trim();
    let span_error=nombre.nextElementSibling;
    if (valor === "") {
      span_error.style.display = "inline";
      span_error.innerText = "El campo del nombre no puede estar vacío.";
      return false;
    }

    else if(!/[A-Za-z]{4,50}/.test(nombre.value.trim())){

      span_error.classList.add("mal");
      span_error.style.display = "inline";
      span_error.classList.remove("bien");
      span_error.innerHTML="tiene que tener entre 4 y 50 caracteres";
      return false;
      
    }else{
      span_error.classList.remove("mal");
      span_error.classList.add("bien");
      span_error.innerText="";
      span_error.style.display = "none";

    }


    return true;
 
};

const validarUsuario = () => {

    let valor=usuario.value.trim();
    let span_error=usuario.nextElementSibling;
    if (valor === "") {
      span_error.style.display="inline";
      span_error.innerText="El campo de usuario no puede estar vacio";
      return false;
    } 

    else if(!/^[A-Za-z][A-Za-z0-9]{4,19}$/.test(usuario.value.trim())){
      span_error.classList.add("mal");
      span_error.style.display = "inline";
      span_error.classList.remove("bien");
      span_error.innerHTML="Debe empezar por una letra y tiene que tener entre 5 y 20 caracteres";
      return false;
      
    }else{
      span_error.classList.remove("mal");
      span_error.classList.add("bien");
      span_error.innerText="";
      span_error.style.display = "none";
    }
    return true;
  };

const validarEdad=()=>{

        let valor_numero =parseInt(edad.value.trim());
        let span_error=edad.nextElementSibling;
        if(isNaN(valor_numero)){
          span_error.style.display="inline";
          span_error.innerText="Debe ser un numero";
            return false;
        }

        else if (valor_numero < 18){
          span_error.style.display="inline";
          span_error.classList.add("mal");
          span_error.classList.remove("bien");
          span_error.innerText="debe ser mayor de edad";
            return false;
        }else{
          span_error.classList.remove("mal");
          span_error.classList.add("bien");
          span_error.innerText="";
          span_error.style.display="none";
        }
        
        return true;
  };


  const validarTelefono=()=>{

    let span_error=telefono.nextElementSibling;


    if(!/^\+34 \d{3} \d{3} \d{3}$/.test(telefono.value.trim())){
      span_error.style.display="inline";
      span_error.classList.add("mal");
      span_error.classList.remove("bien");
      span_error.innerHTML="Debe empezar por +34 y tener 9 digitos";
      return false;
      
    }else{
      span_error.innerText="";
      span_error.classList.remove("mal");
      span_error.classList.add("bien");
      
      span_error.style.display = "none";
    }
    
    return true;
};

const validarContrasena = () => {

    let valor=contrasena.value.trim();
    let span_error=contrasena.nextElementSibling;
    if (valor === "") {
      span_error.style.display="inline";
      span_error.innerText="El campo de contraseño no puede estar vacio";
      return false;
    }
    else if(!/^[A-Za-z][A-Za-z0-9_]{7,15}/.test(contrasena.value.trim())){
      span_error.classList.add("mal");
      span_error.classList.remove("bien");
      span_error.style.display="inline";
      span_error.innerHTML="Debe empezar por una letra y tiene que tener entre 8 y 16 caracteres";
      return false;
      
    }else{
      span_error.classList.remove("mal");
      span_error.classList.add("bien");
      span_error.innerText="";
      span_error.style.display = "none";
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


