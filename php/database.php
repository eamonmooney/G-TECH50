<!-- Creating initial database and tables within -->
<!-- Completed by Sahil, edited by Safa -->

<?php

try {
	// Database connection
    require_once('connectdb.php');
    // Create Database
    $db->exec("CREATE DATABASE IF NOT EXISTS $db_name");
    $db->exec("USE $db_name");

    // Create tables
    $sql = "
        CREATE TABLE IF NOT EXISTS Role (
            RoleID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            Role VARCHAR(50) NOT NULL
        );

        CREATE TABLE IF NOT EXISTS Users (
            UserID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            RoleID INT NOT NULL,
            Name VARCHAR(50) NOT NULL,
            Email VARCHAR(50) UNIQUE NOT NULL,
            Password VARCHAR(50) NOT NULL,
            FOREIGN KEY (RoleID) REFERENCES Role(RoleID)
        );

        CREATE TABLE IF NOT EXISTS OrderTypes (
            OrderID CHAR(10) NOT NULL PRIMARY KEY,
            OrderType CHAR(50) NOT NULL
        );

        CREATE TABLE IF NOT EXISTS ProductType (
            ProductTypeID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            ProductType CHAR(50) NOT NULL
        );

        CREATE TABLE IF NOT EXISTS Products (
            ProductID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            ProductTypeID INT NOT NULL,
            ProductName VARCHAR(50) NOT NULL,
            Returnable BOOLEAN NOT NULL,
            Stock INT NOT NULL,
            Price FLOAT NOT NULL,
            FOREIGN KEY (ProductTypeID) REFERENCES ProductType(ProductTypeID)
        );

        CREATE TABLE IF NOT EXISTS Orders (
            OrderID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            UserID INT NOT NULL,
            OrderTypeID CHAR(10) NOT NULL,
            OrderDate CHAR(10) NOT NULL,
            OrderCost FLOAT NOT NULL,
            FOREIGN KEY (UserID) REFERENCES Users(UserID),
            FOREIGN KEY (OrderTypeID) REFERENCES OrderTypes(OrderID)
        );

        CREATE TABLE IF NOT EXISTS OrderItem (
            OrderID INT NOT NULL,
            ProductID INT NOT NULL,
            Quantity INT NOT NULL,
            PRIMARY KEY (OrderID, ProductID),
            FOREIGN KEY (OrderID) REFERENCES Orders(OrderID),
            FOREIGN KEY (ProductID) REFERENCES Products(ProductID)
        );

        CREATE TABLE IF NOT EXISTS TicketType (
            TicketTypeID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            TicketType VARCHAR(50) NOT NULL
        );

        CREATE TABLE IF NOT EXISTS SupportTickets (
            TicketID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            TicketTypeID INT NOT NULL,
            UserID INT NOT NULL,
            TicketDate DATE NOT NULL,
            Closed BOOLEAN NOT NULL,
            TicketContent VARCHAR(100) NOT NULL,
            FOREIGN KEY (TicketTypeID) REFERENCES TicketType(TicketTypeID),
            FOREIGN KEY (UserID) REFERENCES Users(UserID)
        );

        CREATE TABLE IF NOT EXISTS Returns (
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
    ";
    
    $db->exec($sql);
    echo "Tables created successfully! <br>";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$conn = null;
?>