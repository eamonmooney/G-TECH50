<?php
// Created by Eamon Mooney - 230075926
// Collects all review information from the database to list them in a table on customer-review.html

session_start();

//Database connection
require_once('connectdb.php');

try {

    // Prepare a query to get all reviews
    $stmt = $db->prepare("SELECT ReviewID, UserID, ProductID, Rating FROM reviews");

    // Prepare query with JOIN to get username and ticketType
    /*
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
    */
    $stmt->execute();

    // Fetch all the results
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $newHtml = "";

    //check if there are any reviews
    if (count($reviews) > 0) {
        foreach ($reviews as $review) {
            //Display information about each review in each row
            $newHtml .= "<tr onclick=\"window.location='review-details.html';\">";
            $newHtml .= "<td>" . htmlspecialchars($review['ReviewID']) . "</td>";
            $newHtml .= "<td>" . htmlspecialchars($review['UserID']) . "</td>";
            $newHtml .= "<td>" . htmlspecialchars($review['ProductID']) . "</td>";
            $newHtml .= "<td>" . htmlspecialchars($review['Rating']) . "</td>";
            
            $newHtml .= "</tr>";
        }
    } else {
        //Displays that no reviews are found in the event of no reviews
        $newHtml .= "<tr><td colspan='4'>No reviews found.</td></tr>";
    }

    //Outputs the new table to the html
    echo $newHtml;

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>