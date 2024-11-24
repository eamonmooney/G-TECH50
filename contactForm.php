<?php

//Register button pressed
if (isset($_POST['submitted'])){

    //Database connection
    require_once('connectdb.php');

    ###############################################################
    # Will collect variables from the form submission for SQL Query.
    ###############################################################
    # - Eamon Mooney - 230075926
    ###############################################################

    //Assigns the submitted data, if they do not exist, they are assigned to false instead
    $email=isset($_POST['email'])?$_POST['email']:false;
    $TicketContent=isset($_POST['ticketContent'])?$_POST['ticketContent']:false;

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
        exit("No description has been entered!");
    }

    /*
        TO DO: Need to determine how to get the ticketType
        TO DO: Need to determine how to get the userId
    */

    try {
        //Date and time of the ticket submission
        $ticketDate = date("Y-m-d H:i:s");

        //Insert ticket info into the database
        $stat=$db->prepare("insert into supportTickets values(default,?,?,?,?,?)");
        $stat->execute(array($ticketType, $userId, $ticketDate, false, $TicketContent));

        /*
            TO DO: Operation to tell the user their ticket has been submitted and they will recieve a response via email
        */

    } catch (PDOexception $ex){
        echo "Sorry, an error has occured with the database. <br>";
        echo "Error details: <em>". $ex->getMessage()."</em";
    }
}
?>