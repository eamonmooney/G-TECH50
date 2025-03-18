<?php
session_start();
require_once('connectdb.php');

if (!isset($_SESSION['userId'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

$userID = $_SESSION['userId'];

$query = "SELECT Points FROM Members WHERE UserID = ?";
$stmt = $db->prepare($query);
$stmt->execute([$userID]);
$points = $stmt->fetchColumn();

if ($points !== false) {
    echo json_encode(['points' => $points]);
} else {
    echo json_encode(['error' => 'Unable to fetch points']);
}
?>
