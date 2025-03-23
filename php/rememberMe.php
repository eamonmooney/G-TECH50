<?php
// Remember me logic by Safa
// Should be linked in pages where login is required/ at the start
// Start/resume the session 
session_start();

// Error testing - prints all values in session
// print_r($_SESSION); 

// Auto-login via cookie 
// If user ID isnt set and cookie remember me is active
if (!isset($_SESSION['userId']) && isset($_COOKIE['rememberMe'])) {
	// Create the token for remember me
	$token = $_COOKIE['rememberMe'];

	//SQL Statement to get users tokens where it exists and its not expired
	$sqlFindToken = "SELECT * FROM Users WHERE rememberToken = :token AND tokenExpiry > NOW()";

	// Preparing
	$findTokenStmt = $db->prepare($sqlFindToken);
	// Executing
	$findTokenStmt->execute([':token' => $token]);
	// Fetching the user stuff from the executed statement
	$user = $findTokenStmt->fetch(PDO::FETCH_ASSOC);

	// If theres returned values
	if ($user) {
		// Assign these values ( log user in)
		$_SESSION['userId'] = $user['UserID'];
		$_SESSION['roleId'] = $user['RoleID'];
		$_SESSION['username'] = $user['Name'];
		$_SESSION['email'] = $user['Email'];
		$_SESSION['signedIn'] = True;
		$response = "User is set";
		echo json_encode($response);
		exit();
		// Otherwise if there's no cookie in the database / invalid
	} else {
		// Clear the invalid/expired cookie
		setcookie("rememberMe", "", time() - 3600, "/");
		$response = "User isn't set";
		echo json_encode($response);
		exit();
	}
} else {
	// If the user ID isn't set or if the user ID is 0 (guest)
	if (!isset($_SESSION['userId']) || $_SESSION['userId'] == 0) {
		// Set signed in to false
		$_SESSION['signedIn'] = False;
		// Else (meaning if the user ID is a valid number)
	} else {
		// Set signed in to true
		$_SESSION['signedIn'] = True;
	}

	$response = "No cookie";
	echo json_encode($response);
}
