<?php
// Database connection
require_once('connectdb.php');

// Get the productId from the incoming JSON data
$data = json_decode(file_get_contents("php://input"), true);
$productId = $data['productId'] ?? null;

if ($productId) {
    try {
        // Fetch the pinnedReviewID from the Products table
        $stmtPinnedId = $db->prepare("SELECT pinnedReviewID FROM Products WHERE ProductID = :productId");
        $stmtPinnedId->execute([':productId' => $productId]);
        $pinnedIdResult = $stmtPinnedId->fetch(PDO::FETCH_ASSOC);

        // Check if pinnedReviewID exists and is not null
        $pinnedReview = null;
        if ($pinnedIdResult && $pinnedIdResult['pinnedReviewID'] !== null) {
            $pinnedReviewId = $pinnedIdResult['pinnedReviewID'];

            // Fetch the pinned review details
            $stmtPinnedReview = $db->prepare("SELECT ReviewID, UserID, ProductID, Rating, Review, Hidden FROM Reviews WHERE ReviewID = :pinnedReviewId AND Hidden = 0");
            $stmtPinnedReview->execute([':pinnedReviewId' => $pinnedReviewId]);
            $pinnedReview = $stmtPinnedReview->fetch(PDO::FETCH_ASSOC);
        }

        // Fetch all visible reviews for the product
        $stmtReviews = $db->prepare("SELECT ReviewID, UserID, ProductID, Rating, Review, Hidden FROM Reviews WHERE ProductID = :productId AND Hidden = 0");
        $stmtReviews->execute([':productId' => $productId]);
        $reviews = $stmtReviews->fetchAll(PDO::FETCH_ASSOC);

        // Return both the pinned review and the other reviews
        echo json_encode([
            'pinnedReview' => $pinnedReview,
            'reviews' => $reviews
        ]);

    } catch (PDOException $e) {
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'No productId received.']);
}
?>
