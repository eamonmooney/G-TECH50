<?php

// Creating initial database and tables within 
// Completed by Sahil (230073302), Table Insertion by Eamon (230075926), edited by Safa (230078145) 
// I have tested and ran this in phpMyAdmin and everything seems to be working fine. Sahil.

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
            Email VARCHAR(73) UNIQUE NOT NULL,
            Password VARCHAR(100) NOT NULL,
            rememberToken VARCHAR(64) NULL,
            tokenExpiry DATETIME NULL,
            FOREIGN KEY (RoleID) REFERENCES Role(RoleID)
        );

        CREATE TABLE IF NOT EXISTS OrderTypes (
            OrderTypeID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            OrderType VARCHAR(50) NOT NULL
        );

        CREATE TABLE IF NOT EXISTS ProductType (
            ProductTypeID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            ProductType VARCHAR(50) NOT NULL
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

        CREATE TABLE IF NOT EXISTS GuestInfo (
            GuestID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            Name VARCHAR(50) NOT NULL,
            Email VARCHAR(73) NOT NULL
        );

        CREATE TABLE IF NOT EXISTS Orders (
            OrderID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            UserID INT,
            GuestID INT,
            OrderTypeID INT NOT NULL,
            OrderDate VARCHAR(10) NOT NULL,
            OrderCost FLOAT NOT NULL,
            Address VARCHAR(50) NOT NULL,
            FOREIGN KEY (UserID) REFERENCES Users(UserID),
            FOREIGN KEY (GuestID) REFERENCES GuestInfo(GuestID),
            FOREIGN KEY (OrderTypeID) REFERENCES OrderTypes(OrderTypeID)
        );


        CREATE TABLE IF NOT EXISTS GuestOrders (
            GuestID INT NOT NULL,
            OrderID INT NOT NULL,
            PRIMARY KEY (GuestID, OrderID),
            FOREIGN KEY (GuestID) REFERENCES GuestInfo(GuestID) ON DELETE CASCADE,
            FOREIGN KEY (OrderID) REFERENCES Orders(OrderID) ON DELETE CASCADE
        );



        CREATE TABLE IF NOT EXISTS OrderItem (
            OrderID INT NOT NULL,
            ProductID INT NOT NULL,
            Quantity INT NOT NULL,
            PRIMARY KEY (OrderID, ProductID),
            FOREIGN KEY (OrderID) REFERENCES Orders(OrderID) ON DELETE CASCADE,
            FOREIGN KEY (ProductID) REFERENCES Products(ProductID)
        );

        CREATE TABLE IF NOT EXISTS TicketTypes (
            TicketTypeID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            TicketType VARCHAR(50) NOT NULL
        );

        CREATE TABLE IF NOT EXISTS SupportTickets (
            TicketID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            TicketTypeID INT NOT NULL,
            UserID INT,
            Telephone VARCHAR(11) NOT NULL,
            TicketDate DATE NOT NULL,
            Closed BOOLEAN NOT NULL DEFAULT FALSE,
            TicketContent VARCHAR(100) NOT NULL,
            FOREIGN KEY (TicketTypeID) REFERENCES TicketTypes(TicketTypeID),
            FOREIGN KEY (UserID) REFERENCES Users(UserID)
        );

        CREATE TABLE IF NOT EXISTS Returns (
            ReturnID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            UserID INT NOT NULL,
            OrderID INT NOT NULL,
            ProductID INT NOT NULL,
            ReturnValue FLOAT NOT NULL,
            ReturnDate DATE NOT NULL,
            Reason VARCHAR(255) NOT NULL,
            Status ENUM('Pending', 'Approved', 'Rejected') DEFAULT 'Pending',
            CreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (UserID) REFERENCES Users(UserID),
            FOREIGN KEY (OrderID) REFERENCES Orders(OrderID),
            FOREIGN KEY (ProductID) REFERENCES Products(ProductID)
        );

        CREATE TABLE IF NOT EXISTS Reviews (
            ReviewID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            UserID INT NOT NULL ,
            ProductID INT NOT NULL,
            Rating INT CHECK (Rating BETWEEN 0 AND 10),
            Review VARCHAR(100),
            FOREIGN KEY (UserID) REFERENCES Users(UserID),
            FOREIGN KEY (ProductID) REFERENCES Products(ProductID)
        );

        CREATE TABLE IF NOT EXISTS AccessKeys (
            RoleID INT NOT NULL PRIMARY KEY,
            AccessKey VARCHAR(5) NOT NULL UNIQUE,
            FOREIGN KEY (RoleID) REFERENCES Role(RoleID)
        );

        CREATE TABLE IF NOT EXISTS Members (
            MemberID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            UserID INT NOT NULL,
            CardName VARCHAR(50) NOT NULL,
            CardNumber INT NOT NULL,
            ExpiryDate VARCHAR(5) NOT NULL,
            CVV VARCHAR(3) NOT NULL,
            Points INT NOT NULL DEFAULT 100,
            CurrentRank VARCHAR(30) NOT NULL DEFAULT 'G-TECH50 BEGINNER',
            FOREIGN KEY (UserID) REFERENCES Users(UserID)
        );
    ";

    $db->exec($sql);


    //Check if product types already exists
    $checkProductType = $db->query("SELECT COUNT(*) FROM ProductType")->fetchColumn();
    //If doesn't populate
    if ($checkProductType == 0) {
        //Using a direct executive statement rather than prepared statement because it's not user data - will have to use prepared when admins adding new products
        $db->exec("
            INSERT INTO ProductType (ProductType) VALUES 
            ('Mouse'), 
            ('Monitor'),
            ('Headphone'),
            ('Keyboard'),
            ('Mousepad')
        ");
    }


    // Check if products already exists
    $checkProducts = $db->query("SELECT COUNT(*) FROM Products")->fetchColumn();
    //If doesn't populate
    if ($checkProducts == 0) {
        $db->exec("
            INSERT INTO Products (ProductTypeID, ProductName, Returnable, Stock, Price) VALUES
            (1, 'SUGARGLIDE SPARK', 1, 100, 34.99),
            (1, 'COSMIC CURSOR', 1, 100, 49.99),
            (1, 'WOODLAND WANDERER', 1, 100, 42.99),
            (1, 'PIXELWAVE MOUSE', 1, 100, 49.99),
            (1, 'KITTYCLICKS', 1, 100, 49.99),
            
            (2, 'SUGARSCREEN MONITOR', 1, 100, 179.99),
            (2, 'COSMO VIEW CURVED MONITOR', 1, 100, 299.99),
            (2, 'ECO VIEW MONITOR', 1, 100, 189.99),
            (2, 'RETROVIEW LED MONITOR', 1, 100, 169.99),
            (2, 'KITTYVIEW MONITOR', 1, 100, 199.99),
            
            (3, 'BUBBLEGUM BLASTER', 1, 100, 49.99),
            (3, 'STELLAR SOUNDWAVES', 1, 100, 59.99),
            (3, 'WOODLAND HARMONY', 1, 100, 49.99),
            (3, 'RETRO RAINBOW WAVE', 1, 100, 34.99),
            (3, 'WHISKERTUNES', 1, 100, 39.99),
            
            (4, 'CANDYKEYS KEYBOARD', 1, 100, 34.99),
            (4, 'GALAXYTYPE KEYBOARD', 1, 100, 49.99),
            (4, 'FORESTFLOW KEYBOARD', 1, 100, 42.99),
            (4, 'PIXELPULSE KEYBOARD', 1, 100, 49.99),
            (4, 'PURRKEYS KEYBOARD', 1, 100, 49.99),
            
            (5, 'CANDYPOP MAT', 1, 100, 14.99),
            (5, 'NEBULAGLIDE MAT', 1, 100, 18.99),
            (5, 'EVERGREEN MAT', 1, 100, 16.99),
            (5, 'NEONVIBES PAD', 1, 100, 19.99),
            (5, 'PAWSOFT MAT', 1, 100, 15.99),
            
            (4, 'OMEGA KEYBOARD', 1, 100, 99.99),    
            (2, 'OMEGA MONITOR', 1, 100, 329.99),    
            (1, 'OMEGA MOUSE', 1, 100, 64.99);       
        ");
    }

    // Check if roletypes already exists
    $checkRole = $db->query("SELECT COUNT(*) FROM Role")->fetchColumn();
    //If doesn't populate
    if ($checkRole == 0) {
        //Using a direct executive statement rather than prepared statement because it's not user data - will have to use prepared when admins adding new products
        $db->exec("
            INSERT INTO Role (Role) VALUES 
            ('Guest'),
            ('Customer'),
            ('SubAdmin'), 
            ('MidAdmin'), 
            ('SuperAdmin') 
        ");
    }

    // Check if tickettypes already exists
    $checkTicket = $db->query("SELECT COUNT(*) FROM TicketTypes")->fetchColumn();
    //If doesn't populate
    if ($checkTicket == 0) {
        //Using a direct executive statement rather than prepared statement because it's not user data - will have to use prepared when admins adding new products
        $db->exec("
            INSERT INTO TicketTypes (TicketType) VALUES 
            ('Guest'),
            ('User')
        ");
    }

    //Check if order types already exists
    $checkOrderTypes = $db->query("SELECT COUNT(*) FROM OrderTypes")->fetchColumn();
    //If doesn't populate
    if ($checkOrderTypes == 0) {
        //Using a direct executive statement rather than prepared statement because it's not user data - will have to use prepared when admins adding new products
        $db->exec("
            INSERT INTO OrderTypes (OrderType) VALUES 
            ('Admin'), 
            ('Customer'),
            ('Guest')
        ");
    }


    //Check if AccessKeys values already exists
    $checkAccessKeys = $db->query("SELECT COUNT(*) FROM AccessKeys")->fetchColumn();
    //If doesn't populate
    if ($checkAccessKeys == 0) {
        //Using a direct executive statement rather than prepared statement because it's not user data - will have to use prepared when admins adding new products
        $db->exec("
            INSERT INTO AccessKeys (RoleID, AccessKey) VALUES 
            ('3', 'PLACE'), 
            ('4', 'MIGHT'), 
            ('5', 'BRUNT') 
        ");
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$conn = null;
