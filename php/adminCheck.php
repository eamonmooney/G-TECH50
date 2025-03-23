<?php
// This page should handle the backend for checking if a person is an admin - if they aren't, don't let them access the page
// For use in every admin page
//  Created by Safa Riasat - 230078145

// Start/resume the session 
session_start();

// Ensure the Content-Type is set to JSON
header('Content-Type: application/json');

//Check if user is logged in and an admin
// Admins are 3,4 and 5
//If they arent, redirect them to sign in page
if (!isset($_SESSION['userId']) || (!($_SESSION['userId'] == 3) && !($_SESSION['userId'] == 4) && !($_SESSION['userId'] == 5))) {
	echo json_encode(["redirect" => true]);
	exit();
} else {
	// If admin, return success
	echo json_encode(["redirect" => false]);
}
