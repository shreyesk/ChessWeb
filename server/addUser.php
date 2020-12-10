<?php
// NOTE: next step is to set up email auth and add robot catch

// form content accessible under $_POST["$fieldName"]

$servername = "127.0.0.1";
$username = getenv("dbUser");
$password = getenv("dbPassword");
$dbname = "chessWeb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

$email = $_POST["email"];
$username = $_POST["username"];
$password = $_POST["password"];

// check if email or username already exists
function exists($conn, $fieldName, $fieldValue) {
	$sql = "select count(*) from users where $fieldName='$fieldValue'";
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	$count = $row['count(*)'];
	if($count > 0) {
		return true;
	} else {
		return false;
	}
}

// send an email
function sendEmail($email, $token) {
	$emailUser = getenv("emailUser");
	$emailPassword = getenv("emailPassword");

	$ip = getenv("ip");

	require_once("PHPMailer/PHPMailerAutoload.php");

        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = "ssl";
        $mail->Host = "smtp.gmail.com";
        $mail->Port = "465";
        $mail->isHTML();
        $mail->Username = $emailUser;
        $mail->Password = $emailPassword;
        $mail->SetFrom($emailUser);
        $mail->Subject = "Activation Email";
        $mail->Body = "http://$ip/activate.php?email=$email&token=$token";
        $mail->AddAddress("$email");

        $mail->Send();

}

if(exists($conn, 'email', $email)) {
	echo "Email already exists";
} else if(exists($conn, 'username', $username)) {
	echo "Username already exists";
} else {
	// use password_verify($password, $passwordHash) for checking
	$options = [
		'cost' => 10
	];
	$passwordHash = password_hash($password, PASSWORD_BCRYPT, $options);

	$token = openssl_random_pseudo_bytes(30);
	//Convert the binary data into hexadecimal representation.
	$token = bin2hex($token);

	$sql = "insert into users (email, username, password, active, token) values ('$email', '$username', '$passwordHash', 0, '$token')";
	$conn->query($sql);

	sendEmail($email, $token);

	echo "Activation email sent";
}
?>
