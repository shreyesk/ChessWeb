<?php
// starting a session
session_start();

$emailOrUsername = $_POST["emailOrUsername"];
$accountPassword = $_POST["password"];

$servername = "127.0.0.1";
$username = getenv("dbUser");
$password = getenv("dbPassword");
$dbname = "chessWeb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

$success = false;
$accountUsername = "";
if(strpos($emailOrUsername, '@') == true) {
	$email = $emailOrUsername;
	$sql = "select username, password, active from users where email='$email'";
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	if(!password_verify($accountPassword, $row["password"])) {
		echo "Incorrect email or password";
	} else if($row["active"] != 1) {
		echo "Account not activated";
	} else {
		$accountUsername = $row["username"];
		$success = true;
	}
} else {
	$accountUsername = $emailOrUsername;
	$sql = "select password, active from users where username='$accountUsername'";
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	if(!password_verify($accountPassword, $row["password"])) {
		echo "Incorrect username or password";
	} else if($row["active"] != 1) {
		echo "Account not activated";
	} else {
		$success = true;
	}
}

if($success) {
	$_SESSION["username"] = $accountUsername;
	echo "Success logging in";
}
?>
