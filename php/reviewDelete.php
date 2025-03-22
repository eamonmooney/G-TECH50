<?php
// Deletes the currently selected review in review-details.html - Eamon Mooney - 230075926
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
    $stmt = $db->prepare('DELETE FROM reviews WHERE ReviewID = :reviewID');
    $stmt->execute(['reviewID' => $reviewID]);

    if ($stmt->execute(['reviewID' => $reviewID])) {
        echo json_encode("Review deleted successfully!");
    } else {
        echo json_encode("Failed to delete review.");
    }
} catch (PDOException $e) {
    // Handle any errors in connecting/querying the database
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>