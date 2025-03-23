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

// Function to adjust paths based on current folder depth
function adjustPaths(data) {
    const currentPath = window.location.pathname;
    
    
    const depth = Math.max((currentPath.match(/\//g) || []).length - 1, 0);
    
    return data.map(item => {
        let adjustedUrl = item.url;

        
        if (!adjustedUrl.startsWith("/")) {
            adjustedUrl = (depth > 0 ? '../'.repeat(depth) : '') + adjustedUrl;
        }

        return {
            name: item.name,
            url: adjustedUrl,
        };
    });
}


// Adjusted data with correct URLs
const adjustedData = adjustPaths(data);

//Constants for the search bar
const searchBar = document.getElementById("searchBar");
const searchButton = document.getElementById("searchButton");
const searchResults = document.getElementById("searchResults");

//Search bar functionality
function searchItems(query) {
	const lowerQuery = query.toLowerCase();
	const results = adjustedData.filter(item => item.name.toLowerCase().includes(lowerQuery));

	searchResults.innerHTML = results.length
            ? results.map(item => `<div class="search-result-item" data-url="${item.url}">${item.name}</div>`).join('')
            : '<div>No results found</div>';

}

document.addEventListener("click", function (event) {
    const target = event.target.closest(".search-result-item");
    if (target && target.dataset.url) {
        window.location.href = target.dataset.url;
    }
});


//Listeners incase the search bar is clicked
searchBar.addEventListener("input", () => searchItems(searchBar.value));
searchButton.addEventListener("click", () => searchItems(searchBar.value));

// Test console message to make sure this page is linked
console.log("searchBar.js is linked!");
