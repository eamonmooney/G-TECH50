<?php
// Page by Safa - Send alerts to admin homepage

// DB
require_once('database.php'); 

// Try catch block
try {
	// Threshold for stock to be alerted
    $threshold = 11; 
	// statement
    $alertSTMT = $db->prepare("SELECT ProductName, Stock FROM products WHERE Stock < ? ORDER BY Stock ASC");
	// Execute
    $alertSTMT->execute([$threshold]);

    // Fetch as associative array
    $products = $alertSTMT->fetchAll(PDO::FETCH_ASSOC);

    // Return as JSON
    echo json_encode($products);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>