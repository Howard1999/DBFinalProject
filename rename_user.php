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
	
	// check login
	$is_login = false;
	if(isset($_SESSION['session_id'])){
		$result = $conn->query('select account from user where session_id="'.$_SESSION['session_id'].'"');
		if($row = $result->fetch_row()){
			$account = $row[0];
			$is_login = true;
		}
		$result->close();
	}
	if(!$is_login){
		header("Location: /DBFinalProject/index.php");
		die();
	}
	
	// check post data
	if(isset($_POST['new_name'])){
		$new_name = mysqli_real_escape_string($conn,$_POST['new_name']);
		$result = $conn->query('select * from user where user_name="'.$new_name.'"');
		
		if(mb_strlen($new_name)==0){
			header("Location: /DBFinalProject/profile_page.php?err_msg=name_empty");
			die();
		}
		else if(mb_strlen($new_name)>20){
			header("Location: /DBFinalProject/profile_page.php?err_msg=name_too_long");
			die();
		}
		if($result->fetch_row()){
			header("Location: /DBFinalProject/profile_page.php?err_msg=name_exist");
			die();
		}
		
		$result->close();
	}
	// update 
	if($conn->query('update user set user_name="'.$new_name.'" where account="'.$account.'"')){
		header("Location: /DBFinalProject/profile_page.php");
		die();
	}
	else{
		header("Location: /DBFinalProject/profile_page.php?err_msg=unknown");
		die();
	}
	$conn->close();
?>
