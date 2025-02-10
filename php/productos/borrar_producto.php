<?php

// Si el ID está presente, realizar la eliminación
if (isset($_GET['id_producto'])) {

    $id_producto = $_GET['id_producto'];
    $apiUrl = "http://localhost/DavidDelgado/php/productos/api.php?id_producto=" . $id_producto;

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');

    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
    ]);

    // Ejecutar la solicitud
    $respuesta = json_decode(curl_exec($ch), true);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

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
    echo "ID no indicado para eliminar.";
}
