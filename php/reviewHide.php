<?php
header('Content-Type: application/json');

// Database connection
require_once('connectdb.php');

try {
    $reviewID = $_POST['reviewID'] ?? null;

    if ($reviewID === null) {
        echo json_encode(['error' => 'No review ID provided']);
        exit;
    }

    // Prepare the SQL query to set hidden = true
    $stmt = $db->prepare('UPDATE reviews SET hidden = :hidden WHERE ReviewID = :reviewID');

    // Execute the query with both parameters
    $success = $stmt->execute([
        ':hidden' => true,
        ':reviewID' => $reviewID 
    ]);

    // Check if any rows were affected
    if ($success && $stmt->rowCount() > 0) {
        echo json_encode(['success' => 'Review hidden successfully!']);
    } else {
        echo json_encode(['error' => 'No review found with that ID or it was already hidden.']);
    }

} catch (PDOException $e) {
    // Handle any errors in connecting/querying the database
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
