<?php
// This page should handle the backend for the basket. 
// This should include displaying/ removing items from the basket list based on a basket table.
// Adding items to a basket should be performed on the product pages themselves and it's product ID added to a local session array, $basketItems with productID and quantity
// If the user isn't loaded in, they should have been redirected to the login page from basketStart.php
// If the user has no items in the basket, they should not have anything displayed on screen and are told of this
// User should be able to increase/decrease the quantity of products from basket
// Created by Safa Riasat - 230078145



	// Start output buffering - using this to clean any outputs and remove uneeded outputs from irrelevant areas affecting JSON
	ob_start();

	//Resume the session to allow us to use the session variables
	session_start();

	//Set the userID 
	$userID = $_SESSION['userId'];

	// Set the response content type to JSON
	header('Content-Type: application/json');
	


	//TESTING
	// $_SESSION['basket'] = [
	// 	[
	// 		'name' => 'Keyboard',
	// 		'price' => 50,   // Fixed price for keyboard
	// 		'quantity' => 1   // Fixed quantity for keyboard
	// 	],
	// 	[
	// 		'name' => 'Mouse',
	// 		'price' => 25,   // Fixed price for mouse
	// 		'quantity' => 2   // Fixed quantity for mouse
	// 	]
	// ];


	//If the user has nothing in their basket, tell them they have nothing in their basket and end
	// If the basket hasn't been set, or if it's empty,
	if (!isset($_SESSION['basket']) || empty($_SESSION['basket'])) {	
		//Return an empty list if there's nothing in the basket
		$data = [
			'basket' => [],  // Empty basket
			'totalPrice' => 0];  // Nothing for total price
		echo json_encode($data); 
		//Leaving the php file to ensure nothing else is performed
		exit();
	} 

	//Upon loading in, assuming you are logged in, connect to the database to get info about products from our Basket List
	//Wrapped in a try-catch statement for error handling
	try{
		//Connect to the database if it hasn't been done already
		require_once('connectdb.php');
		



		//Doing a loop to get total price
		$totalPrice = 0;

		//For each loop to go through all items
		foreach ($_SESSION['basket'] as $product) {
			//Check if the product has a price and quantity
			if (isset($product['price'], $product['quantity'])) {
				//Temp variable for the price
				$price = $product['price']; 

				//Temp quantity variable
				$quantity = $product['quantity'];

				//Adding the price
				$totalPrice += $price* $quantity;
			//If they don't, return an error message
			} else {
				echo json_encode(['error' => 'Inaccessible price and quantity in basket.']);
				exit();
			}
		}


		//Making data array to be sent
		$data = [
			'basket' => $_SESSION['basket'],  // Empty basket
			'totalPrice' => $totalPrice  // Nothing for total price
		];

		// Clean output buffer
		ob_end_clean();

		// Return session data as JSON
		echo json_encode($data);


	//Incase of errors,
	} catch (PDOException $ex){
		// Send an error message
		echo json_encode(['error' => 'Sorry, an error has occurred with the database.', 'details' => $ex->getMessage()]);
		
	}

?>