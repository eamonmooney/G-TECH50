<?php
// Fetches all details about a specific review, and checks if it's pinned - Eamon Mooney - 230075926
header('Content-Type: application/json');

// Database connection
require_once('connectdb.php');

try {

    $reviewID = $_POST['reviewID'] ?? null;

    if ($reviewID === null) {
        echo json_encode(['error' => 'No review ID provided']);
        exit;
    }

    // Prepare SQL query to get review and check if it's pinned in the Products table
    $stmt = $db->prepare('
        SELECT r.*, 
               p.pinnedReviewID = r.ReviewID AS isPinned
        FROM reviews r
        LEFT JOIN products p ON p.pinnedReviewID = r.ReviewID
        WHERE r.ReviewID = :reviewID
    ');

    $stmt->execute(['reviewID' => $reviewID]);

    $review = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($review) {
        // Return review details along with whether it is pinned or not
        echo json_encode([
            'review' => $review,
            'isPinned' => $review['isPinned'] ? true : false // Check if it's pinned
        ]);
    } else {
        echo json_encode(['error' => 'Review not found']);
    }
} catch (PDOException $e) {
    // Handle any errors in connecting/querying the database
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>