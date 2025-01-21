
<?php


function motrarServicios($search = '') {
    global $nombre_db, $nombre_host, $nombre_usuario, $password_db;  

    $conexion = conectar($nombre_host, $nombre_usuario, $password_db, $nombre_db);

    $sql_list = "SELECT codigo_servicio, duracion_servicio, descripcion, precio_servicio FROM servicio";

    if ($search) {
        $sql_list = "SELECT codigo_servicio, duracion_servicio, descripcion, precio_servicio FROM servicio WHERE descripcion LIKE ? ";
    }

    $stmt = $conexion->prepare($sql_list);


    if ($search) {
        $searchTerm = '%' . $search . '%';
        $stmt->bind_param("s", $searchTerm, );
    }

    $stmt->execute();
    $resultado = $stmt->get_result();

   echo "<div class='servicios'>";
   echo "<h2 id='servicios' >
            Nuestros Servicios
         </h2>";
   
   
    if ($resultado->num_rows > 0) {
        while ($fila = $resultado->fetch_assoc()) {
           echo "<div class='servicio'>";
           echo "<h3>" . $fila['descripcion'] . "</h3>";
           echo "<p> Precio ". $fila["precio_servicio"] . "€";
           echo "<p> Tiempo Estimado: " .$fila['duracion_servicio'] . " </p>";

           echo "<form method='GET' action='servicios_formulario.php' class='modificar'>";
                echo "<input type='hidden' name='id' value='" . $fila['codigo_servicio'] . "'>";
                echo "<a  href='servicios_formulario.php?modificar=" . $fila['codigo_servicio'] . "'>Modificar</a>";
            echo "</form>";
            echo "</div>";
          }
    } else {
       echo "<p>No hay servicios disponibles.</p>";
     }
   
   echo "</div>";
    $conexion->close();

    
}

function obtenerDatosServicio($id) {
    global $nombre_db, $nombre_host, $nombre_usuario, $password_db;
    $conexion = conectar($nombre_host, $nombre_usuario, $password_db, $nombre_db);

    $stmt = $conexion->prepare("SELECT codigo_servicio, duracion_servicio, descripcion, precio_servicio FROM servicio WHERE codigo_servicio = ?");
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


function actualizarServicio($id, $duracion_servicio, $descripcion, $precio_servicio) {
    global $nombre_db, $nombre_host, $nombre_usuario, $password_db;
    require_once "../../../DavidDelgado/connection/config.php";
    require_once "../../../DavidDelgado/connection/funciones.php";
    $conexion = conectar($nombre_host, $nombre_usuario, $password_db, $nombre_db);

    $stmt = $conexion->prepare("UPDATE servicio SET duracion_servicio = ?, descripcion = ?, precio_servicio = ? WHERE codigo_servicio = ?");
    $stmt->bind_param("issi", $duracion_servicio, $descripcion, $precio_servicio, $id);

    $resultado = $stmt->execute();
    $stmt->close();
    $conexion->close();

    return $resultado;
}

if (isset($_POST['Guardar_Cambios'])) {
    $id = $_POST['codigo_servicio'];
    $duracion_servicio = $_POST['duracion_servicio'];
    $descripcion = $_POST['descripcion'];
    $precio_servicio = $_POST['precio_servicio'];

    if (actualizarServicio($id, $duracion_servicio, $descripcion, $precio_servicio)) {
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
                            <p class='success'>¡Servicio actualizado correctamente!</p>
                            <p class='redirect'>Serás redirigido en 3 segundos...</p>
                        </div>
                    </body>
                    </html>";
                    header("refresh:1; url=servicios_formulario.php");
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
                            <p class='error'>Error al actualizar el servicio: " . $insert->error . "</p>
                            <p class='redirect'>Serás redirigido en 3 segundos...</p>
                        </div>
                    </body>
                    </html>";
                    header("refresh:1; url=servicios_formulario.php");
    }
}

   function generarServicios() {

        global $nombre_db,$nombre_host,$nombre_usuario,$password_db;   
       
       $conexion = conectar($nombre_host,$nombre_usuario,$password_db,$nombre_db);
    
       $sql = "SELECT duracion_servicio, descripcion FROM servicio";
       $resultado = $conexion->query($sql);
   
      
       echo "<div class='servicios'>";
       echo "<h2 id='servicios' class='titulo-animado'>
               Nuestros Servicios
               <span class='border border-top'></span>
               <span class='border border-bottom'></span>
               <span class='border border-left'></span>
               <span class='border border-right'></span>
             </h2>";
       
       
       if ($resultado->num_rows > 0) {
           while ($fila = $resultado->fetch_assoc()) {
               echo "<div class='servicio'>";
               echo "<h3>" . $fila['descripcion'] . "</h3>";
               echo "<p> Tiempo Estimado: " .$fila['duracion_servicio'] . " </p>";
               echo "</div>";
           }
       } else {
           echo "<p>No hay servicios disponibles.</p>";
       }
       
       echo "</div>";
   
       
       $conexion->close();
   }
?>

<?php


   function insertarServicio() {

    global $nombre_db, $nombre_host, $nombre_usuario, $password_db;
    require_once "../../../DavidDelgado/connection/config.php";
    require_once "../../../DavidDelgado/connection/funciones.php";
    
    $conexion = conectar($nombre_host, $nombre_usuario, $password_db, $nombre_db);
    
    if (isset($_POST['submit'])) {
        $duracion_servicio = $_POST['duracion_servicio'];
        $descripcion = $_POST['descripcion'];
        $precio_servicio = $_POST['precio_servicio'];

    
        $sql = "INSERT INTO servicio (duracion_servicio, descripcion, precio_servicio)
                VALUES (?, ?, ?)";
    
        if ($insert = $conexion->prepare($sql)) {
            $insert->bind_param("isi", $duracion_servicio, $descripcion, $precio_servicio);
    

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
                            <p class='success'>¡Servicio registrado correctamente!</p>
                            <p class='redirect'>Serás redirigido en 3 segundos...</p>
                        </div>
                    </body>
                    </html>";
                    header("refresh:1; url=servicios_formulario.php");
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
                            <p class='error'>Error al registrar el servicio: " . $insert->error . "</p>
                            <p class='redirect'>Serás redirigido en 3 segundos...</p>
                        </div>
                    </body>
                    </html>";
                    header("refresh:1; url=servicios_formulario.php");
                }         
            
            $insert->close();
        } else {
            echo "Error al preparar la consulta: " . $conexion->error;
        }
    }
    
    $conexion->close();
    
}
if (isset($_POST['submit'])) {
    insertarServicio();
}

?>