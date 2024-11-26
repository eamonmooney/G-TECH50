<?php
//This is the login to our virtual server database, feel free to change this for local use if needed. - Eamon
$db_host = 'localhost';
$db_name = 'cs2team50_db';
$username = 'cs2team50';

$password = '0uL3mrV0yknvScd';

try {
	$db = new PDO("mysql:dbname=$db_name;host=$db_host", $username, $password); 
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $ex) {
	echo("Failed to connect to the database.<br>");
	echo($ex->getMessage());
	exit;
}
?>