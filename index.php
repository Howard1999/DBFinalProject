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
	
	if(isset($_SESSION['session_id'])){//login check
		$stmt = $conn->prepare("select user_name from user where session_id = ?");
		$stmt->bind_param("s",$_SESSION['session_id']);
		$stmt->execute();
		$stmt->bind_result($user);
		if($stmt->fetch()){
			echo '<p>hi '.$user.'</p>';
			echo '<a id="logout_link" href="/DBFinalProject/logout.php">登出</a>';
			$login = true;
		}
		$stmt->close();
	}
	if(!$login){
		echo '<a id="login_page_link" href="/DBFinalProject/login_page.php">登入</a>';
		echo '<a id="register_page_link" href="/DBFinalProject/register_page.php">前往註冊</a>';
	}
	$conn->close();
?>