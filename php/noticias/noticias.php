
    <?php
    function noticias() {
        global $nombre_db, $nombre_host, $nombre_usuario, $password_db;

        $conexion = conectar($nombre_host, $nombre_usuario, $password_db, $nombre_db);
        $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
        $noticiasPorPagina = 4;
        $offset = ($pagina - 1) * $noticiasPorPagina;

        $sql_news = "SELECT n.id_noticia AS id_noticia, n.titulo, n.imagen, n.fecha_publicacion, 
                            SUBSTRING_INDEX(n.contenido, ' ', 15) AS resumen
                    FROM noticia n
                    ORDER BY n.fecha_publicacion DESC
                    LIMIT $noticiasPorPagina OFFSET $offset";

        $resultado = $conexion->query($sql_news);

        echo "<div class='cards-container'>";

        while ($fila = $resultado->fetch_assoc()) {
            echo "<div class='card'>";
            echo "<h3>{$fila['titulo']}</h3>";
            echo "<img src='/../DavidDelgado/img/news/" . $fila['imagen'] . ".jpg'>";
            echo "<p>{$fila['fecha_publicacion']}</p>";
            echo "<p>{$fila['resumen']}...</p>";
            
            echo "<button class='button'><a href='/DavidDelgado/php/noticias/noticias_file1.php?id={$fila['id_noticia']}'>Ver más</a></button>";

            echo "</div>";
        }

        echo "</div>";

        


        // Paginación
        $paginaAnterior = max($pagina - 1, 1);
        $paginaSiguiente = $pagina + 1;
        echo "<div>";
        echo "<a class='paginado' href='?pagina=$paginaAnterior'>&laquo  </a> ";
        echo "&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;";
        echo "&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;";
        echo "<a class='paginado' id='siguiente' href='?pagina=$paginaSiguiente'> &raquo </a>";
        echo "</div>";

        $conexion->close();
    }

    function mostrarNoticiaPorId($id) {
        global $nombre_db, $nombre_host, $nombre_usuario, $password_db;

        $conexion = conectar($nombre_host, $nombre_usuario, $password_db, $nombre_db);

        $sql_detalle = "SELECT n.titulo, n.imagen, n.fecha_publicacion, n.contenido 
                        FROM noticia n 
                        WHERE n.id_noticia = ?";
        
        $stmt = $conexion->prepare($sql_detalle);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            $noticia = $resultado->fetch_assoc();
            echo "<h2>{$noticia['titulo']}</h2>";
            echo "<img src='/../DavidDelgado/img/news/" . $noticia['imagen'] . ".jpg'>";
            echo "<p>Fecha de publicación: {$noticia['fecha_publicacion']}</p>";
            echo "<p>{$noticia['contenido']}</p>";
        } else {
            echo "<p>No se encontró la noticia solicitada.</p>";
        }

        $conexion->close();
    }

    function obtenerNoticiaPorId($id) {
        global $nombre_db, $nombre_host, $nombre_usuario, $password_db;

        $conexion = conectar($nombre_host, $nombre_usuario, $password_db, $nombre_db);

        $sql_detalle = "SELECT n.titulo, n.imagen, n.fecha_publicacion, n.contenido 
                        FROM noticia n 
                        WHERE n.id_noticia = ?";

        $stmt = $conexion->prepare($sql_detalle);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            return $resultado->fetch_assoc();
        }
        return null;
    }

    function news(){
            global $nombre_db,$nombre_host,$nombre_usuario,$password_db;

            $conexion=conectar($nombre_host,$nombre_usuario,$password_db,$nombre_db);

            $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
            $noticiasPorPagina = 3;
            $offset = ($pagina - 1) * $noticiasPorPagina;
            $sql_news = "SELECT n.id_noticia AS id_noticia, n.titulo, n.imagen, n.fecha_publicacion, 
                        SUBSTRING_INDEX(n.contenido, ' ', 15) AS resumen
                 FROM noticia n
                 ORDER BY n.fecha_publicacion DESC
                 LIMIT $noticiasPorPagina OFFSET $offset";

            $resultado = $conexion->query($sql_news);
        
            echo"<div class='secc-news' href='/DavidDelgado/php/noticias/noticias_file.php'>";
            echo "<h2 ><a href='/DavidDelgado/php/noticias/noticias_file.php'> <img  href='/DavidDelgado/php/noticias/noticias_file.php' src='https://fontmeme.com/permalink/241025/267e1fcdcb9c8baf990cb1759b12c08a.png' alt='fuente-dreams-american-diner' border='0'></a></h2>";
            
            while ($fila = $resultado->fetch_assoc()) {
                echo "<div class='ntc'>";
                    echo "<h3>{$fila['titulo']}</h3>";
                    echo "<img src='/../DavidDelgado/img/news/"  . $fila['imagen'] . '.jpg'.  "'>";
                    echo "<p>{$fila['fecha_publicacion']}</p>";
                    echo "<p>{$fila['resumen']}...</p>";
                    echo "<button class='button'><a href='/DavidDelgado/php/noticias/noticias_file1.php?id={$fila['id_noticia']}'>Ver más</a></button>";
                echo "</div>";
            }

            $paginaAnterior = max($pagina - 1, 1);
            $paginaSiguiente = $pagina + 1;

            echo "<br><a class='paginado' href='?pagina=$paginaAnterior'> &laquo </a>  ";
            echo "&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;";
            echo "<a class='paginado' href='?pagina=$paginaSiguiente'> &raquo </a>";
            echo "</div>";
    }


    function insertarNoticia(){
        global $nombre_db, $nombre_host, $nombre_usuario, $password_db;
        require_once "../../../DavidDelgado/connection/config.php";
        require_once "../../../DavidDelgado/connection/funciones.php";
            
        $conexion = conectar($nombre_host, $nombre_usuario, $password_db, $nombre_db);
            
        if (isset($_POST['submit'])) {
            $titulo = $_POST['titulo'];
            $contenido = $_POST['contenido'];
            $fecha = $_POST['fecha_publicacion'];
        
                $rutaDestino = null;
                if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
                    $foto = $_FILES['imagen'];
            
        
                    $directorioUploads = "../../../DavidDelgado/img/news/";
                    if (!is_dir($directorioUploads)) {
                        mkdir($directorioUploads, 0755, true); 
                    }
            
                    
                    $nombreArchivo = pathinfo($foto['name'], PATHINFO_FILENAME);
                    $rutaDestino = $directorioUploads . $nombreArchivo . ".jpg"; 
            
          
                    if (move_uploaded_file($foto['tmp_name'], $rutaDestino)) {
                        echo "<div class='message-container'>";
                        echo "El archivo se ha subido correctamente.<br>";
                    } else {
                        echo "<div class='message-container'>";
                        echo "Error al mover el archivo subido.<br>";
                        $nombreArchivo = "interrogacion"; 
                    }
                } else {
                    $nombreArchivo = "interrogacion"; 
                }
            
                $sql = "INSERT INTO noticia (id_noticia, titulo, contenido, fecha_publicacion, imagen)
                        VALUES (NULL, ?, ?, ?, ?)";
            
                if ($insert = $conexion->prepare($sql)) {
                    $insert->bind_param("ssss", $titulo, $contenido, $fecha, $nombreArchivo);
            
        
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
                                    <p class='success'>¡Noticia introducida correctamente!</p>
                                    <p class='redirect'>Serás redirigido en 3 segundos...</p>
                                </div>
                            </body>
                            </html>";
                           header("refresh:1; url=noticias_file.php");
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
                                <div>
                                    <p class='error'>Error al registrar la noticia: " . $insert->error . "</p>
                                    <p class='redirect'>Serás redirigido en 3 segundos...</p>
                                </div>
                            </body>
                            </html>";
                           header("refresh:1; url=noticias_file.php");
                        }         
                    
                    $insert->close();
                } else {
                    echo "Error al preparar la consulta: " . $conexion->error;
                }
            }
            
            $conexion->close();

    }
    if (isset($_POST['submit'])) {
        insertarNoticia();
    }
    ?>
