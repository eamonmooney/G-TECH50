<?php
// Ned Goodman 230019355
session_start();
//establishes database connection
require_once('connectdb.php');
//enters when form is submitted
if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $sql0 = "SELECT * FROM Users WHERE Email = :oldEmail limit 1";
    $stmt0=$db->prepare($sql0);
    // executes sql query replacing placeholder with user input
    $stmt0->execute([':oldEmail' => $_POST['oldEmail']]);
    // fetches user data from database as an array indexed by column name
    $uData = $stmt0->fetch(PDO::FETCH_ASSOC);
    // if fails to fetch user data due to no matching email, output error msg
    if (!$uData) {
        echo "<p>Incorrect email</p>";
        die;
    }else {
        // defines user input as variables
        $Username = $_POST['newUsername'];
        $Email = $_POST['newEmail'];
        $Password = password_hash($_POST['newPassword'], PASSWORD_BCRYPT);
        // checks for if the new email is in use
        $sql9 = "SELECT * FROM Users WHERE Email = :newEmail limit 1";
        $stmt9=$db->prepare($sql9);
        $stmt9->execute([':newEmail' => $Email]);
        $eCheck = $stmt9->fetch(PDO::FETCH_ASSOC);
        if ($eCheck) {
            // outputs error msg if new email is in use
            echo "<p>New email is already in use</p>";
            die;
        }
        // SQl query to find if username is in use
        $sql11 = "SELECT COUNT(*) FROM Users WHERE Name = :username";
        // prepare sql statement in database to prevent sql injection
        $stmt11 = $db->prepare($sql11);
        // execute sql statement replacing placeholder with user name
        $stmt11->execute([':username' => $Username]);
        $res2 = $stmt11->fetchColumn();
        // checks if inputted access key matches any in the database
        if ($res2){
            echo "<p>Username is already in use</p>";
            die;
        }
        // sql statement for replacing username
        $sql0 = "UPDATE Users SET Name = REPLACE(Name, :oldUsername, :newUsername) WHERE Email = :oldEmail";
        $replacestmt0 = $db->prepare($sql0);
        $replacestmt0->execute([':oldUsername'=> $uData['Name'],
            ':newUsername' => $Username,
            ':oldEmail' => $_POST['oldEmail']]);
        // checks if the current admin has higher permissions than the account they're trying to change
        if ($uData['RoleID'] > $_SESSION['roleId']) {
            // outputs error msg
            echo "<p>You don't have sufficient permissions";
            die;
        } else {
            $sql4 = "SELECT * FROM AccessKeys WHERE RoleID = 3";
            $stmt4=$db->prepare($sql4);
            $stmt4->execute();
            $codeData1 = $stmt4->fetch(PDO::FETCH_ASSOC);
            // sets permissions lvl to 3 if the accesskey is corrcet
            if (password_verify($_POST['accessCode'], $codeData1['AccessKey'])) {
                $sql5 = "UPDATE Users SET RoleID = REPLACE(RoleID, :oldRoleID, :newRoleID) WHERE Email = :oldEmail";
                $replacestmt5 = $db->prepare($sql5);
                $replacestmt5->execute([':oldRoleID'=> $uData['RoleID'],
                    ':newRoleID' => 3,
                    ':oldEmail' => $_POST['oldEmail']]);
            }else{
                // sets permissions lvl to 4 if the accesskey is corrcet
                $sql2 = "SELECT * FROM AccessKeys WHERE RoleID = 4";
                $stmt2=$db->prepare($sql2);
                $stmt2->execute();
                $codeData2 = $stmt2->fetch(PDO::FETCH_ASSOC);
                if (password_verify($_POST['accessCode'], $codeData2['AccessKey'])) {
                    $sql6 = "UPDATE Users SET RoleID = REPLACE(RoleID, :oldRoleID, :newRoleID) WHERE Email = :oldEmail";
                    $replacestmt6 = $db->prepare($sql6);
                    $replacestmt6->execute([':oldRoleID'=> $uData['RoleID'],
                        ':newRoleID' => 4,
                        ':oldEmail' => $_POST['oldEmail']]);
                }else{
                    // sets permissions lvl to 5 if the accesskey is corrcet
                    $sql3 = "SELECT * FROM AccessKeys WHERE RoleID = 5";
                    $stmt3=$db->prepare($sql3);
                    $stmt3->execute();
                    $codeData3 = $stmt3->fetch(PDO::FETCH_ASSOC);
                    if (password_verify($_POST['accessCode'], $codeData3['AccessKey'])) {
                        $sql7 = "UPDATE Users SET RoleID = REPLACE(RoleID, :oldRoleID, :newRoleID) WHERE Email = :oldEmail";
                        $replacestmt7 = $db->prepare($sql7);
                        $replacestmt7->execute([':oldRoleID'=> $uData['RoleID'],
                            ':newRoleID' => 5,
                            ':oldEmail' => $_POST['oldEmail']]);
                    } else {
                        // sets permissions lvl to 2 if accesskey is left as null
                        if ($_POST['accessCode'] == "NULL"){
                            $sql8 = "UPDATE Users SET RoleID = REPLACE(RoleID, :oldRoleID, :newRoleID) WHERE Email = :oldEmail";
                            $replacestmt8 = $db->prepare($sql8);
                            $replacestmt8->execute([':oldRoleID'=> $uData['RoleID'],
                                ':newRoleID' => 2,
                                ':oldEmail' => $_POST['oldEmail']]);
                        } else {
                            // output error if accesskey is neither NULL nor correct
                            echo "Invalid access key";
                            die;
                        }
                    }
                }
            }
        }
        // changes password if password is not null (which represents not wanting to change password)
        if ($_POST['newPassword'] != "NULL") {
            $sql10 = "UPDATE Users SET Password = REPLACE(Password, :oldPassword, :newPassword) WHERE Email = :oldEmail";
            $replacestmt10 = $db->prepare($sql10);
            $replacestmt10->execute([':oldEmail'=> $_POST['oldEmail'],
            ':newPassword' => $Password,
            ':oldPassword' => $uData['Password']]);
        }
        // replaces email with new email
        $sql1 = "UPDATE Users SET Email = REPLACE(Email, :oldEmail, :newEmail) WHERE Email = :oldEmail";
        $replacestmt1 = $db->prepare($sql1);
        $replacestmt1->execute([':oldEmail'=> $_POST['oldEmail'],
            ':newEmail' => $Email]);
        // outputs success message
        echo "<p>Details changed successfully</p>";
        die;
    }
}
?>