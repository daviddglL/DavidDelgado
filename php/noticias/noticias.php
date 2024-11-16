
    <?php


        function news($test = false){
            global $nombre_db,$nombre_host,$nombre_usuario,$password_db;

            $conexion=conectar($nombre_host,$nombre_usuario,$password_db,$nombre_db);

            $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
            $noticiasPorPagina = 2;
            $offset = ($pagina - 1) * $noticiasPorPagina;
            $sql_news="SELECT n.titulo, n.imagen, n.fecha_publicacion, 
                                SUBSTRING_INDEX(n.contenido, ' ', 15) AS resumen
                            FROM noticia n
                            ORDER BY n.fecha_publicacion DESC
                            LIMIT $noticiasPorPagina OFFSET $offset";

            $resultado = $conexion->query($sql_news);
        
            echo"<div class='secc-news'>";
            echo "<h2><img src='https://fontmeme.com/permalink/241025/267e1fcdcb9c8baf990cb1759b12c08a.png' alt='fuente-dreams-american-diner' border='0'></h2>";
            
            while ($fila = $resultado->fetch_assoc()) {
                echo "<div class='ntc'>";
                    echo "<h3>{$fila['titulo']}</h3>";
                    echo "<img src='{$fila['imagen']}' alt=''>";
                    echo "<p>{$fila['fecha_publicacion']}</p>";
                    echo "<p>{$fila['resumen']}...</p>";
                    echo "<button class='button'><a href='../clubmesa/noticias/noticia1.html'>Ver más</a></button>";//cambiar
                echo "</div>";
            }

            $paginaAnterior = max($pagina - 1, 1);
            $paginaSiguiente = $pagina + 1;

            echo "<br><a class='paginado' href='?pagina=$paginaAnterior'>Página Anterior</a> | ";
            echo "<a class='paginado' href='?pagina=$paginaSiguiente'>Página Siguiente</a>";
            echo "</div>";
        }
    ?>
