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
	
	// check login
	if(isset($_SESSION['session_id'])){
		$result=$conn->query('select account from user where session_id="'.$_SESSION['session_id'].'"');
		if($row=$result->fetch_row()){
			$account = mysqli_real_escape_string($conn,$row[0]);
			$login = true;
		}
		$result->close();
	}
	if(!login){
		header("Location: /DBFinalProject/login_page.php?err_msg=edit_without_login");
		die();
	}
	// post data check
	if(!isset($_POST['building_ID'])||!isset($_POST['article_ID'])){
		redirect();
	}
	else{
		$building_ID = mysqli_real_escape_string($conn,$_POST['building_ID']);
		$article_ID = mysqli_real_escape_string($conn,$_POST['article_ID']);
	}
	// check string
	$string_check = true;
	if(isset($conn,$_POST['title'])){
		$title = mysqli_real_escape_string($conn,$_POST['title']);
		if(mb_strlen($title)==0){
			?>
				<script type="text/javascript">
				alert("標題不能為空");
			<?php
				echo 'window.location.href="edit_page.php?building_ID='.$building_ID.'&article_ID='.$article_ID.'";';
			?>
				</script>
			<?php
			$string_check = false;
		}
		else if(mb_strlen($title)>50){
			?>
				<script type="text/javascript">
				alert("標題長度需在50以下");
			<?php
				echo 'window.location.href="edit_page.php?building_ID='.$building_ID.'&article_ID='.$article_ID.'";';
			?>
				</script>
			<?php
			$string_check = false;
		}
	}
	$content = mysqli_real_escape_string($conn,$_POST['content']);
	if(mb_strlen($content)==0){
		?>
			<script type="text/javascript">
			alert("內文不能為空");
		<?php
			echo 'window.location.href="edit_page.php?building_ID='.$building_ID.'&article_ID='.$article_ID.'";';
		?>
			</script>
		<?php
		$string_check = false;
	}
	else if(mb_strlen($content)>10000){
		?>
			<script type="text/javascript">
			alert("內文限制10000字以下");
		<?php
			echo 'window.location.href="edit_page.php?building_ID='.$building_ID.'&article_ID='.$article_ID.'";';
		?>
			</script>
		<?php
		$string_check = false;
	}
	
	if($string_check){
		$should_rollback = false;
		$conn->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
		$success = true;
		// get article owner's account
		$result = $conn->query('select account from article where article_ID='.$article_ID.' and building_ID'=$building_ID);
		if($row = $result->fetch_row()){
			$owner_account = $row[0];
		}
		else{
			redirect();
		}
		$result->close();
		// check if user is owner
		if($account!=$owner_account)redirect();
		// if post data has title get first article_ID of building and check is equal
		if(isset($title)){
			$is_first = false;
			$result = $conn->query('select min(article_ID) from article where building_ID='.$building_ID);
			if($row = $result->fetch_row()){
				$first_article_ID = $row[0];
				if($first_article_ID==$article_ID){
					$is_first = true;
				}
			}
			$result->close();
			if(!$is_first){
				redirect();
			}
			if(!$conn->query('update article_building set title="'.$title.'" where building_ID='.$building_ID)){
				$should_rollback = true;
			}
		}
		if(!$conn->query('update article set content="'.$content.'" where article_ID='.$article_ID)){
			$should_rollback = true;
		}
		
		if($should_rollback){
			$conn->rollback();
			?>
				<script type="text/javascript">
				alert("更新失敗，請稍後再試");
				
				<?php
				if(isset($_SESSION['last view board'])&&isset($_SESSION['last view page'])&&isset($_SESSION['order_key'])&&isset($_SESSION['order_type'])){
					echo "window.location.href = '/DBFinalProject/board.php?board_name=".$_SESSION['last view board']."&page=".$_SESSION['last view page']."&order_key=".$_SESSION['order_key']."&order_type=".$_SESSION['order_type'].";";
				}
				else{
					echo '"window.location.href ="index.php;"';
				}
				?>
				</script>
			<?php
		}
		else{
			$conn->commit();
			header("Location: /DBFinalProject/article_building.php?building_ID=".$building_ID);
			die();
		}
	}
	
	$conn->close();
?>
