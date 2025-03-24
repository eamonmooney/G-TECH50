<?php
// PHP for Adding New Product to DATABASE by SAFA

// Ensure database is connected
require_once('database.php');

// Read and decode incoming JSON data
$data = json_decode(file_get_contents('php://input'), true);


// Check if all required fields are provided in the incoming request
if (isset($data['ProductName'], $data['Price'], $data['Stock'], $data['Category'], $data['Description'], $data['ImageURL'])) {

    // Sanitize the input values
    $productName = htmlspecialchars($data['ProductName']);
    $stock = (int) $data['Stock'];
    $price = (float) $data['Price'];
    $category = htmlspecialchars($data['Category']);
    $description = htmlspecialchars($data['Description']);
    $imageURL = htmlspecialchars($data['ImageURL']);

    // array for category names
    $categories = [
        'Mice' => 1,
        'Monitors' => 2,
        'Headphones' => 3,
        'Keyboards' => 4,
        'Mousepads' => 5
    ];


    // Map category name to ID (default to 0 if not found)
    $categoryName = trim($data['Category']); 
    $category = $categories[$categoryName] ?? 0;

    // Unknown categories error
    if ($category === 0) {
        // Return an error 
        echo json_encode(['error' => 'Invalid category provided.']);
        exit;
    }

    try {
        // SQL query to insert a new product into the products table
        $insertSQL = "INSERT INTO products (ProductTypeID, ProductName, Price , Stock,  Description, ImageURL) 
                      VALUES (:category, :productName, :price, :stock, :description, :imageURL)";

        // Prepare the SQL statement
        $insertSTMT = $db->prepare($insertSQL);

        // Bind parameters to avoid SQL injection
        $insertSTMT->bindParam(':productName', $productName);
        $insertSTMT->bindParam(':price', $price);
        $insertSTMT->bindParam(':stock', $stock);
        $insertSTMT->bindParam(':category', $category);
        $insertSTMT->bindParam(':description', $description);
        $insertSTMT->bindParam(':imageURL', $imageURL);

        // Execute the query
        $insertSTMT->execute();

        // Check if the product was inserted successfully
        if ($insertSTMT->rowCount() > 0) {
            // Success - Return success JSON response
            echo json_encode(["success" => true]);
        } else {
            // Failure - If no row was inserted, return an error
            echo json_encode(["success" => false, "error" => "Failed to add the product."]);
        }
    } catch (PDOException $e) {
        // Handle any errors and return a message
        echo json_encode(["success" => false, "error" => $e->getMessage()]);
    }
} else {
    // If not all required fields are provided, return an error
    echo json_encode(["success" => false, "error" => "Missing required product fields."]);
}
