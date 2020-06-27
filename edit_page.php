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
	
	//header
	// header start 
	// page link
	echo "<title>編輯</title>";
	echo '<header id="header">';
	echo '<a id="main_page_link" href="/DBFinalProject/index.php">回到主頁</a>';
	$login = false;
	if(isset($_SESSION['session_id'])){//login check
		$result = $conn->query('select account from user where session_id ="'.$_SESSION['session_id'].'"');
		if($row = $result->fetch_row()){
			$account = $row[0];
			echo '<a id="logout_link" href="/DBFinalProject/logout.php">登出</a>';
			$login = true;
		}
		$result->close();
	}
	if(!$login){
		header("Location: /DBFinalProject/login_page.php?err_msg=edit_without_login");
		die();
	}
	//
	if(isset($_GET['building_ID'])&&isset($_GET['article_ID'])){
		$article_ID = mysqli_real_escape_string($conn,$_GET["article_ID"]);
		$building_ID = mysqli_real_escape_string($conn,$_GET["building_ID"]);
		// check building_ID is exist and get building data
		$query = $conn->query('select title,create_time from article_building where building_ID="'.$building_ID.'"');
		if($row = $query->fetch_row()){
			$title = $row[0];
			$create_time = $row[1];
			echo '<a id="board_link" href="/DBFinalProject/article_building.php?building_ID='.$building_ID.'">取消</a>';
		}
		else{
			redirect();
		}
		$query->close();
		echo '<h1 id="article_building_title">'.$title.'</h1>';
		echo '<h5 id="create_time">創建時間:'.$create_time.'</h5>';
		echo '</header>';
		// header end
		
		// check article_ID is exist and is belong user and building
		$query = $conn->query('select content,account from article where article_ID='.$article_ID.' and building_ID='.$building_ID);
		if($row = $query->fetch_row()){
			$content = $row[0];
			$owner_account = $row[1];
			if($account!=$owner_account){
				redirect();
			}
		}
		else{
			redirect();
		}
		$query->close();
		
		// get first article id in this building
		$article_ID = mysqli_real_escape_string($conn,$_GET['article_ID']);
		$result = $conn->query('select min(article_ID) from article where building_ID='.$building_ID);
		if($row=$result->fetch_row()){
			$first_article_ID = $row[0];
		}
		$result->close();
		// form start
		echo '<form id="edit_area" action="edit.php" method="post">';
		echo '<input name="building_ID" type="hidden" value="'.$_GET['building_ID'].'">';
		echo '<input name="article_ID" type="hidden" value="'.$_GET['article_ID'].'">';
		// if is first then can update title
		if(isset($first_article_ID)&&$first_article_ID==$article_ID){
			echo '<label id="title_label" class="title" for="title">標題 :</label><br>';
			echo '<input id="title_input" name="title" type="text" value="'.$title.'"><br>';
			echo '<label id="content_label" class="content" for="content">內容 :</label><br>';
		}
		else{
			echo '<label id="content_label" class="content" for="content">回覆 :</label><br>';
		}
		echo '<textarea id="content_input" class="content" name="content" cols="60" rows="10">'.$content.'</textarea>';
		echo '<input id="submit_button" type="submit" value="更新">';
	}
	else{
		redirect();
	}
?>
<style>
	textarea{
		resize:none;
	}
</style>