<!-- Return Functionality. Made by Sahil Awan. 230073302.

Requirements for this functionality:
    1. Ensure only logged-in users can request a return.
    2. Validate that the order exists and belongs to the user.
    3. Create a return request record in the database.
    4. Update stock levels after a return is processed.
    5. Provide feedback to the user. (success or failure)

-->

<?php

session_start(); // Starts the session.

require_once('connectdb.php'); // Requires connection to the database.

// Requirement 1: only logged-in users can request a return.

if (!isset($_SESSION['userId'])) {
    header("Location: login.html");
    exit();
} // If the user is not logged in, they are referred back to the login page.

$userID = $_SESSION['userId']; // Gets the user ID.

if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Checks to see if the return form is submitted.
    $orderID = $_POST['orderID'];
    $productID = $_POST['productID'];
    $quantity = $_POST['quantity'];

    try {
        // Validate that the order belongs to the user.
        $orderQuery = $conn->prepare("SELECT * FROM Orders WHERE OrderID = ? AND UserID = ?");
        $orderQuery->execute([$orderID, $userID]);
        $order = $orderQuery->fetch(PDO::FETCH_ASSOC); // Fetches the order that the user would like to return.

        if (!$order) {
            echo "Invalid order."; // Validation.
            exit();
        }

        $returnQuery = $conn->prepare("INSERT INTO Returns (OrderID, ProductID, Quantity, Status) VALUES (?, ?, ?, 'Pending')");
        $returnQuery->execute([$orderID, $productID, $quantity]); // Creating a return record in the returns table and setting the status of the return as 'pending'.

        $stockQuery = $conn->prepare("UPDATE Products SET Stock = Stock + ? WHERE id = ?"); // This will update the inventory of that product and increase it if the return has been accepted.
        $stockQuery->execute([$quantity, $productID]); // Not sure if a return request will require the inventory to be updated but can be discussed. 

        echo "Return request submitted successfully."; // Provides confirmation to the user that their request has been sent successfully. 
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

?>