<?php
// Created by Eamon Mooney - 230075926
// Acts as a function to close a ticket selected in ticket.html

session_start();

//Database connection
require_once('connectdb.php');

// Get the ticket ID from the POST request
$ticketID = $_POST['ticket_id'];

// Prepare and execute the update query
$stmt = $db->prepare("UPDATE supporttickets SET Closed = 1 WHERE TicketID = ?");
$stmt->execute([$ticketID]);

// Redirect back to the tickets page
header("Location: ../ticket.html");
exit();
?>
