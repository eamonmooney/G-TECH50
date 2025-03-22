// The basket number update function - Safa

 // Temp store the correct path to the PHP file
 let phpPath;

 // Get the current path of the page
 const currentPath = window.location.pathname;
 // Calculate the number of folders deep the current page is
 const depth = currentPath.split('/').length - 3;
 
 // If we are on the root page, don't go up a lkevel
 if (depth == 0) {
	 phpPath = './php/basketNumber.php';
 } else {
	 // If we are in a subdirectory, go up a level
	 phpPath = '../' + 'php/basketNumber.php';
 }
 
 
 // Using fetch on basketNumber.php based on the calculations
 fetch(phpPath)
	 //Response must be json
	 .then(response => response.json())
	 //With the data
	 .then(data => {
		 //Print number of items to basket
		 console.log("Total number of items in basket:", data.basketNum);
 
		 // Update the basket count in the navbar
		 document.getElementById("basketNum").textContent = data.basketNum;
 
	 })
	 //Catch errors
	 .catch(error => console.error('Error:', error)); 