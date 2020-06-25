<html>
<head>
<meta charset="UTF-8">
<title>註冊使用者</title>
</head>
<body>
<?php
	session_start();
	$servername = "localhost";
	$username = "root";
	$passwordd = "eb87oU7BGKxqxgSR";
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
	
	$in_user_name =null;
	$in_account = null;
	$in_password = null;
	$sql="SELECT user_name from `user`";
	$result= mysqli_query($conn,$sql);
	
	while($row = mysqli_fetch_array($result,MYSQLI_NUM))
	{
		if($row[0]==$user_name)
		{
            echo "<script type='text/javascript'>alert('使用者名稱已有人註冊');</script>";
			header("Location: /DBFinalProject/register_page.php");
			die();
		}
	}
	mysqli_query($conn,"INSERT INTO `user`(`user_name`, `account`, `password`, `user_authority`, `last_login_time`, `board_name`, `login_ip`, `last_login_ip`, `session_id`) VALUES ('$user_name', '$account', '$password', 'C', NULL, NULL, '', '', '')");
	mysqli_close($conn);
	echo "<script type='text/javascript'>alert('註冊成功');</script>";
	header("Location: /DBFinalProject/login_page.php");
	die();
?>
</body> 
</html> 
