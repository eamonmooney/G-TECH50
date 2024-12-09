<!-- OLD PAGE BEING REPLACED - KEPT FOR REFERENCE - TO DELETE
 This page should handle the backend for the basket. 
 This should include displaying/ removing items from the basket list based on a basket table.
 Adding items to a basket should be performed on the product pages themselves and it's product ID added to a local session 2D array, $basketItems with productID and quantity
 If the user isn't loaded in, they should have been redirected to the login page from basketStart.php
 If the user has no items in the basket, they should not have anything displayed on screen and are told of this
 User should be able to increase/decrease the quantity of products from basket
Created by Safa Riasat
  -->
<?php
	//Resume the session to allow us to use the session variables
	session_start();

	//!!! FOR TESTING - REMOVE!!!!
	$_SESSION['userId'] = 1;

	
	//Set the userID 
	$userID = $_SESSION['userId'];


	
	//If the user has nothing in their basket, tell them they have nothing in their basket and end
	// If the basket hasn't been set, or if it's empty,
	if (!isset($_SESSION['basket']) || empty($_SESSION['basket'])) {	
		//Tell user they have nothing in their basket
		echo "Nothing in the basket <br>";
		//Leaving the php file to ensure nothing else is performed
		exit();
	} 

	//Upon loading in, assuming you are logged in, connect to the database to get info about products from our Basket List
	//Wrapped in a try-catch statement for error handling
	try{
		//Connect to the database if it hasn't been done already
		require_once('connectdb.php');


		$totalPrice = 0;

		//For each loop to go through all items
		foreach ($_SESSION['basket'] as $product) {
			//Temp variable for the product ID
			$productName = $product['name']; 
			//Temp variable for the price
			$price = $product['price']; 
			//Temp variable for the quantity
			$quantity = $product['quantity']; 
			$totalPrice += $price* $quantity;

			// Print this info to the user
			// Print name with htmlspecialchars() to sanitise code
			echo "Product name: " . htmlspecialchars($productName) . "<br>";
			// Print price with number_format() and two decimals to sanitise and quantity
			echo "Price: $" . number_format($price, 2) . " - Quantity: " . $quantity . "<br><br>";

			//CONSIDER HAVING THE DETAILED PRODUCT INFO FOR ALL OF THESE PRODUCTS
		

		}

		//Print the total price
		// Print total price with number_format and two decimals to sanitise
		echo "Total Price: $" . number_format($totalPrice, 2) . "<br>";
		// Return session data as JSON
	// header('Content-Type: application/json');
	// echo json_encode($_SESSION['basket']);


	//Incase of errors,
	} catch (PDOException $ex){
		//Output error details to the user for troubleshooting
		echo "Sorry, an error has occured with the database. <br>";
		echo "Error details: <em>". $ex->getMessage()."</em>";
	}

?>