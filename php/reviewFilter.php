<?php
// Created by Eamon Mooney - 230075926
// Collects all review information from the database to list them in a table on customer-review.html

session_start();

//Database connection
require_once('connectdb.php');

try {

    // Prepare a query to get all productIDs and their names
    $stmt = $db->prepare("SELECT ProductID, ProductName FROM products");
    $stmt->execute();

    // Fetch all the results
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $newHtml = "";

    $newHtml .= "<option value=''>Filter by Product ID</option>";
    //check if there are any products
    if (count($products) > 0) {
        foreach ($products as $product) {
            //Display each product as an option
            $newHtml .= '<option value="' . htmlspecialchars($product['ProductID']) . '">' . htmlspecialchars($product['ProductID']) . '</option>';
        }
    } else {
        //Displays that no products are found in the event of no products
        $newHtml .= "<option value=''>No products!</option>";
    }

    //Outputs the new table to the html
    echo $newHtml;

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>