<html>
<head>
<meta charset="UTF-8">
<title>註冊使用者</title>
</head>
<body>
<?php
	session_start();
	$severname = "127.0.0.1";
	$username = "team1";
	$passwordd = "DB338HKkvRVOZzb";
	$dbname = "team1";
	
	$conn = new mysqli($servername,$username,$passwordd,$dbname);
	
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
	mysql_select_db("user",$conn);
	$in_user_name =null;
	$in_account = null;
	$in_password = null;
	$result= mysql_query("select * from user where user_name ='{$user_name}'");
	while($row = mysql_fetch_array($result))
	{
		$in_user_name =$row["user_name"];
		$in_account = $row["account"];
		$in_password = $row["password"];
	}
	if(is_null($in_user_name)==0)
	{
?>
		<script type="text/javascript">
                alert("使用者已存在");
				window.location.href="register.html";
		</script>
<?php
	}
	mysql_query("insert into user values('$user_name','$account','$password',"C",NULL,NULL,NULL,NULL,NULL,NULL)");
	mysql_close($conn);
?>
<script type="text/javascript">
alert("註冊成功");
window.location.href="login_page.html";
</script> 
</body> 
</html> 
