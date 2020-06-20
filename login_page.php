<form action="login.php" method="post">
	<label id="account label" for="account">account :</label><br>
	<?php
		echo "<input id=\"account input\" type=\"text\" name=\"account\" ";
		if(isset($_GET['account'])){
			echo "value=".$_GET['account'];
		}
		echo "><br>";
	?>
	<label id="password label" for="password">password :</label><br>
    <input id="password input"type="password" name="password"><br>
    <input id="submit button" type="submit">
</form>
<?php
	if(isset($_GET['err_msg'])){
		if($_GET['err_msg']=="login_fail"){
			echo "<script type='text/javascript'>alert('帳號或密碼錯誤');</script>";
		}
	}
?>
<a id="main page link" href="/DBFinalProject/index.php">回到主頁</a>
<a id="register page link" href="/DBFinalProject/register.php">前往註冊</a>


