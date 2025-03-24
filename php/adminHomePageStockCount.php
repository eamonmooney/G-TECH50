<?php
// Ned Goodman - 230019355

session_start();

//Database connection
require_once('connectdb.php');

try {
    // query all products
    $sql0 = "SELECT * FROM products";
    $stmt = $db->prepare($sql0);
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stock = 0;
    if (count($products) > 0) {
        // define stock as total stock of all products
        foreach($products as $product) {
            $stock += $product['Stock'];
        }
    };
    // output total stock to javascript
    echo $stock;

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>