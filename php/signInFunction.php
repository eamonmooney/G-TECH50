<?php
session_start();
//establishes database connection
require_once('connectdb.php');
// checks if user has entered detaiks
if($_SERVER['REQUEST_METHOD'] == "POST")
{
    $Username = $_POST['Username'];
    $Password = $_POST['password'];
    {
        $sql0 = "SELECT * FROM Users WHERE Name = :Username limit 1";
        $stmt0=$db->prepare($sql0);
        $stmt0->execute([':Username' => $Username]);
        $uData = $stmt0->fetch(PDO::FETCH_ASSOC);
        if($uData)
        {
            if(password_verify($Password, $uData['Password']))
            {
                $_SESSION['user_id'] = $uData['UserID'];
                $_SESSION['role_id'] = $uData['RoleID'];
                $_SESSION['username'] = $uData['Username'];
                $_SESSION['email'] = $uData['Email'];
                header("Location: /G-TECH50/index.html");
                die;
            }
        }else
        {
            echo "Incorrect details";
        }
    }
}
?>


            
        

