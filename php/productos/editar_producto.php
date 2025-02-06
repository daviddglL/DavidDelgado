<?php
require_once 'api_funciones.php';
require_once "../../connection/config.php";
require_once "../../connection/funciones.php";
$apiUrl="http://localhost/DavidDelgado/php/productos/api.php";

global $nombre_db, $nombre_host, $nombre_usuario, $password_db;  

$conexion = conectar($nombre_host, $nombre_usuario, $password_db, $nombre_db);

// Obtener datos del producto

function obtenerProducto($conexion, $id) {
    global $apiUrl;
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $apiUrl . "?id_producto=" . $id);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPGET, true);

    $respuesta = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);


    if ($httpCode == 200 && $respuesta) {
        // Elimina cualquier texto no relacionado con JSON antes de decodificarlo
        $respuesta_limpia = preg_replace('/^[^{]*/', '', $respuesta);  // Elimina lo que esté antes del JSON
        $respuesta_decodificada = json_decode($respuesta_limpia, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            echo "Error de decodificación JSON: " . json_last_error_msg();
        }
        
// Verificar si 'datos' existe y contiene productos
        if (isset($respuesta_decodificada['datos']) && is_array($respuesta_decodificada['datos']) && count($respuesta_decodificada['datos']) > 0) {
            return $respuesta_decodificada['datos'][0];  // Devuelve el primer producto
        } else {
            echo "No se encontraron productos en la respuesta.";  // Si no se encuentra el producto
            return null;  
        }

    } else {
        echo "Error al ejecutar la consulta API. Código HTTP: " . $httpCode;
        return null;
    }
}




if (isset($_GET['id_producto'])) {
    $id_producto = $_GET['id_producto'];
    
    $producto = obtenerProducto($conexion, $id_producto);
    
} else {
    echo "No se ha recibido el parámetro id_producto.";
}


// Si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($entrada["nombre"]) && isset($entrada["precio"]) && isset($entrada["descripcion"]) && isset($entrada["stock"]) && isset($entrada["membresia"])) {
        $id_producto = (int) $_POST['id_producto'];
        $nombre_producto = $_POST['nombre'];
        $precio = (float) $_POST['precio'];
        $descripcion = $_POST['descripcion'];
        $stock = (int) $_POST['stock'];
        $membresia = (isset($_POST['membresia']) && $_POST['membresia'] === 'on') ? 1 : 0; // Conversión de checkbox

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            'id_producto' => $id_producto,
            'nombre' => $nombre_producto,
            'precio' => $precio,
            'descripcion' => $descripcion,
            'stock' => $stock,
            'membresia' => $membresia
        ]));

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
        ]);

        $respuesta = json_decode(curl_exec($ch), true);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if ($httpCode == 200) {
            $mensaje = $respuesta["mensaje"];
        } else {
            $error = $respuesta["error"];
        }
    } else {
        $error = "Todos los campos son requeridos.";
        echo "<pre>";
print_r($_POST);  // Ver todos los datos enviados desde el formulario
echo "</pre>";
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto</title>
</head>

<body>
    <h1>Editar Producto</h1>

    <?php

    if (isset($mensaje)) {
        echo "<p style='color: green;'>" . $mensaje;
        "</p>";
        header("Refresh: 3; url=index.php");
    }

    if (isset($error)) {
        echo '<p style="color: red;">' . $error . '</p>';
    }

    if (isset($producto)) {
        ?>
        <form method="POST">
            <label for="nombre_producto">Nombre del producto:</label>
            <input type="text" id="nombre_producto" name="nombre_producto" value="<?php echo htmlspecialchars($producto['nombre']); ?>" >
    
            <label for="precio">Precio:</label>
            <input type="number" id="precio" name="precio" value="<?php echo htmlspecialchars($producto['precio']); ?>€" >

            <label for="descripcion">Descripción:</label>
            <textarea name="descripcion" required><?= htmlspecialchars($producto['descripcion']) ?></textarea><br>

            <label for="stock">stock:</label>
            <input type="number" id="stock" name="stock" value="<?php echo htmlspecialchars($producto['stock']); ?>" >
            
            <label>Membresía:</label>
            <input type="checkbox" name="membresia" <?= $producto['membresia'] ? 'checked' : '' ?>><br>

            
            <input type="hidden" id="id_producto" name="id_producto" value="<?php echo htmlspecialchars($producto['id_producto']); ?>">
            <button type="submit">Actualizar</button>
        </form>
        <?php
    } else {
        echo '<p>Producto no encontrado.</p>';
    }
    
    ?>


    <a href="./productos.php" style="color:darkgreen">Volver al listado</a>
</body>

</html>
