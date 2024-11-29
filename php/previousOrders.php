<?php
##########################################################################
# Previous orders page functionalities
##########################################################################
# - Eamon Mooney - 230075926
##########################################################################
session_start();

//Database connection
require_once('connectdb.php');

//Collecting information about the currently logged in user
$userId = $_SESSION['userId'];

// Prepare the query to count the orders for the specific user
$stmt = $db->prepare("SELECT COUNT(*) FROM Orders WHERE UserID = ?");
$stmt->execute([$userId]);
$totalOrders = $stmt->fetchColumn();

// Prepare a query to get all orders made by the specific user
$stmt = $db->prepare("SELECT OrderID, OrderDate, OrderCost FROM Orders WHERE UserID = ?");
$stmt->execute([$userId]);

// Fetch all the results
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

/*
    Any output is temporary and will be changed once the needed frontend exists.
*/

// Check if there are any orders
if (count($orders) > 0) {
    // Iterate through each order
    foreach ($orders as $order) {
        // Output each order's details
        echo "Order ID: " . $order['OrderID'] . "<br>";
        echo "Order Date: " . $order['OrderDate'] . "<br>";
        echo "Order Cost: $" . number_format($order['OrderCost'], 2) . "<br>";

        $stmt = $db->prepare("SELECT ProductID, Quantity FROM OrderItem WHERE OrderID = ?");
        $stmt->execute([$order['OrderID']]);

        // Output details for each OrderItem
        echo "Order Items:<br>";

        // Fetch and loop through each order item for the current order
        while ($orderItem = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "Product ID: " . $orderItem['ProductID'] . "<br>";
            echo "Quantity: " . $orderItem['Quantity'] . "<br><br>";
        }

        echo "<hr>"; // Separator between orders for readability
    }
} else {
    echo "No orders found.<br>";
}

/*
    CREATE TABLE Orders (
                OrderID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                UserID INT NOT NULL,
                OrderTypeID CHAR(10) NOT NULL,
                OrderDate STRING NOT NULL,
                OrderCost FLOAT NOT NULL,
                FOREIGN KEY (UserID) REFERENCES Users(UserID),
                FOREIGN KEY (OrderTypeID) REFERENCES OrderTypes(OrderID)
            );

    CREATE TABLE OrderItem (
            OrderID INT NOT NULL,
            ProductID INT NOT NULL,
            Quantity INT NOT NULL,
            PRIMARY KEY (OrderID, ProductID),
            FOREIGN KEY (OrderID) REFERENCES Orders(OrderID),
            FOREIGN KEY (ProductID) REFERENCES Products(ProductID)
        );
*/
?>