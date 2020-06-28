<!doctype html>
<html>
<style type="text/css">
<!--set no rolling-->
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


</style>
<>
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
		if(isset($_SESSION['last view board'])&&isset($_SESSION['last view page'])&&isset($_SESSION['order_key'])&&isset($_SESSION['order_type'])){
			header('Location: /DBFinalProject/board.php?board_name='.$_SESSION['last view board'].'&page='.$_SESSION['last view page'].'&order_key='.$_SESSION['order_key'].'&order_type='.$_SESSION['order_type']);
			die();
		}
		else{
			header("Location: /DBFinalProject/index.php");
			die();
		}
	}
	// header start 
	// page link
	echo '<div style="text-align:center">';
	echo '<header id="header">';
	echo '<a id="main_page_link" href="/DBFinalProject/index.php"><input type="button" value="回到主頁" style="width:120px;height:40px;border:2px #9999FF dashed;background-color:pink;"></a>';
	$login = false;
	if(isset($_SESSION['session_id'])){//login check
		$result = $conn->query('select user_name from user where session_id ="'.$_SESSION['session_id'].'"');
		if($row = $result->fetch_row()){
			echo '<a id="logout_link" href="/DBFinalProject/logout.php"><input type="button" value="登出" style="width:120px;height:40px;border:2px #9999FF dashed;background-color:pink;"></a>';
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
				echo '<a id="board_link" href="/DBFinalProject/board.php?board_name='.$board_name.'&page='.$_SESSION['last view page'].'"><input type="button" value="取消" style="width:120px;height:40px;border:2px #9999FF dashed;background-color:pink;"></a>';
			}
			else{
				echo '<a id="board_link" href="/DBFinalProject/board.php?board_name='.$board_name.'"><input type="button" value="取消" style="width:120px;height:40px;border:2px #9999FF dashed;background-color:pink;"></a>';
			}
		}
		else{
			redirect();
		}
		$result->close();
		?>	
		<?php
				echo '<h1 id="board_name">'.$board_name.'</h1>';
				echo '</header>';
				echo '</div>';
				// header end
		?>
		
		<div style="text-align:center">	
		<?php	
		echo '<title>發文</title>';
		echo '<form id="post_area" action="post.php" method="post">';
		echo '<input name="board_name" type="hidden" value="'.$_GET['board_name'].'">';
		echo '<label id="title_label" class="title" for="title">標題 :</label><br>';
		echo '<input id="title_input" class="title" type="text" name="title"><br>';
		echo '<label id="content_label" class="content" for="content">內容 :</label><br>';
	}
	else if(isset($_GET['building_ID'])){
		echo '<div style="text-align:center">';
		$building_ID = mysqli_real_escape_string($conn,$_GET["building_ID"]);
		// check building_ID is exist
		$query = $conn->query('select title,create_time from article_building where building_ID="'.$building_ID.'"');
		if($row = $query->fetch_row()){
			$title = $row[0];
			$create_time = $row[1];
			echo "<title>".$title."</title>";
			echo '<a id="board_link" href="/DBFinalProject/article_building.php?building_ID='.$building_ID.'"><input type="button" value="取消" style="width:120px;height:40px;border:2px #9999FF dashed;background-color:pink;"></a>';
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
		echo '<form id="post_area" action="post.php" method="post">';
		echo '<input name="building_ID" type="hidden" value="'.$_GET['building_ID'].'">';
		echo '<label id="content_label" class="content" for="content">回覆 :</label><br>';
	}
	$conn->close;
?>
<textarea id="content_input" class="content" name="content" cols="60" rows="10" style="background-color:#E4E5E2;"></textarea>
<br>
<input id="submit_button" type="submit" value="發布" style="width:120px;height:40px; color:white; background-color:#05143D;">
</form>
</div>
<style>
	textarea{
		resize: none;
	}
</style>
</body>
</html>
