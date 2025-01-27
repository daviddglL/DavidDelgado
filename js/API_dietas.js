// Configuración de las APIs
const appId = '48ac81a9'; // Reemplaza con tu Application ID
const appKey = 'f66b12198263bdc1ef8f8b7dc098441c'; // Reemplaza con tu Application Key
const apiUrl = 'https://api.edamam.com/search';
const translationApiUrl = 'https://api.mymemory.translated.net/get';

// Traducción de texto
async function translateText(text, sourceLang, targetLang) {
  try {
    const response = await fetch(`${translationApiUrl}?q=${encodeURIComponent(text)}&langpair=${sourceLang}|${targetLang}`);
    const data = await response.json();
    return data.responseData.translatedText;
  } catch (error) {
    console.error('Error al traducir:', error);
    return text; // Devuelve el texto original en caso de error
  }
}

document.getElementById('searchForm').addEventListener('submit', async function (e) {
  e.preventDefault();

  const query = document.getElementById('query').value;
  const resultsContainer = document.getElementById('results');
  resultsContainer.innerHTML = 'Buscando recetas...';

  try {
    // Realiza la solicitud a la API de recetas
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

      for (const hit of data.hits) {
        const recipe = hit.recipe;

        // Traduce el título y los ingredientes
        const translatedLabel = await translateText(recipe.label, 'en', 'es');
        const translatedIngredients = await Promise.all(
          recipe.ingredientLines.map(ingredient => translateText(ingredient, 'en', 'es'))
        );

        const div = document.createElement('div');
        div.classList.add('cards');
        div.innerHTML = `
          <img src="${recipe.image}" alt="${translatedLabel}">
          <h3>${translatedLabel}</h3>
          <p><strong>Calorías:</strong> ${Math.round(recipe.calories)} kcal</p>
          <p><strong>Ingredientes:</strong></p>
          <ul>
            ${translatedIngredients.map(ingredient => `<li>${ingredient}</li>`).join('')}
          </ul>
          <a href="${recipe.url}" target="_blank">Ver receta completa</a>
        `;
        container.appendChild(div);
      }

      resultsContainer.appendChild(container);
    } else {
      resultsContainer.innerHTML = 'No se encontraron recetas.';
    }
  } catch (error) {
    resultsContainer.innerHTML = `Hubo un error: ${error.message}`;
  }
});
