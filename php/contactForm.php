<?php

// Collects Variables from the contact form for SQL query
// Created by Eamon Mooney - 230075926
session_start();

//Register button pressed
if ($_SERVER["REQUEST_METHOD"] == "POST"){

    //Database connection
    require_once('connectdb.php');

    //Assigns the submitted data, if they do not exist, they are assigned to false instead
    $email=isset($_POST['email'])?$_POST['email']:false;
    $telephone=isset($_POST['phoneNumber'])?$_POST['phoneNumber']:false;
    $TicketContent=isset($_POST['description'])?$_POST['description']:false;

    //Inform the user if they have missed any crucial part of the form
    if (!($email)){
        exit("An email has not been given!");
    } else {
        // Check if the email is valid
        if (!(filter_var($email, FILTER_VALIDATE_EMAIL))) {
            exit("Invalid email address!");
        } 
    }
    if (!($TicketContent)){
        exit("No description has been given!");
    } else {
        //Check if the ticketContent is valid
        if (!(strlen($TicketContent) <= 100)) {
            exit("Invalid description!");
        } 
    }
    if (!($telephone)){
        exit("No phone number has been given!");
    }
    // Check if the user is logged in and the userId is set in the session
    if (isset($_SESSION['userId'])) {
        $userId = $_SESSION['userId'];
        $TicketTypeId = "2";
    } else {
        $userId = null;
        $TicketTypeId = "1";
    }

    try {
        //Date and time of the ticket submission
        $ticketDate = date("Y-m-d H:i:s");

        //Insert ticket info into the database
        $stmt=$db->prepare("insert into supportTickets values(default,?,?,?,?,?,?)");
        $stmt->execute(array($TicketTypeId, $userId, $telephone, $ticketDate, false, $TicketContent));

        echo "<p>Your ticket has been submitted successfully. You will receive a response soon.</p>";

    } catch (PDOexception $ex){
        echo "Sorry, an error has occured with the database. <br>";
        echo "Error details: <em>". $ex->getMessage()."</em";
    }
}
?>