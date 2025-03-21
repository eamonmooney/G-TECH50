<?php
// Collects Variables from the database to be displayed on the profile page
// Created by Eamon Mooney - 230075926
session_start();

// Error testing - prints all values in session
// print_r($_SESSION); 
// print_r($_COOKIE); 

//Database connection
require_once('connectdb.php');

// If the user isn't logged in, redirect to the signin page
if ($_SESSION['signedIn'] == False) {    
    header("Location: ../signin.html");
    exit();
}
try {
    //Collecting information about the currently logged in user
    $userId = $_SESSION['userId'];

    // Query database to get the username through the userId
    $stmt = $db->prepare("SELECT `name` FROM users WHERE UserID = ? limit 1");
    $stmt->execute([$userId]);
    $username = $stmt->fetchColumn();

    // Query database to get the email through the userId
    $stmt = $db->prepare("SELECT `email` FROM users WHERE UserID = ? limit 1");
    $stmt->execute([$userId]);
    $email = $stmt->fetchColumn();

    $newHtml = "";

    $newHtml .= "<h2>Account Information</h2>";
    $newHtml .= "<p><strong>Username: $username </strong></p>";
    $newHtml .= "<p><strong>Email: $email </strong></p>";
    echo $newHtml;

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>