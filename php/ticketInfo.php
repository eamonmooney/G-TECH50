<?php
// Created by Eamon Mooney - 230075926
// Collects all ticket information from the database to list them in a table on ticket.html

session_start();

//Database connection
require_once('connectdb.php');

try {

    // Prepare a query to get all tickets
    //$stmt = $db->prepare("SELECT TicketID, UserID, Telephone, TicketDate, Closed, TicketContent FROM supporttickets");
    //$stmt->execute();

    // Prepare query with JOIN to get username and ticketType
    $stmt = $db->prepare("
        SELECT 
            supporttickets.TicketID, 
            supporttickets.TicketTypeID,
            tickettypes.TicketType, 
            supporttickets.UserID, 
            users.Name, 
            supporttickets.Telephone, 
            supporttickets.TicketDate, 
            supporttickets.Closed, 
            supporttickets.TicketContent
        FROM 
            supporttickets
        INNER JOIN 
            tickettypes ON supporttickets.TicketTypeID = tickettypes.TicketTypeID
        LEFT JOIN
            users ON supporttickets.UserID = users.UserID
        ORDER BY 
        supporttickets.TicketID ASC;

    ");
    $stmt->execute();

    // Fetch all the results
    $tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $newHtml = "";

    //check if there are any tickets
    if (count($tickets) > 0) {
        foreach ($tickets as $ticket) {
            //Display information about each ticket in each row
            $newHtml .= "<tr>";
            $newHtml .= "<td>" . htmlspecialchars($ticket['TicketID']) . "</td>";
            //Checks to see if the ticket was made by a guest or a user
            if($ticket['TicketTypeID'] == 1) {
                $newHtml .= "<td>" . htmlspecialchars($ticket['TicketType']) . "</td>";
            } else if($ticket['TicketTypeID'] == 2){
                $newHtml .= "<td>" . htmlspecialchars($ticket['Name']) . "</td>";
            } else{$newHtml .= "<td>Error</td>";}
            $newHtml .= "<td>" . htmlspecialchars($ticket['Telephone']) . "</td>";
            $newHtml .= "<td>" . htmlspecialchars($ticket['TicketDate']) . "</td>";
            //Checks and displays whether the tickets are open or closed
            if(!($ticket['Closed'])){
                $newHtml .= "<td>Open</td>";
            } else {
                $newHtml .= "<td>Closed</td>";
            }
            $newHtml .= "<td>" . htmlspecialchars($ticket['TicketContent']) . "</td>";
            //Close button used to close tickets through closeTicket.php
            $newHtml .= "<td><form action='php/closeTicket.php' method='POST'><input type='hidden' name='ticket_id' value='" . htmlspecialchars($ticket['TicketID']) . "'><button class='close-button' type='submit'>Close Ticket</button></form></td>";
            $newHtml .= "</tr>";
        }
    } else {
        //Displays that no tickets are found in the event of no tickets
        $newHtml .= "<tr><td colspan='7'>No tickets found.</td></tr>";
    }

    //Outputs the new table to the html
    echo $newHtml;

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>