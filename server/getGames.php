<?php
session_start();

$accountUsername = $_SESSION["username"];

$servername = "127.0.0.1";
$username = getenv("dbUser");
$password = getenv("dbPassword");
$dbname = "chessWeb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

$sql = "select id from games where playing=0";
$result = $conn->query($sql);
$toRet = "";
while($row = $result->fetch_assoc()) {
	echo $row["id"] . ", ";
}
?>
