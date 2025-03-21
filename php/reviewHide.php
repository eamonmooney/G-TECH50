<?php
header('Content-Type: application/json');

// Database connection
require_once('connectdb.php');

try {
    $reviewID = $_POST['reviewID'] ?? null;
    $hidden = isset($_POST['hidden']) ? (int)$_POST['hidden'] : null;

    if ($reviewID === null || $hidden === null) {
        echo json_encode(['error' => 'Review ID or hidden status not provided']);
        exit;
    }

    // Prepare the SQL query to set hidden = true/false
    $stmt = $db->prepare('UPDATE reviews SET hidden = :hidden WHERE ReviewID = :reviewID');

    $success = $stmt->execute([
        ':hidden' => $hidden,
        ':reviewID' => $reviewID
    ]);

    if ($success && $stmt->rowCount() > 0) {
        $action = $hidden ? 'hidden' : 'unhidden';
        echo json_encode(['success' => "Review successfully $action!"]);
    } else {
        echo json_encode(['error' => 'No review found with that ID or no change made.']);
    }

} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
