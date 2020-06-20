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
	
	$stmt = $conn->prepare("update user set session_id=NULL where session_id= ?");
	$stmt->bind_param("s",$_SESSION['session_id']);
	$stmt->execute();
	session_destroy();
	header("Location: /DBFinalProject/index.php");
	die();
?>