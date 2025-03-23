<?php
// Ned Goodman - 230019355

session_start();

//Database connection
require_once('connectdb.php');

try {
    // query all user data from database, sorting byt userid
    $sql0 = "SELECT * FROM Users ORDER BY UserID";
    $stmt = $db->prepare($sql0);
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // define blank html text
    $newHtml = "";
    $admin = "Admin";
    $customer = "Customer";
    //check if there are any users
    if (count($users) > 0) {
        // iterate over every user
        foreach ($users as $user) {
            // initialise table
            $newHtml .= "<tr>";
            // place all user data into table
            $newHtml .= "<td>" . htmlspecialchars($user['UserID']) . "</td>";
            if($user['RoleID'] > 1) {
                $newHtml .= "<td>" . htmlspecialchars($admin) . "</td>";
            } else {if($user['RoleID'] = 1) {
                $newHtml .= "<td>" . htmlspecialchars($customer) . "</td>";
            } else{$newHtml .= "<td>Error</td>";}
        }
            $newHtml .= "<td>" . htmlspecialchars($user['Name']) . "</td>";
            $newHtml .= "<td>" . htmlspecialchars($user['Email']) . "</td>";
            $newHtml .= "</tr>";
        }
    } else {
        // output message for if there are no users
        $newHtml .= "<tr><td colspan='7'>No users found.</td></tr>";
    }

    // outputs the new table to the html
    echo $newHtml;

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>