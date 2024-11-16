<?php
    // Archivos PHP que solo deben contener funciones o secciones, NO el `<!DOCTYPE html>`

    require_once "connection/config.php";
    require_once "connection/funciones.php";
    require_once("php/inicio.php");
    require_once("php/servicios/servicios.php");
    require_once("php/testimonios/testimonios.php");
    require_once("php/noticias/noticias.php");

 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../club_deportivo/estilos/styles.css">
</head>
<body>
    <div class="container">
        <?php
        
            headerr();   
            contactos();
            echo "<div class='secc'><!--sección central-->";
            generarServicios();
            generarTestimonios();
            echo "</div>";
            news(); 
            footer();
                      
        ?>

    </div>
</body>
</html>
