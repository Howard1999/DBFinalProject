<!doctype html>
<html>
<style type="text/css">
body {
	width: 100%;
	height: 100%;
	padding: 0;
	margin: 0;
	background: url("https://img.rawpixel.com/s3fs-private/rawpixel_images/website_content/pf-misctexture01-beer-000_5.jpg?w=800&dpr=1&fit=default&crop=default&q=65&vib=3&con=3&usm=15&bg=F4F4F3&ixlib=js-2.2.1&s=c1552a7bdc2ea7b6e17d8d0d893c15be");
	background-size: cover;
	background-attachment: fixed;
	background-position: center;
}
.login {
	position: fixed;
	left: 50%;
	top: 55%;
	transform: translate(-50%,-50%);
	background: rgba(0,0,0,0.5);
	text-align: center;
	color: #fff;
	font-family: "微軟正黑體", sans-serif;
	border-radius: 10px;
    padding: 30px;
    height: 60%;
    width: 25%;
}
.login label {
	font-size: 25px;
	margin: 20px;
}
	
.login table {
	display: inline-block;
	width: 90%;
}
.a {
	width: 70%;
}
	
input[type="text"], input[type="password"] {
	font-size: 16px;
	font-family: "微軟正黑體", sans-serif;
	padding: 5px;
	border-radius: 4px;
	margin: 0 auto;
	display: block;
	width: 100%;
}
.login input {
	border: 0;
	padding: 6px 12px;
	margin: 10px auto;
	width: 80%;
	font-size: 20px;
	font-family: "微軟正黑體", sans-serif;
	background: #fff;
	color: #000;
}
@media (max-width: 450px) {
	.login {
        width: 70%;
	}
}
</style>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/all.min.css">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title> 註冊系統 </title>
</head>

<body>
<div style="position:relative;">
        <img class="photo1" src="world.png" alt="" width="100%" height="15%">
</div>
	<form action="register.php" method="post" class="login">
	<table border="1">
　	<tr>
　	<td>使用者名稱</td>
	<td class="a"><input type="text" name="user_name"/></td>
	</tr>
	<tr>
	<td>帳號</td>
	<td class="a"><input type="text" name="account"/></td>
	</tr>
	<tr>
	<td class="a">密碼</td>
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
		else if ($_GET['err_msg']=="invalid")
		{
			echo "<script type='text/javascript'>alert('帳號不能有非法字元或中文!');</script>";
		}
		else if ($_GET['err_msg']=="usernametoolong")
		{
			echo "<script type='text/javascript'>alert('使用者名稱長度上限20字，請重新輸入');</script>";
		}
		else if ($_GET['err_msg']=="accounttoolong")
		{
			echo "<script type='text/javascript'>alert('帳號長度上限20字，請重新輸入');</script>";
		}
		else if ($_GET['err_msg']=="passwordtoolong")
		{
			echo "<script type='text/javascript'>alert('密碼上限20字，請重新輸入');</script>";
		}
		else if ($_GET['err_msg']=="final_fail")
		{
			echo "<script type='text/javascript'>alert('帳號註冊失敗!');</script>";
		}
	}
?>
</body>
</html>

<a id="main_page_link" href="/DBFinalProject/index.php"><img src="go_index.png" border="0" alt="回到主頁" width="10%" height="10%" style="position:absolute;left:38%;top:70%;"></a>
<a id="login_page_link" href="/DBFinalProject/login_page.php"><img src="login.png" border="0" alt="登入" width="10%" height="10%" style="position:absolute;left:53%;top:70%;"></a>

