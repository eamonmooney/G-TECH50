<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

/* Checkout functionality.
   By Sahil Awan (230073302).
   Improved for better maintainability. */

/*  To be able to checkout successfully, we need to be able to do a few things...
    One: make sure we have enough stock for the order.
    Two: create the order and insert a record into the database.
    Three: validate whether the order has gone through or not. */
   
    session_start();
    require_once('connectdb.php');
    
    header('Content-Type: application/json');
    
    // Validate if product data is sent
    if (!isset($_POST['productID']) || !isset($_POST['price']) || !isset($_POST['quantity'])) {
        echo json_encode(["success" => false, "message" => "Invalid product data."]);
        exit();
    }
    
    $productID = $_POST['productID'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    
    // Initialize basket if not set
    if (!isset($_SESSION['basket'])) {
        $_SESSION['basket'] = [];
    }
    
    // Add product to basket
    $_SESSION['basket'][] = [
        "ProductID" => $productID,
        "Price" => $price,
        "Quantity" => $quantity
    ];
    
    error_log(print_r($_SESSION['basket'], true)); // Debugging log
    
    echo json_encode(["success" => true, "message" => "Product added to basket."]);

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate and sanitize form inputs
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $name = filter_var($_POST['name'], FILTER_SANITIZE_SPECIAL_CHARS);
    $surname = filter_var($_POST['surname'], FILTER_SANITIZE_SPECIAL_CHARS);
    $address = filter_var($_POST['address'], FILTER_SANITIZE_SPECIAL_CHARS);
    
    $cardName = filter_var($_POST['card-name'], FILTER_SANITIZE_SPECIAL_CHARS);
    $cardNumber = filter_var($_POST['card-number'], FILTER_SANITIZE_NUMBER_INT);
    $expiry = filter_var($_POST['expiry'], FILTER_SANITIZE_SPECIAL_CHARS);
    $cvv = filter_var($_POST['cvv'], FILTER_SANITIZE_NUMBER_INT);


    // Basic validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["success" => false, "message" => "Invalid email format."]);
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

        // Return success response
        echo json_encode(["success" => true, "message" => "success.html"]);
    } catch (PDOException $ex) {
        echo json_encode(["success" => false, "message" => "Database error: " . $ex->getMessage()]);
    }
}
?>
