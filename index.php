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
#login_page_link{
	display:inline-block;
}
#register_page_link{
	display:inline-block;
}
#logout_link{
	display:inline-block;
}
#profile_link{
	display:inline-block;
}
#get_board_edit{
	display:inline-block;
}
#board{
	display:inline-block;
	width:250px;
	height:20px;
	margin: 2em;
}

</style>
<body>

<div style="position:relative;">
        <img id="photo1" src="world.png" alt="" width="100%" height="15%">
</div>
<pre>
<?php
	session_start();
	//connect to database
	$servername = "127.0.0.1";
	$username = "";
	$password = "DB338HKkvRVOZzb";
	$dbname = "team1";
	
	$conn = new mysqli($servername, $username, $password, $dbname);
	if (!$conn->set_charset("utf8")) {
		printf("Error loading character set utf8: %s\n", $conn->error);
		exit();
	}
	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	$login = false;
	if(isset($_SESSION['session_id'])){//login check
		$stmt = $conn->prepare("select user_name from user where session_id = ?");
		$stmt->bind_param("s",$_SESSION['session_id']);
		$stmt->execute();
		$stmt->bind_result($user);
		if($stmt->fetch())
		{
			$stmt->close();
			echo '<p>hi '.$user.'</p><br><br>';
			echo '<a id="logout_link" href="/DBFinalProject/logout.php"><input type="button" value="登出" style="width:120px;height:40px;border:2px #9999FF dashed;background-color:pink;"></a><br><br>';
			echo '<a id="profile_link" href="/DBFinalProject/profile_page.php"><input type="button" value="修改個人資料" style="width:120px;height:40px;border:2px #9999FF dashed;background-color:pink;"></a><br><br>';
			$login = true;
			// check authority button
			$result = $conn->query('select user_name from user where session_id="'.$_SESSION['session_id'].'" and user_authority = "A"');
			if($row = $result->fetch_row())
			{
				$user_name = $row[0];
				$is_admin = true;
				echo '<a id="get_board_edit" href="/DBFinalProject/board_manage.php"><input type="button" value="管理版" style="width:120px;height:40px;border:2px #9999FF dashed;background-color:pink;"></a><br><br>';
			}
			$result->close();
		}
		
	}
	if(!$login)
	{
		echo '<a id="login_page_link" href="/DBFinalProject/login_page.php"><input type="button" value="登入" style="width:120px;height:40px;border:2px #9999FF dashed;background-color:pink;"></a><br><br>';
		echo '<a id="register_page_link" href="/DBFinalProject/register_page.php"><input type="button" value="前往註冊" style="width:120px;height:40px;border:2px #9999FF dashed;background-color:pink;"></a><br><br>';
	}
	echo '<br>';
	// genarate board list
	$result = $conn->query('select board_name,popularity from board order by popularity desc');
	$num=0;
	while($row=$result->fetch_row())
	{
		if($num==4)
		{
			$num=0;
			echo '<br><br>';
		}
		$board_name = $row[0];
		$popularity = $row[1];
		echo '<a id="board" href="/DBFinalProject/board.php?board_name='.$board_name.'"><input type="button" value="'.$board_name." 人氣: ".$popularity.'" style="width:300px;height:40px;border:2px #9999FF groove;background-color:#21C592 color:#0C4A5F;"></a>';
		echo '   ';
		$num+=1;
	}
	
	$conn->close();
?>
</pre>

</body>
</html>
