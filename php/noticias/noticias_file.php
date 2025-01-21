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
    <title>Noticias</title>
    <link rel="stylesheet" href="../../estilos/styles.css">
    <script defer src="../../js/formulario_noticias.js"></script>
    <style>
        
    </style>
</head>
<body>
    <div class="container">

    <?php
        headerr();
        contactos();
        echo "<div class='secc'><!--sección central-->";
        
    ?>  

    <h1 class="interes">Noticias de interes</h1>

    <?php
        noticias();
        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            mostrarNoticiaPorId($id); 
        } else {
            
        }
    ?>

<div class="formulario">
    <form id="form" method="post" action="noticias.php" enctype="multipart/form-data">
        <h2>Introduzca los datos de la nueva noticia</h2>

        <label for="titulo">Titulo de la noticia:</label>
        <input type="text" id="titulo" name="titulo" placeholder="Introduzca el titulo de la noticia"/>
        <span class="error"></span>
        <br/><br/>

        <label for="contenido">Introduzca el contenido</label>
        <textarea name="contenido" id="contenido" cols="60" rows="15"></textarea>

        <span class="error"></span>
        <br/><br/>

        <label for="fecha">Introduzca la fecha de publicación</label>
        <input type="date" id="fecha" name="fecha_publicacion"/>
        <span class="error"></span>
        <br/><br/>

        <label for="imagen">Suba la imagen de perfil de su usuario</label>
        <input type="file" name="imagen" id="imagen" placeholder="La imagen debe tener la extensión .webp"/>
        <span class="error"></span>
        <br/><br/>

        <button class="button" type="submit" name="submit">Enviar</button>

    </form>
    </div>
    <?php
        echo "</div>";
        news();
        footer();        
    ?>

    </div>
</body>
</html>