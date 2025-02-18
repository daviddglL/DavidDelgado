<?php

    require_once "../../connection/config.php";
    require_once "../../connection/funciones.php";
    require_once ("../../php/inicio.php");
    require_once("../noticias/noticias.php");
    require_once("../servicios/servicios.php");
    require_once("../socios/socios.php");
    session_start();

   
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Noticias</title>
    <link rel="stylesheet" href="../../estilos/styles.css">
</head>
<body>
    <div class="container">

    <?php
        headerr();
        contactos();
        echo "<div class='secc'><!--sección central-->";
    ?>

<?php



if (isset($_GET['id'])) {
    $id_noticia = (int)$_GET['id']; 

    /**
     * @param int $id_noticia
     * @return array|false
     * @throws Exception
     * @throws mysqli_sql_exception
     * @function obtenerNoticiaPorId($id_noticia)
     * @description Obtiene una noticia por su ID
     * @example obtenerNoticiaPorId(1)
     * 
     */

   obtenerNoticiaPorId($id_noticia);

    $noticia = obtenerNoticiaPorId($id_noticia);

    if ($noticia) {
        echo "<div class='news'>";
        echo "<h1>{$noticia['titulo']}</h1>";

        echo "<p><img  src='/../DavidDelgado/img/news/" . $noticia['imagen'] . ".jpg' alt='{$noticia['titulo']}'>  
              Fecha de publicación: {$noticia['fecha_publicacion']}</p><br>
              <p>{$noticia['contenido']}</p>";

        echo "</div>";
    } else {
        echo "<p>No se encontró la noticia solicitada.</p>";
    }
} else {
    echo "<p>No se especificó ninguna noticia.</p>";
}
?>

    <?php
        echo "</div>";
        news();
        footer();        
    ?>

    </div>
</body>
</html>