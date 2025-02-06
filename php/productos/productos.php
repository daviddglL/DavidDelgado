<?php
// Configuración de la API con parámetros de paginación
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = isset($_GET['limit']) && is_numeric($_GET['limit']) ? (int)$_GET['limit'] : 10;
$apiUrl = "http://localhost/DavidDelgado/php/productos/api.php?page=$page&limit=$limit"; // Cambia esta URL al endpoint correcto

?>

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
    

    <!-- Enlace para añadir nuevo producto -->
    <div class="container">
    
    <?php
        headerr();
        contactos();
        echo "<div class='secc'><!--sección central-->";
        echo "<h1>Listado de Productos</h1>";
    ?>
     <div class="search-container">
            <h2>Buscar Producto</h2>

            <form method="GET" action="api.php" class="socios_form">
                <input class="search_placeholder" type="text" name="search" placeholder="Nombre del producto, precio">
                <button type="submit" name="busqueda">Buscar</button>
            </form>  
        </div>
    <div  id="resultadoSocios"></div>
    <?php

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $apiUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type:application/json',
            ));


            $respuesta = json_decode(curl_exec($ch), true);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if (!isset($respuesta["datos"]) || !isset($respuesta["paginacion"])) {
                die("Error: La API no devolvió los datos esperados.");
            }
            
            $productos = $respuesta["datos"];
            $paginacion = $respuesta["paginacion"];
            
            curl_close($ch);
            if ($httpCode == 200) {
               
                $productos = $respuesta["datos"];
                $paginacion = $respuesta["paginacion"];
                $actual = $paginacion['actual'];
                $total = $paginacion['paginas'];
                $limite = $paginacion['limite'];

                echo "<div class='cards-container' id='cards-container'>";

                foreach ($productos as $producto) {
                    echo "<div class='card'>";
                    echo "<h3 class='titulo'>{$producto['nombre']}</h3>";
                    echo "<img src='/../DavidDelgado/img/productos/" . $producto['imagen'] . ".jpg'>";
                    echo "<p class='lista'>Precio:  {$producto['precio']}</p>";
                    echo "<p class='lista'>Descripción:  {$producto['descripcion']}</p>";
                    echo "<p class='lista'>Stock:  {$producto['stock']}</p>";
                    echo "<p class='lista'>Estado:  {$producto['estado']}</p>";
                    
                    echo "<button class='button'><a href='/DavidDelgado/php/productos/editar_producto.php?id_producto={$producto['id_producto']}'>Editar</a></button>";

        
                    echo "</div>";
                }
        
                echo "</div>";
        
                echo '<br>';
            
                echo '<div class="pagination">';
        
                // Enlace a la página anterior (si no estamos en la primera)
                if ($actual > 1) {
                    echo '<a class="paginacion" href="?page=' . ($actual - 1) . '&limit=' . $limite . '">Anterior</a>';
                }
        
                // Enlaces a las páginas
                for ($i = 1; $i <= $total; $i++) {
                    if ($i == $actual) {
                        echo '<span class="current">' . $i . '</span>';
                    } else {
                        echo '<a class="paginacion" href="?page=' . $i . '&limit=' . $limite . '">' . $i . '</a>';
                    }
                }
        
                // Enlace a la siguiente página (si no estamos en la última)
                if ($actual < $total) {
                    echo '<a class="paginacion" href="?page=' . ($actual + 1) . '&limit=' . $limite . '">Siguiente</a>';
                }
        
                echo '</div>';
            } else {
                echo "<p>" . $respuesta["error"] . "AYUDAAAAAA</p>";
            }


        echo "</div>";
        news();
        footer();   
    ?>
    </div>
</body>

</html>
