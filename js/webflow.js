// webflow.js
import config from './config.js';

const productUrl = `https://api.airtable.com/v0/${config.baseId}/${config.tables.products}`;
const categoryUrl = `https://api.airtable.com/v0/${config.baseId}/${config.tables.categories}`;
const skuUrl = `https://api.airtable.com/v0/${config.baseId}/${config.tables.skus}`;

fetch(productUrl, {
  headers: {
    'Authorization': `Bearer ${config.apiKey}`
  }
})
.then(response => response.json())
.then(data => {
  console.log('Products:', data); // Vérifiez les données récupérées depuis Airtable dans la console
  updateProducts(data.records); // Appel de la fonction pour mettre à jour les produits sur votre site
})
.catch(error => {
  console.error('Erreur lors de la récupération des produits depuis Airtable :', error);
});

fetch(categoryUrl, {
  headers: {
    'Authorization': `Bearer ${config.apiKey}`
  }
})
.then(response => response.json())
.then(data => {
  console.log('Categories:', data); // Vérifiez les données récupérées depuis Airtable dans la console
  updateCategories(data.records); // Appel de la fonction pour mettre à jour les catégories sur votre site
})
.catch(error => {
  console.error('Erreur lors de la récupération des catégories depuis Airtable :', error);
});

fetch(skuUrl, {
  headers: {
    'Authorization': `Bearer ${config.apiKey}`
  }
})
.then(response => response.json())
.then(data => {
  console.log('SKUs:', data); // Vérifiez les données récupérées depuis Airtable dans la console
  updateSKUs(data.records); // Appel de la fonction pour mettre à jour les SKUs sur votre site
})
.catch(error => {
  console.error('Erreur lors de la récupération des SKUs depuis Airtable :', error);
});

function updateProducts(products) {
  // Code pour mettre à jour votre site avec les produits récupérés depuis Airtable
  products.forEach(product => {
    // Exemple: création de balises HTML pour afficher les produits
    const productElement = document.createElement('div');
    productElement.classList.add('product');
    productElement.innerHTML = `
      <h2>${product.fields.name}</h2>
      <p>${product.fields.description}</p>
      <p>Prix: ${product.fields.price}</p>
    `;
    document.body.appendChild(productElement); // Ajoutez le produit à votre page HTML
  });
}

function updateCategories(categories) {
  // Code pour mettre à jour votre site avec les catégories récupérées depuis Airtable
  categories.forEach(category => {
    // Exemple: création de balises HTML pour afficher les catégories
    const categoryElement = document.createElement('div');
    categoryElement.classList.add('category');
    categoryElement.innerHTML = `
      <h2>${category.fields.name}</h2>
      <p>Description: ${category.fields.description}</p>
    `;
    document.body.appendChild(categoryElement); // Ajoutez la catégorie à votre page HTML
  });
}

function updateSKUs(skus) {
  // Code pour mettre à jour votre site avec les SKUs récupérés depuis Airtable
  skus.forEach(sku => {
    // Exemple: création de balises HTML pour afficher les SKUs
    const skuElement = document.createElement('div');
    skuElement.classList.add('sku');
    skuElement.innerHTML = `
      <h2>${sku.fields.name}</h2>
      <p>Produit associé: ${sku.fields.product}</p>
      <p>Prix: ${sku.fields.price}</p>
    `;
    document.body.appendChild(skuElement); // Ajoutez le SKU à votre page HTML
  });
}
