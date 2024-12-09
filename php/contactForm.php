<?php
###############################################################
# Collects Variables from the contact form for SQL query
###############################################################
# - Eamon Mooney - 230075926
###############################################################
session_start();

//Register button pressed
if ($_SERVER["REQUEST_METHOD"] == "POST"){

    //Database connection
    require_once('connectdb.php');

    //Assigns the submitted data, if they do not exist, they are assigned to false instead
    $email=isset($_POST['email'])?$_POST['email']:false;
    $TicketContent=isset($_POST['description'])?$_POST['description']:false;
    //$ticketType=isset($_POST['ticketType'])?$_POST['ticketType']:false;

    $ticketType = "Subject";

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

    if (!($ticketType)){
        exit("No subject has been given!");
    }
    // Check if the user is logged in and the userId is set in the session
    if (isset($_SESSION['userId'])) {
        $userId = $_SESSION['userId'];
    } else {
        $userId = null;
    }

    try {
        //Date and time of the ticket submission
        $ticketDate = date("Y-m-d H:i:s");

        //Insert ticket type to form ticketTypeId
        $stmt=$db->prepare("insert into ticketTypes values(default,?)");
        $stmt->execute(array($ticketType));

        //Query database to get the ticketTypeId by checking the most recent value
        $stmt = $db->query("SELECT MAX(TicketTypeID) FROM TicketTypes");
        $TicketTypeId = $stmt->fetchColumn();

        //Insert ticket info into the database
        $stmt=$db->prepare("insert into supportTickets values(default,?,?,?,?,?)");
        $stmt->execute(array($TicketTypeId, $userId, $ticketDate, false, $TicketContent));

        echo "<p>Your ticket has been submitted successfully. You will receive a response soon.</p>";

        /*
            NOTE: Could query the database to see if the given email is registered under a user, THEN assign the userID if said account exists,
                Rather than check to see if the user is currently logged in

                Therefore changing the SQL to:
                    CREATE TABLE SupportTickets (
                        TicketID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                        TicketTypeID INT NOT NULL,
                        UserID INT,
                        TicketDate DATE NOT NULL,
                        Closed BOOLEAN NOT NULL,
                        TicketContent CHAR(100) NOT NULL,
                        FOREIGN KEY (TicketTypeID) REFERENCES TicketType(TicketTypeID),
                        FOREIGN KEY (UserID) REFERENCES Users(UserID)
                    );
        */

    } catch (PDOexception $ex){
        echo "Sorry, an error has occured with the database. <br>";
        echo "Error details: <em>". $ex->getMessage()."</em";
    }
}
?>