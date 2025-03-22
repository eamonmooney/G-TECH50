<?php
// This page should handle the backend for the basket page's redirection to sign in
//  If the user isn't logged in, let them go to the basket page as a guest
//  Created by Safa Riasat - 230078145

	// Start/resume the session 
	session_start();

	

	// Ensure the Content-Type is set to JSON
	header('Content-Type: application/json');

	//Check if user is logged in
	//If they arent, make a fake account
	if (!isset($_SESSION['userId'])) {	


	}

	// If the user isn't logged in, redirect to the signin page
	if (!isset($_SESSION['userId'])) {	
		// Return a response encoded in JSON, with redirect = true, meaning we will redirect
		echo json_encode(["redirect" => true]);
		//Leaving the php file to ensure nothing else is performed
		exit();
	} 

	// Otherwise if user is logged in, return a JSON response with redirect = true, meaning we won't redirect, and return userID and basket

	$response = [
		"redirect" => false,
		"userID" => $_SESSION['userId'],
		"name" => $_SESSION['username'],
		"basket" => isset($_SESSION['basket']) ? $_SESSION['basket'] : [] // Include basket or empty if not set
	];

	// Output the JSON response
	echo json_encode($response);

	




	
?>