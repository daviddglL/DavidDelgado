<?php
    session_start();
    require_once "../../connection/config.php";
    require_once "../../connection/funciones.php";
    require_once "formularios.php";
    require_once ("../../php/inicio.php");
    require_once("../noticias/noticias.php");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <!-- styles css -->
    <link rel="stylesheet" href="../../estilos/styles.css">
</head>
<body>
<div class="container">
    <?php
        headerr();
        contactos();
            echo "<div class='secc'><!--sección central-->";
                echo "<div class='login'>";
                $pagina_actual = basename($_SERVER['PHP_SELF']);
                
                if (isset($_SESSION['username'])) {
                    echo formulario_sesion_iniciada($_SESSION['username']);
                } else {
                    echo formulario_para_iniciar_sesion($pagina_actual);
                }
                echo "</div>";
            echo "</div>";
        news();
        footer();        
    ?>
</div>
</body>
</html>