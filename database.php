<?php
$db_host = 'localhost';
$db_name = 'cs2team50_db';
$username = 'cs2team50';

$password = '0uL3mrV0yknvScd';

try {
	$db = new PDO("mysql:dbname=$db_name;host=$db_host", $username, $password); 
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Create Database
    $conn->exec("CREATE DATABASE IF NOT EXISTS $dbname");
    $conn->exec("USE $dbname");

    // Create tables
    $sql = "
        CREATE TABLE Role (
            RoleID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            Role CHAR(50) NOT NULL
        );

        CREATE TABLE Users (
            UserID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            RoleID INT NOT NULL,
            Name CHAR(50) NOT NULL,
            Email CHAR(50) UNIQUE NOT NULL,
            Password CHAR(50) NOT NULL,
            FOREIGN KEY (RoleID) REFERENCES Role(RoleID)
        );

        CREATE TABLE OrderTypes (
            OrderID CHAR(10) NOT NULL PRIMARY KEY,
            OrderType CHAR(50) NOT NULL
        );

        CREATE TABLE ProductType (
            ProductTypeID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            ProductType CHAR(50) NOT NULL
        );

        CREATE TABLE Products (
            ProductID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            ProductTypeID INT NOT NULL,
            Returnable BOOLEAN NOT NULL,
            Stock INT NOT NULL,
            Price FLOAT NOT NULL,
            FOREIGN KEY (ProductTypeID) REFERENCES ProductType(ProductTypeID)
        );

        CREATE TABLE Orders (
            OrderID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            UserID INT NOT NULL,
            OrderTypeID CHAR(10) NOT NULL,
            OrderDate DATE NOT NULL,
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

        CREATE TABLE TicketType (
            TicketTypeID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            TicketType CHAR(50) NOT NULL
        );

        CREATE TABLE SupportTickets (
            TicketID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            TicketTypeID INT NOT NULL,
            UserID INT NOT NULL,
            TicketDate DATE NOT NULL,
            Closed BOOLEAN NOT NULL,
            TicketContent CHAR(100) NOT NULL,
            FOREIGN KEY (TicketTypeID) REFERENCES TicketType(TicketTypeID),
            FOREIGN KEY (UserID) REFERENCES Users(UserID)
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
    ";
    
    $conn->exec($sql);
    echo "Tables created successfully!";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$conn = null;
?>