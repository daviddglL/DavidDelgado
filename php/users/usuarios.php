<?php

require_once __DIR__ . "/../../connection/config.php";
require_once __DIR__ . "/../../connection/funciones.php";
require_once __DIR__ . "/../users/formularios.php";
require_once __DIR__ . "/../inicio.php";
require_once __DIR__ . "/../noticias/noticias.php";
require_once __DIR__ . "/../users/iniciar_sesion.php";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <!-- styles css -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>estilos/styles.css">
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
            echo "<p>sesion iniciada</p>";
        } else {
            echo formulario_para_iniciar_sesion($pagina_actual);
            echo "<p>sesion no iniciada</p>";
        }
        echo "</div>";
        echo "</div>";
        news();
        footer();        
    ?>
</div>
</body>
</html>