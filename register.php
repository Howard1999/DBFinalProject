<?php
	$severname = "127.0.0.1";
	$username = "team1";
	$password = "DB338HKkvRVOZzb";
	$dbname = "team1";
	
	$conn = new mysqli($servername,$username,$password,$dbname);
	
	if(!$conn->set_charset("utf8"))
	{
		printf("Error loading character set utf8: %s\n",$conn->error);
		exit();
	}
	
	if($conn->connect_error)
	{
		die("Connection failed: " . $conn->connect_error);
	}
	
	$user_name=$_POST['user_name'];
	$account=$_POST['account'];
	$password=$_POST['password'];
	
	$message = "你輸入的資訊為:\\n使用者名稱:$username\\n帳號:$account\\n密碼:$password , 即將導向登入頁面";
	mysql_query("insert into user values('$user_name','$account','$password')");
?>
