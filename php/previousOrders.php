<?php
// Previous orders page functionalities
// Created by Eamon Mooney - 230075926
session_start();

//Database connection
require_once('connectdb.php');
try {
    //Collecting information about the currently logged in user
    //$userId = $_SESSION['userId'];
    $userId = 1;

    // Prepare the query to count the orders for the specific user
    $stmt = $db->prepare("SELECT COUNT(*) FROM Orders WHERE UserID = ?");
    $stmt->execute([$userId]);
    $totalOrders = $stmt->fetchColumn();

    // Prepare a query to get all orders made by the specific user
    $stmt = $db->prepare("SELECT OrderID, OrderDate, OrderCost FROM Orders WHERE UserID = ?");
    $stmt->execute([$userId]);

// Fetch all the results
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

$newHtml = "";

// Check if there are any orders
if (count($orders) > 0) {
    foreach ($orders as $order) {
        $newHtml .= "<li>";
        //$newHtml .= "Order ID: " . htmlspecialchars($order['OrderID']) . "<br>";
        $newHtml .= "Order Date: " . htmlspecialchars($order['OrderDate']) . "<br>";
        $newHtml .= "Order Cost: £" . number_format($order['OrderCost'], 2) . "<br>";

        // Prepare a single query to fetch all OrderItems and their product details
        $stmt = $db->prepare("
            SELECT oi.ProductID, oi.Quantity, p.ProductName, p.Returnable, p.Price 
            FROM OrderItem oi
            INNER JOIN products p ON oi.ProductID = p.ProductID
            WHERE oi.OrderID = ?
        ");
        $stmt->execute([$order['OrderID']]);
        $orderItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Output details for each OrderItem
        if (count($orderItems) > 0) {
            $newHtml .= "Order Items:<br><br>";
            foreach ($orderItems as $item) {
                $newHtml .= "Product Name: " . htmlspecialchars($item['ProductName']) . "<br>";
                $newHtml .= "Returnable: " . ($item['Returnable'] ? 'Yes' : 'No') . "<br>";
                $newHtml .= "Price: £" . number_format($item['Price'], 2) . "<br>";
                $newHtml .= "Quantity: " . htmlspecialchars($item['Quantity']) . "<br><br>";
                $newHtml .= "<button>Review Product</button><br><br>";
            }
        } else {
            $newHtml .= "No items found for this order.<br>";
        }

        $newHtml .= "</li>";
    }
} else {
    $newHtml .= "No orders found.<br>";
}

echo $newHtml;

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>