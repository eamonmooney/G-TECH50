<?php
session_start();

// Check if the user is signed in
if (!isset($_SESSION['userId'])) {
    echo json_encode(["error" => "You must be signed in to become a member."]);
    exit();
}

require_once('connectdb.php');

// Retrieve form data
$firstname = isset($_POST['firstname']) ? trim($_POST['firstname']) : null;
$lastname = isset($_POST['lastname']) ? trim($_POST['lastname']) : null;
$email = isset($_POST['email']) ? trim($_POST['email']) : null;
$confirmEmail = isset($_POST['confirmEmail']) ? trim($_POST['confirmEmail']) : null;
$cardName = isset($_POST['cardName']) ? trim($_POST['cardName']) : null;
$cardNumber = isset($_POST['cardNumber']) ? trim($_POST['cardNumber']) : null;
$expiry = isset($_POST['expiry']) ? trim($_POST['expiry']) : null;
$cvv = isset($_POST['cvv']) ? trim($_POST['cvv']) : null;

// Ensure the emails match
if ($email !== $confirmEmail) {
    echo json_encode(["error" => "Emails do not match."]);
    exit();
}

// Hash the card number, expiry, and CVV before storing them
$hashedCardNumber = hash('sha256', $cardNumber);
$hashedExpiry = hash('sha256', $expiry);
$hashedCVV = hash('sha256', $cvv);

// Get the UserID from the session
$userID = $_SESSION['userId'];

// Check if the user is already a member
$query = "SELECT * FROM Members WHERE UserID = ?";
$stmt = $db->prepare($query);
$stmt->execute([$userID]);
$member = $stmt->fetch();

if ($member) {
    echo json_encode(["redirect" => "membershipRewards.html"]);
    exit();
}

// Insert the membership data into the Members table
$query = "INSERT INTO Members (UserID, CardName, CardNumber, ExpiryDate, CVV, Points, CurrentRank) 
          VALUES (?, ?, ?, ?, ?, 100, 'G-TECH50 BEGINNER')";

$stmt = $db->prepare($query);
$stmt->execute([$userID, $cardName, $hashedCardNumber, $hashedExpiry, $hashedCVV]);

// Check if the insertion was successful
if ($stmt->rowCount() > 0) {
    echo json_encode(["redirect" => "membershipRewards.html"]);
    exit();
} else {
    echo json_encode(["error" => "There was an error creating your membership. Please try again."]);
    exit();
}
?>
