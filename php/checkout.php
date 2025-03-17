<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

/* Checkout functionality.
   By Sahil Awan (230073302).
   Improved for better maintainability. */

/*  To be able to checkout successfully, we need to be able to do a few things...
    One: make sure we have enough stock for the order.
    Two: create the order and insert a record into the database.
    Three: validate whether the order has gone through or not. */
   
    session_start();
    $basket = $_SESSION['basket'];
    print_r($basket)
?>
