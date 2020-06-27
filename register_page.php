<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title> 註冊系統 </title>
</head>
<body>
	<form action="register.php" method="post">
	<table border="1">
　	<tr>
　	<td>使用者名稱</td>
	<td><input type="text" name="user_name"/></td>
	</tr>
	<tr>
	<td>帳號</td>
	<td><input type="text" name="account"/></td>
	</tr>
	<tr>
	<td>密碼</td>
	<td><input type="password" name="password"/></td>
	</tr>
	</table>
	<input type ="submit" value="註冊">
	</form>
<?php
	if(isset($_GET['err_msg']))
	{
		if($_GET['err_msg']=="register_user_name_fail")
		{
			echo "<script type='text/javascript'>alert('使用者名稱已有人註冊過!');</script>";
		}
		else if ($_GET['err_msg']=="register_account_fail")
		{
			echo "<script type='text/javascript'>alert('帳號已有人註冊過!');</script>";
		}
		else if ($_GET['err_msg']=="usernamefail")
		{
			echo "<script type='text/javascript'>alert('使用者名稱不能為空!');</script>";
		}
		else if ($_GET['err_msg']=="accountfail")
		{
			echo "<script type='text/javascript'>alert('帳號不能為空!');</script>";
		}
		else if ($_GET['err_msg']=="passwordfail")
		{
			echo "<script type='text/javascript'>alert('密碼不能為空!');</script>";
		}
	}
?>
</body>
</html>

<a id="main_page_link" href="/DBFinalProject/index.php">回到主頁</a>
<a id="login_page_link" href="/DBFinalProject/login_page.php">登入</a>
