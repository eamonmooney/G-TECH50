// THIS PAGE CONTAINS THE SEARCH BAR JAVASCRIPT
// JAVASCRIPT WRITTEN BY TOBIAS
// COMPILED,  COMMENTED AND ADDED TO EVERY PAGE BY SAFA

//List with links for the search bar
const data = [
	{ name: "Mice", url: "categories/Mice.html" },
	{ name: "Monitors", url: "categories/Monitors.html" },
	{ name: "Headphones", url: "categories/Headphones.html" },
	{ name: "Keyboards", url: "categories/Keyboards.html" },
	{ name: "Mousepads", url: "categories/Mousepads.html" },

];

//Constants for the search bar
const searchBar = document.getElementById("searchBar");
const searchButton = document.getElementById("searchButton");
const searchResults = document.getElementById("searchResults");

//Search bar functionality
function searchItems(query) {
	const lowerQuery = query.toLowerCase();
	const results = data.filter(item => item.name.toLowerCase().includes(lowerQuery));

	searchResults.innerHTML = results.length
		? results.map(item => `<a href="${item.url}">${item.name}</a>`).join('')
		: '<div>No results found</div>';
	searchResults.style.display = query ? 'block' : 'none';
}

//Listeners incase the search bar is clicked
searchBar.addEventListener("input", () => searchItems(searchBar.value));
searchButton.addEventListener("click", () => searchItems(searchBar.value));

// Test console message to make sure this page is linked
console.log("searchBar.js is linked!");
