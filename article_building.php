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
	
	// check building_ID is set
	if(!isset($_GET['building_ID'])){
		header("Location: /DBFinalProject/index.php");
		die();
	}
	else{
		$building_ID = mysqli_real_escape_string($conn,$_GET['building_ID']);
	}
	// check building_ID is exist
	$query = $conn->query('select title,create_time,board_name from article_building where building_ID="'.$building_ID.'"');
	if($row = $query->fetch_row()){
		$title = $row[0];
		$create_time = $row[1];
		$board_name = $row[2];
		echo "<title>".$title."</title>";
	}
	else{
		header("Location: /DBFinalProject/index.php");
		die();
	}
	$query->close();
	// header
	echo '<header id="header">';
	echo '<a id="main_page_link" href="/DBFinalProject/index.php">回到主頁</a>';
	// check login
	$login = false;
	if(isset($_SESSION['session_id'])){
		$query = $conn->query('select user_name,account from user where session_id="'.$_SESSION['session_id'].'"');
		if($row = $query->fetch_row()){
			$user_name = $row[0];
			$user_account = $row[1];
			$login = true;
			echo '<a id="logout_page_link" href="/DBFinalProject/logout.php">登出</a>';
			echo '<a id="reply_link" href="/DBFinalProject/post_page.php?building_ID='.$building_ID.'">回覆文章</a>';
		}
		$query->close();
	}
	if(!$login){
		echo '<a id="login_page_link" href="/DBFinalProject/login_page.php">登入</a>';
		echo '<a id="register_page_link" href="/DBFinalProject/register_page.php">前往註冊</a>';
	}
	
	if(isset($_SESSION['last view board'])&&isset($_SESSION['last view page'])){
		echo '<a id="board_link" href="/DBFinalProject/board.php?board_name='.$board_name.'&page='.$_SESSION['last view page'].'">回到版上</a>';
	}
	else{
		echo '<a id="reply_link" href="/DBFinalProject/board.php?board_name='.$board_name.'">回到版上</a>';
	}
	//
	echo '<h1 id="article_building_title">'.$title.'</h1>';
	echo '<h5 id="create_time">創建時間:'.$create_time.'</h5>';
	echo '</header>';
	// header end
	
	// show article
	$floor_count = 1;
	$query = $conn->query('select user.account,content,last_edit_time,user_name,article_ID from article natural join user where building_ID='.$building_ID.' order by article_ID');
	while($row = $query->fetch_row()){
		$account = $row[0];
		$content = $row[1];
		$last_edit = $row[2];
		$author = $row[3];
		$article_ID = $row[4];
		$space = '&nbsp;&nbsp;&nbsp;&nbsp';
		// an article section
		echo '<section class="article">';
		echo '<p class="article_header">'.$floor_count.'樓'.$space.'作者: '.$author.$space.'最後編輯: '.$last_edit.$space;
		if($account==$user_account)
			echo '<a href="/DBFinalProject/edit.php?article_ID='.$article_ID.'">編輯</a>';
		echo '</p>';
		echo '<textarea class="article_content" cols= "60" rows="10" disabled>'.$content.'</textarea>';
		echo '</section>';
		$floor_count++;
	}
	$query->close();

	$conn->close();
?>
<style>
	.article_content{
		resize:none;
	}
</style>