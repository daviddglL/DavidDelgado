<?php
// Configuración de la API con parámetros de paginación
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = isset($_GET['limit']) && is_numeric($_GET['limit']) ? (int)$_GET['limit'] : 10;
$apiUrl = "http://localhost/club_deportivo/productos/api.php?page=$page&limit=$limit"; // Cambia esta URL al endpoint correcto

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
    <h1>Listado de Productos</h1>

    <!-- Enlace para añadir nuevo producto -->


    <?php
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type:application/json',
        ));

        $respuesta = json_decode(curl_exec($ch), true);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        curl_close($ch);
        if ($httpCode == 200) {
            $productos = $respuesta["datos"];
            $paginacion = $respuesta["paginacion"];

            $actual = $paginacion['actual'];
            $total = $paginacion['paginas'];
            $limite = $paginacion['limite'];
            
            echo '<table>';
            echo '<thead>';
            echo '<tr>';
            echo '<th>ID</th>';
            echo '<th>Nombre</th>';
            echo '<th>Precio</th>';
            echo '<th>Descripción</th>';
            echo '<th>Stock</th>';
            echo '<th>Estado</th>';
            echo '<th>Imagen</th>';
            echo '<th>Membresía</th>';
            echo '<th>Acciones</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';

            // Procesamos y mostramos los productos
            foreach ($productos as $producto) {
                echo '<tr>';
                echo '<td>' . $producto['id'] . '</td>';
                echo '<td>' . $producto['nombre'] . '</td>';
                echo '<td>' . $producto['precio'] . ' €</td>';
                echo '<td>' . $producto['descripcion'] . '</td>';
                echo '<td>' . $producto['stock'] . '</td>';
                echo '<td>' . $producto['estado'] . '</td>';
                echo '<td><img src="' . $producto['imagen'] . '" alt="' . $producto['nombre'] . '" width="50"></td>';
                echo '<td>' . ($producto['membresia'] ? 'Sí' : 'No') . '</td>';
                echo '<td>';
                echo '<a href="editar_producto.php?id=' . $producto['id'] . '" class="edit">Editar</a> | ';
                echo '<a href="borrar_producto.php?id=' . $producto['id'] . '" class="delete" onclick="return confirm(\'¿Seguro que quieres borrar este producto?\');">Borrar</a>';
                echo '</td>';
                echo '</tr>';
            }

            echo '</tbody>';
            echo '</table>';
          
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
    ?>

</body>

</html>
