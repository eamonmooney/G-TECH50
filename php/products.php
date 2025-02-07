<!-- This page intends to handle all PHP functionalities for each product.
 This will mean upon 'Add to basket' being clicked, the product will be added to the basket.
 To make this work, the product's name, quantity and price will be added to a list.
 This list will be a session variable for the basket, including all products to be bought.
 If the user isn't logged in when they try to add something to the basket,
 they will be redirected to the log-in page.
 Basket session variable is now an associative array
 Created by Safa Riasat.

 -->

<?php
// Start the session
session_start();


/*If the form was submitted using POST (ensures the following is accessed upon
	form being submitted) and if the specified field is present */
if (isset($_POST['basketAdd'])) {
	// If the user isn't logged in
	if (!isset($_SESSION['userId'])) {
		//Redirect to the login page 
		header("Location: ../signin.html");
		//Leaving the php file to ensure nothing else is performed
		exit();
	}

	// If basket isn't set
	if (!isset($_SESSION['basket'])) {
		//Initialise basket
		$_SESSION['basket'] = [];
	}

	//Retrieving product information from the POST form
	//Product name
	$prodName = $_POST['prodName'];
	//Product price
	$prodPrice = $_POST['prodPrice'];
	//Product Quantity
	$prodQuant = (int)$_POST['prodQuant'];

	// Checking if product is already in the basket and if it is - update that quantity
	// If the product is already in the basket ( checking like this because it's an associative array with name as it's keyword)
	if (isset($_SESSION['basket'][$prodName])) {
		// Update the product's quantity with the quantity we are adding
		$_SESSION['basket'][$prodName]['quantity'] += $prodQuant;
	//Or, if the product isn't in the basket
	} else {
		// Add the new product to the basket's associative array - searchable by name
		$_SESSION['basket'][$prodName] = [
			// Set product's details
			'price' => $prodPrice,
			'quantity' => $prodQuant
		];
	}

	//Redirect back to the basket for testing
	header("Location: ../basket.html");
	exit();
}
?>