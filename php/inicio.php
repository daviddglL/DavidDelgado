<?php
session_start();
require_once __DIR__ . "/../connection/config.php";
require_once __DIR__ . "/../connection/funciones.php";
require_once __DIR__ . "/users/formularios.php";

function headerr(){
    echo"<div class='header'>";
    echo"<h2><img src='https://fontmeme.com/permalink/241029/1dfbd7c9ac4977d0c92450b4eec0fb06.png' alt='fuente-dreams-american-diner' border='0'></h2>";
    echo "<ul class='menu' data-animation='diagonal'>";
        echo "<li>";
            echo "<a href='" . BASE_URL . "index.php'>
                            Inicio
                            <span class='border border-top'></span>
                            <span class='border border-right'></span>
                            <span class='border border-bottom'></span>
                            <span class='border border-left'></span>
                </a>";
        echo "</li>";
        echo "<li>";
            echo "<a href='" . BASE_URL . "php/testimonios/testimonios_formulario.php'>
                            Sobre Nosotros
                            <span class='border border-top'></span>
                            <span class='border border-right'></span>
                            <span class='border border-bottom'></span>
                            <span class='border border-left'></span>
                </a>";
        echo "</li>";
        
        echo "<li>";
            echo "<a href='" . BASE_URL . "php/servicios/servicios_formulario.php'>
                            Servicios
                            <span class='border border-top'></span>
                            <span class='border border-right'></span>
                            <span class='border border-bottom'></span>
                            <span class='border border-left'></span>
                </a>";
        echo "</li>";

        echo "<li>";
        echo "<a href='" . BASE_URL . "php/citas/citas_formulario.php'>
                        Citas
                        <span class='border border-top'></span>
                        <span class='border border-right'></span>
                        <span class='border border-bottom'></span>
                        <span class='border border-left'></span>
            </a>";
        echo "</li>";

        echo "<li>";
        echo "<a href='" . BASE_URL . "php/socios/socios_formulario.php'>
                        Socios
                        <span class='border border-top'></span>
                        <span class='border border-right'></span>
                        <span class='border border-bottom'></span>
                        <span class='border border-left'></span>
            </a>";
        echo "</li>";

        echo "<li>";
            echo "<a href='" . BASE_URL . "php/noticias/noticias_file.php'>
                            Noticias
                            <span class='border border-top'></span>
                            <span class='border border-right'></span>
                            <span class='border border-bottom'></span>
                            <span class='border border-left'></span>
                </a>";
        echo "</li>";

        echo "<li>";
        echo "<a href='" . BASE_URL . "php/dietas/dietas.php'>
                        Dietas
                        <span class='border border-top'></span>
                        <span class='border border-right'></span>
                        <span class='border border-bottom'></span>
                        <span class='border border-left'></span>
            </a>";
    echo "</li>";

    echo "<li>";
    echo "<a href='" . BASE_URL . "php/productos/productos.php'>
                    Productos
                    <span class='border border-top'></span>
                    <span class='border border-right'></span>
                    <span class='border border-bottom'></span>
                    <span class='border border-left'></span>
        </a>";
    echo "</li>";

    echo "<li>";
    echo "<a href='" . BASE_URL . "php/users/usuarios.php'>
                    <button class='button toggle-cart '>
                        <i class='fa-solid fa-user'></i>
                    </button>
        </a>";
        if (isset($_SESSION['username'])) {
            echo "<a href='" . BASE_URL . "php/users/cerrar_sesion.php'>
                    Cerrar Sesión
                    <span class='border border-top'></span>
                    <span class='border border-right'></span>
                    <span class='border border-bottom'></span>
                    <span class='border border-left'></span>
                </a>";
        }
    echo "</li>";
        
    echo "</ul>";
    echo "</div>";
}

function contactos(){
    echo "<div class='contacts'>";
    echo "<h2 class='titulo'>
            <img src='https://fontmeme.com/permalink/241104/24ce331fddb529bfd2d73e89cd0264f4.png' alt='fuente-dreams-american-diner' border='0'>
          </h2>";
    echo "
            <p>¿Tienes alguna pregunta? ¡Nos encantaría ayudarte!</p>
            
            <div class='contacto-detalles'>

                <div class='contacto-item'>
                <h3>Dirección</h3>
                <p>Club Deportivo 'Los primos'</p>
                <p>Calle Deportiva 123</p>
                <p>Ciudad Fitness, CP 45678</p>
                </div>

                <div class='contacto-item'>
                <h3>Teléfono</h3>
                <p>+34 123 456 789</p>
                </div>

                <div class='contacto-item'>
                <h3>Email</h3>
                <p>info@losprimos.com</p>
                </div>

                <div class='contacto-item'>
                <h3>Horario de Atención</h3>
                <p>Lunes a Viernes: 9:00 - 19:00</p>
                <p>Sábado y Domingo: 10:00 - 14:00</p>
                </div>

                <div class='contacto-item' id='socios'>
                <h3>Cree su cuenta de forma gratuita</h3>
                <p class='contacto'><a  href='" . BASE_URL . "php/socios/socios_formulario.php'>Crear cuenta</a></p>
                </div>

            </div>
        ";
    echo "</div>";
}

function footer(){
    echo "<footer class='footer'>
            <p>Diseñado por David Delgado García de Lomas --   
            PD:'Toda la información o datos personales mostrados son irreales'. </p>
        </footer>";
}
?>

