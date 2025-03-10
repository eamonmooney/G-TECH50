<?php
// Created by Safa Riasat - 230078145
// PHP Code for every page to have a number of items in the basket 

// Start the session
session_start();

	// If basket isn't set
	if (!isset($_SESSION['basket'])) {
		//Initialise basket
		$_SESSION['basket'] = [];
	}

	//Get the number of basket items by adding all quantity items
	$basketNum = array_sum(array_column($_SESSION['basket'], 'quantity'));

	// Return JSON instead of JavaScript
	header('Content-Type: application/json');
	
	// Encode the returned JSON
	echo json_encode(['basketNum' => $basketNum]);

exit();



?>