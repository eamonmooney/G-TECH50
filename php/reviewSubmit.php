<?php
header('Content-Type: application/json');
session_start();
require_once('connectdb.php');

try {
    // Check for session userId; if not found, it stays NULL
    $userID = $_SESSION['userId'] ?? null;

    // POST inputs
    $productID = $_POST['productID'] ?? null;
    $rating = $_POST['rating'] ?? null;
    $review = $_POST['review'] ?? null;

    // Validate the inputs
    if (!$productID || !$rating || !$review) {
        echo json_encode(['error' => 'Missing productID, rating, or review.']);
        exit;
    }

    // Prepare insert statement
    $stmt = $db->prepare("
        INSERT INTO Reviews (UserID, ProductID, Rating, Review, Hidden)
        VALUES (:userID, :productID, :rating, :review, 0)
    ");

    if (!isset($_SESSION['userId']) || $_SESSION['userId'] === 0) {
        $userID = null;
    } else {
        $userID = $_SESSION['userId'];
    }

    // Explicitly bind userID as NULL if there is no logged in user
    if ($userID !== null) {
        $stmt->bindValue(':userID', $userID, PDO::PARAM_INT);
    } else {
        $stmt->bindValue(':userID', null, PDO::PARAM_NULL);
    }

    $stmt->bindValue(':productID', $productID, PDO::PARAM_INT);
    $stmt->bindValue(':rating', $rating, PDO::PARAM_INT);
    $stmt->bindValue(':review', $review, PDO::PARAM_STR);

    // Execute the statement
    $stmt->execute();

    echo json_encode(['success' => true]);

} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
