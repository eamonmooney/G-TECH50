<?php
// Created by Safa Riasat - 230078145
// PHP for getting report data for reports page

// Start the session
session_start();

// Try catch statement for error handling
try {
	// Ensure database is connected
	require_once('database.php');


	// Get the 'type' from the URL query string
	$type = $_GET['type'] ?? '';

	// Changes output depending on the selected report info 
	switch ($type) {
		// if its stock,
		case 'stock':
			// prepare, execute and return
			$stockSTMT = $db->prepare("SELECT ProductName, Stock FROM products ORDER BY ProductName");
			$stockSTMT->execute();
			echo json_encode($stockSTMT->fetchAll(PDO::FETCH_ASSOC));
			break;

		// If it's incoming orders
		case 'incoming':
			// prepare, execute and return

			// o is orders table, p is products table and oi is order items table
			// Get the order ID, order date, product name and quantity from orders table where status is processing
			$incomingSTMT = $db->prepare("
                SELECT o.OrderID, o.OrderDate, p.ProductName, oi.Quantity
                FROM orders o
                JOIN orderitem oi ON o.OrderID = oi.OrderID
                JOIN products p ON oi.ProductID = p.ProductID
                WHERE o.Status = 'Processing'
                ORDER BY o.OrderDate DESC
            ");

			// Execute
			$incomingSTMT->execute();
			echo json_encode($incomingSTMT->fetchAll(PDO::FETCH_ASSOC));
			break;

		// If it's outgoing orders

		case 'outgoing':
			// Get the order ID, order date, product name and quantity from orders table where status is delivering
			$stmt = $db->prepare("
                SELECT o.OrderID, o.OrderDate, p.ProductName, oi.Quantity
                FROM orders o
                JOIN orderitem oi ON o.OrderID = oi.OrderID
                JOIN products p ON oi.ProductID = p.ProductID
                WHERE o.Status = 'Delivering'
                ORDER BY o.OrderDate DESC
            ");
			// Execute
			$stmt->execute();
			echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
			break;


		// If it's total orders (for total orders over time)
		case 'totalOrders':
			// Get the order date and count

			$stmt = $db->prepare("
				SELECT DATE(OrderDate) AS OrderDay, COUNT(*) AS TotalOrders
				FROM orders
				GROUP BY OrderDay
				ORDER BY OrderDay ASC
			");
			// Execute
			$stmt->execute();
			echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
			break;


		// Invalid error message
		default:
			echo json_encode(["error" => "Invalid report type requested."]);
	}
} catch (PDOException $e) {
	echo json_encode(["error" => $e->getMessage()]);
}
