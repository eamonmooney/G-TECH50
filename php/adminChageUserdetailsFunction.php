<?php
// Created by Ned Goodman - 230019355
session_start();
//establishes database connection
require_once('connectdb.php');

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $sql0 = "SELECT * FROM Users WHERE Email = :oldEmail limit 1";
    $stmt0=$db->prepare($sql0);
    // executes sql query replacing placeholder with user input
    $stmt0->execute([':oldEmail' => $_POST['oldEmail']]);
    // fetches user data from database as an array indexed by column name
    $uData = $stmt0->fetch(PDO::FETCH_ASSOC);
    // defines user input as variables
    $Username = $_POST['newUsername'];
    $Email = $_POST['newEmail'];
    // sql statements for replacing username, email and password respectively
    $sql0 = "UPDATE Users SET Name = REPLACE(Name, :oldUsername, :newUsername) WHERE Email = :oldEmail";
    $replacestmt0 = $db->prepare($sql0);
    $replacestmt0->execute([':oldUsername'=> $uData['Name'],
        ':newUsername' => $Username,
        ':oldEmail' => $_POST['oldEmail']]);
    $sql4 = "SELECT * FROM AccessKeys WHERE RoleID = 2";
    $stmt4=$db->prepare($sql4);
    $stmt4->execute();
    $codeData1 = $stmt4->fetch(PDO::FETCH_ASSOC);
    if (password_verify($_POST['accessCode'], $codeData1['AccessKey'])) {
        $sql5 = "UPDATE Users SET RoleID = REPLACE(RoleID, :oldRoleID, :newRoleID) WHERE Email = :oldEmail";
        $replacestmt5 = $db->prepare($sql5);
        $replacestmt5->execute([':oldRoleID'=> $uData['RoleID'],
            ':newRoleID' => 2,
            ':oldEmail' => $_POST['oldEmail']]);
            }
    $sql2 = "SELECT * FROM AccessKeys WHERE RoleID = 3";
    $stmt2=$db->prepare($sql2);
    $stmt2->execute();
    $codeData2 = $stmt2->fetch(PDO::FETCH_ASSOC);
    if (password_verify($_POST['accessCode'], $codeData2['AccessKey'])) {
        $sql6 = "UPDATE Users SET RoleID = REPLACE(RoleID, :oldRoleID, :newRoleID) WHERE Email = :oldEmail";
        $replacestmt6 = $db->prepare($sql6);
        $replacestmt6->execute([':oldRoleID'=> $uData['RoleID'],
            ':newRoleID' => 3,
            ':oldEmail' => $_POST['oldEmail']]);
    }
    $sql3 = "SELECT * FROM AccessKeys WHERE RoleID = 4";
    $stmt3=$db->prepare($sql3);
    $stmt3->execute();
    $codeData3 = $stmt3->fetch(PDO::FETCH_ASSOC);
    if (password_verify($_POST['accessCode'], $codeData3['AccessKey'])) {
        $sql7 = "UPDATE Users SET RoleID = REPLACE(RoleID, :oldRoleID, :newRoleID) WHERE Email = :oldEmail";
        $replacestmt7 = $db->prepare($sql7);
        $replacestmt7->execute([':oldRoleID'=> $uData['RoleID'],
            ':newRoleID' => 4,
            ':oldEmail' => $_POST['oldEmail']]);
    }
    $sql1 = "UPDATE Users SET Email = REPLACE(Email, :oldEmail, :newEmail) WHERE Email = :oldEmail";
    $replacestmt1 = $db->prepare($sql1);
    $replacestmt1->execute([':oldEmail'=> $_POST['oldEmail'],
        ':newEmail' => $Email]);
    die;
}
?>