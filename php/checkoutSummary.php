<?php

// Checkout functionality. By Sahil Awan (230073302). 
session_start();
ob_start(); // Preventing comments from the database file to be added in the output.
require_once('connectdb.php');

header('Content-Type: application/json');

$basket = isset($_SESSION['basket']) ? $_SESSION['basket'] : [];
$userID = isset($_SESSION['userId']) ? $_SESSION['userId'] : null;
// Initialise
$_SESSION['orderDetails'] = [];

// Get user details for storage in the database as session variables
//Check if user is logged in - if logged in, don't need additional qns
// So if not signed in
if ($_SESSION['signedIn'] == False) {
    // Get the data from the form
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $firstName = isset($_POST['firstName']) ? $_POST['firstName'] : '';
    $surname = isset($_POST['surename']) ? $_POST['surname'] : ''; 
    $fullName = $firstName . $surname;
    // Store all details in session variable
    $_SESSION['orderDetails'] = [
        "email" => $email,
        "fullName" => $fullName,
    ];
} 

// Both needs an address
$address = isset($_POST['address']) ? $_POST['address'] : '';

// Store address in session variable
$_SESSION['orderDetails']['address'] = $address;

if (empty($basket)) {
    echo json_encode(["status" => "empty", "message" => "Your basket is empty."]);
    exit;
}

$orderSummary = [];

foreach ($basket as $itemName => $details) {
    $orderSummary[] = [
        "item" => htmlspecialchars($itemName),
        "price" => number_format($details['price'], 2),
        "quantity" => $details['quantity'],
        "total" => number_format($details['price'] * $details['quantity'], 2)
    ];
}

ob_end_clean(); // Preventing comments from being added to the output.
echo json_encode(["status" => "success", "order" => $orderSummary]);
