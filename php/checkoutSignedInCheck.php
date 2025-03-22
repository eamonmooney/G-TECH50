<?php
// This page should handle the backend for checking if the user is signed in, in the checkout page
//  Created by Safa Riasat - 230078145

	// Start/resume the session 
	session_start();

	// Ensure the Content-Type is set to JSON
	header('Content-Type: application/json');

	//Check if user is logged in
	if ($_SESSION['signedIn'] == False) {	
		// Return notSignedIn
		echo json_encode(["status" => "notSignedIn"]);

	}else{
		// Return signedIn
		echo json_encode(["status" => "signedIn"]);
	}



?>