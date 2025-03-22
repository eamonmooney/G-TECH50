<?php
// Previous orders page functionalities
// Created by Eamon Mooney - 230075926
session_start();

//Database connection
require_once('connectdb.php');
try {
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

    $newHtml = "";

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
                    $newHtml .= "<button onclick='reviewProduct(" . json_encode($item['ProductName']) . ", " . htmlspecialchars($item['ProductID']) . ")'>Review Product</button><br><br>";

                    // Only show return button if the product is returnable - Sahil Awan (230073302)
                    if ($item['Returnable']) {
                        $newHtml .= "
                        <form method='POST' action='php/processReturn.php' style='margin-bottom: 20px;'>
                            <input type='hidden' name='userId' value='" . $userId . "'>
                            <input type='hidden' name='orderId' value='" . $order['OrderID'] . "'>
                            <input type='hidden' name='productId' value='" . $item['ProductID'] . "'>
                    
                            <label for='reason'>Reason for return:</label>
                            <select name='reason' required style='margin-bottom: 15px; padding: 5px;'>
                                <option value='Damaged'>Damaged</option>
                                <option value='Wrong item'>Wrong item</option>
                                <option value='Not as described'>Not as described</option>
                                <option value='Other'>Other</option>
                            </select>
                    
                            <button type='submit' style='margin-top: 10px; padding: 10px 15px;'>Return Product</button>
                        </form>";
                    }
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