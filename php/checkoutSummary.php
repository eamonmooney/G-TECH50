<?php
// Checkout functionality. By Sahil Awan (230073302). 
session_start();
ob_start(); // Preventing comments from the database file to be added in the output.
require_once('connectdb.php');

header('Content-Type: application/json');

$basket = $_SESSION['basket'] ?? [];
$userID = $_SESSION['userId'] ?? null;
$_SESSION['orderDetails'] = [];

// Get user details for storage in the database as session variables
if ($_SESSION['signedIn'] == false) {
    $email = $_POST['email'] ?? '';
    $firstName = $_POST['firstName'] ?? '';
    $surname = $_POST['surname'] ?? '';
    $fullName = $firstName . ' ' . $surname;

    $_SESSION['orderDetails'] = [
        "email" => $email,
        "fullName" => $fullName,
    ];
}

$address = trim($_POST['address'] ?? '');
$_SESSION['orderDetails']['address'] = $address;

if (empty($basket)) {
    echo json_encode(["status" => "empty", "message" => "Your basket is empty."]);
    exit;
}

$orderSummary = [];

foreach ($basket as $itemName => $details) {
    if ($details['quantity'] <= 0) {
        echo json_encode(["status" => "failure", "message" => "Not enough stock to complete the order."]);
        exit();
    }

    $orderSummary[] = [
        "item" => htmlspecialchars($itemName),
        "price" => number_format($details['price'], 2),
        "quantity" => $details['quantity'],
        "total" => number_format($details['price'] * $details['quantity'], 2)
    ];
}

ob_end_clean();  // Clear buffer to avoid unintended output
echo json_encode(["status" => "success", "order" => $orderSummary]);
?>
