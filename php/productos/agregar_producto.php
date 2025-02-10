<?php
// URL del backend (API)

// Procesar el formulario al enviarlo
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $apiUrl="http://localhost/DavidDelgado/php/productos/api.php"; // Cambia esta URL al endpoint correcto

    if (isset($_POST['nombre_asignatura']) && isset($_POST['creditos'])) {
        $nombre_asignatura =$_POST['nombre_asignatura'];
        $creditos=(int) $_POST['creditos'];
        $ch= curl_init();

        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            'nombre_asignatura' => $nombre_asignatura,
            'creditos' => $creditos
        ]));

        curl_setopt($ch, CURLOPT_HTTPHEADER,[
            'Content-Type: application/json',
        ]);

        // Ejecutar la solicitud
        $respuesta = json_decode(curl_exec($ch),true);
        $httpCode= curl_getinfo($ch, CURLINFO_HTTP_CODE);

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
                            <p class='success'>¡Producto borrado correctamente!</p>
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
                    <p class='error'>Error al borrar el producto: " . $respuesta["error"] . "</p>
                    <p class='redirect'>Serás redirigido en 3 segundos...</p>
                </div>
            </body>
            </html>";
            header("refresh:3; url=productos.php");
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
    <title>Añadir Asignatura</title>
    <link rel="stylesheet" href="estilos.css">
</head>

<body>
    <h1>Añadir Asignatura</h1>

    <?php if (isset($mensaje)) {
        echo "<p style='color: green;'>" . $mensaje;
        "</p>";
        header("Refresh: 3; url=index.php");
    }
    ?>

    <?php if (isset($error)) {
        echo "<p style='color: red;'>" . $error;
        "</p>";
    }
    ?>

    <form method="POST">
        <label for="nombre_asignatura">Nombre de la asignatura:</label>
        <input type="text" id="nombre_asignatura" name="nombre_asignatura" required>

        <label for="creditos">Créditos:</label>
        <input type="number" id="creditos" name="creditos" required>

        <button type="submit" class="add">Añadir Asignatura</button>
    </form>

    <a style="color:darkgreen" href="index.php">Volver al listado</a>
</body>

</html>