<?php
require_once 'api_funciones.php';
require_once "../../connection/config.php";
require_once "../../connection/funciones.php";
$apiUrl="http://localhost/DavidDelgado/php/productos/api.php";

global $nombre_db, $nombre_host, $nombre_usuario, $password_db;  

$conexion = conectar($nombre_host, $nombre_usuario, $password_db, $nombre_db);

// Obtener ID del producto desde la URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    die("ID de producto no válido.");
}

// Obtener datos del producto
$producto = obtenerProductos($conexion, $id);
function obtenerProducto($id)
{
    global $apiUrl;
    $ch= curl_init();

        curl_setopt($ch, CURLOPT_URL, $apiUrl . "?id=" . $id);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_HTTPGET, true);

        $respuesta=curl_exec($ch);
        curl_close($ch);

        return json_decode($respuesta, true);
}


if (isset($_GET['id'])) {
    $id_asignatura=$_GET['id'];
    $asignatura =obtenerProducto($id_asignatura);

}


// Si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($entrada["nombre"]) && isset($entrada["precio"]) && isset($entrada["descripcion"]) && isset($entrada["stock"]) && isset($entrada["imagen"]) && isset($entrada["membresia"])) {
        $id_producto=(int)$_POST['id'];
        $nombre_producto=$_POST['nombre'];
        $precio=(float)$_POST['precio'];
        $descripcion=$_POST['descripcion'];
        $stock=(int)$_POST['stock'];
        $imagen=$_POST['imagen'];
        $membresia=(int)$_POST['membresia'];

        $ch= curl_init();

        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            'id_producto' => $id_producto,
            'nombre_producto' => $nombre_producto,
            'precio' => $precio,
            'descripcion' => $descripcion,
            'stock' => $stock,
            'imagen' => $imagen,
            'membresia' => $membresia
        ]));

        curl_setopt($ch, CURLOPT_HTTPHEADER,[
            'Content-Type: application/json',
        ]);


        $respuesta = json_decode(curl_exec($ch),true);
        $httpCode= curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if ($httpCode == 200) {
            $mensaje = $respuesta["mensaje"];
        } else {
            $error = $respuesta["error"];
        }
    } else {
        $error = "Todos los campos son requeridos.";
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

    <h2>Editar Producto</h2>
    <form method="POST">
        <label>Nombre:</label>
        <input type="text" name="nombre" value="<?= htmlspecialchars($producto['nombre']) ?>" required><br>

        <label>Precio:</label>
        <input type="number" name="precio" value="<?= htmlspecialchars($producto['precio']) ?>" step="0.01" required><br>

        <label>Descripción:</label>
        <textarea name="descripcion" required><?= htmlspecialchars($producto['descripcion']) ?></textarea><br>
        
        <label>Membresía:</label>
        <input type="checkbox" name="membresia" <?= $producto['membresia'] ? 'checked' : '' ?>><br>

        <button type="submit">Guardar cambios</button>
    </form>

</body>
</html>
