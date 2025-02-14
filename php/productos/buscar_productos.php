<?php
require_once "../../connection/config.php";
require_once "../../connection/funciones.php";

$search = isset($_GET['search']) ? $_GET['search'] : '';
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = isset($_GET['limit']) && is_numeric($_GET['limit']) ? (int)$_GET['limit'] : 4;

$productos = obtenerProductos($conn, $search, $page, $limit);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Productos</title>
    <!-- font-awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" />
    <!-- styles css -->
    <link rel="stylesheet" href="../../estilos/styles.css">
</head>
<body>
    <div class="container">
        <?php
            headerr();
            contactos();
            echo "<div class='secc'><!--sección central-->";
            echo "<h1>Resultados de la Búsqueda</h1>";
        ?>
        <div class="search-container">
            <h2>Buscar Producto</h2>
            <form method="GET" action="buscar_productos.php" class="socios_form">
                <input class="search_placeholder" type="text" name="search" placeholder="Nombre del producto, precio" value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" name="busqueda">Buscar</button>
            </form>  
        </div>

        <?php
            if (!empty($productos['datos'])) {
                echo "<div class='cards-container' id='cards-container'>";

                foreach ($productos['datos'] as $producto) {
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
            
                if ($productos['paginacion']['actual'] > 1) {
                    echo '<a class="paginacion" href="?page=' . ($productos['paginacion']['actual'] - 1) . '&limit=' . $productos['paginacion']['limite'] . '&search=' . urlencode($search) . '">Anterior</a>';
                }
            
                for ($i = 1; $i <= $productos['paginacion']['total']; $i++) {
                    if ($i == $productos['paginacion']['actual']) {
                        echo '<span class="current">' . $i . '</span>';
                    } else {
                        echo '<a class="paginacion" href="?page=' . $i . '&limit=' . $productos['paginacion']['limite'] . '&search=' . urlencode($search) . '">' . $i . '</a>';
                    }
                }
            
                if ($productos['paginacion']['actual'] < $productos['paginacion']['total']) {
                    echo '<a class="paginacion" href="?page=' . ($productos['paginacion']['actual'] + 1) . '&limit=' . $productos['paginacion']['limite'] . '&search=' . urlencode($search) . '">Siguiente</a>';
                }
            
                echo '</div>';
            } else {
                echo "<p>No se encontraron productos.</p>";
            }

            echo "</div>";
            news();
            footer();   
        ?>
    </div>
</body>
</html>