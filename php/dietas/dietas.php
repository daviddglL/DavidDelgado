<?php

    require_once "../../connection/config.php";
    require_once "../../connection/funciones.php";
    require_once ("../../php/inicio.php");
    require_once("../noticias/noticias.php");

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Buscador de Recetas</title>
  <script defer src="../../js/API_dietas.js"></script>
  <link rel="stylesheet" href="../../estilos/styles.css">
  <style>
  </style>
</head>
<body>
  <div class="container">
    <?php
          headerr();
          contactos();
          echo "<div class='secc'><!--sección central-->";
          
      ?>  
      <h1>Buscador de Recetas Saludables</h1>
      <div class="formulario">
      <form id="searchForm">
        <label for="query">Buscar receta:</label>
        <input type="text" id="query" name="query" placeholder="Ejemplo: pollo, aguacate" required>
        <br><button class="button" type="submit">Buscar</button>
      </form>
      </div>
    
    <div id="results"></div>
    <?php
        echo "</div>";
        news();
        footer();        
    ?>
    </div>
</body>
</html>
