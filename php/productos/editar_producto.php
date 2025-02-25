<?php
require_once 'api_funciones.php';
require_once "../../connection/config.php";
require_once "../../connection/funciones.php";
require_once ("../../php/inicio.php");
require_once("../noticias/noticias.php");
require_once("../testimonios/testimonios.php");
require_once("../servicios/servicios.php");
require_once("../socios/socios.php");

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
        
        if (isset($respuesta_decodificada['datos']) && is_array($respuesta_decodificada['datos']) && count($respuesta_decodificada['datos']) > 0) {
            foreach ($respuesta_decodificada['datos'] as $producto) {
                if ($producto["id_producto"] == $id) {
                    return $producto;  // Retorna los datos del producto con el ID correcto
                }
            }
            echo "Producto con ID $id no encontrado.";
            return null;
        } else {
            echo "No se encontraron productos en la respuesta.";
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
if (isset($_POST['cambios_producto'])) {
    if (isset($_POST["id_producto"], $_POST["nombre"], $_POST["precio"], $_POST["descripcion"], $_POST["stock"])) {
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

        $respuestaJson = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($respuestaJson === false) {
            $error = "Error en cURL: " . curl_error($ch);
            error_log($error); // Registrar el error en el log
        } else {
            $respuesta = json_decode($respuestaJson, true);
            if ($respuesta === null) {
                $error = "Error al procesar la respuesta de la API. Respuesta recibida: " . $respuestaJson;
                error_log($error); // Registrar la respuesta recibida
            } else {
                // Depuración: imprimir la respuesta de la API
                error_log("Respuesta de la API: " . print_r($respuesta, true));
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
                                    <p class='success'>¡Producto actualizado correctamente!</p>
                                    <p class='redirect'>Serás redirigido en 3 segundos...</p>
                                </div>
                        </body>
                    </html>";
                    header("refresh:3; url=productos.php");
                    exit();
                } else {
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
                            <p class='error'>Error al actualizar el producto: " . $respuesta["error"] . "</p>
                            <p class='redirect'>Serás redirigido en 3 segundos...</p>
                        </div>
                    </body>
                    </html>";
                    header("refresh:3; url=productos.php");
                    exit();
                }
            }
        }

        curl_close($ch);
    } else {
        echo "Todos los campos son requeridos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto</title>
    <link rel="stylesheet" href="../../estilos/styles.css">
    <script defer src="../../js/formulario_productos.js"></script>

</head>

<body>
<div class="container">
    <?php
        headerr();
        contactos();
        echo "<div class='secc'><!--sección central-->";
    ?>
    <br><br><br>
    <div class="formulario">
        
    <h1>Editar Producto</h1>

    <?php


    if (isset($error)) {
        echo '<p style="color: red;">' . $error . '</p>';
    }

    if (isset($producto)) {
        ?>
        
            <form  id='form-modificar' method='POST'  enctype='multipart/form-data'>
                <label for="nombre">Nombre del producto:</label>
                <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($producto['nombre']); ?>" >
                <span class='error'></span>
        
                <label for="precio">Precio:</label>
                <input type="number" id="precio" name="precio" value="<?php echo htmlspecialchars($producto['precio']); ?>€" >
                <span class='error'></span>

                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion" name="descripcion" required><?= htmlspecialchars($producto['descripcion']) ?></textarea><br>
                <span class='error'></span>

                <label for="stock">stock:</label>
                <input type="number" id="stock" name="stock" value="<?php echo htmlspecialchars($producto['stock']); ?>" >
                <span class='error'></span>
                
                <label>Membresía:</label>
                <input type="checkbox" name="membresia" <?= $producto['membresia'] ? 'checked' : '' ?>><br>

                
                <input type="hidden" id="id_producto" name="id_producto" value="<?php echo htmlspecialchars($producto['id_producto']); ?>">
                <button type="submit" name='cambios_producto'>Actualizar</button>
            </form>
        
        <?php
    } else {
        echo '<p>Producto no encontrado.</p>';
    }
    
    ?>
    </div>
    <?php
        echo "</div>";
        news();
        footer();        
    ?>

    <a href="./productos.php" style="color:darkgreen">Volver al listado</a>
</body>

</html>
