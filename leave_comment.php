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
		header("Location: /DBFinalProject/login_page.php?err_msg=leave_comment_without_login");
		die();
	}
	// check article_ID
	$article_exist = false;
	if(isset($_POST['article_ID'])){
		$result = $conn->query('select building_ID from article where article_ID='.$_POST['article_ID']);
		if($row = $result->fetch_row()){
			$building_ID = $row[0];
			$article_exist = true;
		}
	}

	if($article_exist){
		// check comment
		if(isset($_POST['comment'])){
			$comment = mysqli_real_escape_string($conn,$_POST['comment']);
			if(mb_strlen($comment)==0){
				$err_msg = '留言不能為空';
			}
			else if(mb_strlen($comment)>100){
				$err_msg = '留言字數過多(需在100字以下)';
			}
		}
	}
	else{
		$err_msg = '文章不存在';
	}
	if(!isset($err_msg)){
		// insert comment
		if(!$conn->query('insert into comment values("'.$account.'",'.$_POST['article_ID'].',"'.$comment.'",current_timestamp)')){
			$err_msg = '發生未知錯誤請稍後再試';
		}
		else{
			header("Location: /DBFinalProject/article_building.php?building_ID=".$building_ID);
			die();
		}
	}
	$conn->close();
	if(isset($err_msg)){
		?>
			<script type="text/javascript">
			<?php
				echo 'alert("'.$err_msg.'");';
				echo 'window.location.href ="article_building.php?building_ID='.$building_ID.'";';
			?>
			</script>
		<?php
	}
?>