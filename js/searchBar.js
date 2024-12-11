// THIS PAGE CONTAINS THE SEARCH BAR JAVASCRIPT
// JAVASCRIPT WRITTEN BY TOBIAS
// COMPILED,  COMMENTED AND ADDED TO EVERY PAGE BY SAFA
// FILE PATH ADJUSTEMENT BY EAMON

//List with links for the search bar
const data = [
	{ name: "Mice", url: "categories/Mice.html" },
	{ name: "Monitors", url: "categories/Monitors.html" },
	{ name: "Headphones", url: "categories/Headphones.html" },
	{ name: "Keyboards", url: "categories/Keyboards.html" },
	{ name: "Mousepads", url: "categories/Mousepads.html" },
];

// Function to adjust paths based on current folder depth
function adjustPaths(data) {
	// Get the current path of the page
	const currentPath = window.location.pathname;
	// Calculate the number of folders deep the current page is
	const depth = currentPath.split('/').length - 3; 
	// Generate the relative prefix (e.g., "../" for one level up)
	const prefix = '../'.repeat(depth);
	// Adjust URLs
	return data.map(item => ({
		name: item.name,
		url: prefix + item.url,
	}));
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
		? results.map(item => `<a href="${item.url}">${item.name}</a>`).join('')
		: '<div>No results found</div>';
	searchResults.style.display = query ? 'block' : 'none';
}

//Listeners incase the search bar is clicked
searchBar.addEventListener("input", () => searchItems(searchBar.value));
searchButton.addEventListener("click", () => searchItems(searchBar.value));

// Test console message to make sure this page is linked
console.log("searchBar.js is linked!");