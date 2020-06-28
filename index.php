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
	position: absolute;
	top: 0;
	left: 0;
}
</style>
<body>

<div style="position:relative;">
        <img class="photo1" src="world.png" alt="" width="100%" height="15%">
</div>
<pre>
<?php
	session_start();
	//connect to database
	$servername = "127.0.0.1";
	$username = "team1";
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
			echo '<p>hi '.$user.'</p><br><br>';
			echo '<a id="logout_link" href="/DBFinalProject/logout.php"><button>登出</button></a><br><br>';
			echo '<a id="profile_link" href="/DBFinalProject/profile_page.php"><button>修改個人資料</button></a><br><br>';
			$login = true;
			// check authority button
			$result = $conn->query('select user_name from user where session_id="'.$_SESSION['session_id'].'" and user_authority = "A"');
			if($row = $result->fetch_row())
			{
				$user_name = $row[0];
				$is_admin = true;
				echo '<a id="get_board_edit" href="/DBFinalProject/board_manage.php"><button>管理版</button></a><br><br>';
			}
			$result->close();
		}
		$stmt->close();
	}
	if(!$login){
		echo '<a id="login_page_link" href="/DBFinalProject/login_page.php"><button>登入</button></a><br><br>';
		echo '<a id="register_page_link" href="/DBFinalProject/register_page.php"><button>前往註冊</button></a><br><br>';
	}
	echo '<br>';
	// genarate board list
	$result = $conn->query('select board_name,popularity from board order by popularity desc');
	$num=0;
	while($row=$result->fetch_row())
	{
		if($num==3)
		{
			$num=0;
			echo '<br><br>';
		}
		$board_name = $row[0];
		$popularity = $row[1];
		echo '<a href="/DBFinalProject/board.php?board_name='.$board_name.'"><button>'.$board_name." 人氣: ".$popularity.'</button></a>';
		echo '   ';
		$num+=1;
	}
	
	$conn->close();
?>
</pre>
</body>
</html>
