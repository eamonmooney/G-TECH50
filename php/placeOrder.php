<?php
// Place Order functionality implemented and designed by Sahil Awan (230073302).
// Changes made to support guest Orders by Safa (230078145).
session_start();
require_once('connectdb.php');
// $_SESSION['orderDetails'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'place_order') {
    $userID = isset($_SESSION['userId']) ? $_SESSION['userId'] : 0;
    $basket = isset($_SESSION['basket']) ? $_SESSION['basket'] : [];


    
    // Order details set temp variables
    $fullName = isset($_SESSION['orderDetails']['fullName']) ? $_SESSION['orderDetails']['fullName'] : Null;
    $email = isset($_SESSION['orderDetails']['email']) ? $_SESSION['orderDetails']['email'] : Null;
    $address = isset($_SESSION['orderDetails']['address']) ? $_SESSION['orderDetails']['address'] : Null;
    
    if (empty($basket)) {
        echo "Your basket is empty.";
        exit;
    }

    try {
        $orderDate = date("Y-m-d");

        // Total Order
        $total = 0;
        foreach ($basket as $itemName => $details) {
            $total += $details['price'] * $details['quantity'];
        }

        // OrderTypeID: 2 Is customer, 3 is guest 
        // UserID: 0 is guest, 1 is customer

        // If it's a guest - add to GuestInfo table, then Orders table with guest info
        if ($userID === 0) {
            // Guest Order type
            $orderTypeID = 3;
            // Set UserID to NULL for guests
            $userID = NULL;

            // GUESTINFO table
            // Make a new guest entry
            // Prepare the SQL query to insert into the GuestInfo table
            $GuestInfoquery = "INSERT INTO GuestInfo (Name, Email) VALUES (?, ?)";
            // Prepare the statement
            $GuestInfostmt = $db->prepare(query: $GuestInfoquery);
            // Execute the statement with the provided values
            // Variables from order details session variable
            $GuestInfostmt->execute([$fullName, $email]);
            // Get the last inserted guestID
            $guestID = $db->lastInsertId();

            // ORDERS table 
            // Insert into Orders table as a guest order
            $query = "INSERT INTO Orders (UserID, GuestID, OrderTypeID, OrderDate, OrderCost, Address) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $db->prepare($query);
            $stmt->execute([NULL, $guestID, $orderTypeID, $orderDate, $total, $address]);
            $orderID = $db->lastInsertId();


            // GUESTORDERS
            // Insert into GuestOrders table 
            $query = "INSERT INTO GuestOrders (GuestID, OrderID) VALUES (?, ?)";
            $stmt = $db->prepare($query);
            $stmt->execute([$guestID, $orderID]);

            // Otherwise - if its a customer just add to orders table and do the points system
        } else {
            $orderTypeID = 2; // Customer Order (includes admins)

            // Insert into Orders table
            $query = "INSERT INTO Orders (UserID, OrderTypeID, OrderDate, OrderCost, Address) VALUES (?, ?, ?, ?, ?)";
            $stmt = $db->prepare($query);
            $stmt->execute([$userID, $orderTypeID, $orderDate, $total, $address]);
            $orderID = $db->lastInsertId();

            // Adding points to the user's account when an order is placed. £1 = 1 point.
            $userID = $_SESSION['userId'];  // Make sure this matches the correct session variable
            $points_to_add = (int) $total;  // Convert total to integer (e.g., £36 -> 36 points)

            $query = "UPDATE Members SET Points = Points + ? WHERE UserID = ?";
            $stmt = $db->prepare($query);
            $stmt->bindValue(1, $points_to_add, PDO::PARAM_INT);
            $stmt->bindValue(2, $userID, PDO::PARAM_INT);

            if ($stmt->execute()) {
                echo "Points updated successfully!";
            } else {
                $error = $stmt->errorInfo();
                echo "Error updating points: " . $error[2]; // Fetch the SQL error message
            }
        }

        // Both guests and customers do this

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

        unset($_SESSION['basket']);
        unset($_SESSION['orderDetails']);
    } catch (Exception $e) {  // Properly catch any errors
        echo ("Error processing order: " . $e->getMessage());
    }
} else {  // This should be OUTSIDE the try block
    echo "Invalid request!";
}
