<?php
require_once('connectdb.php');

$returnId = $_POST['returnId'];
$action = $_POST['action'];  // 'approve' or 'reject'

// Fetch the return details
$stmt = $db->prepare("SELECT * FROM Returns WHERE ReturnID = ?");
$stmt->execute([$returnId]);
$return = $stmt->fetch();

if (!$return) {
    echo "Return not found.";
    exit();
}

if ($action === 'approve') {
    // Update the product stock
    $stmt = $db->prepare("
        UPDATE Products
        SET Stock = Stock + 1
        WHERE ProductID = ?
    ");
    $stmt->execute([$return['ProductID']]);

    // Mark the return as approved
    $stmt = $db->prepare("
        UPDATE Returns
        SET Status = 'Approved'
        WHERE ReturnID = ?
    ");
    $stmt->execute([$returnId]);

    echo "Return approved and stock updated.";
} elseif ($action === 'reject') {
    // Mark the return as rejected
    $stmt = $db->prepare("
        UPDATE Returns
        SET Status = 'Rejected'
        WHERE ReturnID = ?
    ");
    $stmt->execute([$returnId]);

    echo "Return rejected.";
} else {
    echo "Invalid action.";
}
?>