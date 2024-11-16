
<?php
   
    require_once "connection/config.php";
    require_once "connection/funciones.php";
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
