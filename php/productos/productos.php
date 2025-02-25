<?php

// Configuración de la API con parámetros de paginación
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = isset($_GET['limit']) && is_numeric($_GET['limit']) ? (int)$_GET['limit'] : 4;
$search = isset($_GET['search']) ? $_GET['search'] : '';
$apiUrl = "http://localhost/DavidDelgado/php/productos/api.php?page=$page&limit=$limit&search=" . urlencode($search);

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
            <button class="button cart-close">
                <i class="fas fa-times"></i>
            </button>
            <header>
                <h3 class="text-slanted">Añadido hasta ahora</h3>
            </header>
            <!-- cart items -->
            <div class="cart-items"></div>
            <footer>
                <button class="button cart-checkout ">Vaciar carro</button>
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
        ?>
        <div class="search-container">
            <h2>Buscar Producto</h2>
            <form method="GET" action="productos.php" class="socios_form">
                <input class="search_placeholder" type="text" name="search" placeholder="Nombre del producto, precio" value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" name="busqueda">Buscar</button>
            </form>  
        </div>

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
                $total = $paginacion['total'];
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
                    
                    if (isset($_SESSION['id_socio']) && $_SESSION['id_socio'] == 0) {
                        echo "<button class='button'><a href='/DavidDelgado/php/productos/editar_producto.php?id_producto={$producto['id_producto']}'>Editar</a></button>";
                        echo "<button class='button'><a href='/DavidDelgado/php/productos/borrar_producto.php?id_producto={$producto['id_producto']}'>Borrar</a></button>";
                    }

                    if (isset($_SESSION['id_socio']) && $_SESSION['id_socio'] != 0) {
                        echo "<button class='button comprar-btn' data-id='{$producto['id_producto']}'>Comprar</button>";
                    }

                    echo "</div>";
                }

                echo "</div>";

                echo '<br>';
            
                echo '<div class="pagination">';
            
                if ($actual > 1) {
                    echo '<button class="button"> <a  href="?page=' . ($actual - 1) . '&limit=' . $limite . '&search=' . urlencode($search) . '">Anterior</a></button>';
                }
            
                for ($i = 1; $i <= $total; $i++) {
                    if ($i == $actual) {
                        echo '<span class="current">' . $i . "&emsp;". '</span>';
                    } else {
                        echo '<a  href="?page=' . $i . '&limit=' . $limite . '&search=' . urlencode($search) . '">' . $i . "&emsp;".'</a>';
                    }
                }
            
                if ($actual < $total) {
                    echo '<button class="button"><a  href="?page=' . ($actual + 1) . '&limit=' . $limite . '&search=' . urlencode($search) . '">Siguiente</a></button>';
                }
            
                echo '</div>';
            } else {
                echo "<p>" . $respuesta["error"] . "AYUDAAAAAA</p>";
            }

            if (isset($_SESSION['id_socio']) && $_SESSION['id_socio'] === 0) {
                echo "<button class='button'><a href='/DavidDelgado/php/productos/agregar_producto.php'>Agregar Producto</a></button>";
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