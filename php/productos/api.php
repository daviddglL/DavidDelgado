<?php
// Encabezados para permitir CORS
header("Access-Control-Allow-Origin: *"); // Permite todas las solicitudes de origen
// Establecer tipo de contenido en JSON
header("Content-Type: application/json");


// Configuración de la base de datos
require_once "config.php";
require_once "funciones.php";

try {
    $conn = conectar($nombre_host, $nombre_usuario, $password_db, $nombre_db);
} catch (mysqli_sql_exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Error al conectar con la base de datos"]);
    die();
}

// Determinar el método HTTP
$metodo = $_SERVER['REQUEST_METHOD'];

if($metodo =="POST" || $metodo=="PUT"){
    $entrada=json_decode(file_get_contents("php://input"),true);
}



switch ($metodo) {
    case 'GET':
        if (isset($_GET['id'])) {
            // Obtener una asignatura por ID
            $resultado=obtenerAsignaturas($conn,$_GET["id"]);
            http_response_code($resultado["http"]);
            echo json_encode($resultado["respuesta"]);

        } else {
            // Parámetros de paginación con valores predeterminados
            $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
            $limit = isset($_GET['limit']) && is_numeric($_GET['limit']) ? (int)$_GET['limit'] : 10;

            if ($page < 1 || $limit < 1) {
                http_response_code(400); // Bad Request
                echo json_encode([
                    "error" => "Parámetros de paginación inválidos"
                ]);
                die();
            }

            $resultado=obtenerAsignaturasPag($conn,$page,$limit);
            http_response_code($resultado["http"]);
            echo json_encode($resultado["respuesta"]);

        }
        break;
    

    case 'POST':
        if(isset($entrada["nombre_asignatura"]) && isset($entrada["creditos"])){
            $resultado=crearAsignatura($conn,
                                            $entrada["nombre"],
                                            $entrada["precio"],
                                       $entrada["descripcion"],
                                             $entrada["stock"],
                                            $entrada["imagen"],
                                         $entrada["membresia"],);

        http_response_code($resultado["http"]);
        echo json_encode($resultado["respuesta"]);
        }else{
            http_response_code(405);
            echo json_encode(["error"=>"Faltan parametros"]);
        }
        
        break;

    case 'PUT':
        if(isset($entrada["id"]) && isset($entrada["nombre"]) && isset($entrada["precio"]) && isset($entrada["descripcion"]) && isset($entrada["stock"]) && isset($entrada["imagen"]) && isset($entrada["membresia"])){
            $resultado=modificarAsignatura($conn,
                                    $entrada["id"],
                                    $entrada["nombre"],
                                    $entrada["precio"],
                               $entrada["descripcion"],
                                     $entrada["stock"],
                                    $entrada["imagen"],
                                 $entrada["membresia"],
                                );

        http_response_code($resultado["http"]);
        echo json_encode($resultado["respuesta"]);
        }else{
            http_response_code(405);
            echo json_encode(["error"=>"Faltan parametros"]);
        }
        break;

    case 'DELETE':
        if(isset($_GET["id"])){
            $resultado=borrarAsignatura($conn,$_GET["id"]);
            http_response_code($resultado["http"]);
            echo json_encode($resultado["respuesta"]);
        }else{
            http_response_code(405);
            echo json_encode(["error"=>"Método no soportado"]);
        }
        
        break;

    default:
        http_response_code(405);
        echo json_encode(["error"=>"Método no soportado"]);
}

// Cerrar la conexión
$conn->close();
