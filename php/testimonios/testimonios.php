

    <?php
   
   function testimonios(): void {
           
    global $nombre_db,$nombre_host,$nombre_usuario,$password_db;   

    $conexion=conectar($nombre_host,$nombre_usuario,$password_db,$nombre_db);
    
    $sql_test = "SELECT s.foto, s.nombre, t.contenido, t.fecha 
                    FROM socio s 
                    JOIN testimonio t ON t.autor = s.id_socio 
                    ORDER BY t.fecha DESC";
    $resultado = $conexion->query($sql_test);

   
    echo "<div id='testimonios' class='testimonios_class'>";
    echo "<h2 class='titulo-animado'>
            Lo que dicen nuestros socios
        </h2>";
    
    
    if ($resultado->num_rows > 0) {
        while ($fila = $resultado->fetch_assoc()) {
            echo "<div class='testimonio'>";
            echo "<img src='" . '/../DavidDelgado/img/socios/' . $fila['foto'] . '.jpg' . "'class='imagen-perfil'>";
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
    function selectSocio($conexion){
        $select="SELECT socio.id_socio, socio.usuario FROM socio WHERE socio.id_socio=1";

        $res=$conexion->query($select);
        if($res){
            echo "<select type='select' id='socio' name='socio'>";
            while ($usuarios=$res->fetch_array(MYSQLI_ASSOC)){
                $usuario=$usuarios['usuario'];
                $id= $usuarios['id_socio'];
                echo "<option value='$id'>$usuario</option>";
            }
            echo "</select>";
        }
    }
    function insertarTestimonio() {

        global $nombre_db, $nombre_host, $nombre_usuario, $password_db;
        require_once "../../../DavidDelgado/connection/config.php";
        require_once "../../../DavidDelgado/connection/funciones.php";
        
        $conexion = conectar($nombre_host, $nombre_usuario, $password_db, $nombre_db);
        
        if (isset($_POST['submit'])) {
            $autor = $_POST['socio'];
            $contenido = $_POST['contenido'];
            $fechaActual = date('Y-m-d');
       

        
            $sql = "INSERT INTO testimonio (id_testimonio, autor, contenido, fecha) 
                    VALUES (null,?,?,?)";
        
            if ($insert = $conexion->prepare($sql)) {
                $insert->bind_param("sss", $autor, $contenido,$fechaActual);
        

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
                                <p class='success'>¡Testimonio registrado correctamente!</p>
                                <p class='redirect'>Serás redirigido en 3 segundos...</p>
                            </div>
                        </body>
                        </html>";
                        header("refresh:1; url=testimonios_formulario.php");
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
                                <p class='error'>Error al registrar el testimonio: " . $insert->error . "</p>
                                <p class='redirect'>Serás redirigido en 3 segundos...</p>
                            </div>
                        </body>
                        </html>";
                        header("refresh:1; url=testimonios_formulario.php");
                    }         
                 
                $insert->close();
            } else {
                echo "Error al preparar la consulta: " . $conexion->error;
            }
        }
        
        $conexion->close();
        
    }
    if (isset($_POST['submit'])) {
        insertarTestimonio();
    }

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
                    echo "<img src='" . './img/socios/' . $fila['foto'] . '.jpg' . "'class='imagen-perfil'>";
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
