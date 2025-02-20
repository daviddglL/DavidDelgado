<?php

require_once "../../connection/config.php";
require_once "../../connection/funciones.php";
require_once ("../../php/inicio.php");
require_once("../noticias/noticias.php");
require_once("../testimonios/testimonios.php");
require_once("../servicios/servicios.php");
require_once("../socios/socios.php");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Socios</title>
    <link rel="stylesheet" href="../../estilos/styles.css">
    <script defer src="../../js/formulario_socios.js"></script>
    <style>
        #socios{
            display: none;
        }

        .modificar{
            display: flex;
            max-width: 120px;
            align-items: end;
            text-align: center;
        }

    </style>
</head>
<body>
    <div class="container">
    <?php
        headerr();
        contactos();
        echo "<div class='secc'><!--sección central-->";
    ?>
        <div class="search-container">
            <h2>Buscar Socio</h2>

            <form method="GET" action="socios_formulario.php" class="socios_form">
                <input class="search_placeholder" type="text" name="search" placeholder="Nombre o Teléfono">
                <button type="submit" name="busqueda">Buscar</button>
            </form>  
        </div>

    <div id="resultadoSocios">
        <?php 
        include_once 'socios.php'; 

        if (isset($_SESSION['id_socio']) && $_SESSION['id_socio'] == 0) {
            // Mostrar todos los socios menos el admin
            if (isset($_GET['busqueda']) && !empty($_GET['search'])) {
                $searchTerm = $_GET['search'];
                motrarsocios($searchTerm, true);
            } else {
                motrarsocios('', true);
            }

            // Mostrar formulario de modificación si se selecciona un socio
            if (isset($_GET['modificar'])) {
                $id_socio = $_GET['modificar'];
                if ($id_socio != 0) {
                    $datosSocio = obtenerDatosSocio($id_socio);

                    if ($datosSocio) {
                        echo "
                        <div class='formulario'>
                            <form id='form-modificar' method='POST' action='socios.php' enctype='multipart/form-data'>
                                <h2>Modificar Socio</h2>
                                
                                <input type='hidden' name='id_socio' value='" . $id_socio . "'>

                                <label for='usuario'>Usuario:</label>
                                <input type='text' name='usuario' id='usuario' value='" . $datosSocio['usuario']  . "'>
                                <span class='error'></span>

                                <label for='nombre'>Nombre:</label>
                                <input type='text' name='nombre' id='nombre' value='" . $datosSocio['nombre'] . "'>
                                <span class='error'></span>

                                <label for='edad'>Edad:</label>
                                <input type='text' name='edad' id='edad' value='" . $datosSocio['edad'] . "'>
                                <span class='error'></span>

                                <label for='telefono'>Teléfono:</label>
                                <input type='text' name='telefono' id='telefono' value='" . $datosSocio['telefono'] . "'>
                                <span class='error'></span>

                                <label for='contrasena'>Contraseña:</label>
                                <input type='text' name='contrasena' id='contrasena' value='" . $datosSocio['contrasena'] . "'>
                                <span class='error'></span>

                                <label for='foto'>Foto:</label>
                                <input type='file' name='foto' id='foto'>
                                <span class='error'></span>

                                <button type='submit' name='guardar_cambios'>Guardar Cambios</button>
                            </form>
                            <a href='socios_formulario.php'>Cancelar</a>
                            
                        </div>";
                    } else {
                        echo "<p>Socio no encontrado.</p>";
                    }
                }
            }
        } else {
            // Mostrar solo la información del socio logueado
            $id_socio = $_SESSION['id_socio'];
            if ($id_socio != 0) {
                $datosSocio = obtenerDatosSocio($id_socio);

                if ($datosSocio) {
                    echo "
                    <div class='formulario'>
                        <form id='form-modificar' method='POST' action='socios.php' enctype='multipart/form-data'>
                            <h2>Modificar Socio</h2>
                            
                            <input type='hidden' name='id_socio' value='" . $id_socio . "'>

                            <label for='usuario'>Usuario:</label>
                            <input type='text' name='usuario' id='usuario' value='" . $datosSocio['usuario']  . "' disabled>
                            <span class='error'></span>

                            <label for='nombre'>Nombre:</label>
                            <input type='text' name='nombre' id='nombre' value='" . $datosSocio['nombre'] . "' disabled>
                            <span class='error'></span>

                            <label for='edad'>Edad:</label>
                            <input type='text' name='edad' id='edad' value='" . $datosSocio['edad'] . "' disabled>
                            <span class='error'></span>

                            <label for='telefono'>Teléfono:</label>
                            <input type='text' name='telefono' id='telefono' value='" . $datosSocio['telefono'] . "'>
                            <span class='error'></span>

                            <label for='contrasena'>Contraseña:</label>
                            <input type='text' name='contrasena' id='contrasena' value='" . $datosSocio['contrasena'] . "'>
                            <span class='error'></span>

                            <label for='foto'>Foto:</label>
                            <input type='file' name='foto' id='foto'>
                            <span class='error'></span>

                            <button type='submit' name='guardar_cambios'>Guardar Cambios</button>
                        </form>
                        <a href='socios_formulario.php'>Cancelar</a>
                        
                    </div>";
                } else {
                    echo "<p>Socio no encontrado.</p>";
                }
            }
        }
        ?>

        <?php if (isset($_SESSION['id_socio']) && $_SESSION['id_socio'] == 0): ?>
        <div class="formulario">
            <form id="form" method="post" action="socios.php" enctype="multipart/form-data">
                <h2>Introduzca los datos del nuevo socio</h2>

                <label for="nombre">Nombre del socio:</label>
                <input type="text" id="nombre" name="nombre" placeholder="Introduzca el nombre y primer apellido del socio" required/>
                <span class="error"></span>

                <br/><br/>

                <label for="edad">Introduzca su edad</label>
                <input type="text" id="edad" name="edad"/>
                <span class="error"></span>
                <br/><br/>

                <label for="usuario">Introduzca el nombre de usuario</label>
                <input type="text" id="usuario" name="usuario"/>
                <span class="error"></span>
                <br/><br/>

                <label for="telefono">Introduzca el número de telefono (formato: +34 612 345 678)</label>
                <input type="text" name="telefono" id="telefono" placeholder="+34 612 345 678"/>
                <span class="error"></span>
                <br/><br/>

                <label for="contrasena">Introduzca su contraseña</label>
                <input type="text" name="contrasena" id="contrasena"/>
                <span class="error"></span>
                <br/><br/>

                <label for="foto">Suba la imagen de perfil de su usuario</label>
                <input type="file" name="foto" id="foto" placeholder="Si no añade una foto, se le asignará una por defecto"/>
                <span class="error"></span>
                <br/><br/>

                <button type="submit" name="submit">Enviar</button>

            </form>
        </div>
        <?php endif; ?>

    </div>
    <?php
        echo "</div>";
        news();
        footer();        
    ?>
</body>
</html>
