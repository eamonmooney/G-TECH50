<!-- Checkout functionality. By Sahil Awan (230073302). -->

<?php
session_start();
ob_start(); // Preventing comments from the database file to be added in the output.
require_once('connectdb.php');

header('Content-Type: application/json');

$basket = isset($_SESSION['basket']) ? $_SESSION['basket'] : [];
$userID = isset($_SESSION['userId']) ? $_SESSION['userId'] : null;


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
?>
