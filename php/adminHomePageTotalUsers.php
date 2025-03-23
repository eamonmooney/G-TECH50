<?php
// Ned Goodman - 230019355

session_start();

//Database connection
require_once('connectdb.php');

try {
    // query the number of total users
    $sql0 = "SELECT * FROM users";
    $stmt = $db->prepare($sql0);
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $numUsers = 0;
    if (count($users) > 0) {
        // define sales as total number of orders
        foreach($users as $user) {
            $numUsers += 1;
        }
    };
    // output total sales to javascript
    echo $numUsers;

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>