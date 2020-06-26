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
	// function
	function redirect(){
		if(isset($_SESSION['last view board'])&&isset($_SESSION['last view page'])){
			header("Location: /DBFinalProject/board.php?board_name=".$_SESSION['last view board']."&page=".$_SESSION['last view page']);
			die();
		}
		else{
			header("Location: /DBFinalProject/index.php");
			die();
		}
	}
	// header start 
	// page link
	echo '<header id="header">';
	echo '<a id="main_page_link" href="/DBFinalProject/index.php">回到主頁</a>';
	$login = false;
	if(isset($_SESSION['session_id'])){//login check
		$result = $conn->query('select user_name from user where session_id ="'.$_SESSION['session_id'].'"');
		if($result->fetch_row()){
			echo '<a id="logout_link" href="/DBFinalProject/logout.php">登出</a>';
			$login = true;
		}
		$result->close();
	}
	if(!$login){
		header("Location: /DBFinalProject/login_page.php?err_msg=post_without_login");
		die();
	}
	
	if(isset($_GET['board_name'])&&isset($_GET['building_ID']))redirect();
	
	if(isset($_GET['board_name'])){
		//check board exist
		$board_name = mysqli_real_escape_string($conn,$_GET["board_name"]);
		$result = $conn->query('select board_name from board where board_name="'.$board_name.'"');
		if($row = $result->fetch_row()){
			if(isset($_SESSION['last view board'])&&isset($_SESSION['last view page'])){
				echo '<a id="board_link" href="/DBFinalProject/board.php?board_name='.$board_name.'&page='.$_SESSION['last view page'].'">取消</a>';
			}
			else{
				echo '<a id="board_link" href="/DBFinalProject/board.php?board_name='.$board_name.'">取消</a>';
			}
		}
		else{
			redirect();
		}
		$result->close();
		echo '<h1 id="board_name">'.$board_name.'</h1>';
		echo '</header>';
		// header end
		
		echo '<title>發文</title>';
		echo '<form action="post.php" method="post">';
		echo '<input name="board_name" type="hidden" value="'.$_GET['board_name'].'">';
		echo '<label id="title_label" class="title" for="title">標題 :</label><br>';
		echo '<input id="title_input" class="title" type="text" name="title"><br>';
		echo '<label id="content_lebel" class="content" for="content">內容 :</label><br>';
	}
	else if(isset($_GET['building_ID'])){
		$building_ID = mysqli_real_escape_string($conn,$_GET["building_ID"]);
		// check building_ID is exist
		$query = $conn->query('select title,create_time from article_building where building_ID="'.$building_ID.'"');
		if($row = $query->fetch_row()){
			$title = $row[0];
			$create_time = $row[1];
			echo "<title>".$title."</title>";
			echo '<a id="board_link" href="/DBFinalProject/article_building.php?building_ID='.$building_ID.'">取消</a>';
		}
		else{
			redirect();
		}
		$query->close();
		// header
		echo '<h1 id="article_building_title">'.$title.'</h1>';
		echo '<h5 id="create_time">創建時間:'.$create_time.'</h5>';
		echo '</header>';
		// header end
		
		echo '<title>回覆</title>';
		echo '<form action="post.php" method="post">';
		echo '<input name="building_ID" type="hidden" value="'.$_GET['building_ID'].'">';
		echo '<label id="content_lebel" class="content" for="content">回覆 :</label><br>';
	}
	$conn->close;
?>
<textarea id="content_input" class="content" name="content" cols="50" rows="15"></textarea>
<input id="submit_button" type="submit" value="發布">
</form>

<style>
	textarea{
		resize: none;
	}
</style>