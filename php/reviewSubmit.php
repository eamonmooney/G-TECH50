<?php
header('Content-Type: application/json');
session_start();
require_once('connectdb.php');

try {
    // Check for session userId; if not found, set it to NULL
    $userID = $_SESSION['userId'] ?? null;

    // If the userId is 0, treat it as NULL 
    if ($userID === 0) {
        $userID = null;
    }

    // POST inputs
    $productID = $_POST['productID'] ?? null;
    $rating = $_POST['rating'] ?? null;
    $review = $_POST['review'] ?? null;

    // Validate the inputs
    if (!$productID || !$rating || !$review) {
        echo json_encode(['error' => 'Missing productID, rating, or review.']);
        exit;
    }

    // Default to not verified if the user is not logged in
    $verified = 0;

    // If the user is logged in, check if they have ordered the product
    if ($userID) {
        $stmtOrderCheck = $db->prepare("
            SELECT COUNT(*) 
            FROM orderitem oi
            JOIN orders o ON oi.OrderID = o.OrderID
            WHERE o.UserID = :userID AND oi.ProductID = :productID
        ");
        $stmtOrderCheck->execute([':userID' => $userID, ':productID' => $productID]);

        $orderCount = $stmtOrderCheck->fetchColumn();

        // If the user has ordered the product, set Verified to true
        if ($orderCount > 0) {
            $verified = 1;
        }
    }

    // Prepare the insert statement for the review
    $stmt = $db->prepare("
        INSERT INTO Reviews (UserID, ProductID, Rating, Review, Hidden, Verified)
        VALUES (:userID, :productID, :rating, :review, 0, :verified)
    ");

    // Bind the values, ensure userID is NULL if not logged in
    $stmt->bindValue(':userID', $userID, $userID !== null ? PDO::PARAM_INT : PDO::PARAM_NULL);
    $stmt->bindValue(':productID', $productID, PDO::PARAM_INT);
    $stmt->bindValue(':rating', $rating, PDO::PARAM_INT);
    $stmt->bindValue(':review', $review, PDO::PARAM_STR);
    $stmt->bindValue(':verified', $verified, PDO::PARAM_INT);

    // Execute the statement
    $stmt->execute();

    echo json_encode(['success' => true]);

} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
