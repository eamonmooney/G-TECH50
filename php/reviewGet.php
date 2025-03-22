<?php
// Database connection
require_once('connectdb.php');

// Get the productId from the incoming JSON data
$data = json_decode(file_get_contents("php://input"), true);
$productId = $data['productId'] ?? null;

if ($productId) {
    try {
        // Prepare the SQL query to fetch reviews for the given productId
        $stmt = $db->prepare("SELECT ReviewID, UserID, ProductID, Rating, Review, Hidden FROM Reviews WHERE ProductID = :productId");
        $stmt->execute([':productId' => $productId]);

        // Fetch the results
        $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Calculate the average rating
        $stmtAvg = $db->prepare("SELECT AVG(Rating) as avgRating FROM Reviews WHERE ProductID = :productId");
        $stmtAvg->execute([':productId' => $productId]);
        $avgRatingResult = $stmtAvg->fetch(PDO::FETCH_ASSOC);
        $avgRating = round($avgRatingResult['avgRating'], 1); // Round to 1 decimal place

        // Return both reviews and the average rating as a JSON response
        echo json_encode([
            'reviews' => $reviews,
            'averageRating' => $avgRating
        ]);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'No productId received.']);
}
?>
