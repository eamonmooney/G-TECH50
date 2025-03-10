<?php
// Created by Emaon Mooney - 230075926
//This is the login to our virtual server database, feel free to change this for local use if needed. - Eamon

//For the server 
// $db_host = 'localhost';
// $db_name = 'cs2team50_db';
// $username = 'cs2team50';
// $password = '0uL3mrV0yknvScd';

//For local use
$db_host = 'localhost';
$db_name = 'cs2team50_db';
$username = 'root';
$password = '';

try {
	$db = new PDO("mysql:dbname=$db_name;host=$db_host", $username, $password); 
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	require_once('database.php');
} catch(PDOException $ex) {
	echo("Failed to connect to the database. <br>");
	echo($ex->getMessage());
	exit;
}
?>