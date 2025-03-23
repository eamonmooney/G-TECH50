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
        // define number of users by iterating over each user
        foreach($users as $user) {
            $numUsers += 1;
        }
    };
    // output total number of users to javascript
    echo $numUsers;

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>