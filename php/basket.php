<!-- This page should handle the backend for the basket. 
 This should include displaying/ removing items from the basket list based on a basket table.
 Adding items to a basket should be performed on the product pages themselves and it's product ID added to a local session 2D array, $basketItems with productID and quantity
 If the user isn't loaded in, redirect to the login page.
 If the user has no items in the basket, they should not have anything displayed on screen and are told of this
 User should be able to increase/decrease the quantity of products from basket
Created by Safa Riasat
  -->
<?php

	//Resume the session to allow us to use the session variables
	session_start();

	//If the user isn't loaded in, redirect to the login page
	// If the user isn't logged in
	if (!isset($_SESSION['userId'])) {	
		//Redirect to the login page 
		header("Location: index.html"); //!!! CHANGE TO LOGIN PAGE
		//Leaving the php file to ensure nothing else is performed
		exit();
	} 
	//Set the userID 
	$userID = $_SESSION['userId'];

	//If the user has nothing in their basket, tell them they have nothing in their basket and end
	// If the basket hasn't been set, or if it's empty,
	if (!isset($_SESSION['basketItems']) || empty($_SESSION['basketItems'])) {	
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

		//Tell user this is the list of items in the basket
		echo "Items in basket: <br>";

		$price = 0;

		//For each loop to go through all items
		foreach ($_SESSION['basketItems'] as $product) {
			//Temp variable for the product ID
			$productID = $product[0]; 
			//Temp variable for the quantity
			$quantity = $product[1]; 
			//Print this info to the user
			echo "Product ID: " . $productID . " - Quantity: " . $quantity . "<br>";
			//CONSIDER HAVING THE DETAILED PRODUCT INFO FOR ALL OF THESE PRODUCTS
			//GO INTO DATABASE AND GET PRICE OF THIS AND ADD TO THE PRICE VARIABLE - MAYBE ALSO ADD TO THIS PRODUCT DESCRIPTION AREA

		}

		//Print the total price
		echo "Price: $" . $price . "<br>";


	//Incase of errors,
	} catch (PDOexception $ex){
		//Output error details to the user for troubleshooting
		echo "Sorry, an error has occured with the database. <br>";
		echo "Error details: <em>". $ex->getMessage()."</em";
	}

?>