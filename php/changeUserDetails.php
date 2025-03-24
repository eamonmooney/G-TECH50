<?php
// Created by Ned Goodman - 230019355
session_start();
//establishes database connection
require_once('connectdb.php');

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $sql0 = "SELECT * FROM Users WHERE Name = :Username limit 1";
    $stmt0=$db->prepare($sql0);
    // executes sql query replacing placeholder with user input
    $stmt0->execute([':Username' => $_SESSION['username']]);
    // fetches user data from database as an array indexed by column name
    $uData = $stmt0->fetch(PDO::FETCH_ASSOC);
    // defines user input as variables
    $Username = $_POST['username'];
    $Email = $_POST['email'];
    $Password = $_POST['password'];
    // SQl query to find if email is in use
    $sql1 = "SELECT COUNT(*) FROM Users WHERE email = :email";
    // prepare sql statement in database to prevent sql injection
    $stmt1 = $db->prepare($sql0);
    // execute sql statement replacing placeholder with user email
    $stmt1->execute([':email' => $Email]);
    $res1 = $stmt1->fetchColumn();
    // checks if inputted access key matches any in the database
    if ($res1){
        echo "<p>Email is already in use</p>";
        die;
    } else{
        // SQl query to find if username is in use
        $sql5 = "SELECT COUNT(*) FROM Users WHERE Name = :username";
        // prepare sql statement in database to prevent sql injection
        $stmt5 = $db->prepare($sql5);
        // execute sql statement replacing placeholder with user name
        $stmt5->execute([':username' => $Name]);
        $res2 = $stmt5->fetchColumn();
        // checks if inputted access key matches any in the database
        if ($res2){
            echo "<p>Username is already in use</p>";
            die;
        } else{
            // sql statements for replacing username, email and password respectively
            $sql2 = "UPDATE Users SET Name = REPLACE(Name, :oldUsername, :newUsername) WHERE Name = :oldUsername";
            $replacestmt2 = $db->prepare($sql2);
            $replacestmt2->execute([':oldUsername'=> $uData['Name'],
                ':newUsername' => $Username]);
            $sql3 = "UPDATE Users SET Email = REPLACE(Email, :oldEmail, :newEmail) WHERE Email = :oldEmail";
            $replacestmt3 = $db->prepare($sql3);
            $replacestmt3->execute([':oldEmail'=> $uData['Email'],
                ':newEmail' => $Email]);
            $sql4 = "UPDATE Users SET Password = REPLACE(Password, :oldPassword, :newPassword) WHERE Password = :oldPassword";
            $replacestmt4 = $db->prepare($sql4);
            $replacestmt4->execute([':oldPassword'=> $uData['Password'],
                ':newPassword' => password_hash($Password, PASSWORD_BCRYPT)]);
            // sql statement for finding updated user details
            $sql6 = "SELECT * FROM Users WHERE Name = :Username limit 1";
            $stmt6=$db->prepare($sql6);
            $stmt6->execute([':Username' => $Username]);
            // fetches new user data from database as an array indexed by column name
            $newUData = $stmt1->fetch(PDO::FETCH_ASSOC);
            // redefine session variables to match changes
            $_SESSION['userId'] = $newUData['UserID'];
            $_SESSION['roleId'] = $newUData['RoleID'];
            $_SESSION['username'] = $newUData['Name'];
            $_SESSION['email'] = $newUData['Email'];
            // relocate to home
            header("Location: ../index.html");
            die;
        }
    }
}
?>