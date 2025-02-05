<?php
session_start();
//establishes database connection
require_once('connectdb.php');
// checks if user has entered details
if($_SERVER['REQUEST_METHOD'] == "POST")
{
    // defines variables from user input
    $Username = $_POST['Username'];
    $Password = $_POST['password'];
    {
        // prepares sql statement to prevent sql injection
        $sql0 = "SELECT * FROM Users WHERE Name = :Username limit 1";
        $stmt0=$db->prepare($sql0);
        // executes sql query replacing placeholder with user input
        $stmt0->execute([':Username' => $Username]);
        // fetches user data from database as an array indexed by column name
        $uData = $stmt0->fetch(PDO::FETCH_ASSOC);
        // checkes in user data was found
        if($uData)
        {
            // verifies password input with hash in database
            // if(password_verify($Password, $uData['Password']))
            // {
                // declare session variables
                $_SESSION['userId'] = $uData['UserID'];
                $_SESSION['roleId'] = $uData['RoleID'];
                $_SESSION['username'] = $uData['Name'];
                $_SESSION['email'] = $uData['Email'];
                // change page to index page
                header("Location: /G-TECH50/index.html");
                die;
            //}
        }else
        {
            // output error message as password or username doesnt match
            echo "Incorrect details";
        }
    }
}
?>


            
        

