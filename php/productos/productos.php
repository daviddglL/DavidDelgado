<?php

// Configuración de la API con parámetros de paginación
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = isset($_GET['limit']) && is_numeric($_GET['limit']) ? (int)$_GET['limit'] : 10;
$apiUrl = "http://localhost/DavidDelgado/php/productos/api.php?page=$page&limit=$limit"; // Cambia esta URL al endpoint correcto

require_once "../../connection/config.php";
require_once "../../connection/funciones.php";
require_once ("../../php/inicio.php");
require_once("../noticias/noticias.php");

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
    <!-- font-awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" />
    <!-- styles css -->
    <link rel="stylesheet" href="../../estilos/styles.css">
    <script defer src="../../js/carrito.js"></script>
    <script type="text/javascript" src="lista_productos.js"></script>
</head>

<body>
    <!-- cart -->
    <div class="cart-overlay">
        <aside class="cart">
            <button class="cart-close">
                <i class="fas fa-times"></i>
            </button>
            <header>
                <button class="button cart-checkout btn">Vaciar carro</button>
                <h3 class="text-slanted">Añadido hasta ahora</h3>
            </header>
            <!-- cart items -->
            <div class="cart-items"></div>
            <footer>
                <h3 class="cart-total">Total: <span class="total-price"></span></h3>
                <button class="button cart-checkout ">Tramitar pedido</button>
            </footer>  
        </aside>
    </div>

    <div class="container">
        <?php
            headerr();
            echo "<div class='toggle-container'>
                    <button class='button toggle-cart '>
                        <i class='fas fa-shopping-cart '></i>
                    </button>
                  </div>";
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

        <?php
            /**
             * respuesta de la Api
             * @var mixed $apiUrl
             * @var mixed $ch
             * @var mixed $respuesta
             * @var mixed $httpCode
             * @var mixed $productos
             * @var mixed $producto
             * @var mixed $i
             * 
             */
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
                /**
                 * 
                 * Aquí se muestra el listado de productos
                 *
                 */
                foreach ($productos as $producto) {
                    echo "<div class='card'>";
                    echo "<h3 class='titulo'>{$producto['nombre']}</h3>";
                    echo "<img src='/../DavidDelgado/img/productos/" . $producto['imagen'] . ".jpg'>";
                    echo "<p class='lista'>Precio:  {$producto['precio']}</p>";
                    echo "<p class='lista'>Descripción:  {$producto['descripcion']}</p>";
                    echo "<p class='lista'>Stock:  {$producto['stock']}</p>";
                    echo "<p class='lista'>Estado:  {$producto['estado']}</p>";
                    
                    echo "<button class='button'><a href='/DavidDelgado/php/productos/editar_producto.php?id_producto={$producto['id_producto']}'>Editar</a></button>";
                    echo "<button class='button'><a href='/DavidDelgado/php/productos/borrar_producto.php?id_producto={$producto['id_producto']}'>Borrar</a></button>";
                    echo "<button class='button comprar-btn' data-id='{$producto['id_producto']}'>Comprar</button>";
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
                /**
                 * 
                 * paginación
                 * @var mixed $page
                 * @var mixed $limit
                 * @var mixed $total
                 * @var mixed $paginacion
                 * @var mixed $productos
                 * @var mixed $respuesta
                 * 
                 */
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
    <!--alert-->
    <div class="alerta"></div>
    <script>
        const lista_productos = <?php echo json_encode($productos); ?>;
    </script>
</body>
</html>