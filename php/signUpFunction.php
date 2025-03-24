<?php
// Created by Ned Goodman - 230019355
// Input sanitization by Elisha Kolade - 220383798
session_start();
//establishes database connection
require_once('connectdb.php');
// checks if user has entered detaiks
if($_SERVER['REQUEST_METHOD'] == "POST")
{
    $errors = [];

    // Sanitize inputs
    $Name = trim($_POST['name']);
    $Email = trim($_POST['email']);
    $Password = $_POST['password'];
    $ConfirmPassword = $_POST['confirmpassword'];

    // Validation
    if (empty($Name) || strlen($Name) > 50) {
        $errors[] = "Username is required and must be at most 50 characters long.";
    }

    if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        die("Valid email is required.");
    }

    if (strlen($_POST["password"]) < 8) {
        die("Password must be at least 8 characters.");
    }

    if (!preg_match("/[a-z]/i", $_POST["password"])) {
        die("Password must contain at least one letter.");
    }

    if (!preg_match("/[0-9]/", $_POST["password"])) {
        die("Password must contain at least one number.");
    }

    if ($_POST["password"] !== $_POST["confirmpassword"]) {
        die("Passwords do not match.");
    }
    //Defines user input as variables
    $RoleID = 1;
    $Name = $_POST['name'];
    $Password = $_POST['password'];
    $Email = $_POST['email'];
    // SQl query to find if email is in use
    $sql0 = "SELECT COUNT(*) FROM Users WHERE email = :email";
    // prepare sql statement in database to prevent sql injection
    $stmt0 = $db->prepare($sql0);
    // execute sql statement replacing placeholder with user email
    $stmt0->execute([':email' => $Email]);
    $res0 = $stmt0->fetchColumn();
    // checks if inputted access key matches any in the database
    if ($res0){
        echo "<p>Email is already in use</p>";
        die;
    } else{
        $sql1 = "SELECT * FROM AccessKeys WHERE RoleID = 2";
        $stmt1=$db->prepare($sql1);
        $stmt1->execute();
        $codeData1 = $stmt1->fetch(PDO::FETCH_ASSOC);
        if (password_verify($_POST['accesscode'], $codeData1['AccessKey'])) {
            $RoleID = 2;
        }
        $sql2 = "SELECT * FROM AccessKeys WHERE RoleID = 3";
        $stmt2=$db->prepare($sql2);
        $stmt2->execute();
        $codeData2 = $stmt2->fetch(PDO::FETCH_ASSOC);
        if (password_verify($_POST['accesscode'], $codeData2['AccessKey'])) {
            $RoleID = 3;
        }
        $sql3 = "SELECT * FROM AccessKeys WHERE RoleID = 4";
        $stmt3=$db->prepare($sql3);
        $stmt3->execute();
        $codeData3 = $stmt3->fetch(PDO::FETCH_ASSOC);
        if (password_verify($_POST['accesscode'], $codeData3['AccessKey'])) {
            $RoleID = 4;
        }
    }
    // prepare sql statement to insert inputted user data to database
    $sql4 = "INSERT INTO Users (RoleID, Name, Email, Password) VALUES (:RoleID, :Name, :Email, :Password)";
    $insertstmt4=$db->prepare($sql4);
    // execute sql statement replacing placeholders with user data and hashing password for security
    $insertstmt4->execute([
        ':RoleID' => $RoleID,
        ':Name' => $Name, 
        ':Email' => $Email, 
        ':Password' => password_hash($Password, PASSWORD_BCRYPT)
    ]);
    // send user to sign in page
    header("Location: ../signin.html");
    die;
}
?>
