<?php
session_start();
require_once('connectdb.php');

// Check if the user is signed in
if (!isset($_SESSION['userId'])) {
    header('Location: ../signin.html');
    exit();
}

$userId = $_POST['userId'];
$orderId = $_POST['orderId'];
$productId = $_POST['productId'];
$reason = $_POST['reason'];

// Validate data
if (!$userId || !$orderId || !$productId || !$reason) {
    echo "Invalid form data.";
    exit();
}

try {
    // Insert the return request into the Returns table
    $stmt = $db->prepare("
        INSERT INTO Returns (UserID, OrderID, ProductID, Reason, Status)
        VALUES (?, ?, ?, ?, 'Pending')
    ");
    $stmt->execute([$userId, $orderId, $productId, $reason]);

    // Redirect to the previous orders page with a success message
    header('Location: ../returnConfirmation.html');
    exit();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
