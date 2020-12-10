<?php
session_start();

$accountUsername = $_SESSION["username"];

$servername = "127.0.0.1";
$username = getenv("dbUser");
$password = getenv("dbPassword");
$dbname = "chessWeb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

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
if(exists($conn, 'white', $accountUsername)) {
	echo "Already playing";
} else if(exists($conn, 'black', $accountUsername)) {
	echo "Already playing";
} else {
	$game = (int)$_POST["id"];
	$sql = "update games set black='$accountUsername', playing=1 where id=$game";
	echo $sql;
	$conn->query($sql);
}
?>
