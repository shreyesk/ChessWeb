<?php
session_start();
$accountUsername = $_SESSION["username"];

$servername = "127.0.0.1";
$username = getenv("dbUser");
$password = getenv("dbPassword");
$dbname = "chessWeb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// check if is currently playing
function exists($conn, $fieldName, $fieldValue) {
	$sql = "select count(*) from games where $fieldName='$fieldValue'";
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	$count = $row['count(*)'];
	if($count > 0) {
		return true;
	} else {
		return false;
	}
}

function getField($conn, $field, $fieldColor, $accountUsername) {
	$sql = "select $field from games where $fieldColor='$accountUsername'";
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	$toRet = $row[$field];
	return $toRet;
}

// returns playing status and fen back to front end
// sets color and game for session to the current game
if(exists($conn, 'white', $accountUsername)) {
	$fen = getField($conn, 'fen', 'white', $accountUsername);
	$_SESSION["game"] = getField($conn, 'id', 'white', $accountUsername);
	$_SESSION["color"] = 'white';
	if(getField($conn, 'playing', 'white', $accountUsername) == 1) {
		echo "Playing white|" . $fen;
	} else {
		echo "Waiting";
	}
} else if(exists($conn, 'black', $accountUsername)) {
	$fen = getField($conn, 'fen', 'black', $accountUsername);
	$_SESSION["game"] = getField($conn, 'id', 'black', $accountUsername);
	$_SESSION["color"] = 'black';
	if(getField($conn, 'playing', 'black', $accountUsername) == 1) {
		echo "Playing black|" . $fen;
	} else {
		echo "Waiting";
	}
} else {
	echo "Not playing";
}
?>
