<?php
// Created by Safa Riasat - 230078145
// PHP for Inventory Management - THIS PAGE WILL PULL ITEMS FOR THIS
/* 
5.1 ✅ Product Listing: image, price, description, stock level, additional info 

5.2 ✅Alert System - out of stock/under threshold

5.3 ✅Reports on stock: current and incoming/outgoing orders

5.4 ✅Search filter

5.5 ✅add, ✅edit, ✅ remove products
*/

// Start the session
session_start();

// Try catch statement for error handling
try {
	// Ensure database is connected
	require_once('database.php');


	// Grab the products from the database
	// SQL query to get each product from the database
	$productsSQL = "SELECT ProductID, ProductTypeID, ProductName, Stock, Price, ImageURL, Description FROM products";

	//Preparing our SQL statement by sending to database=
	$productsSTMT = $db->prepare($productsSQL);
	// Execute
	$productsSTMT->execute();

	// Store in products as an associative array
	$products = $productsSTMT->fetchAll(PDO::FETCH_ASSOC);

	// Process product data
	// Loop through all products - referencing each element rather than copying them
	foreach ($products as &$product) {
		// Assign status based on stock level
		if ($product['Stock'] == 0) {
			$product['Status'] = 'Out of Stock';
		} elseif ($product['Stock'] > 10) {
			$product['Status'] = 'In Stock';
		} else {
			$product['Status'] = 'Low Stock';
		}

		// array for category names
		$categories = [
			1 => 'Mice',
			2 => 'Monitors',
			3 => 'Headphones',
			4 => 'Keyboards',
			5 => 'Mousepads'
		];

		// Map ProductTypeID to category names
		$product['Category'] = $categories[$product['ProductTypeID']] ?? 'Unknown';
	}

	// Return JSON response
	header('Content-Type: application/json');
	echo json_encode($products);
	exit;
} catch (PDOException $e) {
	echo json_encode(["error" => "Database connection failed: " . $e->getMessage()]);
	exit;
}
