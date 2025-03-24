<?php
// Ned Goodman - 230019355

session_start();

//Database connection
require_once('connectdb.php');

try {
    // query the number of total orders
    $sql0 = "SELECT * FROM orders";
    $stmt = $db->prepare($sql0);
    $stmt->execute();
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $sales = 0;
    if (count($orders) > 0) {
        // define sales as total number of orders
        foreach($orders as $order) {
            $sales += 1;
        }
    };
    // output total sales to javascript
    echo $sales;

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>