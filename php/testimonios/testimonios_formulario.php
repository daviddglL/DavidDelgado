
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
    <title>Testimonios</title>
    <link rel="stylesheet" href="../../estilos/styles.css">
    <script defer src="../../js/formulario_testimonios.js"></script>
</head>
<body>
<div class="container">
    <?php
        headerr();
        contactos();
        echo "<div class='secc'><!--sección central-->";
        testimonios();
        $conexion = conectar($nombre_host, $nombre_usuario, $password_db, $nombre_db);
    ?>

    <div  id="resultadoSocios">
      
        <div class="formulario">
            <form id="form" method="POST" action="testimonios.php" enctype="multipart/form-data">
                <h2>Introduzca los datos del nuevo testimonio</h2>
                
                <label for="socio">Introduzca su contenido:</label>
                <?php selectSocio($conexion);?>
                <span class="error"></span>

                <label for="contenido">Introduzca su contenido:</label>
                <input type="text" id="contenido" name="contenido"/>
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