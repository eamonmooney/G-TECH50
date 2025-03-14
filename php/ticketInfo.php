<?php
// Created by Eamon Mooney - 230075926
// Collects all ticket information from the database to list them in a table on ticket.html

session_start();

//Database connection
require_once('connectdb.php');

try {

    // Prepare a query to get all tickets
    $stmt = $db->prepare("SELECT TicketID, UserID, Telephone, TicketDate, Closed, TicketContent FROM supporttickets");
    $stmt->execute();

    // Fetch all the results
    $tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $newHtml = "";

    //check if there are any tickets
    if (count($tickets) > 0) {
        foreach ($tickets as $ticket) {
            $newHtml .= "<tr>";
            $newHtml .= "<td>" . htmlspecialchars($ticket['TicketID']) . "</td>";



            //----- INSERT USERNAME ----
            $newHtml .= "<td>USERNAME</td>";



            $newHtml .= "<td>" . htmlspecialchars($ticket['Telephone']) . "</td>";
            $newHtml .= "<td>" . htmlspecialchars($ticket['TicketDate']) . "</td>";
            $newHtml .= "<td>" . htmlspecialchars($ticket['Closed']) . "</td>";
            $newHtml .= "<td>" . htmlspecialchars($ticket['TicketContent']) . "</td>";
            $newHtml .= "</tr>";
        }
    } else {
        $newHtml .= "No tickets found.";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>