<?php
// Created by Safa Riasat - 230078145
// PHP for changing status on User Transaction page
/* 
updates status in order page based on orderid and new status
*/

// Start the session
session_start();

// Ensure database is connected
require_once('database.php');

// Get JSON input
$json = file_get_contents("php://input");
$data = json_decode($json, true);



if ($data === null) {
	echo json_encode(["success" => false, "message" => "Invalid JSON received". file_get_contents("php://input")]);
	exit;
}

// Validate input - check if the orderID and Status are sent
// If they aren't set
if (!isset($data["orderID"], $data["status"])) {
	// Echo a message and exit
	echo json_encode(["success" => false, "message" => "Invalid inputs"]);
	exit;
}

// Get the integer value of orderID
$orderID = intval($data["orderID"]);

// Get the status - checking if it matches these for ~safety~
// Set an array of allowed statuses
$allowedStatuses = ["Processing", "Delivering", "Sent", "Cancelled"];
// If the status is in the array
if (in_array($data["status"], $allowedStatuses)) {
	// Set it
	$status = $data["status"];
	// Otherwise die
} else {
	// Echo a message and exit
	echo json_encode(["success" => false, "message" => "Invalid status chosen"]);
	exit;
}

try {

	// SQL status update statement
	$statusSQL = "UPDATE orders SET Status = :status WHERE OrderID = :orderID";
	// prepare sql statement
	$statusSTMT = $db->prepare($statusSQL);
	// Execute
	$statusSTMT->execute([
		':status' => $status,
		':orderID' => $orderID

	]);

	// Success response
	echo json_encode(["success" => true, "message" => "Order status updated"]);
	// Errors with failure errors
} catch (PDOException $e) {
	echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
