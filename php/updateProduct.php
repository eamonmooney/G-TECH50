<?php
// PHP for Adding New Product to DATABASE by SAFA

// Ensure database is connected
require_once('database.php');

// Read and decode incoming JSON data
$data = json_decode(file_get_contents('php://input'), true);
error_log("Received data: " . print_r($data, true));

// Check for valid JSON
if (is_null($data)) {
	echo json_encode(["success" => false, "error" => "Invalid JSON received."]);
	exit;
}

// Check if all required fields are present
if (isset($data['ProductID'], $data['ProductName'], $data['Price'], $data['Stock'], $data['Category'], $data['Description'], $data['ImageURL'])) {

	// Sanitize inputs
	$productID = (int) $data['ProductID'];
	$productName = htmlspecialchars($data['ProductName']);
	$price = (float) $data['Price'];
	$stock = (int) $data['Stock'];
	$description = htmlspecialchars($data['Description']);
	$imageURL = htmlspecialchars($data['ImageURL']);
	$categoryName = trim($data['Category']);

	// Categories
	$categories = [
		'Mice' => 1,
		'Monitors' => 2,
		'Headphones' => 3,
		'Keyboards' => 4,
		'Mousepads' => 5
	];

	// Category mapping (name to ID)
	$category = $categories[$categoryName] ?? 0;

	// If category is wrong
	if ($category === 0) {
		echo json_encode(["success" => false, "error" => "Invalid category provided."]);
		exit;
	}

	// Try update products
	try {
		// SQL query to update the product
		$updateSQL = "UPDATE products 
                      SET ProductTypeID = :category, ProductName = :productName, Price = :price, Stock = :stock,  Description = :description, ImageURL = :imageURL

                      WHERE ProductID = :productID";

		// Prepare
		$stmt = $db->prepare($updateSQL);

		// Bind parameters
		$stmt->bindParam(':productID', $productID);
		$stmt->bindParam(':category', $category);
		$stmt->bindParam(':productName', $productName);
		$stmt->bindParam(':price', $price);
		$stmt->bindParam(':stock', $stock);
		$stmt->bindParam(':description', $description);
		$stmt->bindParam(':imageURL', $imageURL);

		// Execute update
		$stmt->execute();

		// Check if any row was actually updated
		if ($stmt->rowCount() > 0) {
			echo json_encode(["success" => true]);
		} else {
			echo json_encode(["success" => false, "error" => "No changes were made or product not found."]);
		}
	} catch (PDOException $e) {
		echo json_encode(["success" => false, "error" => $e->getMessage()]);
	}
} else {
	echo json_encode(["success" => false, "error" => "Missing required product fields."]);
}
