<html>
<head>
<meta charset="UTF-8">
<title>註冊使用者</title>
</head>
<body>
<?php
	$servername = "127.0.0.1";
	$username = "team1";
	$passwordd = "DB338HKkvRVOZzb";
	$dbname = "team1";
	
	$conn = new mysqli($servername,$username,$passwordd,$dbname);
	
	if(!$conn->set_charset("utf8"))
	{
		printf("Error loading character set utf8: %s\n",$conn->error);
		exit();
	}
	
	if(mysqli_connect_errno($conn))
	{
		printf("Connect failed: %s\n",mysqli_connect_error());
		exit();
	}
	mysqli_select_db($conn,"user");
	
	$user_name=$_POST['user_name'];
	$account=$_POST['account'];
	$password=$_POST['password'];
	if($user_name=="")
	{
		header("Location: register_page.php?err_msg=usernamefail");
		die();
	}
	else if ($account=="")
	{
		header("Location: register_page.php?err_msg=accountfail");
		die();
	}
	else if ($password=="")
	{
		header("Location: register_page.php?err_msg=passwordfail");
		die();
	}
	$in_user_name =null;
	$in_account = null;
	$in_password = null;
	$sql="SELECT * from `user` where user_name='$user_name' or account = '$account' ";
	$result= mysqli_query($conn,$sql);
	
	while($row = mysqli_fetch_row($result))
	{
		if($row[0]==$user_name)
		{
			header("Location: register_page.php?err_msg=register_user_name_fail");
			die();
		}
		else if ($row[1]==$account)
		{
			header("Location: register_page.php?err_msg=register_account_fail");
			die();
		}
	}
	mysqli_query($conn,"INSERT INTO `user`(`user_name`, `account`, `password`, `user_authority`, `last_login_time`, `board_name`, `login_ip`, `last_login_ip`, `session_id`) VALUES ('$user_name', '$account', '$password', 'C', NULL, NULL, NULL, NULL, NULL)");
	mysqli_close($conn);
	header("Location: /DBFinalProject/login_page.php?account='$account'");
	die();
?>
</body> 
</html> 
