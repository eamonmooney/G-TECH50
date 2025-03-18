<?php
// Place Order functionality implemented and designed by Sahil Awan (230073302).
session_start();
require_once('connectdb.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'place_order') {
    $userID = isset($_SESSION['userId']) ? $_SESSION['userId'] : null;
    $basket = isset($_SESSION['basket']) ? $_SESSION['basket'] : [];

    if (empty($basket)) {
        echo "Your basket is empty.";
        exit;
    }

    try {
        $orderDate = date("Y-m-d");
        $orderTypeID = 1; // Assuming 1 is for normal orders

        // Insert into Orders table
        $query = "INSERT INTO Orders (UserID, OrderTypeID, OrderDate, OrderCost) VALUES (?, ?, ?, ?)";
        $stmt = $db->prepare($query);
        $total = 0;

        foreach ($basket as $itemName => $details) {
            $total += $details['price'] * $details['quantity'];
        }

        $stmt->execute([$userID, $orderTypeID, $orderDate, $total]);
        $orderID = $db->lastInsertId();

        // Insert each product into OrderItem table
        foreach ($basket as $itemName => $details) {
            $quantity = $details['quantity'];

            // Get ProductID
            $stmt = $db->prepare("SELECT ProductID FROM Products WHERE ProductName = ?");
            $stmt->execute([$itemName]);
            $productID = $stmt->fetchColumn();

            if (!$productID) {
                throw new Exception("Error: Product '$itemName' not found in database.");
            }

            // Insert into OrderItem table
            $stmt = $db->prepare("INSERT INTO OrderItem (OrderID, ProductID, Quantity) VALUES (?, ?, ?)");
            $stmt->execute([$orderID, $productID, $quantity]);

            // Update product stock
            $stmt = $db->prepare("UPDATE Products SET Stock = Stock - ? WHERE ProductID = ?");
            $stmt->execute([$quantity, $productID]);
        }

        // Adding points to the user's account when an order is placed. £1 = 1 point.
        $userID = $_SESSION['userId'];  // Make sure this matches the correct session variable
        $points_to_add = (int) $total;  // Convert total to integer (e.g., £36 -> 36 points)

        $query = "UPDATE Members SET Points = Points + ? WHERE UserID = ?";
        $stmt = $db->prepare($query);
        $stmt->bindValue(1, $points_to_add, PDO::PARAM_INT);
        $stmt->bindValue(2, $userID, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo json_encode("Order completed and points updated successfully!");
        } else {
            $error = $stmt->errorInfo();
            echo json_encode("Error updating points: " . $error[2]); // Fetch the SQL error message
        }

    } catch (Exception $e) {  // Properly catch any errors
        echo json_encode("Error processing order: " . $e->getMessage());
    }

    unset($_SESSION['basket']);
    
} else {  // This should be OUTSIDE the try block
    echo "Invalid request!";
}
?>
