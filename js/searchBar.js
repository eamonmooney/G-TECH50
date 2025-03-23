// THIS PAGE CONTAINS THE SEARCH BAR JAVASCRIPT
// JAVASCRIPT WRITTEN BY TOBIAS
// FILE PATH ADJUSTEMENT BY EAMON

//List with links for the search bar
const data = [
	{ name: "Mice", url: "categories/Mice.html" },
	{ name: "Monitors", url: "categories/Monitors.html" },
	{ name: "Headphones", url: "categories/Headphones.html" },
	{ name: "Keyboards", url: "categories/Keyboards.html" },
	{ name: "Mousepads", url: "categories/Mousepads.html" },
	{ name: "Bubblegum", url: "products/bubblegum.html" },
	{ name: "Candykeys", url: "products/candykeys.html" },
	{ name: "Candypop", url: "products/candypop.html" },
	{ name: "Cosmic", url: "products/cosmic.html" },
	{ name: "Cosmo", url: "products/cosmo.html" },
	{ name: "Eco", url: "products/eco.html" },
	{ name: "Evergreen", url: "products/evergreen.html" },
	{ name: "Forestflow", url: "products/forestflow.html" },
	{ name: "Galaxytype", url: "products/galaxytype.html" },
	{ name: "Kittyclicks", url: "products/kittyclicks.html" },
	{ name: "Kittyview", url: "products/kittyview.html" },
	{ name: "Nebulaglide", url: "products/nebulaglide.html" },
	{ name: "Neonvibes", url: "products/neonvibes.html" },
	{ name: "Pawsoft", url: "products/pawsoft.html" },
	{ name: "Pixelpulse", url: "products/pixelpulse.html" },
	{ name: "Pixelwave", url: "products/pixelwave.html" },
	{ name: "Purrkeys", url: "products/purrkeys.html" },
	{ name: "Retro", url: "products/retro.html" },
	{ name: "Retroview", url: "products/retroview.html" },
	{ name: "Stellar", url: "products/stellar.html" },
	{ name: "Sugarglide", url: "products/sugarglide.html" },
	{ name: "Sugarscreen", url: "products/sugarscreen.html" },
	{ name: "Whiskertunes", url: "products/whiskertunes.html" },
	{ name: "Woodland", url: "products/woodland.html" },
	{ name: "Woodland Wanderer", url: "products/woodland_wanderer.html" },
];

const searchBar = document.getElementById("searchBar");
const searchButton = document.getElementById("searchButton");
const searchResults = document.getElementById("searchResults");

function searchItems(query) {
  const lowerQuery = query.toLowerCase();
  const results = data.filter(item => item.name.toLowerCase().includes(lowerQuery));
  searchResults.innerHTML = results.length
    ? results.map(item => `
        <div class="search-result-item" data-url="${item.url}">
          <a href="${item.url}" class="search-result-link">${item.name}</a>
        </div>
      `).join('')
    : '<div>No results found</div>';
  searchResults.style.display = query ? 'block' : 'none';
}

searchBar.addEventListener("input", () => searchItems(searchBar.value));
searchButton.addEventListener("click", () => searchItems(searchBar.value));

document.addEventListener("click", function (event) {
  const target = event.target.closest(".search-result-item");
  if (target && target.dataset.url) {
    window.location.href = target.dataset.url;
  }
});


// Test console message to make sure this page is linked
console.log("searchBar.js is linked!");
