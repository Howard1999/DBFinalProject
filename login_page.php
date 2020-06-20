<form action="login.php" method="post">
	<label id="account_label" for="account">account :</label><br>
	<?php
		echo "<input id=\"account input\" type=\"text\" name=\"account\" ";
		if(isset($_GET['account'])){
			echo "value=".$_GET['account'];
		}
		echo "><br>";
	?>
	<label id="password_label" for="password">password :</label><br>
    <input id="password_input"type="password" name="password"><br>
    <input id="submit_button" type="submit">
</form>
<?php
	if(isset($_GET['err_msg'])){
		if($_GET['err_msg']=="login_fail"){
			echo "<script type='text/javascript'>alert('帳號或密碼錯誤');</script>";
		}
	}
?>
<a id="main_page_link" href="/DBFinalProject/index.php">回到主頁</a>
<a id="register_page_link" href="/DBFinalProject/register_page.php">前往註冊</a>


