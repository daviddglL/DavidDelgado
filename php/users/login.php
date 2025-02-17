<?php
session_start();
require_once "../../connection/config.php";
require_once "../../connection/funciones.php";
require_once "formularios.php";

$pagina_actual = basename($_SERVER['PHP_SELF']);

if (isset($_SESSION['username'])) {
    echo formulario_sesion_iniciada($_SESSION['username']);
} else {
    echo formulario_para_iniciar_sesion($pagina_actual);
}
?>