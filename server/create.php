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
	$fen = "rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1";
	$sql = "insert into games (white, playing, fen) values ('$accountUsername', 0, '$fen')";
	$conn->query($sql);
}
?>
