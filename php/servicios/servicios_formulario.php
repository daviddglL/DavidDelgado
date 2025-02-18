
<?php

    require_once "../../connection/config.php";
    require_once "../../connection/funciones.php";
    require_once ("../../php/inicio.php");
    require_once("../noticias/noticias.php");
    require_once("../testimonios/testimonios.php");
    require_once("../servicios/servicios.php");
    require_once("../socios/socios.php");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servicios</title>
    <link rel="stylesheet" href="../../estilos/styles.css">
    <script defer src="../../js/formulario_servicios.js"></script>
</head>
<body>
<div class="container">
    <?php
        headerr();
        contactos();
        echo "<div class='secc'><!--sección central-->";
      
    ?>
    <div class="search-container">
            <h2>Buscar Servicio</h2>

            <form method="GET" action="servicios_formulario.php" class="socios_form">
                <input class="search_placeholder" type="text" name="search" placeholder="Nombre del servicio">
                <button type="submit" name="busqueda">Buscar</button>
            </form>  
        </div>
    <div  id="resultadoSocios">

        <?php
        include_once 'servicios.php'; 
        if (isset($_GET['busqueda']) && !empty($_GET['search'])) {
            $searchTerm = $_GET['search'];
            motrarServicios($searchTerm);
        } else {
            motrarServicios();
        }                
            if (!isset($_GET['modificar'])) {
            } else {
                $id = $_GET['modificar'];
                $datosServicio = obtenerDatosServicio($id);
            
                if ($datosServicio) {
                
                    echo "
                    <div class='formulario'>
                        <form id='form-modificar' method='POST' action='servicios.php' enctype='multipart/form-data'>
                        <h2>Modificar Servicio</h2>
                            <input type='hidden' name='codigo_servicio' value='$id'>
                            <label>Descripcion:</label>
                            <input type='text' id='descripcion' name='descripcion' value='" . $datosServicio['descripcion'] . "'>
                            <span class='error'></span>
                            <label>Precio servicio:</label>
                            <input type='text' id='precio_servicio' name='precio_servicio' value='" . $datosServicio['precio_servicio'] . "'>
                            <span class='error'></span>
                            <label>Duración servicio:</label>
                            <input type='number'id='duracion_servicio'  name='duracion_servicio' value='" . $datosServicio['duracion_servicio'] . "'>
                            <span class='error'></span><br><br>
                            <button class='button' type='submit' value='GuardarCambios' name='Guardar_Cambios'>Actualizar</button>
                        </form>
                        <a href='servicios_formulario.php'>Cancelar</a>
                    </div>
                    ";
                } else {
                    echo "<p>Socio no encontrado.</p>";
                }
            }
        ?>

        <div class="formulario">
            <form id="form" method="POST" action="servicios.php" enctype="multipart/form-data">
                <h2>Introduzca los datos del nuevo servicio</h2>

                <label for="descripcion">Descripcion del servicio:</label>
                <input type="text" id="descripcion" name="descripcion" placeholder="Introduzca la descripción del servicio"/>
                <span class="error"></span>
                <br/><br/>

                <label for="precio_servicio">Introduzca su precio:</label>
                <input type="number" id="precio_servicio" step="0.01" min="1"  name="precio_servicio"/>
                <span class="error"></span>
                <br/><br/>

                <label for="duracion_servicio">Introduzca su duracion en minutos:</label>
                <input type="number" name="duracion_servicio" id="duracion_servicio" />
                <span class="error"></span>
                <br/><br/>

                <button class="button" type="submit" name="submit">Enviar</button>

            </form>
        </div>
    </div>
    <?php
        echo "</div>";
        news();
        footer();        
    ?>

</div>
    
</body>
</html>