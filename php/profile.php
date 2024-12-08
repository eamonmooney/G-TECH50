<?php
##########################################################################
# Collects Variables from the database to be displayed on the profile page
##########################################################################
# - Eamon Mooney - 230075926
##########################################################################
session_start();

//Database connection
require_once('connectdb.php');

if (!isset($_SESSION['userId'])) {	
    header("Location: signin.html");
    exit(); // This checks to see if the user is in a valid session.
    // If they are not in a valid session, they will be redirected to the login page.
}
try {
    //Collecting information about the currently logged in user
    //$userId = $_SESSION['userId'];
    $userId = 1;

    // Query database to get the username through the userId
    $stmt = $db->prepare("SELECT `name` FROM users WHERE UserID = ? limit 1");
    $stmt->execute([$userId]);
    $username = $stmt->fetchColumn();

    // Prepare the query to count the orders for the specific user
    $stmt = $db->prepare("SELECT COUNT(*) FROM Orders WHERE UserID = ? limit 1");
    $stmt->execute([$userId]);
    $totalOrders = $stmt->fetchColumn();

    // Prepare the query to count the returns for the specific user
    $stmt = $db->prepare("SELECT COUNT(*) FROM `returns` WHERE UserID = ? limit 1");
    $stmt->execute([$userId]);
    $totalReturns = $stmt->fetchColumn();

    // Read the HTML file
    $html = file_get_contents('profile.html');

    var_dump($totalOrders);
    var_dump($username);
    
    //Replace the total orders placeholder
    $html = str_replace('{{ORDERTOTAL}}', $totalOrders, $html);

    //Display username
    $html = str_replace('{{USERNAME}}', $username, $html);

    //Display email
    $html = str_replace('{{USEREMAIL}}', "myemail@mail.com", $html);

    echo $totalOrders;
    echo $username;

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

/*

    CREATE TABLE Users (
            UserID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            RoleID INT NOT NULL,
            Name CHAR(50) NOT NULL,
            Email CHAR(50) UNIQUE NOT NULL,
            Password CHAR(50) NOT NULL,
            FOREIGN KEY (RoleID) REFERENCES Role(RoleID)
        );

    CREATE TABLE Orders (
            OrderID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            UserID INT NOT NULL,
            OrderTypeID CHAR(10) NOT NULL,
            OrderDate STRING NOT NULL,
            OrderCost FLOAT NOT NULL,
            FOREIGN KEY (UserID) REFERENCES Users(UserID),
            FOREIGN KEY (OrderTypeID) REFERENCES OrderTypes(OrderID)
        );

    CREATE TABLE Returns (
            ReturnID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            UserID INT NOT NULL,
            OrderID INT NOT NULL,
            ProductID INT NOT NULL,
            ReturnValue FLOAT NOT NULL,
            ReturnDate DATE NOT NULL,
            FOREIGN KEY (UserID) REFERENCES Users(UserID),
            FOREIGN KEY (OrderID) REFERENCES Orders(OrderID),
            FOREIGN KEY (ProductID) REFERENCES Products(ProductID)
        );

*/
?>