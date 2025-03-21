<?php
/* This PHP page is to update the basket according to the changes made in the basket.html
		Created by Safa Riasat - 230078145
	*/

// Start output buffering - using this to clean any outputs and remove uneeded outputs from irrelevant areas affecting JSON
// ob_start();


//Create / continue session
session_start();

// TEST - Check basket contents
// header('Content-Type: text/plain');
// var_dump($_SESSION['basket']);
// exit;


header('Content-Type: application/json');




if (!isset($_SESSION['basket'])) {
	$_SESSION['basket'] = [];
}

$basket = $_SESSION['basket'];

// Get the raw POST data and decode
$data = json_decode(file_get_contents('php://input'), true);

// Check if the 'name' and 'change' fields exist in the decoded JSON
// if (isset($data['name']) && isset($data['change'])) {
if (isset($data['name']) && isset($data['change'])) {
	// Set Item name
	$name = $data['name']; 
	// Set change (either 1 for increase or -1 for decrease) and convert to integer
	$change = (int) $data['change'];

	// Check if the item exists in the basket
	if (isset($_SESSION['basket'][$name])) {
		//Get the current quantity
		$currentQuantity = $_SESSION['basket'][$name]['quantity'];
		//Calculate new quantity
		$newQuantity = $currentQuantity + $change;
		// If quantity is below 1, remove the item from the basket
		if ($newQuantity < 1) {
			// Remove item from the basket
			unset($_SESSION['basket'][$name]); 
			//Return that the item has been removed
			$response = ['status' => 'removed', 'message' => 'Item removed from basket'];
		//Or, if the quantity isn't negative / 0
		} else {
			// Update the quantity of the item in the session basket variable
			$_SESSION['basket'][$name]['quantity'] = $newQuantity;
			//Return a response with the status being updated and the new quantity
			$response = ['status' => 'updated', 'quantity' => $newQuantity, 'message' => 'Item added to basket'];
		}
	//Otherwise if the item doesn't exist
	} else {

		$response = ['status' => 'error', 'message' => 'Item not found in basket'];
		echo json_encode($_SESSION['basket']);
		exit;
		// Return a response that the item isn't in the basket
		// $response = ['status' => 'error', 'message' => 'Item not found in basket'.$data['name']. $data['change'] ];
	}

	// Send the response as JSON to .html file
	echo json_encode($response);
	// Otherwise if we don't have the necessary parameters 
} else {
	//Send a message that the request parameters are invalid
	echo json_encode(['status' => 'error', 'message' => 'Invalid request parameters']);
}

session_write_close(); // Saves the changes made in this session - Sahil.
// Clean any previous output
// ob_end_clean(); 
