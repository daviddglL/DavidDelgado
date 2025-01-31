<?php
/*
    require_once "../../connection/config.php";
    require_once "../../connection/funciones.php";

	global $nombre_db, $nombre_host, $nombre_usuario, $password_db;  

    $conexion = conectar($nombre_host, $nombre_usuario, $password_db, $nombre_db);
function generateApiKey() {
    return bin2hex(random_bytes(32)); // Clave segura de 64 caracteres
}

function saveApiKey($userId, $conexion) {
    // Verificar que el usuario sea simple
    $stmt = $conexion->prepare("SELECT role FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user || $user['role'] !== 'user') {
        return "Error: Solo los usuarios simples pueden tener una API key.";
    }

    // Generar la API key
    $apiKey = generateApiKey();

    // Insertar en la base de datos
    $stmt = $conexion->prepare("INSERT INTO api_keys (user_id, api_key) VALUES (?, ?)");
    if ($stmt->execute([$userId, $apiKey])) {
        return "API Key generada para el usuario $userId: $apiKey";
    }
    return "Error al guardar la API Key.";
}

// Generar API Key para un usuario específico (Ejemplo con ID 2)
echo saveApiKey(2, $conexion);


function authenticate() {
    global $conexion;
    $apiKey = $_SERVER['HTTP_API_KEY'] ?? '';

    if (!$apiKey || !validateApiKey($apiKey, $conexion)) {
        http_response_code(401);
        die(json_encode(["error" => "Acceso no autorizado"]));
    }
}

// Antes de ejecutar código de la API, autenticar usuario
authenticate();
echo json_encode(["message" => "Acceso autorizado"]);
*/

?>