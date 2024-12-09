<?php
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
	// If the connection is successful, send a success message in JSON format
	echo json_encode(['message' => 'Database connected successfully.']);
} catch(PDOException $ex) {
 	// If there’s an error, send the error message in JSON format
 	echo json_encode(['error' => 'Database connection failed:' . $ex->getMessage()]);
	exit;
}
?>