<!doctype html>
<html>
<style type="text/css">
<!--set no rolling-->
#main {
  max-width: 600px;
  margin: 0 auto; 
}
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
#logout_link{
	display:inline-block;
}
#profile_link{
	display:inline-block;
}
body{font-family: arial,"Microsoft JhengHei","微軟正黑體",sans-serif !important;}
</style>


<title>個人資料</title>
<header>
<a id="logout_link" href="/DBFinalProject/logout.php"><input type="button" value="登出" style="width:120px;height:40px;border:2px #9999FF dashed;background-color:pink;"></a>
<a id="profile_link" href="/DBFinalProject/index.php"><input type="button" value="回到主畫面" style="width:120px;height:40px;border:2px #9999FF dashed;background-color:pink;">
</a><h1 align="center">個人資料</h1>
</header>
<!--hidden form-->
<form id="rename_table" action="rename_user.php" method="post">
	<input type="hidden" id="new_name" name="new_name" value="">
</form>
<!--hidden form-->

<div style="border-width:3px;color:#4A525B;border-style:solid;background-color:#E9E1BD;border-color:black;padding:3px;">
<table style="width:50%" align="center">
	<tr><th>屬性</th><th>值</th>
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

	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	// check login
	$is_login = false;
	if(isset($_SESSION['session_id'])){
		$result = $conn->query('select * from user where session_id="'.$_SESSION['session_id'].'"');
		if($row = $result->fetch_row()){
			$is_login = true;
		}
		$result->close();
	}
	
	if(!$is_login){
		header("Location: /DBFinalProject/index.php");
		die();
	}
	else{
		echo '<tr>';
			echo '<td align="center">使用者名稱</td>';
			echo '<td align="center"><input id=user_name type="text" value="'.$row[0].'" align="center"></td>';
			echo '<td><button onclick="rename()" style="width:120px;height:22px; color:white; background-color:#05143D;">rename</button></td>';
		echo '</tr>';
		
		echo '<tr>';
			echo '<td align="center">帳號</td>';
			echo '<td align="center">'.$row[1].'</td>';
		echo '</tr>';
		
		echo '<tr>';
			echo '<td align="center">權限</td>';
			if($row[3]=="A")$auth="管理員";
			else if($row[3]=="B")$auth="版主";
			else if($row[3]=="C")$auth="一般使用者";
			echo '<td align="center">'.$auth.'</td>';
		echo '</tr>';
		
		if($row[3]=="B"){
			echo '<tr>';
				echo '<td align="center">管理的版</td>';
				echo '<td align="center">'.$row[6].'</td>';
			echo '</tr>';
		}
		
		echo '<tr>';
			echo '<td align="center">登入時間</td>';
			echo '<td align="center">'.$row[4].'</td>';
		echo '</tr>';
		
		echo '<tr>';
			echo '<td align="center">上次登入時間</td>';
			echo '<td align="center">'.$row[5].'</td>';
		echo '</tr>';
		
		echo '<tr>';
			echo '<td align="center">登入IP</td>';
			echo '<td align="center">'.$row[7].'</td>';
		echo '</tr>';
		
		echo '<tr>';
			echo '<td align="center">上次登入IP</td>';
			echo '<td align="center">'.$row[8].'</td>';
		echo '</tr>';
		
		echo '<tr>';
			$result=$conn->query('select count(article_ID) from article where account="'.$row[1].'"');
			$article_count = $result->fetch_row();
			$result->close();
			echo '<td align="center">發過的文章數量</td>';
			echo '<td align="center">'.$article_count[0].'</td>';
		echo '</tr>';
		
		echo '<tr>';
			$result=$conn->query('select count(article_ID) from like_dislike where account="'.$row[1].'" and type=true');
			$like_count = $result->fetch_row();
			$result->close();
			
			$result=$conn->query('select count(article_ID) from like_dislike where account="'.$row[1].'" and type=false');
			$dislike_count = $result->fetch_row();
			$result->close();
			echo '<td align="center">發出的推/噓</td>';
			echo '<td align="center">'.$like_count[0].'/'.$dislike_count[0].'</td>';
		echo '</tr>';
	}
	$conn->close();
	// alert err_msg
	if(isset($_GET['err_msg'])){
		if($_GET['err_msg']=="name_exist"){
			echo "<script type='text/javascript'>alert('名稱已被使用');</script>";
		}
		else if($_GET['err_msg']=='name_empty'){
			echo "<script type='text/javascript'>alert('名稱不可為空');</script>";
		}
		else if($_GET['err_msg']=='name_too_long'){
			echo "<script type='text/javascript'>alert('名稱太長(最多20個字)');</script>";
		}
		else if($_GET['err_msg']=='unknown'){
			echo "<script type='text/javascript'>alert('發生未知錯誤請稍後再試');</script>";
		}
	}
?>
</div>
<style>
	input { 
		text-align: center; 
	}
</style>
<script type="text/javascript">
	function rename(){
		const form = document.getElementById('rename_table');
		const textField = document.getElementById('new_name');
		const input = document.getElementById('user_name');
		textField.value = input.value;
		
		form.submit();
	}
</script>
<html>
