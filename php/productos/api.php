<?php
// Encabezados para permitir CORS
header("Access-Control-Allow-Origin: *"); // Permite todas las solicitudes de origen
// Establecer tipo de contenido en JSON
header("Content-Type: application/json");


// Configuración de la base de datos
    require_once "../../connection/config.php";
    require_once "../../connection/funciones.php";

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
        require_once 'api_funciones.php';
    
        $id = isset($_GET['id_producto']) ? (int)$_GET['id_producto'] : null;
        $nombre = isset($_GET['nombre']) ? trim($_GET['nombre']) : null;
        $precio = isset($_GET['precio']) ? (float)$_GET['precio'] : null;   
        
        if ($id !== null || $nombre !== null || $precio !== null) {
            
            $resultado = obtenerProductos($conn, $id, $nombre, $precio);
        } 

        else {
            
            // Parámetros de paginación con valores predeterminados
            $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
            $limit = isset($_GET['limit']) && is_numeric($_GET['limit']) ? (int)$_GET['limit'] : 10;
    
            if ($page < 1 || $limit < 1) {
                http_response_code(400); // Bad Request
                echo json_encode(["error" => "Parámetros de paginación inválidos"]);
                die();
            }
    
            $resultado = obtenerProductosPag($conn, $page, $limit);
        }
    
        http_response_code($resultado["http"]);
        echo json_encode($resultado["respuesta"]);
        break;
    

    case 'POST':
        if(isset($entrada["nombre"]) && isset($entrada["precio"]) && isset($entrada["descripcion"]) && isset($entrada["stock"]) && isset($entrada["imagen"]) && isset($entrada["membresia"])){
            $resultado=crearProducto($conn,
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
            
            if (isset($entrada["id_producto"], $entrada["nombre"], $entrada["precio"], $entrada["descripcion"], $entrada["stock"], $entrada["membresia"])) {
                $resultado = modificarProducto(
                    $conn,
                    $entrada["id_producto"],
                    $entrada["nombre"],
                    $entrada["precio"],
                    $entrada["descripcion"],
                    $entrada["stock"],
                    $entrada["membresia"]
                );
        
                http_response_code($resultado["http"]);
                echo json_encode($resultado["respuesta"]);
            } else {
                http_response_code(400);
                echo json_encode(["error" => "Faltan parámetros"]);
            }
            break;        

    case 'DELETE':
        if(isset($_GET["id"])){
            $resultado=borrarProducto($conn,$_GET["id"]);
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
