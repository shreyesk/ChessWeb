<?php
$email = $_GET["email"];
$token = $_GET["token"];

$servername = "127.0.0.1";
$username = getenv("dbUser");
$password = getenv("dbPassword");
$dbname = "chessWeb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

$sql = "select token from users where email='$email'";
$result = $conn->query($sql);
$resultToken = $result->fetch_assoc()["token"];

if($token == $resultToken) {
        $sql = "update users set active=1 where email='$email'";
        $conn->query($sql);
        echo "<script>alert('Account activated');</script>";
} else {
        echo "<script>alert('Invalid token or email');</script>";
}
?>
