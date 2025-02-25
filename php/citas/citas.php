<?php

function crearBoton($fecha, $citas) {
    $id_socio = $_SESSION['id_socio'];
    if ($id_socio === 0) {

        if (isset($citas[$fecha])) {
            return "<form class='calendar' method='GET' action=''>
                        <input type='hidden' name='fecha' value='$fecha'>
                        <button type='submit'>Ver citas</button>
                    </form>";
        }
        return '';
    }
    else{
        if ($id_socio != 0 && isset($citas[$fecha]) && is_array($citas[$fecha])) {
            foreach ($citas[$fecha] as $cita) {
                if (isset($cita['codigo_socio']) && $cita['codigo_socio'] == $id_socio) {
                    return "<form class='calendar' method='GET' action=''>
                                <input type='hidden' name='fecha' value='" . htmlspecialchars($fecha, ENT_QUOTES, 'UTF-8') . "'>
                                <button type='submit'>Ver citas</button>
                            </form>";
                }
            }
        }
        return '';
        
        

        
    }
    
}


function calendar($conexion) {
    
    // Obtener mes y año actuales, asegurándonos de que se actualicen correctamente
    $currentMonth = isset($_GET['month']) ? (int)$_GET['month'] : date('m');
    $currentYear = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');

    if (isset($_GET['action'])) {
        if ($_GET['action'] === 'prev') {
            $currentMonth--;
            if ($currentMonth < 1) {
                $currentMonth = 12;
                $currentYear--;
            }
        } elseif ($_GET['action'] === 'next') {
            $currentMonth++;
            if ($currentMonth > 12) {
                $currentMonth = 1;
                $currentYear++;
            }
        }
    }

    // Asegúrate de obtener las citas del mes actualizado
    $citasPorMes = obtenerCitasPorMes($conexion, $currentMonth, $currentYear);

    $timestamp = mktime(0, 0, 0, $currentMonth, 1, $currentYear);
    $daysInMonth = date("t", $timestamp);
    $firstDay = date("N", $timestamp);

    // Navegación entre meses
    echo "<div class='navigation'>";
    echo "<a href='?action=prev&month=$currentMonth&year=$currentYear'>&laquo; Previous</a>";
    echo "&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;";
    echo "<span class='month'>" . date("F Y", $timestamp) . "</span>";
    echo "&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;";
    echo "<a href='?action=next&month=$currentMonth&year=$currentYear'>Next &raquo;</a>";
    echo "</div><br>";

    // Crear la tabla del calendario
    echo "<table class='calendar-table'>";
    echo "<tr><th>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th><th>Fri</th><th>Sat</th><th>Sun</th></tr>";
    $dayCount = 1;
    echo "<tr>";

    // Rellenar los días previos al inicio del mes
    for ($i = 1; $i <= 7; $i++) {
        if ($i < $firstDay) {
            echo "<td></td>";
        } else {
            $fecha = sprintf('%04d-%02d-%02d', $currentYear, $currentMonth, $dayCount);
            echo "<td>$dayCount " . crearBoton($fecha, $citasPorMes) . "</td>";
            $dayCount++;
        }
    }
    echo "</tr>";

    // Rellenar los días restantes del mes
    while ($dayCount <= $daysInMonth) {
        echo "<tr>";
        for ($i = 1; $i <= 7 && $dayCount <= $daysInMonth; $i++) {
            $fecha = sprintf('%04d-%02d-%02d', $currentYear, $currentMonth, $dayCount);
            echo "<td>$dayCount " . crearBoton($fecha, $citasPorMes) . "</td>";
            $dayCount++;
        }
        echo "</tr>";
    }
    echo "</table>";
}

function obtenerCitasPorMes($conexion, $mes, $año) {
    $inicioMes = sprintf('%04d-%02d-01', $año, $mes);
    $finMes = date("Y-m-t", strtotime($inicioMes));

    $sql = "SELECT fecha_cita, hora_cita, codigo_socio
            FROM citas
            WHERE fecha_cita BETWEEN ? AND ?";

    if ($stmt = $conexion->prepare($sql)) {
        $stmt->bind_param('ss', $inicioMes, $finMes);
        $stmt->execute();
        $resultado = $stmt->get_result();

        $citas = [];
        while ($fila = $resultado->fetch_assoc()) {
            $fecha = $fila['fecha_cita'];
            $citas[$fecha][] = [
                'hora' => $fila['hora_cita'],
                'codigo_socio' => $fila['codigo_socio']
            ];
        }
        $stmt->close();
        return $citas;
    } 

    return [];
}


function obtenerDatosCitas($conexion, $searchTerm) {
    // Consulta SQL que busca en socio, servicio y fecha
    $sql = "SELECT socio.nombre, servicio.descripcion, c.fecha_cita, c.hora_cita
            FROM citas c
            INNER JOIN socio ON socio.id_socio = c.codigo_socio
            INNER JOIN servicio ON servicio.codigo_servicio = c.codigo_servicio
            WHERE socio.nombre LIKE ? 
               OR servicio.descripcion LIKE ? 
               OR c.fecha_cita LIKE ?";

    if ($stmt = $conexion->prepare($sql)) {
        // Preparar el término de búsqueda con comodines para LIKE
        $searchTermLike = "%$searchTerm%";
        $stmt->bind_param("sss", $searchTermLike, $searchTermLike, $searchTermLike);
        $stmt->execute();
        $resultado = $stmt->get_result();

        // Mostrar resultados en formato tabla
        if ($resultado->num_rows > 0) {
            echo "<br><br><div class='citas'>";
            echo "<h3>Resultados de búsqueda para '$searchTerm':</h3>";
            echo "<table class='appointment-table'>";
            echo "<tr><th>Socio</th><th>Servicio</th><th>Fecha</th><th>Hora</th></tr>";
            while ($fila = $resultado->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$fila['nombre']}</td>";
                echo "<td>{$fila['descripcion']}</td>";
                echo "<td>{$fila['fecha_cita']}</td>";
                echo "<td>{$fila['hora_cita']}</td>";
                echo "</tr>";
            }
            echo "</table>";
            echo "</div>";
        } else {
            echo "<p>No se encontraron resultados para '$searchTerm'.</p>";
        }

        $stmt->close();
    } else {
        echo "<p>Error al preparar la consulta: " . $conexion->error . "</p>";
    }
}


function actualizarEstadoCitasPasadas($conexion) {
    $fechaActual = date('Y-m-d');
    $horaActual = date('H:i:s');

    // Actualiza todas las citas cuya fecha y hora ya pasaron a "completada"
    $sql = "UPDATE citas SET estado = 'completada' 
            WHERE (fecha_cita < ? OR (fecha_cita = ? AND hora_cita < ?)) 
            AND estado != 'completada'";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sss", $fechaActual, $fechaActual, $horaActual);
    $stmt->execute();
    $stmt->close();
}

 function mostrarCitas($conexion, $fecha){
    actualizarEstadoCitasPasadas($conexion);
    $id_socio = $_SESSION['id_socio'];
    if ($id_socio === 0) {
        $sql = "SELECT id_cita, socio.nombre, servicio.descripcion, fecha_cita, hora_cita, estado FROM citas c 
                INNER JOIN socio on socio.id_socio= c.codigo_socio  
                INNER JOIN servicio on servicio.codigo_servicio= c.codigo_servicio 
                WHERE c.fecha_cita= ?";


        if ($stmt = $conexion->prepare($sql)) {
            $stmt->bind_param('s', $fecha);
            $stmt->execute();
            $resultado = $stmt->get_result();
            
                if ($resultado->num_rows > 0) {
                    echo "<br><br><div class='citas'>";
                    echo "<h3>Citas para el día $fecha:</h3>";
                    echo "<table class='appointment-table'>";
                    echo "<tr><th>Socio</th><th>Servicio</th><th>Fecha</th><th>Hora</th><th>Estado</th></tr>";
                    while ($fila = $resultado->fetch_assoc()) {
                        echo "<tr>";
                            echo "<td>{$fila['nombre']}</td>";
                            echo "<td>{$fila['descripcion']}</td>";
                            echo "<td>{$fila['fecha_cita']}</td>";
                            echo "<td>{$fila['hora_cita']}</td>";
                            echo "<td>";
                                if ($fila['estado'] === 'pendiente') {
                                    echo "
                                    <form class='borrar' method='POST' action='citas.php'>
                                        <input type='hidden' name='id_cita' value='{$fila['id_cita']}'>
                                        <button type='submit' class='cancel-button'>Anular</button>
                                    </form>";
                                } elseif ($fila['estado'] === 'anulada' && $fila['fecha_cita'] > date('Y-m-d')) {
                                    echo "
                                    <form class='borrar' method='POST' action='citas.php'>
                                        <input type='hidden' name='borrar' value='{$fila['id_cita']}'>
                                        <button type='submit' class='delete-button'>Borrar</button>
                                    </form>";
                                } elseif ($fila['estado'] === 'completada') {
                                    echo "<span class='completed-message'>Realizada</span>";
                                }
                            echo "</td>";
                        echo "</tr>";
                        
                            }
                            echo "</table>";
                        } else {
                            echo "<p>No hay citas para el día $fecha.</p>";
                        }
                            echo "</div>";
                        $stmt->close();
                    } 
        } else {
            $sql = "SELECT id_cita, socio.nombre, servicio.descripcion, fecha_cita, hora_cita, estado FROM citas c 
                    INNER JOIN socio on socio.id_socio= c.codigo_socio  
                    INNER JOIN servicio on servicio.codigo_servicio= c.codigo_servicio 
                    WHERE c.codigo_socio = ? AND c.fecha_cita= ?";

            if ($stmt = $conexion->prepare($sql)) {
                $stmt->bind_param('is', $id_socio, $fecha);
                $stmt->execute();
                $resultado = $stmt->get_result();
    
            if ($resultado->num_rows > 0) {
                echo "<br><br><div class='citas'>";
                echo "<h3>Citas para el día $fecha:</h3>";
                echo "<table class='appointment-table'>";
                echo "<tr><th>Socio</th><th>Servicio</th><th>Fecha</th><th>Hora</th></tr>";
                while ($fila = $resultado->fetch_assoc()) {
                    echo "<tr>";
                        echo "<td>{$fila['nombre']}</td>";
                        echo "<td>{$fila['descripcion']}</td>";
                        echo "<td>{$fila['fecha_cita']}</td>";
                        echo "<td>{$fila['hora_cita']}</td>";
                    echo "</tr>";
                    
                }
                echo "</table>";
            } else {
                echo "<p>No hay citas para el día $fecha.</p>";
            }
                echo "</div>";
            $stmt->close();
} 
        }
            
        
    
 }


    function selectSocio($conexion){
        $select="SELECT socio.id_socio, socio.usuario FROM socio;";

        $res=$conexion->query($select);
        if($res){
            echo "<select id='defecto_socio' type='select' id='socio' name='socio'>";
            echo "<option >Seleccionar un cliente</option> ";
            while ($usuarios=$res->fetch_array(MYSQLI_ASSOC)){
                $usuario=$usuarios['usuario'];
                $id= $usuarios['id_socio'];
                echo "<option value='$id'>$usuario</option>";
            }
            echo "</select>";
        }
    }

    function selectServicio($conexion){
        $select="SELECT servicio.codigo_servicio, servicio.descripcion FROM servicio;";

        $res=$conexion->query($select);
        if($res){
            echo "<select id='defecto_servicio' type='select' id='servicio' name='servicio'>";
            echo "<option >Seleccionar un servicio</option> ";
            while ($servicios=$res->fetch_array(MYSQLI_ASSOC)){
                $servicio=$servicios['descripcion'];
                $id= $servicios['codigo_servicio'];
                echo "<option value='$id'>$servicio</option>";
            }
            echo "</select>";
        }
    }

    
function insertarCita() {
    global $nombre_db, $nombre_host, $nombre_usuario, $password_db;
    require_once "../../../DavidDelgado/connection/config.php";
    require_once "../../../DavidDelgado/connection/funciones.php";
    $conexion = conectar($nombre_host, $nombre_usuario, $password_db, $nombre_db);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['citas'])) {
            $servicio = $_POST['servicio'];
            $fecha = $_POST['fecha'];
            $usuario = $_POST['socio'];
            $hora = $_POST['hora'];

            // Verificar si ya existe una cita para el socio en la misma fecha y hora
            $sqlVerificar = "SELECT COUNT(*) AS total 
                             FROM citas 
                             WHERE codigo_socio = ? AND fecha_cita = ? AND hora_cita = ?";
            if ($stmt = $conexion->prepare($sqlVerificar)) {
                $stmt->bind_param("sss", $usuario, $fecha, $hora);
                $stmt->execute();
                $resultado = $stmt->get_result();
                $fila = $resultado->fetch_assoc();
                $stmt->close();

                if ($fila['total'] > 0) {
                    // Si ya existe una cita
                    echo "
                    <html>
                    <head>
                    <meta http-equiv='refresh' content='1;url=citas_formulario.php'>
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
                            <p class='error'>Ya existe una cita para este socio en la misma fecha y hora.</p>
                            <p class='redirect'>Serás redirigido en 3 segundos...</p>
                        </div>
                    </body>
                    </html>";
                    return;
                }
            } else {
                echo "Error al preparar la consulta de verificación: " . $conexion->error;
                return;
            }

            // Si no existe una cita, insertar la nueva cita
            $sqlInsertar = "INSERT INTO citas (id_cita, codigo_socio, codigo_servicio, fecha_cita, hora_cita) 
                            VALUES (NULL, ?, ?, ?, ?)";
            if ($inserts = $conexion->prepare($sqlInsertar)) {
                $inserts->bind_param("ssss", $usuario, $servicio, $fecha, $hora);

                if ($inserts->execute()) {
                    echo "
                    <html>
                    <head>
                    <meta http-equiv='refresh' content='1;url=citas_formulario.php'>
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
                            <p class='success'>¡Cita registrada correctamente!</p>
                            <p class='redirect'>Serás redirigido en 2 segundos...</p>
                        
                        </div>
                    </body>
                    </html>";
                } 
                $inserts->close();
            } 
        }
    }
    $conexion->close();
}

if (isset($_POST['citas'])) {
    insertarCita();
}
?>



<?php
require_once "../../../DavidDelgado/connection/config.php";
require_once "../../../DavidDelgado/connection/funciones.php";

$conexion = conectar($nombre_host, $nombre_usuario, $password_db, $nombre_db);

    if (isset($_POST['id_cita'])) {
        $idCita = $_POST['id_cita'];

        // Actualizar el estado de la cita a 'anulada'
        $sql = "UPDATE citas SET estado = 'anulada' WHERE id_cita = ? AND fecha_cita > ?";
        $stmt = $conexion->prepare($sql);
        $fechaActual = date('Y-m-d');
        $stmt->bind_param("is", $idCita, $fechaActual);

        if ($stmt->execute()) {
            echo "
            <html>
            <head>
            <meta http-equiv='refresh' content='1;url=citas_formulario.php'>
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
                    <p class='success'>¡Cita anulada correctamente!</p>
                    <p class='redirect'>Serás redirigido en 2 segundos...</p>
                
                </div>
            </body>
            </html>";
        } else {
            echo "<p>Error al intentar anular la cita.</p>";
        }
        $stmt->close();
    }




    if (isset($_POST['borrar'])) {
        $idCita = $_POST['borrar'];

        // Verificar la fecha de la cita
        $sql = "SELECT fecha_cita FROM citas WHERE id_cita = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $idCita);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            $fila = $resultado->fetch_assoc();
            $fechaCita = $fila['fecha_cita'];

            // Obtener la fecha actual
            $fechaActual = date('Y-m-d');

            // Verificar si la cita es de una fecha futura
            if ($fechaCita > $fechaActual) {
                // Eliminar la cita
                $deleteSql = "DELETE FROM citas WHERE id_cita = ?";
                $deleteStmt = $conexion->prepare($deleteSql);
                $deleteStmt->bind_param("i", $idCita);

                if ($deleteStmt->execute()) {
                    echo "
                    <html>
                    <head>
                    <meta http-equiv='refresh' content='1;url=citas_formulario.php'>
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
                            <p class='success'>¡Cita borrada correctamente!</p>
                            <p class='redirect'>Serás redirigido en 3 segundos...</p>
                        
                        </div>
                    </body>
                    </html>";
                } else {
                    echo "<p>Error al intentar eliminar la cita.</p>";
                }
                $deleteStmt->close();
            } else {
                echo "
                    <html>
                    <head>
                    <meta http-equiv='refresh' content='1;url=citas_formulario.php'>
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
                        <p class='error'> Solo se pueden borrar citas con antelación al día actual.</p>
                        <p class='redirect'>Serás redirigido en 2 segundos...</p>
                      
                    </body>
                    </html>";
            }
        } else {
            echo "<p>No se encontró la cita especificada.</p>";
        }
        $stmt->close();
    } 

$conexion->close();
?>
