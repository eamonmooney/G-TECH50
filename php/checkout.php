<!-- Checkout Functionality. Created by Sahil Awan. 230073302. -->

<?php
/* To be able to checkout successfully, we need to be able to do a few things...
   One: make sure we have enough stock for the order.
   Two: create the order and insert a record into the database.
   Three: validate whether the order has gone through or not. */

    session_start(); // Session is resumed to allow us to use current variables.
    require_once ('connectdb.php'); // Establishes a connection to the database.

    if (!isset($_SESSION['userId'])) {	
		header("Location: index.html");
		exit(); // This checks to see if the user is in a valid session.
        // If they are not in a valid session, they will be redirected to the login page.
	}

    $userID = $_SESSION('userId'); // Sets the userID from the session.
    $cart = $_SESSION['basketItems']; // Get basket items from the session.
    $total = 0; // Initialising the total cost of the order.


    // Requirement 1.
    function stockCheck($cart, $conn) {
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
    
        if (stockCheck($cart, $conn)) {
            if (createOrder($userID, $cart, $total, $conn)) { /* If there is sufficient stock and the order has been created, 
                a message will be displayed to indicate that the order has been created. */
                echo "Order placed successfully!";
                unset($_SESSION['basketItems']); // The session will be reset and the user will be redirected to the login page for the time being.
                header("Location: login.html");
            } else {
                echo "Failed to create order."; 
            }
        }
    }

    // Requirement 3 can only be done if we integrate a payment system which can be done later.
?>