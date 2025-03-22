<?php
// Database connection
require_once('connectdb.php');

// Get the productId from the incoming JSON data
$data = json_decode(file_get_contents("php://input"), true);
$productId = $data['productId'] ?? null;

if ($productId) {
    try {
        // Prepare the SQL query to fetch reviews for the given productId
        $stmt = $db->prepare("SELECT ReviewID, UserID, ProductID, Rating, Review, Hidden FROM Reviews WHERE ProductID = :productId AND Hidden = 0");
        $stmt->execute([':productId' => $productId]);

        // Fetch the results
        $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Return the reviews as a JSON response
        echo json_encode($reviews);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'No productId received.']);
}
?>
