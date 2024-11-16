

    <?php
   
        require_once "connection/config.php";
        require_once "connection/funciones.php";

        function generarTestimonios(): void {
           
            global $nombre_db,$nombre_host,$nombre_usuario,$password_db;   

            $conexion=conectar($nombre_host,$nombre_usuario,$password_db,$nombre_db);
            
            $sql_test = "SELECT s.foto, s.nombre, t.contenido, t.fecha 
                            FROM socio s 
                            JOIN testimonio t ON t.autor = s.id_socio 
                            ORDER BY RAND() 
                            LIMIT 3;"
            ;
            $resultado = $conexion->query($sql_test);
        
           
            echo "<div id='testimonios' class='testimonios'>";
            echo "<h2 class='titulo-animado'>
                    Lo que dicen nuestros socios
                    <span class='border border-top'></span>
                    <span class='border border-bottom'></span>
                    <span class='border border-left'></span>
                    <span class='border border-right'></span>
                </h2>";
            
            
            if ($resultado->num_rows > 0) {
                while ($fila = $resultado->fetch_assoc()) {
                    echo "<div class='testimonio'>";
                    echo "<img src= '$fila[foto]'  class='imagen-perfil'>";
                    echo "<blockquote>
                                <p> “" .$fila['contenido']. "”</p>
                           </blockquote> ";
                    echo "<p class='autor'>".$fila['nombre'] ."<br>" . $fila['fecha'] . "</p>";
                    echo "</div>";
                }
            } else {
                echo "<p>No hay reseñas disponibles.</p>";
            }
            
            echo "</div>";
        
            
            $conexion->close();
        }
    ?>
