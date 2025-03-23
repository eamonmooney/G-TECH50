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
    // sql statements for replacing username, email and password respectively
    $sql0 = "UPDATE Users SET Name = REPLACE(Name, :oldUsername, :newUsername) WHERE Name = :oldUsername";
    $replacestmt0 = $db->prepare($sql0);
    $replacestmt0->execute([':oldUsername'=> $uData['Name'],
        ':newUsername' => $Username]);
    $sql1 = "UPDATE Users SET Email = REPLACE(Email, :oldEmail, :newEmail) WHERE Email = :oldEmail";
    $replacestmt1 = $db->prepare($sql1);
    $replacestmt1->execute([':oldEmail'=> $uData['Email'],
        ':newEmail' => $Email]);
    $sql2 = "UPDATE Users SET Password = REPLACE(Password, :oldPassword, :newPassword) WHERE Password = :oldPassword";
    $replacestmt2 = $db->prepare($sql2);
    $replacestmt2->execute([':oldPassword'=> $uData['Password'],
        ':newPassword' => password_hash($Password, PASSWORD_BCRYPT)]);
    // sql statement for finding updated user details
    $sql1 = "SELECT * FROM Users WHERE Name = :Username limit 1";
    $stmt1=$db->prepare($sql1);
    $stmt1->execute([':Username' => $Username]);
    // fetches new user data from database as an array indexed by column name
    $newUData = $stmt1->fetch(PDO::FETCH_ASSOC);
    // redefine session variables to match changes
    $_SESSION['userId'] = $newUData['UserID'];
    $_SESSION['roleId'] = $newUData['RoleID'];
    $_SESSION['username'] = $newUData['Name'];
    $_SESSION['email'] = $newUData['Email'];
    // relocate to home
    header("Location: /G-TECH50/index.html");
    die;
}
?>