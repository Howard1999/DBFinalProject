<?php
session_start();
$servername = "127.0.0.1";
$username = "team1";
$password = "DB338HKkvRVOZzb";
$dbname = "team1";

$conn = new mysqli($servername, $username, $password, $dbname);

if (!$conn->set_charset("utf8")) {
    printf("Error loading character set utf8: %s\n", $conn->error);
    exit();
}
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['account']) && isset($_POST['password'])){
	$account = $_POST['account'];
	$password = $_POST['password'];
	$sql = "select user_name from user where account=? && password=?";
	$stmt = $conn->prepare($sql);
	$stmt->bind_param('ss',$account,$password);
	$stmt->execute();
	$stmt->bind_result($user_name);
	
	if($stmt->fetch()){
		$stmt->close();
		//session setup
		$_SESSION["session_id"] = session_id();
		//update new user session id
		$sql = "update user set session_id=? where account=?";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param('ss',$_SESSION["session_id"],$account);
		$stmt->execute();
		$stmt->close();
		//update login ip
		$sql = "update user set last_login_ip=login_ip,login_ip=? where account=?";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param('ss',$_SERVER['REMOTE_ADDR'],$account);
		$stmt->execute();
		$stmt->close();
		
		//redirect to main_page
		if(isset($_SESSION['last view board'])&&isset($_SESSION['last view page'])){
			header("Location: /DBFinalProject/board.php?board_name=".$_SESSION['last view board']."&page=".$_SESSION['last view page']);
			die();
		}
		else{
			header("Location: /DBFinalProject/index.php");
			die();
		}
	}
	else{
		header("Location: /DBFinalProject/login_page.php?err_msg=login_fail");
		die();
	}
}
$conn->close();
?>