<?php
require_once "../../connection/config.php";
require_once "../../connection/funciones.php";

function formulario_para_iniciar_sesion($pagina_actual){
    return "<div class='login-container'>
                <form class='login-form' action='iniciar_sesion.php' method='POST'>
                    <label for='username'>Usuario:</label>
                    <input type='text' id='username' name='username' placeholder='Introduce tu usuario' required>
                    <label for='password'>Contraseña:</label>
                    <input type='password' id='password' name='password' placeholder='Introduce tu contraseña' required>
                    <input type='hidden' id='origen' name='origen' value='$pagina_actual'>
                    <button type='submit'>Iniciar sesión</button>
                </form>
            </div>";
}

function formulario_sesion_iniciada($nombre_usuario){
    return "<div class='login-container'>
                <form class='login-form' action='cerrar_sesion.php' method='POST'>
                    <label>Usuario logueado: $nombre_usuario</label>
                    <button type='submit'>Cerrar sesión</button>
                </form>
            </div>";
}

function menu_navegacion(){
    return "<nav>
                <ul>
                    <li><a href='index.php'>Inicio</a></li>
                    <li><a href='nosotros.php'>Nosotros</a></li>
                    <li><a href='servicios.php'>Servicios</a></li>
                    <li><a href='contacto.php'>Contacto</a></li>
                    <li><a href='usuarios.php'>Usuarios</a></li>
                </ul>
            </nav>";
}

function select_tipo_usuario() {
    echo "<label for='tipo'>Tipo de Usuario:</label>";
    echo "<select id='tipo' name='tipo' required>";
    echo "<option value='normal'>Normal</option>";
    echo "<option value='socio'>Socio</option>";
    echo "</select><br><br>";
}

function verificar_permiso($tipo_usuario, $seccion) {
    $permisos = [
        "admin" => ["inicio", "nosotros", "servicios", "contacto", "usuarios"],
        "socio" => ["inicio", "nosotros", "servicios", "contacto"],
        "normal" => ["inicio", "nosotros", "contacto"]
    ];

    foreach ($permisos[$tipo_usuario] as $permitido) {
        if ($permitido === $seccion) {
            return true;
        }
    }

    return false;
}
?>
