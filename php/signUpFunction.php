<?php
session_start();
//establishes database connection
require_once('connectdb.php');
// checks if user has entered detaiks
if($_SERVER['REQUEST_METHOD'] == "POST")
{
    //Defines user input as variables
    $RoleID = 2;
    $Name = $_POST['name'];
    $Password = $_POST['password'];
    $Email = $_POST['email'];
    // SQl query to find if email is in use
    $sql0 = "SELECT COUNT(*) FROM Users WHERE email = :email";
    // prepare sql statement in database to prevent sql injection
    $stmt0 = $db->prepare($sql0);
    // execute sql statement replacing placeholder with user email
    $stmt0->execute([':email' => $Email]);
    // 
    $res0 = $stmt0->fetchColumn();
    // checks if email is in use, outputting error message if it is
    if ($res0 > 0 && !empty($Email))
    {
        echo "This email is already in use";
    // checks if the user has left empty boxes
    }else // enter if email is not in use
    {
        // prepare sql statement to insert inputted user data to database
        $sql1 = "INSERT INTO Users (RoleID, Name, Email, Password) VALUES (:RoleID, :Name, :Email, :Password)";
        $insertstmt1=$db->prepare($sql1);
        // execute sql statement replacing placeholders with user data and hashing password for security
        $insertstmt1->execute([
            ':RoleID' => $RoleID,
            ':Name' => $Name, 
			':Email' => $Email, 
			':Password' => password_hash($Password, PASSWORD_BCRYPT)
        ]);
        // send user to sign in page
        echo "You have successfully created an account";
        header("Location: /G-TECH50/signin.html");
        die;
    }
}
?>


            
        

