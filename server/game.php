<?php
session_start();
$game = $_SESSION["game"];
$color = $_SESSION["color"];
$accountUsername = $_SESSION["username"];

$servername = "127.0.0.1";
$username = getenv("dbUser");
$password = getenv("dbPassword");
$dbname = "chessWeb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

function getField($conn, $field, $fieldColor, $accountUsername) {
	$sql = "select $field from games where $fieldColor='$accountUsername'";
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	$toRet = $row[$field];
	return $toRet;
}

$fen = getField($conn, 'fen', $color, $accountUsername);

// code to replicate

// fen = fen.substr(0, fen.indexOf(" "));
// fen = fen.replace(/\//g, "");
// var fenI = 0;
// for(var i = 7; i >= 0; i--) {
// 	for(var j = 0; j < 8; j++) {
// 		currChar = fen.charAt(fenI);
// 		if(currChar >= '0' && currChar <= '9') {
// 			j += parseInt(currChar) - 1;
// 		} else {
// 			if(currChar == currChar.toUpperCase()) {
// 				// is a white piece
// 				setSquare(i, j, "images/w" + currChar + ".png");
// 			} else {
// 				setSquare(i, j, "images/b" + currChar + ".png");
// 			}
// 		}
// 		fenI += 1;
// 	}
// }
$board = array();
$rowEqs = array();
$colEqs = array();
$rowEqs["1"] = 0;
$rowEqs["2"] = 1;
$rowEqs["3"] = 2;
$rowEqs["4"] = 3;
$rowEqs["5"] = 4;
$rowEqs["6"] = 5;
$rowEqs["7"] = 6;
$rowEqs["8"] = 7;
$colEqs["a"] = 0;
$colEqs["b"] = 1;
$colEqs["c"] = 2;
$colEqs["d"] = 3;
$colEqs["e"] = 4;
$colEqs["f"] = 5;
$colEqs["g"] = 6;
$colEqs["h"] = 7;

$fen = substr($fen, 0, strpos($fen, " "));
$fen = str_replace("/", "", $fen);
$fenI = 0;
for($i = 7; $i >= 0; $i = $i - 1) {
	for($j = 0; $j < 8; $j = $j + 1) {
		$currChar = $fen[$fenI];
		if(is_numeric($currChar)) {
			for($k = 0; $k < (int)$currChar; $k++) {
				$board[$i * 8 + $j + $k] = "1";
			}
			$j = $j + (int)$currChar - 1;
		} else {
			$board[$i * 8 + $j] = $currChar;
		}
		$fenI = $fenI + 1;
	}
}

$move = $_POST["move"];
if($move == "resign") {
	$sql = "delete from games where id=$game";
	$conn->query($sql);
} else {
	$ci = $move[0];
	$ri = $move[1];
	$cf = $move[2];
	$rf = $move[3];
	$ci = $colEqs[$ci];
	$ri = $rowEqs[$ri];
	$cf = $colEqs[$cf];
	$rf = $rowEqs[$rf];

	$initChar = $board[$ri * 8 + $ci];
	$board[$rf * 8 + $cf] = $initChar;
	$board[$ri * 8 + $ci] = "1";

	$fenNew = "";
	for($i = 7; $i >= 0; $i = $i - 1) {
		for($j = 0; $j < 8; $j = $j + 1) {
			$fenNew = $fenNew . $board[$i * 8 + $j];
		}
	}
	// should add this space for formatting compatability
	$fenNew = $fenNew . " ";
	if(getField($conn, 'playing', $color, $accountUsername) == 1) {
		$sql = "update games set fen='$fenNew' where id=$game";
		$conn->query($sql);
	}
}
?>
