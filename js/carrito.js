"use strict";

const alerta = document.querySelector(".alerta");
const carrito = document.querySelector(".cart-overlay");
const cerrar_carrito = document.querySelector(".cart-close");
const carrito_aside = document.querySelector("aside.cart");
const carrito_productos = document.querySelector(".cart-items");
const abrir_carrito = document.querySelector(".toggle-cart");
const total_price_span = document.querySelector(".total-price");
const vaciar_carrito_btn = document.querySelector(".button.cart-checkout"); // Botón "Vaciar carro"

document.addEventListener("click", () => {
  carrito.classList.remove("show");
});

carrito_aside.addEventListener("click", (evento) => {
  evento.stopPropagation();
});

abrir_carrito.addEventListener("click", (evento) => {
  carrito.classList.add("show");
  evento.stopPropagation();
});

cerrar_carrito.addEventListener("click", () => {
  carrito.classList.remove("show");
});

vaciar_carrito_btn.addEventListener("click", () => {
  lista_carrito = [];
  localStorage.setItem(carrito_local, JSON.stringify(lista_carrito));
  while (carrito_productos.firstChild) {
    carrito_productos.removeChild(carrito_productos.firstChild);
  }
  calcularTotal();
  mostrarMensaje("Carrito vaciado", "danger");
});

const carrito_local = "carrito";
//RENDERIZAR EL LOCALSTORAGE DEL CARRITO
let lista_carrito = JSON.parse(localStorage.getItem(carrito_local)) ?? [];

for (let item of lista_carrito) {
  carrito_productos.appendChild(crearItemCarrito(item));
}
calcularTotal();

//==================FUNCIONES AUXILIARES=====================================================

/**
 * Description placeholder
 *
 * @param {*} datos_item 
 * @returns {*} 
 */
function crearItemCarrito(datos_item) {
  const nuevo_item = document.createElement("article");
  
  nuevo_item.classList.add("cart-item");
  nuevo_item.setAttribute("data-id", datos_item.id_producto);
  nuevo_item.innerHTML = `
  <img src="/../DavidDelgado/img/productos/${datos_item.imagen}.jpg"
            class="cart-item-img"
            alt="${datos_item.nombre}"
          />  
          <div>
            <h4 class="cart-item-name" style='color: #D4AF37'>${datos_item.nombre}</h4>
            <p class="cart-item-price" style='color: #D4AF37'>${datos_item.precio} €</p>
            <button class="button cart-item-remove-btn" style='color: #D4AF37' data-id="${datos_item.id_producto}">Eliminar <i class="fas fa-times"></i></button>
          </div>`;

          
  /**
   * Description placeholder
   *
   * @type {*}
   */
  const eliminar = nuevo_item.querySelector(".cart-item-remove-btn");
  eliminar.addEventListener("click", () => {
    const posicion = lista_carrito.findIndex((item) => item["id_producto"] == datos_item.id_producto);
    lista_carrito.splice(posicion, 1);
    localStorage.setItem(carrito_local, JSON.stringify(lista_carrito));
    nuevo_item.remove();
    calcularTotal();
    mostrarMensaje("Producto eliminado del carrito", "danger");
  });

  return nuevo_item;
}

/**
 * Description placeholder
 *
 * @param {*} texto 
 * @param {*} clase 
 */
function mostrarMensaje(texto, clase) {
  alerta.innerHTML = `<h3>${texto}</h3>`;
  alerta.classList.add(clase);
  alerta.style.display = "block"; // Asegúrate de que el mensaje sea visible
  
  // remove alert
  setTimeout(() => {
    alerta.innerText = "";
    alerta.classList.remove(clase);
    alerta.style.display = "none"; // Oculta el mensaje después de 2 segundos
  }, 2000);
}

function calcularTotal() {
  const total = lista_carrito.reduce((acc, item) => acc + item.precio, 0);
  total_price_span.textContent = `${total.toFixed(2)} €`;
}

// NUEVO: Añadir productos al carrito desde productos.php
document.addEventListener("DOMContentLoaded", () => {
  const botonesComprar = document.querySelectorAll(".comprar-btn");
  botonesComprar.forEach((boton) => {
    boton.addEventListener("click", (evento) => {
      const idProducto = evento.target.getAttribute("data-id");
      const producto = lista_productos.find((item) => item.id_producto == idProducto);
      if (producto) {
        lista_carrito.push(producto);
        const nuevo_elemento = crearItemCarrito(producto);
        carrito_productos.appendChild(nuevo_elemento);
        localStorage.setItem(carrito_local, JSON.stringify(lista_carrito));
        calcularTotal();
        mostrarMensaje("Producto añadido", "success"); // Añadir mensaje aquí
      }
    });
  });
});
