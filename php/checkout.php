<?php
/* Checkout functionality.
   By Sahil Awan (230073302).
   Improved for better maintainability. */

/*  To be able to checkout successfully, we need to be able to do a few things...
    One: make sure we have enough stock for the order.
    Two: create the order and insert a record into the database.
    Three: validate whether the order has gone through or not. */
   
    session_start();
    require_once('connectdb.php');
    
    // Validate if user is logged in
    header('Content-Type: application/json');

    if (!isset($_SESSION['userId'])) {
        echo json_encode(["success" => false, "message" => "User not logged in."]);
        exit();
    }
    
    if (!isset($_SESSION['basket']) || empty($_SESSION['basket'])) {
        echo json_encode(["success" => false, "message" => "Basket is empty."]);
        exit();
    }
    
    // Check if form is submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Validate and sanitize form inputs
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
        $surname = filter_var($_POST['surname'], FILTER_SANITIZE_STRING);
        $address = filter_var($_POST['address'], FILTER_SANITIZE_STRING);
        $cardName = filter_var($_POST['card-name'], FILTER_SANITIZE_STRING);
        $cardNumber = filter_var($_POST['card-number'], FILTER_SANITIZE_STRING);
        $expiry = filter_var($_POST['expiry'], FILTER_SANITIZE_STRING);
        $cvv = filter_var($_POST['cvv'], FILTER_SANITIZE_STRING);
    
        // Basic validation
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Invalid email format.";
            exit();
        }
    
        if (empty($name) || empty($surname) || empty($address) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(["success" => false, "message" => "Invalid form inputs."]);
            exit();
        }
    
        // Store the order details
        $userID = $_SESSION['userId'];
        $cart = $_SESSION['basket'];
        $total = 0;
    
        // Calculate total
        foreach ($cart as $item) {
            $total += $item['Price'] * $item['Quantity'];
        }
    
        // Create order
    try {
        $orderDate = date("Y-m-d");
        $orderTypeID = 1;
        $query = "INSERT INTO Orders (UserID, OrderTypeID, OrderDate, OrderCost, Email, Name, Surname, Address, CardName, CardNumber, Expiry, CVV)
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $db->prepare($query);
        $stmt->execute([$userID, $orderTypeID, $orderDate, $total, $email, $name, $surname, $address, $cardName, $cardNumber, $expiry, $cvv]);
    
        $orderID = $db->lastInsertId(); // Get the last inserted order ID.
    
        // Insert order items into OrderItem table
        foreach ($cart as $item) {
            $productID = $item['ProductID'];
            $quantity = $item['Quantity'];
    
            // Insert each item
            $query = "INSERT INTO OrderItem (OrderID, ProductID, Quantity) VALUES (?, ?, ?)";
            $stmt = $db->prepare($query);
            $stmt->execute([$orderID, $productID, $quantity]);
    
            // Update product stock
            $query = "UPDATE Products SET Stock = Stock - ? WHERE ProductID = ?";
            $stmt = $db->prepare($query);
            $stmt->execute([$quantity, $productID]);
        }
    
        // Clear the basket
        unset($_SESSION['basket']);
    
        // Redirect to the success page
        echo json_encode(["success" => true, "message" => "Order placed successfully!"]);
    } catch (PDOException $ex) {
        echo json_encode(["success" => false, "message" => "Database error: " . $ex->getMessage()]);
    }
        
    ?>
   