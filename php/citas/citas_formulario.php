<?php

require_once "../../connection/config.php";
require_once "../../connection/funciones.php";
require_once ("../../php/inicio.php");
require_once("../noticias/noticias.php");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Citas</title>
    <link rel="stylesheet" href="../../estilos/styles.css">
    <script defer src="../../js/formulario_citas.js"></script>
    <style>
        /* Añadir estilos CSS específicos aquí si es necesario */
    </style>
</head>
<body>
    <div class="container">
        <?php
            headerr();
            contactos();
        ?>
        <div class="secc">
            <?php require_once "citas.php"; ?>
            <div class="search-container">
                <h2>Buscar Cita</h2>
                <form method="GET" action="citas_formulario.php" class="socios_form">
                    <input class="search_placeholder" type="text" name="search" placeholder="Socio, fecha o servicio contratado.">
                    <button type="submit" name="busqueda">Buscar</button>
                </form>  
            </div>

            <?php
            $conexion = conectar($nombre_host, $nombre_usuario, $password_db, $nombre_db);
            if (isset($_GET['busqueda']) && !empty($_GET['search'])) {
                $searchTerm = $_GET['search'];
                obtenerDatosCitas($conexion, $searchTerm);
            }
            
            if (isset($_GET['fecha'])) {
                $fechaSeleccionada = $_GET['fecha'];
                calendar($conexion);
                mostrarCitas($conexion, $fechaSeleccionada);
            } else {
                calendar($conexion);
            }


            if (isset($_SESSION['id_socio']) && $_SESSION['id_socio'] == 0) {
            ?>


            <div class="formulario">
                <form id="form" method="POST" action="citas.php">
                    <h2>Introduzca los datos de su nueva cita</h2>

                    <label for="servicio">Servicio a realizar:</label>
                    <?php selectServicio($conexion); ?>
                    <span class="error"></span>
                    <br/><br/>

                    <label for="socio">Seleccione en titular de la cita:</label>
                    <?php selectSocio($conexion); ?>
                    <span class="error"></span>
                    <br/><br/>

                    <label for="fecha">Fecha de la cita:</label>
                    <input type="date" name="fecha" id="fecha" />
                    <span class="error"></span>
                    <br/><br/>

                    <label for="hora">Hora de la cita:</label>
                    <input type="time" name="hora" id="hora" />
                    <span class="error"></span>
                    <br/><br/>
                    <button class="button" type="submit" id="insertarCitas" name="citas">Enviar</button>
                </form>
            </div>
            <?php
            }
            ?>
        </div>
        <?php
            news();
            footer();        
        ?>
    </div>
</body>
</html>