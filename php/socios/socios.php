<?php



function motrarsocios($search = '', $excludeAdmin = false) {
    global $nombre_db, $nombre_host, $nombre_usuario, $password_db;  

    $conexion = conectar($nombre_host, $nombre_usuario, $password_db, $nombre_db);

    $sql_list = "SELECT id_socio, usuario, nombre, edad, telefono, contrasena, foto FROM socio";
    if ($excludeAdmin) {
        $sql_list .= " WHERE id_socio != 0";
    }

    if ($search) {
        $searchTerm = '%' . $search . '%';
        $sql_list .= $excludeAdmin ? " AND" : " WHERE";
        $sql_list .= " (nombre LIKE ? OR telefono LIKE ?)";
    }

    $stmt = $conexion->prepare($sql_list);

    if ($search) {
        $stmt->bind_param("ss", $searchTerm, $searchTerm);
    }

    $stmt->execute();
    $resultado = $stmt->get_result();

    echo "<div class='socios'>";
    echo "<h2 id='socios'>
            Nuestros Socios
         </h2>";

    if ($resultado->num_rows > 0) {
        while ($fila = $resultado->fetch_assoc()) {
            echo "<div class='socio'>";
            echo "<img loading='lazy' src='../../img/socios/" . $fila['foto'] . ".jpg' class='imagen-perfil'>";
            echo "<h3>" . $fila['nombre'] . "</h3>";
            echo "<p> Usuario: " . $fila["usuario"] . "</p>";
            echo "<p> Edad: " . $fila['edad'] . "</p>";
            echo "<p> Teléfono: " . $fila['telefono'] . "</p>";

            if (isset($_SESSION['id_socio']) && $_SESSION['id_socio'] == 0) {
                echo "<form method='GET' action='socios_formulario.php' class='modificar'>";
                echo "<input type='hidden' name='id' value='" . $fila['id_socio'] . "'>";
                echo "<a href='socios_formulario.php?modificar=" . $fila['id_socio'] . "'>Modificar</a>";
                echo "</form>";
            }

            echo "</div>";
        }
    } else {
        echo "<p>No hay socios disponibles.</p>";
    }

    echo "</div>";
    $conexion->close();
}

function obtenerDatosSocio($id) {
    global $nombre_db, $nombre_host, $nombre_usuario, $password_db;
    $conexion = conectar($nombre_host, $nombre_usuario, $password_db, $nombre_db);

    $stmt = $conexion->prepare("SELECT id_socio, usuario, nombre, edad, telefono, contrasena, foto FROM socio WHERE id_socio = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();

    $datosSocio = null;
    if ($resultado->num_rows > 0) {
        $datosSocio = $resultado->fetch_assoc();
    }

    $stmt->close();
    $conexion->close();

    return $datosSocio;
}

function actualizarSocio($id, $usuario, $nombre, $edad, $telefono, $contrasena, $nombreArchivo) {
    global $nombre_db, $nombre_host, $nombre_usuario, $password_db;
    require_once "../../../DavidDelgado/connection/config.php";
    require_once "../../../DavidDelgado/connection/funciones.php";
    $conexion = conectar($nombre_host, $nombre_usuario, $password_db, $nombre_db);

    // Obtener la foto actual del socio
    $sqlFoto = $conexion->prepare("SELECT foto FROM socio WHERE id_socio = ?");
    $sqlFoto->bind_param("i", $id);
    $sqlFoto->execute();
    $sqlFoto->bind_result($fotoActual);
    $sqlFoto->fetch();
    $sqlFoto->close();

    // Si no hay nueva foto, usamos la existente
    if ($nombreArchivo == "anonimo") {
        $nombreArchivo = $fotoActual;
    }

    // Preparar y ejecutar la consulta de actualización
    $stmt = $conexion->prepare("UPDATE socio SET usuario = ?, nombre = ?, edad = ?, telefono = ?, contrasena = ?, foto = ? WHERE id_socio = ?");
    $stmt->bind_param("ssisssi", $usuario, $nombre, $edad, $telefono, $contrasena, $nombreArchivo, $id);
    $resultado = $stmt->execute();

    $stmt->close();
    $conexion->close();

    return $resultado;
}

if (isset($_POST['guardar_cambios'])) {
    $id = $_POST['id_socio'];
    $usuario = $_POST['usuario'];
    $nombre = $_POST['nombre'];
    $edad = $_POST['edad'];
    $telefono = $_POST['telefono'];
    $contrasena = $_POST['contrasena'];
    $nombreArchivo = "anonimo"; 

    // Verificar si se cargó una nueva foto
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $foto = $_FILES['foto'];

        $directorioUploads = "../../../DavidDelgado/img/socios/";
        if (!is_dir($directorioUploads)) {
            mkdir($directorioUploads, 0755, true);
        }

        $nombreSinExtension = pathinfo($foto['name'], PATHINFO_FILENAME);
        $nombreArchivo = $nombreSinExtension . ".jpg";
        $rutaDestino = $directorioUploads . $nombreArchivo;

        if (!move_uploaded_file($foto['tmp_name'], $rutaDestino)) {
            echo "<div class='message-container'>Error al mover el archivo subido.<br></div>";
            $nombreArchivo = "anonimo"; 
        }
    }

    // Llamar a la función actualizarSocio con la lógica ajustada
    if (actualizarSocio($id, $usuario, $nombre, $edad, $telefono, $contrasena, $nombreArchivo)) {
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
                            color: #D4AF37; /* Color dorado */
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
                            <p class='success'>¡Socio actualizado correctamente!</p>
                            <p class='redirect'>Serás redirigido en 3 segundos...</p>
                        </div>
                </body>
            </html>";
            header("refresh:1; url=socios_formulario.php");
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
                color: #D4AF37; /* Color dorado */
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
                <p class='error'>Error al actualizar el socio: " . $insert->error . "</p>
                <p class='redirect'>Serás redirigido en 3 segundos...</p>
            </div>
        </body>
        </html>";
        header("refresh:3; url=socios_formulario.php");
    }
}

?>

<?php
function insertar() {

    global $nombre_db, $nombre_host, $nombre_usuario, $password_db;
    require_once "../../../DavidDelgado/connection/config.php";
    require_once "../../../DavidDelgado/connection/funciones.php";
    
    $conexion = conectar($nombre_host, $nombre_usuario, $password_db, $nombre_db);
    
    if (isset($_POST['submit'])) {
        $nombre = $_POST['nombre'];
        $edad = $_POST['edad'];
        $usuario = $_POST['usuario'];
        $telefono = $_POST['telefono'];
        $contrasena = $_POST['contrasena'];
    

        $nombreArchivo = "anonimo"; 
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
            $foto = $_FILES['foto'];
    
            $directorioUploads = "../../../DavidDelgado/img/socios/";
            if (!is_dir($directorioUploads)) {
                mkdir($directorioUploads, 0755, true);
            }
    
            $nombreSinExtension = pathinfo($foto['name'], PATHINFO_FILENAME);
            $nombreArchivo = $nombreSinExtension . ".jpg";
            $rutaDestino = $directorioUploads . $nombreArchivo;
    
            if (move_uploaded_file($foto['tmp_name'], $rutaDestino)) {
                echo "<div class='message-container'>";
                echo "El archivo se ha subido correctamente.<br>";
            } else {
                echo "<div class='message-container'>";
                echo "Error al mover el archivo subido.<br>";
                $nombreArchivo = "anonimo"; 
            }
        } else {
            $nombreArchivo = "anonimo";
        }
    
        $sql = "INSERT INTO socio (id_socio, nombre, edad, usuario, telefono, contrasena, foto)
                VALUES (NULL, ?, ?, ?, ?, ?, ?)";
    
        if ($insert = $conexion->prepare($sql)) {
            $insert->bind_param("sisiss", $nombre, $edad, $usuario, $telefono, $contrasena, $nombreArchivo);
    

                if ($insert->execute()) {
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
                            color: #D4AF37; /* Color dorado */
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
                        <p class='success'>¡Socio registrado correctamente!</p>
                        <p class='redirect'>Serás redirigido en 3 segundos...</p>
                        </div>
                    </body>
                    </html>";
                    header("refresh:1; url=socios_formulario.php");
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
                            color: #D4AF37; /* Color dorado */
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
                        
                            <p class='error'>Error al registrar el socio: " . $insert->error . "</p>
                            <p class='redirect'>Serás redirigido en 3 segundos...</p>
                      
                    </body>
                    </html>";
                    header("refresh:1; url=socios_formulario.php");
                }         
            
            $insert->close();
        } else {
            echo "Error al preparar la consulta: " . $conexion->error;
        }
    }
    
    $conexion->close();
    
}

if (isset($_POST['submit'])) {
    insertar();
}

?>
