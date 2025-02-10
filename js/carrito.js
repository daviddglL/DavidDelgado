"use strict";

let lista_carrito = []; //lista con lo que se añade al carrito
let lista_productos; //lista completa de productos
const alerta = document.querySelector(".alerta");

const carrito = document.querySelector(".cart-overlay");
const cerrar_carrito = document.querySelector(".cart-close");
const carrito_productos = document.querySelector(".cart-items");
const abrir_carrito = document.querySelector(".toggle-cart");

async function obtenerDatosApi(url_api) {
  const respuesta = await fetch(url_api);

  if (respuesta.ok) {
    const datos_json = await respuesta.json();
    lista_productos = Object.values(datos_json.data);

    for (let producto of lista_productos) {
      contenedor.appendChild(crearProducto(producto));
    }
  } else {
    let respues_error = await respuesta.json();
    mostrarMensaje(respues_error.error, "danger");
  }
}

//NUEVO
//Aqui vamos a recuperar de la api la lista de productos y renderizarlos en la web
const contenedor = document.querySelector(".products-container");

obtenerDatosApi("productos_api.php");

//==================FUNCIONES AUXILIARES=====================================================

//FUNCION DEL DOM Y EVENTOS PARA EL INTERFAZ DE LA TIENDA

function crearProducto(producto) {
  let nuevo_producto = document.createElement("article");

  nuevo_producto.innerHTML = ` <article class="product">
        <div class="product-container" data-id="${producto.id}">
          <img src="${producto.image}" class="product-img img">
          <div class="product-icons">
            <button class="product-cart-btn product-icon">
              <i class="fas fa-shopping-cart"></i>
            </button>
          </div>
        </div>
        <footer>
          <p class="product-name">${producto.title}</p>
          <h4 class="product-price">${producto.price}</h4>
          <h4 class=".single-product-company">${producto.company}</h4>
        </footer>
      </article>`;

  let boton_añadir = nuevo_producto.querySelector(".product-cart-btn");
  boton_añadir.addEventListener("click", () => {
    lista_carrito.push(producto);
    const nuevo_elemento = crearItemCarrito(producto);
    carrito_productos.appendChild(nuevo_elemento);
    localStorage.setItem(carrito_local, JSON.stringify(lista_carrito));

    mostrarMensaje("Producto añadido al carrito", "success");
  });
  return nuevo_producto;
}

//FUNCION DEL DOM Y EVENTOS PARA EL CARRITO

function crearItemCarrito(datos_item) {
  const nuevo_item = document.createElement("article");

  nuevo_item.classList.add("cart-item");
  nuevo_item.setAttribute("data-id", datos_item.id);
  nuevo_item.innerHTML = `
  <img src="${datos_item.image}"
            class="cart-item-img"
            alt="${datos_item.title}"
          />  
          <div>
            <h4 class="cart-item-name">${datos_item.title}</h4>
            <p class="cart-item-price">${datos_item.price}</p>
            <button class="cart-item-remove-btn" data-id="${datos_item.id}">Eliminar <i class="fas fa-times"></i></button>
          </div>`;

  const eliminar = nuevo_item.querySelector(".cart-item-remove-btn");
  eliminar.addEventListener("click", () => {
    const posicion = lista_carrito.findIndex((item) => item["id"] == datos_item.id);
    lista_carrito.splice(posicion, 1);
    localStorage.setItem(carrito_local, JSON.stringify(lista_carrito));
    nuevo_item.remove();
  });

  return nuevo_item;
}

function mostrarMensaje(texto, clase) {
  alerta.innerHTML = `<h3>${texto}</h3>`;

  alerta.classList.add(clase);
  // remove alert
  setTimeout(() => {
    alerta.innerText = "";
    alerta.classList.remove(clase);
  }, 2000);
}

//CODIGO PARA CARGAR LO QUE HAYA EN EL CARRITO
const carrito_local = "carrito";

lista_carrito = JSON.parse(localStorage.getItem(carrito_local) ?? "[]");

carrito_productos.innerHTML = "";
lista_carrito.forEach((objeto) => {
  const producto = crearItemCarrito(objeto);
  carrito_productos.appendChild(producto);
});

//CODIGO PARA EL FUNCIONAMIENTO DEL CARRITO

abrir_carrito.addEventListener("click", () => {
  carrito.classList.add("show");
});

cerrar_carrito.addEventListener("click", () => {
  carrito.classList.remove("show");
});

// NUEVO: Añadir productos al carrito desde productos.php
document.addEventListener("DOMContentLoaded", () => {
  const botonesComprar = document.querySelectorAll(".comprar-btn");
  botonesComprar.forEach((boton) => {
    boton.addEventListener("click", (evento) => {
      const idProducto = evento.target.getAttribute("data-id");
      const producto = lista_productos.find((item) => item.id == idProducto);
      if (producto) {
        lista_carrito.push(producto);
        const nuevo_elemento = crearItemCarrito(producto);
        carrito_productos.appendChild(nuevo_elemento);
        localStorage.setItem(carrito_local, JSON.stringify(lista_carrito));
        mostrarMensaje("Producto añadido al carrito", "success");
      }
    });
  });
});
