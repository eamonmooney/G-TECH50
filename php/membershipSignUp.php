<?php
session_start();
header('Content-Type: application/json');

// Step 1: Check if user is signed in
if (!isset($_SESSION['userId'])) {
    header("Location: ../signin.html");
    exit();
}

$userID = $_SESSION['userId'];

// Connect to the database
require_once('connectdb.php');

// Check if the user is already a member
$query = "SELECT * FROM Members WHERE UserID = ?";
$stmt = $db->prepare($query);
$stmt->execute([$userID]);
$member = $stmt->fetch();

if ($member) {
    header("Location: ../membershipRewards.html");
    exit();
} 
exit();
?>