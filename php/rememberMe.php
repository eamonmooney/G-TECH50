<?php
// Remember me logic by Safa
// Should be linked in pages where login is required/ at the start

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
		$response = "User is set";
		echo json_encode($response);
		exit();
		// Otherwise
	} else {
		// Clear the invalid/expired cookie
		setcookie("rememberMe", "", time() - 3600, "/");
		$response = "User isn't set";
		echo json_encode($response);
		exit();
	}
}else{
	$response = "No cookie";
	echo json_encode($response);

}
?>

