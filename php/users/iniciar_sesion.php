<?php
session_start();

require_once __DIR__ . "/../../connection/config.php";
require_once __DIR__ . "/../../connection/funciones.php";
$conexion = conectar($nombre_host, $nombre_usuario, $password_db, $nombre_db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['usuario']) && isset($_POST['contrasena'])) {
        $username = $_POST['usuario'];
        $password = $_POST['contrasena'];

        $stmt = $conexion->prepare("SELECT id_socio, contrasena, role, nombre FROM socio WHERE usuario = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows == 1) {
            $usuario = $resultado->fetch_assoc();
            $hashed_password = trim($usuario["contrasena"]);

            if ($hashed_password === $password) { 

                $nuevo_hash = password_hash($password, PASSWORD_DEFAULT);
    
                $update_stmt = $conexion->prepare("UPDATE socio SET contrasena = ? WHERE usuario = ?");
                $update_stmt->bind_param("ss", $nuevo_hash, $login);
                $update_stmt->execute();
    
                $hashed_password = $nuevo_hash;
                $update_stmt->close();
            }

            if (password_verify($password, $hashed_password)) {
                $_SESSION["id_socio"] = $usuario["id_socio"];
                $_SESSION["nombre"] = $usuario["nombre"];
                $_SESSION["role"] = $usuario["role"];
                $_SESSION["username"] = $username;
                header("Location: " . BASE_URL . "index.php");
                exit();
            } else {
                echo "Contraseña incorrecta.";
            }
        } else {
            echo "Usuario no encontrado.";
        }
        $stmt->close();
        $conexion->close();
    } else {
        echo "Por favor, complete todos los campos.";
    }
}
?>