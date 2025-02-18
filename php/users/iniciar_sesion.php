<?php
session_start();

require_once __DIR__ . "/../../connection/config.php";
require_once __DIR__ . "/../../connection/funciones.php";
$conexion = conectar($nombre_host, $nombre_usuario, $password_db, $nombre_db);

$username = $_POST['username'];
$password = $_POST['password'];

$stmt = $conexion->prepare("SELECT id_socio, contrasena, role, nombre FROM socio WHERE usuario = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows == 1) {
    $usuario = $resultado->fetch_assoc();
    $hashed_password = trim($usuario["contrasena"]);

    if (password_verify($password, $hashed_password)) {
        $_SESSION["id_socio"] = $usuario["id_socio"];
        $_SESSION["nombre"] = $usuario["nombre"];
        $_SESSION["role"] = $usuario["role"];
        $_SESSION["username"] = $username; // Añadir esta línea
        header("Location: " . BASE_URL . "/php/users/usuarios.php"); // Redirigir a la página principal
        exit();
    } else {
        echo "Contraseña incorrecta.";
    }
} else {
    echo "Usuario no encontrado.";
}
$stmt->close();
$conexion->close();

?>