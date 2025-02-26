<?php
// Encabezados para permitir CORS
header("Access-Control-Allow-Origin: *"); // Permite todas las solicitudes de origen
// Establecer tipo de contenido en JSON
header("Content-Type: application/json");


// Configuración de la base de datos
require_once "../../connection/config.php";
require_once "../../connection/funciones.php";
require_once 'api_funciones.php';

try {
    $conn = conectar($nombre_host, $nombre_usuario, $password_db, $nombre_db);
} catch (mysqli_sql_exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Error al conectar con la base de datos"]);
    die();
}

// Determinar el método HTTP
$metodo = $_SERVER['REQUEST_METHOD'];

if ($metodo == "POST" || $metodo == "PUT") {
    $entrada = json_decode(file_get_contents("php://input"), true);
}

switch ($metodo) {
    case 'GET':
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = isset($_GET['limit']) && is_numeric($_GET['limit']) ? (int)$_GET['limit'] : 4;
        
        $productos = obtenerProductos($conn, $search, $page, $limit); // Función que obtiene los productos de la base de datos
        echo json_encode($productos);
        break;

    case 'POST':
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        
        // Capturar el JSON recibido
        $input = file_get_contents("php://input");
        error_log("Datos recibidos en API: " . $input);
        
        $data = json_decode($input, true);
        
        // Verificar si los datos fueron decodificados correctamente
        if ($data === null) {
            error_log("Error: JSON mal formado o vacío.");
            echo json_encode(["error" => "JSON mal formado o vacío."]);
            exit();
        }
        
        // Verificar que los campos requeridos están presentes
        if (!isset($data['nombre'], $data['precio'], $data['descripcion'], $data['stock'], $data['estado'], $data['imagen'], $data['membresia'])) {
            error_log("Error: Datos incompletos en la API.");
            echo json_encode(["error" => "Faltan datos en la solicitud"]);
            exit();
        }
        
        if (isset($data["nombre"]) && isset($data["precio"]) && isset($data["descripcion"]) && isset($data["stock"]) && isset($data["imagen"]) && isset($data["membresia"])) {
            $resultado = crearProducto(
                $conn,
                $data["nombre"],
                (float) $data["precio"],  
                $data["descripcion"],
                (int) $data["stock"],      
                $data["imagen"],
                (bool) $data["membresia"]  
            );
        
        

            http_response_code($resultado["http"]);
            echo json_encode($resultado["respuesta"]);
        } else {
            
            http_response_code(405);
            echo json_encode(["error" => "Faltan parametros"]);
        }
        
        break;

    case 'PUT':
        // Depuración: imprimir el contenido de $entrada
        error_log("Contenido de entrada: " . json_encode($entrada));

        if (isset($entrada["id_producto"], $entrada["nombre"], $entrada["precio"], $entrada["descripcion"], $entrada["stock"])) {
            $resultado = modificarProducto(
                $conn,
                $entrada["id_producto"],
                $entrada["nombre"],
                $entrada["precio"],
                $entrada["descripcion"],
                $entrada["stock"]

            );

            http_response_code($resultado["http"]);
            echo json_encode($resultado["respuesta"]);
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Faltan parámetros", "entrada" => $entrada]);
        }
        break;        

    case 'DELETE':
        if (isset($_GET["id_producto"])) {
            $resultado = borrarProducto($conn, $_GET["id_producto"]);
            http_response_code($resultado["http"]);
            echo json_encode($resultado["respuesta"]);
        } else {
            http_response_code(405);
            echo json_encode(["error" => "Método no soportado"]);
        }
        
        break;

    default:
        http_response_code(405);
        echo json_encode(["error" => "Método no soportado"]);
}

// Cerrar la conexión
$conn->close();
?>
