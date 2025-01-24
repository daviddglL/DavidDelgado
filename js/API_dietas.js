// Configuración de la API
const appId = '48ac81a9'; // Reemplaza con tu Application ID
const appKey = 'f66b12198263bdc1ef8f8b7dc098441c'; // Reemplaza con tu Application Key
const apiUrl = 'https://api.edamam.com/search';

document.getElementById('searchForm').addEventListener('submit', async function (e) {
  e.preventDefault();

  const query = document.getElementById('query').value;
  const resultsContainer = document.getElementById('results');
  resultsContainer.innerHTML = 'Buscando recetas...';

  try {
    // Realiza la solicitud a la API
    const response = await fetch(`${apiUrl}?q=${encodeURIComponent(query)}&app_id=${appId}&app_key=${appKey}`);
    if (!response.ok) {
      throw new Error(`Error: ${response.statusText}`);
    }

    const data = await response.json();

    // Procesa y muestra los resultados
    resultsContainer.innerHTML = '';
    if (data.hits && data.hits.length > 0) {
      const container = document.createElement('div');
      container.classList.add('cards-container');
    
      data.hits.forEach(hit => {
        const recipe = hit.recipe;
        const div = document.createElement('div');
        div.classList.add('cards');
        div.innerHTML = `
          <img src="${recipe.image}" alt="${recipe.label}">
          <h3>${recipe.label}</h3>
          <p><strong>Calorías:</strong> ${Math.round(recipe.calories)} kcal</p>
          <p><strong>Ingredientes:</strong></p>
          <ul>
            ${recipe.ingredientLines.map(ingredient => `<li>${ingredient}</li>`).join('')}
          </ul>
          <a href="${recipe.url}" target="_blank">Ver receta completa</a>
        `;
        container.appendChild(div);
      });
    
      resultsContainer.appendChild(container);
    } else {
      resultsContainer.innerHTML = 'No se encontraron recetas.';
    }
    
  } catch (error) {
    resultsContainer.innerHTML = `Hubo un error: ${error.message}`;
  }
});
