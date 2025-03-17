<?php
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

        // Clear session basket
        unset($_SESSION['basket']);
        session_write_close(); // Ensures session is updated

        echo "Order placed successfully!";
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid request!";
}
?>
