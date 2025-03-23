<?php
// Created by Safa Riasat - 230078145
// PHP for User Transaction
/* 
3.1list of all orders
3.2 can process these
3.3can filter these
*/

// Start the session
session_start();

// Try catch statement for error handling
try {
	// Ensure database is connected
	require_once('database.php');


	// Grab the customer transactions
	// Grab the Guest transactions
	// Filter by time, product, usertype


	// SQL query to get customer and guest orders - OrderTypeID 2 (customers), 3 (guests)
	$ordersSQL = "SELECT 
                OrderID, 
                UserID, 
                GuestID, 
                OrderDate, 
                OrderAmount, 
                OrderCost, 
                Status,
                CASE 
                    WHEN OrderTypeID = 2 THEN 'Customer' 
                    WHEN OrderTypeID = 3 THEN 'Guest' 
                    ELSE 'Unknown' 
                END AS OrderType
             FROM orders 
             WHERE OrderTypeID IN (2, 3)";
	
	//Preparing our SQL statement by sending to database=
	$ordersSTMT = $db->prepare($ordersSQL);
	// Execute
	$ordersSTMT->execute();
	// Store in result
	$result = $ordersSTMT->fetchAll();

	// If there wasnt a result, show error - error debugging
	if (!$result) {
		die("SQL query failed: " . $db->error);
	}

	// Output data as JSON
	header('Content-Type: application/json');
	echo json_encode($result);



	exit();

	//If there's been an error
} catch (PDOException $e) {
	//Display the message
	echo "Error: " . $e->getMessage();
	
}

exit();