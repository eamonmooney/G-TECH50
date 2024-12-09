<?php
// This page should handle the backend for the basket page's redirection to sign in
//  If the user isn't loaded in, redirect to the login page.
//  Created by Safa Riasat

	// Start/resume the session 
	session_start();

		//!!! FOR TESTING - REMOVE!!!!
		$_SESSION['userId'] = 1;

		// $_SESSION['basket'] = null;

	  // Ensure the Content-Type is set to JSON
	  header('Content-Type: application/json');

	//If the user isn't loaded in, redirect to the signin page
	// If the user isn't logged in
	if (!isset($_SESSION['userId'])) {	
		// Return a response encoded in JSON, with redirect = true, meaning we will redirect
		echo json_encode(["redirect" => true]);
		//Leaving the php file to ensure nothing else is performed
		exit();
	} 

	// // Otherwise if user is logged in, return a JSON response with redirect = true, meaning we won't redirect, and return userID and basket

	$response = [
		"redirect" => false,
		"userID" => $_SESSION['userId'],
		"basket" => isset($_SESSION['basket']) ? $_SESSION['basket'] : [] // Include basket or empty if not set
	];

	// Output the JSON response
	echo json_encode($response);

	
?>