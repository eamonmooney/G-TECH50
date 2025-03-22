<?php

session_start();
require_once('connectdb.php');

if (!isset($_SESSION['userId'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

$userID = $_SESSION['userId'];

$orderQuery = "SELECT COUNT(*) AS purchaseCount FROM Orders WHERE UserID = ?";
$stmt = $db->prepare($orderQuery);
$stmt->execute([$userID]);
$orderResult = $stmt->fetch();
$purchases = $orderResult['purchaseCount'];

$query = "SELECT Points, CurrentRank FROM Members WHERE UserID = ?";
$stmt = $db->prepare($query);
$stmt->execute([$userID]);
$data = $stmt->fetch();

$points = $data['Points'];
$currentRank = $data['CurrentRank'];

function getRank($purchases) {
    if ($purchases >= 20) {
        return ['rank' => 'G-TECH50 ULTIMATE', 'bonus' => 300];
    } elseif ($purchases >= 15) {
        return ['rank' => 'G-TECH50 PRO', 'bonus' => 150];
    } elseif ($purchases >= 5) {
        return ['rank' => 'G-TECH50 INTERMEDIATE', 'bonus' => 50];
    } else {
        return ['rank' => 'G-TECH50 BEGINNER', 'bonus' => 0];
    }
}

$newRankData = getRank($purchases);
$newRank = $newRankData['rank'];
$bonusPoints = $newRankData['bonus'];

// Only add points if the rank changes
if ($newRank !== $currentRank) {
    $points += $bonusPoints;

    // Update points and current rank
    $updateQuery = "UPDATE Members 
                    SET Points = ?, CurrentRank = ? 
                    WHERE UserID = ?";
    $updateStmt = $db->prepare($updateQuery);
    $updateStmt->execute([$points, $newRank, $userID]);
}

// Return the updated data
echo json_encode([
    "purchases" => $purchases,
    "points" => $points,
    "rank" => $newRank
]);

exit();

?>