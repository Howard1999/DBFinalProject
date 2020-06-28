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
	font-size: 18px;
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
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>登入</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/all.min.css">
</head>
<div style="position:relative;">
        <img class="photo1" src="world.png" alt="" width="100%" height="15%">
</div>

<form action="login.php" method="post" class="login">
	<label id="account_label" class="account" for="account">account :<img src="account.png" alt="account" width="30px" height="30px" style="position:absolute;left:28%;top:7.5%;"></label><br>
	<?php
		echo "<input id=\"account class=\"account\" input\" type=\"text\" name=\"account\" ";
		if(isset($_GET['account'])){
			echo "value=".$_GET['account'];
		}
		echo "><br>";
	?>
	<label id="password_label" class="password" for="password">password :<img src="password.png" alt="password" width="22px" height="32px" style="position:absolute;left:27%;top:33%;"></label><br>
    <input id="password_input" class="password" type="password" name="password"><br>
    <input id="submit_button" type="submit" value="登入">
</form>
<?php
	if(isset($_GET['err_msg'])){
		if($_GET['err_msg']=="login_fail"){
			echo "<script type='text/javascript'>alert('帳號或密碼錯誤');</script>";
		}
		else if($_GET['err_msg']=='post_without_login'){
			echo "<script type='text/javascript'>alert('請先登入後再PO文');</script>";
		}
		else if($_GET['err_msg']=='edit_without_login'){
			echo "<script type='text/javascript'>alert('請先登入後再編輯');</script>";
		}
		else if($_GET['err_msg']=='leave_comment_without_login'){
			echo "<script type='text/javascript'>alert('請先登入後再留言');</script>";
		}
		else if($_GET['err_msg']=='like_dislike_without_login'){
			echo "<script type='text/javascript'>alert('請先登入後再推噓');</script>";
		}
	}
?>
<a id="main_page_link" href="/DBFinalProject/index.php"><img src="go_index.png" border="0" alt="回到主頁" width="10%" height="10%" style="position:absolute;left:38%;top:70%;"></a>
<a id="register_page_link" href="/DBFinalProject/register_page.php"><img src="go_register.png" border="0" alt="前往註冊" width="10%" height="10%" style="position:absolute;left:52%;top:70%;"></a>
