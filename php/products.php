<!-- This page intends to handle all PHP functionalities for each product.
 This will mean upon 'Add to basket' being clicked, the product will be added to the basket.
 To make this work, the product's name, quantity and price will be added to a list.
 This list will be a session variable for the basket, including all products to be bought.
 If the user isn't logged in when they try to add something to the basket,
 they will be redirected to the log-in page.
 Created by Safa Riasat.

 -->

<?php
	// Start the session
	session_start();

	//!!! FOR TESTING - REMOVE!!!!
	$_SESSION['userId'] = 1;


	/*If the form was submitted using POST (ensures the following is accessed upon
	form being submitted) and if the specified field is present */
	if (isset($_POST['basketAdd'])) {
		// If the user isn't logged in
		if (!isset($_SESSION['userId'])) {	
			//Redirect to the login page 
			header("Location: ../index.html"); //!!! CHANGE TO LOGIN PAGE
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
		$prodQuant = $_POST['prodQuant'];

		// Checking if product is already in the basket and if it is - update that quantity
		//Make temp variable prodFound and set to false - to track if we find the product in the basket or not
		$prodFound = false;
		//Loop through each item in the basket, and set each item to $item
		foreach ($_SESSION['basket'] as &$item) {
			//If the item's name is the same as the item we are adding
			if ($item['name'] === $prodName) {
				// Update that product's quantity with the quantity we are adding
				$item['quantity'] += $prodQuant;
				//Set prodFound to true
				$prodFound = true;
				//Break the for each loop
				break;
			}
		}

		// If prodFound is false (hasn't been found in basket), then we add this product to the basket
		if (!$prodFound) {
			//Append the new product to the end of the basket session variable
			$_SESSION['basket'][] = [
				//Set all of these to the product's details
				'name' => $prodName,
				'price' => $prodPrice,
				'quantity' => $prodQuant
			];
		}

		//Redirect back to the basket for testing
		header("Location: ../basket.html");
		exit();
	}

?>