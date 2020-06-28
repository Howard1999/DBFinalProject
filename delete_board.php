<?php
	session_start();
	//connect to database
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
	
	// check user authority
	$is_admin = false;
	if(isset($_SESSION['session_id'])){
		$result = $conn->query('select user_name from user where session_id="'.$_SESSION['session_id'].'" and user_authority = "A"');
		if($row = $result->fetch_row()){
			$user_name = $row[0];
			$is_admin = true;
		}
		$result->close();
	}
	if(!$is_admin){
		header("Location: /DBFinalProject/index.php");
		die();
	}
	
	// check post data
	if(isset($_POST['board_name'])){
		$board_name = mysqli_real_escape_string($conn,$_POST['board_name']);
		$result = $conn->query('select * from board where board_name="'.$board_name.'"');
		if(!$result->fetch_row()){
			header("Location: /DBFinalProject/board_manage.php?err_msg=board_not_qxist");
			die();
		}
		$result->close();
	}
	// delete 
	if($conn->query('delete from board where board_name="'.$board_name.'"')){
		header("Location: /DBFinalProject/board_manage.php");
		die();
	}
	else{
		header("Location: /DBFinalProject/board_manage.php?err_msg=unknown");
		die();
	}
	$conn->close();
?>