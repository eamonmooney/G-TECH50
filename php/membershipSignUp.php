<?php
session_start();

header('Content-Type: application/json');

// Doesn't allow the user to access the membership page without signing in. 

if (!isset($_SESSION['userId'])) {
    echo json_encode(["redirect" => true]);
    exit();
}

$response = [
    "redirect" => false,
    "userID" => $_SESSION['userId'],
    "name" => $_SESSION['username'],

];

echo json_encode($response);

?>
