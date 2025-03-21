<?php
// Fetches all details about a specific review - Eamon Mooney - 230075926
header('Content-Type: application/json');

//Database connection
require_once('connectdb.php');

try {

    $reviewID = $_POST['reviewID'] ?? null;

    if ($reviewID === null) {
        echo json_encode(['error' => 'No review ID provided']);
        exit;
    }

    // Prepare SQL query to get review
    $stmt = $db->prepare('SELECT * FROM reviews WHERE ReviewID = :reviewID');
    $stmt->execute(['reviewID' => $reviewID]);

    $review = $stmt->fetch();

    if ($review) {
        echo json_encode($review);
    } else {
        echo json_encode(['error' => 'Review not found']);
    }
} catch (PDOException $e) {
    // Handle any errors in connecting/querying the database
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>