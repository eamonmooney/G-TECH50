<?php
/* To be able to checkout successfully, we need to be able to do a few things...
   One: make sure we have enough stock for the order.
   Two: create the order and insert a record into the database.
   Three: validate whether the order has gone through or not. */

    require_once ('connectdb.php');  

    // Requirement 1.
    function stockCheck($cart) {
        foreach ($cart as $item) {
            $productID = $item['ProductID'];
            $quantity = $item['Quantity'];
            $query = "SELECT Stock FROM Products WHERE id = $productID";
        } // Getting the quantity of the stock available of the product that the user would like to order.
        $result = $conn->exec($query); // Puts the result of the query into a variable called result.
        $product = $result->fetch_assoc(); // Fetches the next product in the basket as an associative array.

        if ($product['Stock'] < $quantity) {
            return false; /* Checks to see if the stock levels in our inventory can handle the amount of products
            the user has requested to buy. */
        }
    } return true;

    // Requirement 2.
    function createOrder($userID, $cart, $total) {
        $query = "INSERT INTO Orders (UserID, OrderTypeID, OrderDate, OrderCost) VALUES ('$userID', '$orderTypeID', '$orderDate', '$total')";
        $orderID = $conn->insert_id; // Inserting everything from the basket into an order record in the database.
        
        foreach ($cart as $item) {
            $productID = $item['ProductID'];
            $quantity = $item['Quantity']; 
            $conn->$query("INSERT INTO OrderItem (OrderID, ProductID, Quantity) VALUES ('$orderID', '$productID', '$quantity'");
        } // Fetches ProductID and Quantity from the cart and inserts is as an ORDERITEM record.
    }

    // Requirement 3 can only be done if we integrate a payment system. 
?>