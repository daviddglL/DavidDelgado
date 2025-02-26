<?php
// URL del backend (API)
$apiUrl = "http://localhost/DavidDelgado/php/productos/api.php"; // Cambia esta URL al endpoint correcto

// Procesar el formulario al enviarlo
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['nombre']) && isset($_POST['precio']) && isset($_POST['descripcion']) && isset($_POST['stock']) && isset($_FILES['imagen'])) {
        $nombre = $_POST['nombre'];
        $precio = (float) $_POST['precio'];
        $descripcion = $_POST['descripcion'];
        $stock = (int) $_POST['stock'];
        $estado = $stock > 0 ? 'disponible' : 'no disponible';
        $imagen = $_FILES['imagen']['name'];
        $imagen_tmp = $_FILES['imagen']['tmp_name'];
        $membresia = isset($_POST['membresia']) ? 1 : 0; // Obtener el valor de la membresía

        // Mover la imagen a la carpeta de destino
        move_uploaded_file($imagen_tmp, "../../img/productos/" . $imagen . ".jpg");

        $data = [
            'nombre' => $nombre,
            'precio' => $precio,
            'descripcion' => $descripcion,
            'stock' => $stock,
            'estado' => $estado,
            'imagen' => $imagen,
            'membresia' => $membresia // Incluir el valor de la membresía
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json', 
        ]);

        // Ejecutar la solicitud
        $respuesta_raw = curl_exec($ch);
        $respuesta = json_decode($respuesta_raw, true);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        error_log("Respuesta cruda de la API: " . $respuesta_raw);
        $respuesta = json_decode($respuesta_raw, true);

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // Depuración: Verificar los datos enviados y la respuesta
        error_log("Datos enviados: " . json_encode($data));
        error_log("Respuesta de la API: " . json_encode($respuesta));
        error_log("Código HTTP: " . $httpCode);

        if ($httpCode == 200) { 
            echo "
            <html>
                <head>
                    <style>
                        body, html {
                            margin: 0;
                            padding: 0;
                            height: 100%;
                            background-color: #1a1c1d;
                            color: #aaaebc;
                            display: flex;
                            justify-content: center;
                            align-items: center;
                        }
        
                        .message-container {
                            display: flex;
                            flex-direction: column;
                            justify-content: center;
                            align-items: center;
                            text-align: center;
                            background-color: #2C2C2C;
                            padding: 2rem;
                            border-radius: 10px;
                            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
                            border: 2px solid #F5F5F5;
                        }
        
                        .message-container .success {
                            color: #D4AF37; 
                            font-size: 1.5rem;
                            margin-bottom: 1rem;
                            text-shadow: 1px 1px #800020;
                        }
        
                        .message-container .redirect {
                            color: #F5F5F5;
                            font-size: 1rem;
                        }
        
                    </style>
                </head>
                <body>
                        <div class='message-container'>
                            <p class='success'>¡Producto agregado correctamente!</p>
                            <p class='redirect'>Serás redirigido en 3 segundos...</p>
                        </div>
                </body>
            </html>";
            header("refresh:3; url=productos.php");
            exit();
        } else {
            // Verificar si $respuesta es NULL antes de acceder a 'error'
            $mensaje_error = isset($respuesta["error"]) ? $respuesta["error"] : "Error desconocido en la API";
            
            echo "
            <html>
            <head>
                <style>
                    body, html {
                    margin: 0;
                    padding: 0;
                    height: 100%;
                    background-color: #1a1c1d;
                    color: #aaaebc;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                }
        
                .message-container {
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    align-items: center;
                    text-align: center;
                    background-color: #2C2C2C;
                    padding: 2rem;
                    border-radius: 10px;
                    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
                    border: 2px solid #F5F5F5;
                }
        
                .message-container .error {
                    color: #D4AF37;
                    font-size: 1.5rem;
                    margin-bottom: 1rem;
                    text-shadow: 1px 1px #800020;
                }
        
                .message-container .redirect {
                    color: #F5F5F5;
                    font-size: 1rem;
                }
                </style>
            </head>
            <body>
                <div class='message-container'>
                    <p class='error'>Error al agregar el producto: $mensaje_error</p>
                    <p class='redirect'>Serás redirigido en 3 segundos...</p>
                </div>
            </body>
            </html>";

            error_log("Datos enviados al API: " . json_encode($data));
echo "<pre>";
print_r($data);
echo "</pre>";

            exit();
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
    <title>Agregar Producto</title>
    <link rel="stylesheet" href="../../estilos/styles.css">
    <style>
        .formulario {
            margin-top: 100px;
            align-items: center;
            margin-left: 700px;
        }
    </style>
</head>
<body>
    <div class="container">
        
        <form class="formulario" id="agregar-producto-form" action="agregar_producto.php" method="POST" enctype="multipart/form-data">
        <h2>Agregar Nuevo Producto</h2>
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>

            <label for="precio">Precio:</label>
            <input type="number" id="precio" name="precio" step="0.01" required>

            <label for="descripcion">Descripción:</label>
            <textarea id="descripcion" name="descripcion" required></textarea>

            <label for="stock">Stock:</label>
            <input type="number" id="stock" name="stock" required>

            <label for="imagen">Imagen:</label>
            <input type="file" id="imagen" name="imagen" accept="image/*" required>

            <label for="membresia">Membresía:</label>
            <input type="checkbox" id="membresia" name="membresia">

            <button type="submit" class="button">Agregar Producto</button>
        </form>
    </div>
            
</body>
</html>

