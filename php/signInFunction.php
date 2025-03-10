<?php
// Created by Ned Goodman - 230019355
// Remember me functionality by Safa Riasat - 230078145
session_start();
//establishes database connection
require_once('connectdb.php');
// checks if user has entered details
if ($_SERVER['REQUEST_METHOD'] == "POST") {

    // defines variables from user input
    $Username = $_POST['Username'];
    $Password = $_POST['password'];

    // check if remember me was checked in form
    $rememberMe = isset($_POST['rememberMe']); {

        // prepares sql statement to prevent sql injection
        $sql0 = "SELECT * FROM Users WHERE Name = :Username limit 1";
        $stmt0 = $db->prepare($sql0);

        // executes sql query replacing placeholder with user input
        $stmt0->execute([':Username' => $Username]);

        // fetches user data from database as an array indexed by column name
        $uData = $stmt0->fetch(PDO::FETCH_ASSOC);

        // checkes in user data was found
        if ($uData) {
            // verifies password input with hash in database
            // if(password_verify($Password, $uData['Password']))
            // {
            // declare session variables
            $_SESSION['userId'] = $uData['UserID'];
            $_SESSION['roleId'] = $uData['RoleID'];
            $_SESSION['username'] = $uData['Name'];
            $_SESSION['email'] = $uData['Email'];


            // Remember me functionality

            // If the remmeber me session variable is valid (if the user ticked remmeber me)
            if ($rememberMe) {
                // Create a token of length 32
                // Using bin2hex turns these into a 64character hex string
                // Using random_bytes securely generates 32 random bytes - better than rand() because its more secure
                $token = bin2hex(random_bytes(32));

                // Create an expiry date of today + 30 days
                // seconds * minutes * hours * days
                $expiry = time() + (60 * 60 * 24 * 30);

                // Store token in database
                // Create SQL statement to execute
                $rememberSQL = "UPDATE Users SET rememberToken = :token, tokenExpiry = :expiry WHERE UserID = :userId";

                // Prepare this
                $rememberStmt = $db->prepare($rememberSQL);
                // Execute this with the variables
                $rememberStmt->execute(([
                    ':token' => $token,
                    ':expiry' => date('d-m-Y H:i:s', $expiry),
                    ':userId' => $uData['UserID']
                ]));

                // Set cookie with the token -HTTP and Secure
                setcookie("rememberMe", $token, $expiry, "/", "", true, true); 
            }


            // change page to index page
            header("Location: /G-TECH50/index.html");
            die;
            //}
        } else {
            // output error message as password or username doesnt match
            echo "Incorrect details";
        }
    }

    

}
