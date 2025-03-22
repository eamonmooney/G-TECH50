<?php
// Created by Ned Goodman - 230019355
// Cookie destruction by Safa Riasat - 230078145
session_start();
// establish database connection
require_once('connectdb.php');

// check if logout button has been pressed
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // If there's a userID sessionm variable
    if (isset($_SESSION['userId'])) {
        // Set the session variable as a variable
        $userId = $_SESSION['userId'];

        // Output to user for error decoding
        echo "Logging out UserID: " . $userId;

        // Go into SQL and remove the tokens
        // Create SQL statement to execute - set token aspects to null
        $rememberSQL = "UPDATE Users SET rememberToken = NULL, tokenExpiry = NULL WHERE UserID = :userId";

        // Prepare this
        $rememberStmt = $db->prepare($rememberSQL);

        // If we can successfully do this statement
        if ($rememberStmt->execute([':userId' => $userId])) {
            // Print its a success (for debugging)
            echo "Token successfully removed!";
        // Otherwise print error info for not being able to change the database
        } else {
            echo "Error updating token: ";
            print_r($rememberStmt->errorInfo());
        }
    // If there isnt a user ID, print error for this
    } else {
        echo "UserID not found in session.";
    }

    // Clear the rememberme cookie by setting the cookie to an hour ago, which makes the browser delete it
    setcookie("rememberMe", "", time() - 3600, "/", "", true, true);

    // remove session variables
    session_unset();
    // Destroy the session file and invalidate session ID
    session_destroy();

    // change user to index page
    header("Location: /G-TECH50/index.html");

    exit();
}
