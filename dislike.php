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
	
	// check login
	$is_login = false;
	if(isset($_SESSION['session_id'])){
		$result = $conn->query('select account from user where session_id="'.$_SESSION['session_id'].'"');
		if($row = $result->fetch_row()){
			$account = $row[0];
			$is_login = true;
		}
		$result->close();
	}
	if(!$is_login){
		header("Location: /DBFinalProject/login_page.php?err_msg=like_dislike_without_login");
		die();
	}
	
	// check already like or dislike
	if(isset($_GET['article_ID'])){
		$article_ID = $_GET['article_ID'];
		// get building_ID
		$result = $conn->query('select building_ID from article where article_ID='.$article_ID);
		$building_ID = $result->fetch_row();
		$building_ID = $building_ID[0];
		$result->close();
		$result = $conn->query('select type from like_dislike where article_ID='.$article_ID.' and account="'.$account.'"');
		if($result->fetch_row()){
			?>
				<script type="text/javascript">
				alert("你已經推或噓過這篇文章了");
			<?php
				echo 'window.location.href="article_building.php?building_ID='.$building_ID.'";';
			?>
				</script>
			<?php
		}
		else{
			$conn->query('insert into like_dislike values("'.$account.'",'.$article_ID.',false)');
			header("Location: /DBFinalProject/article_building.php?building_ID=".$building_ID);
			die();
		}
	}
?>