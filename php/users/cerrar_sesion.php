<?php
require_once __DIR__ . "/../../connection/config.php";
require_once __DIR__ . "/../../connection/funciones.php";
session_start();
session_unset();
session_destroy();
header("Location: " . BASE_URL . "index.php");
exit();
?>