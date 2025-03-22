<?php
header('Content-Type: application/json');
session_start();
require_once('connectdb.php'); 

try {
    // Get the logged-in user ID from session
    $userID = $_SESSION['userId'] ?? null;

    if (!$userID) {
        echo json_encode(['error' => 'User not logged in.']);
        exit;
    }

    // Get the review data from POST
    $productID = $_POST['productID'] ?? null;
    $rating = $_POST['rating'] ?? null;
    $review = $_POST['review'] ?? null;

    // Validate inputs
    if (!$productID || !$rating || !$review) {
        echo json_encode(['error' => 'Missing productID, rating, or review.']);
        exit;
    }

    // Insert into your Reviews table, Hidden defaults to 0
    $stmt = $db->prepare("INSERT INTO Reviews (UserID, ProductID, Rating, Review, Hidden) VALUES (:userID, :productID, :rating, :review, 0)");
    $stmt->execute([
        ':userID'    => $userID,
        ':productID' => $productID,
        ':rating'    => $rating,
        ':review'    => $review
    ]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
