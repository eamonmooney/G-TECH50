<?php
// This page should handle the backend for creating a default account for ppl when using the site unlogged in
//  Created by Safa Riasat - 230078145

	// Start/resume the session 
	session_start();

	// Ensure the Content-Type is set to JSON
	header('Content-Type: application/json');

	//Check if user is logged in
	//If they arent, make a default guest account
	if (!isset($_SESSION['userId'])) {	
		// Temporarily given 0 so we know its a guest - not used in database tho
		$_SESSION['userId'] = 0;
		$_SESSION['roleId'] = 1;
		$_SESSION['username'] = 'Guest';
	}

	// If basket isnt set, initialise
	if (!isset($_SESSION['basket'])) {
		// Initialize basketas an empty array	
		$_SESSION['basket'] = []; 
	}

?>