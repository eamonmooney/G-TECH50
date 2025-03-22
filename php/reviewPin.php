<?php
// Pins the currently selected review in review-details.html - Eamon Mooney - 230075926
header('Content-Type: application/json');

// Database connection
require_once('connectdb.php');

try {
    // Set the current reviewID and the status of pin
    $reviewID = $_POST['reviewID'] ?? null;
    $pinned = isset($_POST['pinned']) ? (int)$_POST['pinned'] : null;

    if ($reviewID === null || $pinned === null) {
        echo json_encode(['error' => 'Review ID or pin status not provided']);
        exit;
    }

    // Get the associated productID for the review
    $sql = "SELECT productID FROM Reviews WHERE ReviewID = :reviewID LIMIT 1";
    $stmt = $db->prepare($sql);
    $stmt->execute(['reviewID' => $reviewID]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        // Check if the pinned status is 0 (unpin) or 1 (pin)
        if ($pinned === 1) {
            // Assign the pinned review ID to the product
            $sql = "UPDATE Products
                    SET pinnedReviewID = :reviewID
                    WHERE productID = (SELECT productID FROM Reviews WHERE ReviewID = :reviewID LIMIT 1)";
        } else {
            // Unassign the pinned review ID from the product
            $sql = "UPDATE Products
                    SET pinnedReviewID = NULL
                    WHERE productID = (SELECT productID FROM Reviews WHERE ReviewID = :reviewID LIMIT 1)";
        }
        
        // Prepare and execute the query
        $stmt = $db->prepare($sql);
        $stmt->execute(['reviewID' => $reviewID]);        

        // Return a success message
        echo json_encode(['success' => 'Review pinned status updated successfully']);
    } else {
        echo json_encode(['error' => 'Review not found for the given Review ID']);
    }

} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
