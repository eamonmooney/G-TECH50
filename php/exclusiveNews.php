<?php
session_start();
header('Content-Type: application/json');

// Step 1: Check if user is signed in

$userID = $_SESSION['userId'];

if (!$userID) {
    echo json_encode(["redirect" => "signin.html"]);
    exit();
}

// Connect to the database
require_once('connectdb.php');

// Check if the user is already a member
$query = "SELECT * FROM Members WHERE UserID = ?";
$stmt = $db->prepare($query);
$stmt->execute([$userID]);
$member = $stmt->fetch();

if (!$member) {
    echo json_encode(["redirect" => "membershipSignUp.html"]);
    exit();
} 
?>