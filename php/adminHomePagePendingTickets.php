<?php
// Ned Goodman - 230019355

session_start();

//Database connection
require_once('connectdb.php');

try {
    // query all open support tickets
    $sql0 = "SELECT * FROM supporttickets WHERE closed = :bool";
    $stmt = $db->prepare($sql0);
    $stmt->execute([':bool' => false]);
    $tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $numTickets = 0;
    if (count($tickets) > 0) {
        // define number of tickets by iterating over every open ticket
        foreach($tickets as $ticket) {
            $numTickets += 1;
        }
    };
    // output total number of tickets to javascript
    echo $numTickets;

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>