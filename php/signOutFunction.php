<?php
session_start();
// establish database connection
require_once('connectdb.php');
// check if logout button has been pressed
if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['/logout']))
{
    // remove session variables
    session_unset();
    // change user to index page
    header("Location: /G-TECH50/index.html");
}

?>
