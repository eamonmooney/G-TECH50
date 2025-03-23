<?php
// PHP FOR DELETING PRODUCTS FROM INVENTORY MANAGEMENT BY SAFA

// Ensure database is connected
require_once('database.php');

// Read and decode incoming JSON
$data = json_decode(file_get_contents('php://input'), true);

// Check if product IDs were received
if (isset($data['productIDs']) && is_array($data['productIDs'])) {
	// Store product ID
	$productIDs = $data['productIDs'];

	// Error handling
	try {
		// Prepare placeholders for SQL based on how many products there are to delete
		$placeholders = implode(',', array_fill(0, count($productIDs), '?'));

		// SQL to delete selected products
		$deleteSQL = "DELETE FROM products WHERE ProductID IN ($placeholders)";
		// Prepare
		$deleteSTMT = $db->prepare($deleteSQL);
		// Execute 
		$deleteSTMT->execute($productIDs);

		// Check if rows were affected
		if ($deleteSTMT->rowCount() > 0) {
			// Success
			echo json_encode(["success" => true]);
			// Failure
		} else {
			echo json_encode(["success" => false, "error" => "No products were deleted."]);
		}
		// eRROR
	} catch (PDOException $e) {
		echo json_encode(["success" => false, "error" => $e->getMessage()]);
	}
	// eRROr
} else {
	echo json_encode(["success" => false, "error" => "Invalid product IDs."]);
}
