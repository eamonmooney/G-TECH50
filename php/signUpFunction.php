<?php
session_start();
//establishes database connection
require_once('connectdb.php');
// checks if user has entered detaiks
if($_SERVER['REQUEST_METHOD'] == "POST")
{
    //Defines user input as variables
    $RoleID = 1;
    $Name = $_POST['name'];
    $Password = $_POST['password'];
    $Email = $_POST['email'];
    // SQl query to find if email is in use
    $sql0 = "SELECT COUNT(*) FROM Users WHERE email = :email";
    $stmt0 = $db->prepare($sql0);
    $stmt0->execute([':email' => $Email]);
    $res0 = $stmt0->fetchColumn();
    // checks if email is in use, outputting error message if it is
    if ($res0 > 0)
    {
        echo "This email is already in use. Please wait a moment, and you will be returned to the sign up page";
        sleep(5);
        header("Location: signup.html");
    // checks if the user has left empty boxes
    }else
    {
        $sql1 = "INSERT INTO Users (RoleID, Name, Email, Password) VALUES (:RoleID, :Name, :Email, :Password)";
        $insertstmt1=$db->prepare($sql1);
        $insertstmt1->execute([
            ':RoleID' => $RoleID,
            ':Name' => $Name, 
			':Email' => $Email, 
			':Password' => password_hash($Password, PASSWORD_BCRYPT)
        ]);
        header("Location: signin.html");
        die;
    }
}
?>


            
        

