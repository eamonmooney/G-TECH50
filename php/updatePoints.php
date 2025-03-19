<?php
session_start();
require_once('connectdb.php');

$userID = $_SESSION['userId'] ?? null;

if (!$userID) {
    echo json_encode(["error" => "User not logged in"]);
    exit;
}

$query = "SELECT Points FROM Members WHERE UserID = ?";
$stmt = $db->prepare($query);
$stmt->execute([$userID]);
$points = $stmt->fetchColumn();

echo json_encode(["points" => $points]);
?>
