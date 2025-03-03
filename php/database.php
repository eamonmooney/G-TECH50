<!-- Creating initial database and tables within -->
<!-- Completed by Sahil (230073302), edited by Safa, padding by Eamon, edited by Safa -->
<!-- I have tested and ran this in phpMyAdmin and everything seems to be working fine. Sahil. -->

<?php

try {
    require_once('connectdb.php');
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
            Password VARCHAR(50) NOT NULL,
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

        CREATE TABLE IF NOT EXISTS Orders (
            OrderID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            UserID INT NOT NULL,
            OrderTypeID INT NOT NULL,
            OrderDate VARCHAR(10) NOT NULL,
            OrderCost FLOAT NOT NULL,
            FOREIGN KEY (UserID) REFERENCES Users(UserID),
            FOREIGN KEY (OrderTypeID) REFERENCES OrderTypes(OrderTypeID)
        );

        CREATE TABLE IF NOT EXISTS OrderItem (
            OrderID INT NOT NULL,
            ProductID INT NOT NULL,
            Quantity INT NOT NULL,
            PRIMARY KEY (OrderID, ProductID),
            FOREIGN KEY (OrderID) REFERENCES Orders(OrderID),
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
            FOREIGN KEY (UserID) REFERENCES Users(UserID),
            FOREIGN KEY (OrderID) REFERENCES Orders(OrderID),
            FOREIGN KEY (ProductID) REFERENCES Products(ProductID)
        );

        
    ";

    $db->exec($sql);

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
            (5, 'PAWSOFT MAT', 1, 100, 15.99);
        ");
    }

    // Check if roletypes already exists
    $checkRole = $db->query("SELECT COUNT(*) FROM Role")->fetchColumn();
    //If doesn't populate
    if ($checkRole == 0) {
        //Using a direct executive statement rather than prepared statement because it's not user data - will have to use prepared when admins adding new products
        $db->exec("
            INSERT INTO Role (Role) VALUES 
            ('Admin'), 
            ('Customer');
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
            ('Customer');
        ");
    }

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
            ('Mousepad');
        ");
    }




} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$db = null;

?>