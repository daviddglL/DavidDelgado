<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Buscador de Recetas</title>
  <script defer src="../../js/API_dietas.js"></script>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 20px;
    }
    #results {
      margin-top: 20px;
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
      gap: 20px;
    }
    .recipe-card {
      border: 1px solid #ccc;
      padding: 15px;
      border-radius: 5px;
      background-color: #f9f9f9;
      text-align: center;
    }
    .recipe-card img {
      max-width: 100%;
      border-radius: 5px;
    }
    .recipe-card h3 {
      font-size: 18px;
      margin: 10px 0;
    }
  </style>
</head>
<body>
  <h1>Buscador de Recetas Saludables</h1>
  <form id="searchForm">
    <label for="query">Buscar receta:</label>
    <input type="text" id="query" name="query" placeholder="Ejemplo: pollo, aguacate" required>
    <button type="submit">Buscar</button>
  </form>
  <div id="results"></div>


</body>
</html>
