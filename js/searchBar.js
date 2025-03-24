// THIS PAGE CONTAINS THE SEARCH BAR JAVASCRIPT
// JAVASCRIPT WRITTEN BY TOBIAS
// FILE PATH ADJUSTEMENT BY EAMON

//List with links for the search bar
const data = [
	{ name: "Mice", url: "/G-TECH50/categories/Mice.html" },
	{ name: "Monitors", url: "/G-TECH50/categories/Monitors.html" },
	{ name: "Headphones", url: "/G-TECH50/categories/Headphones.html" },
	{ name: "Keyboards", url: "/G-TECH50/categories/Keyboards.html" },
	{ name: "Mousepads", url: "/G-TECH50/categories/Mousepads.html" },
	{ name: "Bubblegum", url: "/G-TECH50/products/bubblegum.html" },
	{ name: "Candykeys", url: "/G-TECH50/products/candykeys.html" },
	{ name: "Candypop", url: "/G-TECH50/products/candypop.html" },
	{ name: "Cosmic", url: "/G-TECH50/products/cosmic.html" },
	{ name: "Cosmo", url: "/G-TECH50/products/cosmo.html" },
	{ name: "Eco", url: "/G-TECH50/products/eco.html" },
	{ name: "Evergreen", url: "/G-TECH50/products/evergreen.html" },
	{ name: "Forestflow", url: "/G-TECH50/products/forestflow.html" },
	{ name: "Galaxytype", url: "/G-TECH50/products/galaxytype.html" },
	{ name: "Kittyclicks", url: "/G-TECH50/products/kittyclicks.html" },
	{ name: "Kittyview", url: "/G-TECH50/products/kittyview.html" },
	{ name: "Nebulaglide", url: "/G-TECH50/products/nebulaglide.html" },
	{ name: "Neonvibes", url: "/G-TECH50/products/neonvibes.html" },
	{ name: "Pawsoft", url: "/G-TECH50/products/pawsoft.html" },
	{ name: "Pixelpulse", url: "/G-TECH50/products/pixelpulse.html" },
	{ name: "Pixelwave", url: "/G-TECH50/products/pixelwave.html" },
	{ name: "Purrkeys", url: "/G-TECH50/products/purrkeys.html" },
	{ name: "Retro", url: "/G-TECH50/products/retro.html" },
	{ name: "Retroview", url: "/G-TECH50/products/retroview.html" },
	{ name: "Stellar", url: "/G-TECH50/products/stellar.html" },
	{ name: "Sugarglide", url: "/G-TECH50/products/sugarglide.html" },
	{ name: "Sugarscreen", url: "/G-TECH50/products/sugarscreen.html" },
	{ name: "Whiskertunes", url: "/G-TECH50/products/whiskertunes.html" },
	{ name: "Woodland", url: "/G-TECH50/products/woodland.html" },
	{ name: "Woodland Wanderer", url: "/G-TECH50/products/woodland_wanderer.html" },
];

function adjustPaths(data) {
	const currentPath = window.location.pathname;
	console.log('Current Path:', currentPath);

	// Project root folder, e.g., '/myproject/'
	const projectRoot = '/G-TECH50/';
	
	// Remove project root from currentPath to get relative path inside the project
	const relativePath = currentPath.replace(projectRoot, '');
	
	// Count folders deep 
	const folders = relativePath.split('/').slice(0, -1);
	const depth = folders.length;

	console.log('Relative Path:', relativePath);
	console.log('Folders Deep:', depth);

	// Build prefix
	const prefix = '../'.repeat(depth);
	console.log('Prefix:', prefix);

	// Return adjusted URLs
	return data.map(item => ({
		name: item.name,
		url: prefix + item.url
	}));
}

const adjustedData = adjustPaths(data);



const searchBar = document.getElementById("searchBar");
const searchButton = document.getElementById("searchButton");
const searchResults = document.getElementById("searchResults");

function searchItems(query) {
  const lowerQuery = query.toLowerCase();
  const results = adjustedData.filter(item => item.name.toLowerCase().includes(lowerQuery));
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
