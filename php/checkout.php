<!-- Checkout Functionality. Created by Sahil Awan. 230073302. -->

<?php
/* To be able to checkout successfully, we need to be able to do a few things...
   One: make sure we have enough stock for the order.
   Two: create the order and insert a record into the database.
   Three: validate whether the order has gone through or not. */

session_start(); // Session is resumed to allow us to use current variables.
require_once('connectdb.php'); // Establishes a connection to the database.

if (!isset($_SESSION['userId'])) {	
    header("Location: index.html");
    exit(); // This checks to see if the user is in a valid session.
    // If they are not in a valid session, they will be redirected to the login page.
}

$userID = $_SESSION['userId']; // Sets the userID from the session.
$cart = $_SESSION['basket']; // Get basket items from the session.
$total = 0; // Initialising the total cost of the order.

// Requirement 1.
function stockCheck($cart, $db) {
    foreach ($cart as $item) {
        $productID = $item['ProductID'];
        $quantity = $item['Quantity'];

        // Getting the quantity of the stock available of the product that the user would like to order.
        $query = "SELECT Stock FROM Products WHERE ProductID = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$productID]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC); // Fetches the stock as an associative array.

        if ($product['Stock'] < $quantity) {
            return false; /* Checks to see if the stock levels in our inventory can handle the amount of products
            the user has requested to buy. */
        }
    }
    return true; // If all products have sufficient stock.
}

// Requirement 2.
function createOrder($userID, $cart, $total, $db) {
    $orderDate = date("Y-m-d"); // Setting the order date.
    $orderTypeID = 1; // Example order type ID.

    // Insert the order into the Orders table.
    $query = "INSERT INTO Orders (UserID, OrderTypeID, OrderDate, OrderCost) VALUES (?, ?, ?, ?)";
    $stmt = $db->prepare($query);
    $stmt->execute([$userID, $orderTypeID, $orderDate, $total]);
    $orderID = $db->lastInsertId(); // Retrieve the last inserted OrderID.

    // Inserting each product from the cart as an OrderItem and updating stock.
    foreach ($cart as $item) {
        $productID = $item['ProductID'];
        $quantity = $item['Quantity'];

        // Insert order item into OrderItem table.
        $query = "INSERT INTO OrderItem (OrderID, ProductID, Quantity) VALUES (?, ?, ?)";
        $stmt = $db->prepare($query);
        $stmt->execute([$orderID, $productID, $quantity]);

        // Update stock for the product.
        $query = "UPDATE Products SET Stock = Stock - ? WHERE ProductID = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$quantity, $productID]);
    }
    return $orderID; // Return the created OrderID.
}

// Checkout process.
if (stockCheck($cart, $db)) { // Check if there is sufficient stock.
    foreach ($cart as $item) {
        $total += $item['Price'] * $item['Quantity']; // Calculate total cost of the order.
    }

    $orderID = createOrder($userID, $cart, $total, $db); // Create the order.
    echo "Order placed successfully! Your order ID is: $orderID<br>";
    unset($_SESSION['basketItems']); // Clear the basket after the order is placed.
    header("Location: success.html"); // Redirect to the success page.
    exit();
} else {
    echo "Insufficient stock for one or more items!"; // Error message if stock is insufficient.
}

?>
